<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nama', 'kegunaan_ruangan'])]
class Ruangan extends Model
{
    protected $table = 'ruangan';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kegunaan_ruangan' => 'array',
        ];
    }
}
