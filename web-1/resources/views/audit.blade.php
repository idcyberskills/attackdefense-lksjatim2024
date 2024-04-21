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
        <div>
            <div class="card">
            @php
                $data = unserialize($l->message);
            @endphp
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
        @endforeach
</body>

</html>
</body>

</html>