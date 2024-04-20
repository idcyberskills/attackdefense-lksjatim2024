@foreach ($errors->all() as $error)
    <div class="alert alert-error">{{ $error }}</div>
@endforeach