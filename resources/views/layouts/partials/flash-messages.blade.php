@if (session('status'))
    <div class="notice notice-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="notice notice-danger">
        <strong>Perlu diperbaiki:</strong>
        <ul class="notice-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
