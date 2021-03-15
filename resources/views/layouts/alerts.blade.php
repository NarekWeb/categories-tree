{{-- Success --}}
@if (session('success'))
    <div class="alert alert-success">
        <strong>Success!</strong> {{ session('success') }}
    </div>
@endif

{{-- Error --}}
@if (session('error'))
    <div class="alert alert-danger">
        <strong>Success!</strong> {{ session('success') }}
    </div>
@endif
