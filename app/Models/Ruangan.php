<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nama', 'kegunaan_ruangan'])]
class Ruangan extends Model
{
    protected $table = 'ruangan';

    /**
     * Parse the PostgreSQL native array to a PHP array.
     */
    protected function kegunaanRuangan(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn(?string $value) => $value ? explode(',', trim($value, '{}')) : [],
            set: fn($value) => is_array($value) ? '{' . implode(',', $value) . '}' : $value,
        );
    }
}
