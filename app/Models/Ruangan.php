<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable()]
class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = ['nama', 'kegunaan_ruangan'];

    /**
     * Cast kegunaan_ruangan sebagai JSON array untuk MySQL.
     * Sebelumnya menggunakan PostgreSQL native array format {TEORI,PRAKTIK}.
     */
    protected $casts = [
        'kegunaan_ruangan' => 'array',
    ];
}
