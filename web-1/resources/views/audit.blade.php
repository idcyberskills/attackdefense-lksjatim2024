<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('header')
</head>

@include('alerts.error')
@include('alerts.success')
@include('nav')

<body class="hack dark">
    <div class="grid main-form">
        @if (empty($logs))
        <div class="alert alert-warning">No logs found.</div>
        @endif
        @foreach ($logs as $l)
        @php
            $data = @unserialize($l->message);
        @endphp
        @if ($data)
        <div>
            <div class="card">            
                <header class="card-header card-title-with-delete">
                    <div class="left-div">
                        Action: {{ $data['action'] }}
                    </div>
                </header>
                <header class="card-header message-title">From {{ $l->remote_addr }}</header>
                <header class="card-header message-title">{{ $l->user_agent }}</header>
                <div class="card-content">
                    <div class="inner">Raw Message:</div>
                    <div class="inner">{{ $l->message }}</div>
                </div>
            </div>
        </div>
        @else
        <div style="width: 100%">
            <div class="alert alert-warning">Log {{ $l->id }} cannot be displayed due to errors.</div>
        </div>
        @endif
        @endforeach
</body>

</html>
</body>

</html>