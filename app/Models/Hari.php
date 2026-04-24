<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nama', 'jam_mulai', 'jam_selesai', 'jam_mulai_istirahat', 'jam_selesai_istirahat'])]
class Hari extends Model
{
    protected $table = 'hari';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jam_mulai' => 'integer',
            'jam_selesai' => 'integer',
            'jam_mulai_istirahat' => 'integer',
            'jam_selesai_istirahat' => 'integer',
        ];
    }
}
