<?php

namespace App\Http\Controllers;

use App\Models\StatusPage;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    public function show(string $slug)
    {
        $statusPage = StatusPage::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $monitors = $statusPage->monitors()
            ->with(['checks' => function ($query) {
                $query->latest()->limit(90); // Last 90 checks for sparkline
            }, 'incidents' => function ($query) {
                $query->where('status', 'ongoing')
                    ->orWhere(function ($q) {
                        $q->where('status', 'resolved')
                            ->where('resolved_at', '>=', now()->subDays(7));
                    });
            }])
            ->get();

        return view('status.show', compact('statusPage', 'monitors'));
    }
}
