<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'value',
        'updated_by',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function get(string $key, $default = null)
    {
        $policy = static::where('key', $key)->first();
        return $policy ? $policy->value : $default;
    }
}
