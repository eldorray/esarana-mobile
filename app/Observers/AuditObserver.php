<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model, null, $this->getValues($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $dirty = array_keys($model->getDirty());
        $old   = $this->getValues(array_intersect_key($model->getOriginal(), array_flip($dirty)));
        $new   = $this->getValues(array_intersect_key($model->getAttributes(), array_flip($dirty)));

        // Skip if only timestamps changed
        $meaningful = array_diff($dirty, AuditLog::HIDDEN_FIELDS);
        if (empty($meaningful)) return;

        $this->log('updated', $model, $old, $new);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model, $this->getValues($model->getAttributes()), null);
    }

    private function getValues(array $attributes): array
    {
        return array_diff_key($attributes, array_flip(AuditLog::HIDDEN_FIELDS));
    }

    private function log(string $action, Model $model, ?array $old, ?array $new): void
    {
        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => get_class($model),
            'model_id'    => $model->getKey(),
            'model_label' => $this->getLabel($model),
            'old_values'  => $old,
            'new_values'  => $new,
            'ip_address'  => request()->ip(),
        ]);
    }

    private function getLabel(Model $model): string
    {
        return $model->nama
            ?? $model->name
            ?? $model->title
            ?? ('#' . $model->getKey());
    }
}
