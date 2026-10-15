@extends(config('errorlog.dashboard.layout') ?: 'errorlog::layout')

@section('content')
    @include('errorlog::partials.custom-css')

    <div class="el-header">
        <h1>Error<span>Log</span></h1>
    </div>

    <div class="el-card">
        @if($groups->isEmpty())
            <div class="el-empty">No errors recorded yet.</div>
        @else
            <table class="el-table">
                <thead>
                    <tr>
                        <th>Exception</th>
                        <th style="width:80px">Events</th>
                        <th style="width:170px">Last seen</th>
                        <th style="width:170px">First seen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                        <tr>
                            <td>
                                <a class="el-link" href="{{ route('errorlog.show', $group->fingerprint) }}">
                                    <span class="el-class">{{ class_basename($group->exception_class) }}</span>
                                    <div class="el-message">{{ \Illuminate\Support\Str::limit($group->message, 140) }}</div>
                                    <div class="el-meta">{{ $group->exception_class }}</div>
                                </a>
                            </td>
                            <td><span class="el-count">{{ $group->events_count }}</span></td>
                            <td class="el-time">{{ \Carbon\Carbon::parse($group->last_seen_at)->diffForHumans() }}</td>
                            <td class="el-time">{{ \Carbon\Carbon::parse($group->first_seen_at)->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="el-paginate">
        {{ $groups->links() }}
    </div>
@endsection
