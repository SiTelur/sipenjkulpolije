<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Jadwal extends Model
{
    protected $table = 'jadwal';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = ['is_success', 'jadwal', 'semester', 'jadwal_view', 'title', 'unscheduled_count', 'unscheduled_items', 'summary', 'teknisi_summary'];
    protected function casts(): array
    {
        return [
            'is_success' => 'boolean',
            'jadwal' => 'array',
            'jadwal_view' => 'array',
            'unscheduled_count' => 'integer',
            'unscheduled_items' => 'array',
            'summary' => 'array',
            'teknisi_summary' => 'array',
        ];
    }
}
