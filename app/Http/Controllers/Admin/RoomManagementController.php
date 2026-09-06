<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomManagementController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::with(['rooms', 'photos'])->get();
        $rooms = Room::with('roomType')->get();

        return view('admin.rooms.index', compact('roomTypes', 'rooms'));
    }

    public function editType($id)
    {
        $roomType = RoomType::with(['photos'])->findOrFail($id);
        return view('admin.rooms.edit-type', compact('roomType'));
    }

    public function updateType(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'kapasitas_min' => 'required|integer|min:1',
            'kapasitas_max' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'thumbnail_url' => 'nullable|url',
        ]);

        $thumbnailPath = $roomType->thumbnail;

        // Jika upload file thumbnail baru
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = 'thumb_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('packages', $filename, 'public');
            $thumbnailPath = '/storage/' . $path;

            // Catat juga ke galeri room_photos sebagai primary
            RoomPhoto::where('room_type_id', $roomType->id)->update(['is_primary' => false]);
            RoomPhoto::create([
                'room_type_id' => $roomType->id,
                'file_path' => $thumbnailPath,
                'caption' => 'Foto Utama - ' . $roomType->nama,
                'urutan' => 1,
                'is_primary' => true,
            ]);
        } elseif ($request->filled('thumbnail_url')) {
            $thumbnailPath = $request->thumbnail_url;
        }

        $fasilitasArray = array_filter(array_map('trim', explode("\n", $request->input('fasilitas_raw', ''))));

        $roomType->update([
            'nama' => $request->nama,
            'harga_dasar' => $request->harga_dasar,
            'kapasitas_min' => $request->kapasitas_min,
            'kapasitas_max' => $request->kapasitas_max,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $thumbnailPath,
            'fasilitas' => !empty($fasilitasArray) ? array_values($fasilitasArray) : $roomType->fasilitas,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.rooms.edit_type', $roomType->id)
            ->with('success', "Data paket & foto '{$roomType->nama}' berhasil diperbarui.");
    }

    /**
     * Upload foto-foto galeri baru (file upload atau URL)
     */
    public function uploadPhotos(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'photo_url' => 'nullable|url',
            'caption' => 'nullable|string|max:255',
        ]);

        $uploadedCount = 0;

        // Jika upload file gambar
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = 'gallery_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('packages/gallery', $filename, 'public');
                $photoPath = '/storage/' . $path;

                $nextOrder = RoomPhoto::where('room_type_id', $roomType->id)->max('urutan') + 1;

                RoomPhoto::create([
                    'room_type_id' => $roomType->id,
                    'file_path' => $photoPath,
                    'caption' => $request->caption ?? ($roomType->nama . ' - Galeri'),
                    'urutan' => $nextOrder,
                    'is_primary' => false,
                ]);
                $uploadedCount++;
            }
        }

        // Jika input via URL online
        if ($request->filled('photo_url')) {
            $nextOrder = RoomPhoto::where('room_type_id', $roomType->id)->max('urutan') + 1;

            RoomPhoto::create([
                'room_type_id' => $roomType->id,
                'file_path' => $request->photo_url,
                'caption' => $request->caption ?? ($roomType->nama . ' - Galeri'),
                'urutan' => $nextOrder,
                'is_primary' => false,
            ]);
            $uploadedCount++;
        }

        if ($uploadedCount === 0) {
            return back()->with('error', 'Pilih file gambar atau masukkan tautan URL foto.');
        }

        return back()->with('success', "Berhasil menambahkan {$uploadedCount} foto baru ke galeri paket.");
    }

    /**
     * Jadikan foto terpilih sebagai foto utama (thumbnail)
     */
    public function setPrimaryPhoto($photoId)
    {
        $photo = RoomPhoto::findOrFail($photoId);
        $roomType = $photo->roomType;

        // Reset foto lain
        RoomPhoto::where('room_type_id', $roomType->id)->update(['is_primary' => false]);

        $photo->update(['is_primary' => true]);
        $roomType->update(['thumbnail' => $photo->file_path]);

        return back()->with('success', 'Foto utama (thumbnail) paket berhasil diperbarui.');
    }

    /**
     * Hapus foto dari galeri
     */
    public function deletePhoto($photoId)
    {
        $photo = RoomPhoto::findOrFail($photoId);
        $roomType = $photo->roomType;

        // Hapus file fisik jika ada di disk storage
        if (str_starts_with($photo->file_path, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $photo->file_path);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
        }

        $wasPrimary = $photo->is_primary;
        $photo->delete();

        // Jika foto utama yang dihapus, alihkan ke foto berikutnya jika ada
        if ($wasPrimary) {
            $nextPhoto = RoomPhoto::where('room_type_id', $roomType->id)->first();
            if ($nextPhoto) {
                $nextPhoto->update(['is_primary' => true]);
                $roomType->update(['thumbnail' => $nextPhoto->file_path]);
            }
        }

        return back()->with('success', 'Foto berhasil dihapus dari galeri.');
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'nomor_kamar' => 'required|string|max:50|unique:rooms,nomor_kamar',
            'status' => 'required|in:tersedia,terisi,pemeliharaan',
            'catatan' => 'nullable|string',
        ]);

        Room::create($request->only('room_type_id', 'nomor_kamar', 'status', 'catatan'));

        return back()->with('success', 'Unit kamar fisik baru berhasil ditambahkan.');
    }

    public function updateRoomStatus(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $request->validate([
            'status' => 'required|in:tersedia,terisi,pemeliharaan',
        ]);

        $room->update(['status' => $request->status]);

        return back()->with('success', "Status kamar {$room->nomor_kamar} diubah ke {$room->status}.");
    }
}
