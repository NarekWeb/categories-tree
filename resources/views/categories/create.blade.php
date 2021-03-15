@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h4 class="col-12 text-center py-2 pt-4">Create category</h4>

            <form action="{{ route('categories.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="parent">Parent</label>
                    <select class="form-control" id="parent" name="parent_id">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"{{ $category->id == $parentId ? ' selected' : ''}}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('parent_id'))
                        <span class="text-danger">
                             <strong>{{ $errors->first('parent_id') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="sel1">Name</label>
                    <input type="text" class="form-control" name="name">
                    @if ($errors->has('name'))
                        <span class="text-danger">
                             <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <button type="submit" class="btn btn-default">Create</button>
            </form>
        </div>
    </div>
@endsection
