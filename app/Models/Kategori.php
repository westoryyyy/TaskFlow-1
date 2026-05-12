<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama',
        'color',
    ];

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}