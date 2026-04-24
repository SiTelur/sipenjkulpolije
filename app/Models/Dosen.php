<?php

namespace App\Models;

use App\Enums\DosenType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'nidn', 'is_active', 'tipe_dosen'])]
class Dosen extends Model
{
    protected $table = 'dosen';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'tipe_dosen' => DosenType::class,
        ];
    }

    /**
     * Get the courses taught by this lecturer.
     */
    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'id_pengampu');
    }
}
