<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['kode', 'nama', 'semester', 'id_pengampu', 'sks_teori', 'sks_praktek', 'is_active', 'kelas'])]
class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'id_pengampu' => 'integer',
            'sks_teori' => 'integer',
            'sks_praktek' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the lecturer who teaches this course.
     */
    public function pengampu(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'id_pengampu');
    }
}
