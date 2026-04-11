<?php

namespace App\Livewire\MasterData;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogIndex extends Component
{
    use WithPagination;

    public string $filterAction = '';
    public string $filterModel  = '';
    public string $search       = '';

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterAction(): void { $this->resetPage(); }
    public function updatingFilterModel(): void  { $this->resetPage(); }

    public function render()
    {
        $query = AuditLog::with('user')
            ->when($this->filterAction, fn($q) => $q->where('action', $this->filterAction))
            ->when($this->filterModel,  fn($q) => $q->where('model_type', 'like', "%{$this->filterModel}%"))
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('model_label', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
            }))
            ->latest()
            ->paginate(20);

        $modelTypes = AuditLog::select('model_type')
            ->distinct()
            ->pluck('model_type')
            ->map(fn($t) => ['value' => $t, 'label' => class_basename($t)])
            ->values();

        return view('livewire.master-data.audit-log-index', [
            'logs'       => $query,
            'modelTypes' => $modelTypes,
        ]);
    }
}
