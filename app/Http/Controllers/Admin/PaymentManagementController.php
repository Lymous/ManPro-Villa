<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking', 'verifier'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, $id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        $payment->update([
            'status' => 'diverifikasi',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $booking = $payment->booking;
        $totalPaid = $booking->payments()->where('status', 'diverifikasi')->sum('nominal');

        if ($totalPaid >= $booking->total_harga) {
            $booking->update(['status' => 'lunas']);
            $newStatus = 'lunas';
        } else {
            $booking->update(['status' => 'dp_dibayar']);
            $newStatus = 'dp_dibayar';
        }

        BookingLog::log(
            $booking->id,
            'verify_payment',
            'menunggu_konfirmasi',
            'Pembayaran Rp '.number_format($payment->nominal, 0, ',', '.').' diverifikasi oleh '.auth()->user()->name
        );

        // Notifikasi WA
        Notification::create([
            'booking_id' => $booking->id,
            'jenis' => $newStatus === 'lunas' ? 'wa_lunas' : 'wa_konfirmasi',
            'no_tujuan' => $booking->no_wa,
            'pesan' => "Halo {$booking->nama_tamu}, pembayaran transfer Anda sebesar Rp ".number_format($payment->nominal, 0, ',', '.').' telah diverifikasi oleh admin kami. Status booking: '.strtoupper($newStatus).'.',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran sebesar Rp '.number_format($payment->nominal, 0, ',', '.').' berhasil diverifikasi.');
    }

    public function reject(Request $request, $id)
    {
        $payment = Payment::with('booking')->findOrFail($id);
        $request->validate(['alasan' => 'nullable|string']);

        $payment->update([
            'status' => 'ditolak',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        BookingLog::log(
            $payment->booking_id,
            'reject_payment',
            'menunggu_konfirmasi',
            'Ditolak oleh '.auth()->user()->name.'. Alasan: '.($request->alasan ?? 'Bukti tidak valid')
        );

        return back()->with('warning', 'Bukti pembayaran ditandai sebagai ditolak.');
    }

    public function createSettlement(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $request->validate([
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|string',
            'jenis' => 'required|in:pelunasan,denda',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'verified_by' => auth()->id(),
            'jenis' => $request->jenis,
            'metode_pembayaran' => $request->metode_pembayaran,
            'nominal' => $request->nominal,
            'tanggal_transfer' => now()->toDateString(),
            'status' => 'diverifikasi',
            'verified_at' => now(),
        ]);

        $totalPaid = $booking->payments()->where('status', 'diverifikasi')->sum('nominal');
        if ($totalPaid >= $booking->total_harga) {
            $booking->update(['status' => 'lunas']);
        }

        BookingLog::log(
            $booking->id,
            'settlement_added',
            null,
            "Pembayaran {$request->jenis} Rp ".number_format($request->nominal, 0, ',', '.').' dicatat oleh '.auth()->user()->name
        );

        return back()->with('success', "Pembayaran {$request->jenis} berhasil dicatat.");
    }
}
