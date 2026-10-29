@extends(config('errorlog.dashboard.layout') ?: 'errorlog::layout')

@section('content')
    @include('errorlog::partials.custom-css')

    <div class="el-header">
        <h1>Error<span>Log</span></h1>
        <a class="el-back" href="{{ route('errorlog.index') }}">&larr; All errors</a>
    </div>

    <div class="el-card">
        <div class="el-detail">
            <span class="el-class">{{ class_basename($group->exception_class) }}</span>
            <div class="el-message" style="font-size:1rem;margin-top:.35rem">{{ $group->message }}</div>
            <div class="el-meta">{{ $group->exception_class }}</div>

            <dl>
                <dt>Location</dt>
                <dd>{{ $group->file }}:{{ $group->line }}</dd>
                <dt>First seen</dt>
                <dd>{{ optional($events->getCollection()->min('occurred_at'))->toDateTimeString() }}</dd>
                <dt>Last seen</dt>
                <dd>{{ optional($group->occurred_at)->toDateTimeString() }}</dd>
                <dt>URL</dt>
                <dd>{{ $group->payload['method'] ?? '' }} {{ $group->payload['url'] ?? '—' }}</dd>
                @if(! empty($group->payload['input']))
                    <dt>Input</dt>
                    <dd><pre style="margin:0;white-space:pre-wrap;word-break:break-all">{{ json_encode($group->payload['input'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre></dd>
                @endif
                <dt>Environment</dt>
                <dd>{{ $group->environment ?? '—' }} &middot; PHP {{ $group->payload['php_version'] ?? '?' }} &middot; Laravel {{ $group->payload['laravel_version'] ?? '?' }}</dd>
                <dt>User</dt>
                <dd>{{ $group->user_id ?? '—' }}</dd>
                <dt>IP</dt>
                <dd>{{ $group->payload['ip'] ?? '—' }}</dd>
                <dt>User agent</dt>
                <dd>{{ $group->payload['user_agent'] ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    @foreach($events as $event)
        <div class="el-card">
            <div class="el-detail" style="padding-bottom:.5rem">
                <strong style="font-size:.85rem">{{ optional($event->occurred_at)->toDateTimeString() }}</strong>
                <span class="el-meta">{{ $event->payload['url'] ?? 'CLI' }}</span>
            </div>
            <div style="padding:0 1.25rem 1.25rem">
                <div class="el-trace">
                    <div class="fr"><span class="loc">{{ $event->file }}:{{ $event->line }}</span> &larr; thrown here</div>
                    @foreach($event->payload['trace'] ?? [] as $frame)
                        <div class="fr">{{ $frame['function'] ?: '{main}' }} <span class="loc">{{ $frame['file'] }}:{{ $frame['line'] }}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="el-paginate">
        {{ $events->links() }}
    </div>
@endsection
