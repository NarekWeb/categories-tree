<nav class="navbar navbar-expand-sm navbar-dark">
    <a class="navbar-brand" href="{{ route('categories') }}">
        <img src="{{ asset('images/contant_logo.png') }}" alt="Logo">
    </a>
    @if(!request()->is('*categories/create*'))
        <div class="navbar-nav ml-auto ">
            <a href="{{ route('categories.create') }}" class="btn btn-primary ">
                <i class="fa fa-plus" aria-hidden="true"></i> Create new category
            </a>
        </div>
    @endif
</nav>
