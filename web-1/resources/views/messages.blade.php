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
        <form class="form" method="GET" action="{{ route('view_messages') }}" />
        <fieldset class="form-group">
            <label for="username">Search</label>
            <input id="search" name="search_query" type="text" placeholder="Enter search query..." class="form-control">
            <input id="search" name="column" type="hidden" value="created_at" class="form-control">
        </fieldset>
        <div class="form-actions">
            <input type="submit" class="btn btn-primary btn-block btn-ghost" name="search" />
        </div>
        </form>
    </div>
    <br />
    <div class="grid main-form">
        @if (empty($messages))
        <div class="alert alert-warning">No messages found.</div>
        @endif
        @foreach ($messages as $m)
        <div>
            <div class="card">
                <header class="card-header card-title-with-delete">
                    <div class="left-div">
                        To: {{ $m->recipient->username }} ({{ $m->recipient->email }}) 
                        <br />
                        From: {{ $m->sender->username }} ({{ $m->sender->email }})
                    </div>
                    @if(Auth::user()->id == $m->sender->id)
                    <div class="right-div">
                        <a href="/message/delete/{{ $m->id }}"><button type="button" class="btn btn-error"><i class="fas fa-trash"></i></button></a>
                    </div>
                    @endif
                </header>
                <header class="card-header message-title">{{ $m->title }}</header>
                <div class="card-content">
                    <div class="inner">{{ $m->message }}</div>
                </div>
            </div>
        </div>
        @endforeach
</body>

</html>
</body>

</html>