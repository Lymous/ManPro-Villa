<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Notification;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected string $apiKey;

    protected string $privateKey;

    protected string $merchantCode;

    protected string $apiUrl;

    protected string $mode;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key', 'DEV-MOCK-API-KEY');
        $this->privateKey = config('tripay.private_key', 'DEV-MOCK-PRIVATE-KEY');
        $this->merchantCode = config('tripay.merchant_code', 'T0001');
        $this->apiUrl = config('tripay.api_url', 'https://tripay.co.id/api-sandbox/');
        $this->mode = config('tripay.mode', 'sandbox');
    }

    /**
     * Mendapatkan daftar channel pembayaran aktif
     */
    public function getPaymentChannels(): array
    {
        // Jika memiliki kredensial riil, coba request langsung ke Tripay
        if ($this->apiKey && $this->apiKey !== 'DEV-MOCK-API-KEY') {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                ])->timeout(5)->get($this->apiUrl.'merchant/payment-channel');

                if ($response->successful() && isset($response->json()['data'])) {
                    return $response->json()['data'];
                }
            } catch (Exception $e) {
                Log::warning('Tripay live channel fetch failed, fallback to local: '.$e->getMessage());
            }
        }

        // Default channels dari config
        $channels = [];
        foreach (config('tripay.channels', []) as $code => $info) {
            $channels[] = [
                'code' => $code,
                'name' => $info['name'],
                'group' => $info['group'],
                'type' => 'direct',
                'fee_merchant' => [
                    'flat' => 0,
                    'percent' => 0,
                ],
                'fee_customer' => [
                    'flat' => $info['fee_flat'],
                    'percent' => $info['fee_percent'],
                ],
                'total_fee' => [
                    'flat' => $info['fee_flat'],
                    'percent' => $info['fee_percent'],
                ],
                'icon_url' => $info['icon'],
                'active' => true,
            ];
        }

        return $channels;
    }

    /**
     * Membuat Closed Payment Transaction di Tripay
     */
    public function createClosedTransaction(Booking $booking, Payment $payment, string $method): array
    {
        $merchantRef = 'PAY-'.$booking->kode_booking.'-'.$payment->id;
        $amount = (int) round($payment->nominal);
        $signature = hash_hmac('sha256', $this->merchantCode.$merchantRef.$amount, $this->privateKey);

        $orderItems = [];
        foreach ($booking->bookingRooms as $br) {
            $orderItems[] = [
                'sku' => 'ROOM-'.$br->room_type_id,
                'name' => $br->roomType->nama.' ('.$br->jumlah_malam.' malam)',
                'price' => (int) round($br->harga_per_malam),
                'quantity' => $br->jumlah_kamar,
            ];
        }

        foreach ($booking->bookingAddOns as $ba) {
            $orderItems[] = [
                'sku' => 'ADDON-'.$ba->add_on_id,
                'name' => $ba->addOn->nama,
                'price' => (int) round($ba->harga_satuan),
                'quantity' => $ba->jumlah,
            ];
        }

        $payload = [
            'method' => $method,
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => $booking->nama_tamu,
            'customer_email' => $booking->email ?? 'tamu@bukitbunga.com',
            'customer_phone' => $booking->no_wa,
            'order_items' => $orderItems,
            'return_url' => route('booking.invoice', $booking->kode_booking),
            'expired_time' => $booking->hold_expires_at ? $booking->hold_expires_at->timestamp : (time() + 24 * 3600),
            'signature' => $signature,
        ];

        // Jika mode live dan API key tersedia, tembak ke Tripay
        if ($this->apiKey && $this->apiKey !== 'DEV-MOCK-API-KEY') {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                ])->timeout(8)->post($this->apiUrl.'transaction/create', $payload);

                if ($response->successful() && isset($response->json()['data'])) {
                    $data = $response->json()['data'];
                    $payment->update([
                        'tripay_reference' => $data['reference'] ?? $merchantRef,
                        'tripay_pay_code' => $data['pay_code'] ?? ($data['qr_string'] ?? null),
                        'tripay_checkout_url' => $data['checkout_url'] ?? null,
                        'tripay_channel' => $method,
                        'tripay_status' => $data['status'] ?? 'UNPAID',
                        'payload' => $data,
                    ]);

                    return [
                        'success' => true,
                        'data' => $data,
                    ];
                }
            } catch (Exception $e) {
                Log::warning('Tripay live create failed: '.$e->getMessage());
            }
        }

        // Mode Simulasi / Sandbox Cerdas
        $mockRef = 'PAY-'.strtoupper(bin2hex(random_bytes(6)));
        $isQris = str_contains(strtoupper($method), 'QRIS');

        $mockPayCode = $isQris
            ? '00020101021226590014ID.LINKAJA.WWW011893600911002120000002150000000000000005204581253033605802ID5917VILLA BUKIT BUNGA6009MOJOKERTO6304E07B'
            : ('8808'.str_pad((string) $booking->id, 8, '0', STR_PAD_LEFT));

        $mockData = [
            'reference' => $mockRef,
            'merchant_ref' => $merchantRef,
            'payment_method' => $method,
            'payment_name' => config("tripay.channels.{$method}.name", $method),
            'customer_name' => $booking->nama_tamu,
            'amount' => $amount,
            'fee_merchant' => 0,
            'fee_customer' => config("tripay.channels.{$method}.fee_flat", 3500),
            'total_amount' => $amount + config("tripay.channels.{$method}.fee_flat", 3500),
            'pay_code' => $mockPayCode,
            'qr_string' => $isQris ? $mockPayCode : null,
            'qr_url' => $isQris ? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($mockPayCode) : null,
            'status' => 'UNPAID',
            'expired_time' => time() + (24 * 3600),
            'instructions' => [
                [
                    'title' => 'Pembayaran melalui '.config("tripay.channels.{$method}.name", $method),
                    'steps' => [
                        $isQris ? 'Buka aplikasi e-wallet / mobile banking Anda.' : 'Pilih menu Transfer / Virtual Account.',
                        $isQris ? 'Pindai kode QRIS di atas.' : 'Masukkan nomor VA: '.$mockPayCode,
                        'Periksa detail nama Villa Bukit Bunga dan total tagihan.',
                        'Selesaikan pembayaran sebelum batas waktu berakhir.',
                    ],
                ],
            ],
        ];

        $payment->update([
            'tripay_reference' => $mockRef,
            'tripay_pay_code' => $mockPayCode,
            'tripay_checkout_url' => route('booking.invoice', $booking->kode_booking),
            'tripay_channel' => $method,
            'tripay_status' => 'UNPAID',
            'payload' => $mockData,
        ]);

        return [
            'success' => true,
            'data' => $mockData,
        ];
    }

    /**
     * Memproses callback dari Tripay Webhook
     */
    public function handleCallback(array $data, ?string $signatureHeader = null): array
    {
        $reference = $data['reference'] ?? null;
        $status = strtoupper($data['status'] ?? '');

        if (! $reference) {
            return ['success' => false, 'message' => 'No reference provided'];
        }

        $payment = Payment::where('tripay_reference', $reference)->first();
        if (! $payment) {
            return ['success' => false, 'message' => 'Payment record not found'];
        }

        $booking = $payment->booking;

        if ($status === 'PAID') {
            $payment->update([
                'status' => 'diverifikasi',
                'tripay_status' => 'PAID',
                'verified_at' => now(),
            ]);

            // Update status booking
            $totalPaid = $booking->payments()->where('status', 'diverifikasi')->sum('nominal');

            if ($totalPaid >= $booking->total_harga) {
                $booking->update(['status' => 'lunas']);
                $newStatus = 'lunas';
            } else {
                $booking->update(['status' => 'dp_dibayar']);
                $newStatus = 'dp_dibayar';
            }

            // Log Audit
            BookingLog::log(
                $booking->id,
                'tripay_payment_received',
                'menunggu_pembayaran',
                $newStatus,
                null
            );

            // Notifikasi WhatsApp
            $waMsg = "Halo {$booking->nama_tamu}, pembayaran Anda sebesar Rp ".number_format($payment->nominal, 0, ',', '.')." untuk Villa Bukit Bunga ({$booking->kode_booking}) telah berhasil kami terima! Status reservasi: ".strtoupper($newStatus).'. Terima kasih.';
            Notification::create([
                'booking_id' => $booking->id,
                'jenis' => $newStatus === 'lunas' ? 'wa_lunas' : 'wa_konfirmasi',
                'no_tujuan' => $booking->no_wa,
                'pesan' => $waMsg,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return ['success' => true, 'message' => 'Payment marked as PAID'];
        }

        if (in_array($status, ['EXPIRED', 'FAILED'])) {
            $payment->update([
                'status' => 'kadaluarsa',
                'tripay_status' => $status,
            ]);

            return ['success' => true, 'message' => 'Payment marked as '.$status];
        }

        return ['success' => true, 'message' => 'Ignored status: '.$status];
    }
}
