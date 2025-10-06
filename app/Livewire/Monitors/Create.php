<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $type = 'http_ping';
    public $url = '';
    public $check_interval = 60;
    public $timeout = 10;
    public $method = 'GET';
    public $expected_status_codes = '200';
    public $keyword = '';
    public $keyword_present = true;
    public $grace_period = 0;
    public $expected_heartbeat = 3600;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:http_ping,heartbeat,ssl_certificate',
            'check_interval' => 'required|integer|min:30',
            'timeout' => 'required|integer|min:1|max:60',
        ];

        if ($this->type === 'http_ping' || $this->type === 'ssl_certificate') {
            $rules['url'] = 'required|url';
            $rules['method'] = 'required|in:GET,POST,PUT,PATCH,DELETE,HEAD';
            $rules['expected_status_codes'] = 'nullable|string';
        }

        if ($this->type === 'heartbeat') {
            $rules['expected_heartbeat'] = 'required|integer|min:60';
            $rules['grace_period'] = 'required|integer|min:0';
        }

        return $rules;
    }

    public function save()
    {
        $validated = $this->validate();

        $expectedCodes = array_map('intval', array_filter(explode(',', $this->expected_status_codes)));

        $monitor = Monitor::create([
            'name' => $this->name,
            'type' => $this->type,
            'url' => $this->url ?: null,
            'check_interval' => $this->check_interval,
            'timeout' => $this->timeout,
            'method' => $this->method,
            'expected_status_codes' => !empty($expectedCodes) ? $expectedCodes : [200],
            'keyword' => $this->keyword ?: null,
            'keyword_present' => $this->keyword_present,
            'grace_period' => $this->grace_period,
            'expected_heartbeat' => $this->expected_heartbeat,
            'is_active' => true,
            'status' => 'unknown',
        ]);

        session()->flash('message', 'Monitor created successfully.');
        
        return redirect()->route('monitors.index');
    }

    public function render()
    {
        return view('livewire.monitors.create');
    }
}
