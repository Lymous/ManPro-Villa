<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'file_path',
        'caption',
        'urutan',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'urutan' => 'integer',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
