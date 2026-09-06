<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\RoomType;
use Illuminate\Http\Request;

class BlockedDateController extends Controller
{
    public function index()
    {
        $blockedDates = BlockedDate::with(['roomType', 'creator'])->latest()->paginate(15);
        $roomTypes = RoomType::where('is_active', true)->get();

        return view('admin.blocked-dates.index', compact('blockedDates', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|in:pemeliharaan,pribadi,libur',
            'room_type_id' => 'nullable|exists:room_types,id',
            'catatan' => 'nullable|string',
        ]);

        BlockedDate::create([
            'room_type_id' => $request->room_type_id,
            'created_by' => auth()->id(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Jadwal blokir tanggal berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->delete();

        return back()->with('success', 'Jadwal blokir tanggal berhasil dihapus.');
    }
}
