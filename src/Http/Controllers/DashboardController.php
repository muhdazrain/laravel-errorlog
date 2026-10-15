<?php

namespace Azrain\LaravelErrorLog\Http\Controllers;

use Azrain\LaravelErrorLog\Models\ErrorLogEvent;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $groups = ErrorLogEvent::query()
            ->selectRaw('fingerprint, exception_class, MAX(message) as message, COUNT(*) as events_count, MAX(occurred_at) as last_seen_at, MIN(occurred_at) as first_seen_at')
            ->groupBy('fingerprint', 'exception_class')
            ->orderByDesc('last_seen_at')
            ->paginate(25);

        return view('errorlog::index', compact('groups'));
    }

    public function show(string $fingerprint)
    {
        $events = ErrorLogEvent::query()
            ->where('fingerprint', $fingerprint)
            ->latest('occurred_at')
            ->paginate(20);

        abort_if($events->isEmpty(), 404);

        return view('errorlog::show', [
            'events' => $events,
            'group' => $events->first(),
        ]);
    }
}
