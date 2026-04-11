<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'model_label', 'old_values', 'new_values', 'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Columns to exclude from diff (noisy / irrelevant)
    const HIDDEN_FIELDS = ['created_at', 'updated_at', 'remember_token', 'password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getModelShortNameAttribute(): string
    {
        return class_basename($this->model_type);
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'error',
            default   => 'tertiary',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'add_circle',
            'updated' => 'edit',
            'deleted' => 'delete',
            default   => 'info',
        };
    }

    /** Return only the fields that actually changed (for updated events). */
    public function getChangedFieldsAttribute(): array
    {
        if ($this->action !== 'updated' || ! $this->old_values || ! $this->new_values) {
            return [];
        }

        $changed = [];
        foreach ($this->new_values as $key => $new) {
            $old = $this->old_values[$key] ?? null;
            if ($old != $new) {
                $changed[$key] = ['from' => $old, 'to' => $new];
            }
        }
        return $changed;
    }
}
