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

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        // Verifikasi signature jika di environment produksi
        $privateKey = config('tripay.private_key');
        if ($privateKey && $privateKey !== 'DEV-MOCK-PRIVATE-KEY' && $signature) {
            $computedSignature = hash_hmac('sha256', $json, $privateKey);
            if ($computedSignature !== $signature) {
                Log::warning('Tripay webhook signature mismatch!');
                return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
            }
        }

        $result = $this->tripayService->handleCallback($data);

        return response()->json($result);
    }

    /**
     * Endpoint Simulasi Cepat (Testing Sandbox)
     */
    public function simulatePaid(string $reference)
    {
        $payment = Payment::where('tripay_reference', $reference)
            ->orWhere('id', $reference)
            ->firstOrFail();

        $this->tripayService->handleCallback([
            'reference' => $payment->tripay_reference,
            'status' => 'PAID',
        ]);

        return redirect()->route('booking.invoice', $payment->booking->kode_booking)
            ->with('success', 'Simulasi Pembayaran Berhasil! Pembayaran telah diverifikasi oleh sistem.');
    }
}
