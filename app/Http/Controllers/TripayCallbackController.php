<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    protected TripayService $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    /**
     * Webhook Callback dari Tripay
     */
    public function handle(Request $request)
    {
        $signature = $request->header('X-Callback-Signature');
        $event = $request->header('X-Callback-Event');

        $json = $request->getContent();
        $data = json_decode($json, true);

        if (! $data) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        $privateKey = config('tripay.private_key');

        // Verifikasi signature jika private key terkonfigurasi
        if ($privateKey && $privateKey !== 'DEV-MOCK-PRIVATE-KEY') {
            if (! $signature) {
                Log::warning('Tripay webhook rejected: Missing X-Callback-Signature header.');

                return response()->json(['success' => false, 'message' => 'Missing signature header'], 403);
            }

            $computedSignature = hash_hmac('sha256', $json, $privateKey);
            if (! hash_equals($computedSignature, $signature)) {
                Log::warning('Tripay webhook signature mismatch!');

                return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
            }
        }

        if (empty($data['reference']) || empty($data['status'])) {
            return response()->json(['success' => false, 'message' => 'Incomplete callback payload'], 422);
        }

        $result = $this->tripayService->handleCallback($data);

        return response()->json($result);
    }

    /**
     * Endpoint Simulasi Cepat (Testing Sandbox)
     * Hanya diizinkan pada local environment untuk Admin / Penjaga
     */
    public function simulatePaid(string $reference)
    {
        abort_unless(app()->environment('local'), 403, 'Simulasi pembayaran hanya diizinkan di lingkungan lokal.');
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPenjaga()), 403, 'Akses ditolak.');

        $payment = Payment::where('tripay_reference', $reference)
            ->orWhere('id', $reference)
            ->firstOrFail();

        $this->tripayService->handleCallback([
            'reference' => $payment->tripay_reference ?? 'MOCK-'.$payment->id,
            'status' => 'PAID',
        ]);

        return redirect()->route('booking.invoice', $payment->booking->kode_booking)
            ->with('success', 'Simulasi Pembayaran Berhasil! Pembayaran telah diverifikasi oleh sistem.');
    }
}
