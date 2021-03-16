@if($category->subcategories->count())
    <ul>
        @foreach($category->subcategories as $category)
            <li>
                <i class="icon-hdd"></i>
                <span class="node-cpe" data-id="{{ $category->id }}">{{ $category->name }}
                    <span class="pl-2 buttons" hidden>
                        <a class="px-1" href="{{ route('categories.create', ['parent_id' => $category->id]) }}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>

                        <a class="px-1" href="{{ route('categories.edit', $category->id) }}">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                    </span>
                </span>
                @if($category->subcategories->count())
                    <ul>
                        @foreach($category->subcategories as $category)
                            <li>
                                <i class="icon-hdd"></i>
                                <span class="node-cpe" data-id="{{ $category->id }}">
                                    {{ $category->name }}
                                    <span class="pl-2 buttons" hidden>
                                        <a class="px-1"
                                           href="{{ route('categories.create', ['parent_id' => $category->id]) }}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>

                                        <a class="px-1" href="{{ route('categories.edit', $category->id) }}">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    </span>
                                </span>
                                @include('categories._folder-structure-helper',['category' => $category])
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif

