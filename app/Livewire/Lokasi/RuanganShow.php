<?php

namespace App\Livewire\Lokasi;

use App\Models\Ruangan;
use App\Services\RuanganQrService;
use Livewire\Component;

class RuanganShow extends Component
{
    public Ruangan $ruangan;

    public bool $showQr = false;
    public string $qrDataUri = '';
    public string $qrPayload = '';

    public function mount(Ruangan $ruangan): void
    {
        $this->ruangan = $ruangan->load([
            'lokasi',
            'inventaris.kategori',
            'bahanHabisPakais.kategori',
        ]);
    }

    public function generateQr(): void
    {
        $service = new RuanganQrService();
        $this->qrPayload  = $service->buildPayload($this->ruangan);
        $this->qrDataUri  = $service->generateDataUri($this->qrPayload);
        $this->showQr     = true;
    }

    public function render()
    {
        return view('livewire.lokasi.ruangan-show');
    }
}
