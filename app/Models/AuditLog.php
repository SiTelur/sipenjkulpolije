<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AuditLog extends Model
{
    protected $table = 'audit_log';

    protected $keyType = 'string';

    protected $fillable = ['id', 'action', 'changed_at', 'table_name', 'changed_columns'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
            'changed_columns' => 'array',
        ];
    }

    /**
     * Get a human-readable message for the audit log.
     */
    protected function message(): Attribute
    {
        return Attribute::get(function () {
            $table = str_replace('_', ' ', $this->table_name ?? 'unknown table');
            return ucfirst($this->action ?? 'performed action') . " on " . $table;
        });
    }

    /**
     * Scope a query to filter by table name.
     */
    public function scopeForTable($query, string $tableName)
    {
        return $query->where('table_name', $tableName);
    }
}
