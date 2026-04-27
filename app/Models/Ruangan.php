<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nama', 'kegunaan_ruangan'])]
class Ruangan extends Model
{
    protected $table = 'ruangan';

    /**
     * Cast kegunaan_ruangan sebagai JSON array untuk MySQL.
     * Sebelumnya menggunakan PostgreSQL native array format {TEORI,PRAKTIK}.
     */
    protected $casts = [
        'kegunaan_ruangan' => 'array',
    ];
}
