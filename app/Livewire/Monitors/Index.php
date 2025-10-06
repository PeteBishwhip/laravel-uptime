<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteMonitor($monitorId)
    {
        $monitor = Monitor::findOrFail($monitorId);
        $monitor->delete();

        session()->flash('message', 'Monitor deleted successfully.');
    }

    public function toggleActive($monitorId)
    {
        $monitor = Monitor::findOrFail($monitorId);
        $monitor->update(['is_active' => !$monitor->is_active]);
    }

    public function render()
    {
        $monitors = Monitor::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('url', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.monitors.index', [
            'monitors' => $monitors,
        ]);
    }
}
