@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <ul id="dragRoot">
                @foreach($mainCategories as $mainCategory)
                    <li>
                        <i class="icon-building"></i>
                        <span class="node-facility" data-id="{{ $mainCategory->id }}">
                            {{ $mainCategory->name }}
                            <span class="pl-2 buttons" hidden>
                                <a class="px-1" href="{{ route('categories.create', ['parent_id' => $mainCategory->id]) }}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>

                                <a class="px-1" href="{{ route('categories.edit', $mainCategory->id) }}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            </span>
                        </span>
                        @if($mainCategory->subcategories->count())
                            <ul>
                                @foreach($mainCategory->subcategories as $category)
                                    <li>
                                        <i class="icon-building"></i>
                                        <span class="node-cpe" data-id="{{ $category->id }}">
                                            {{ $category->name }}
                                            <span class="pl-2 buttons" hidden>
                                                <a class="px-1" href="{{ route('categories.create', ['parent_id' => $category->id]) }}">
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
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.node-cpe').on('mousedown', function () {
            window.elementId = $(this).data('id')
        });

        $('.node-cpe').mouseover(function () {
            $(this).children('.buttons').attr('hidden', false)
        });

        $('.node-cpe').mouseout(function () {
            $(this).children('.buttons').attr('hidden', true)
        });

        var DragAndDrop = (function (DragAndDrop) {

            function shouldAcceptDrop(item) {

                var $target = $(this).closest("li");
                var $item = item.closest("li");

                if ($.contains($item[0], $target[0])) {
                    // can't drop on one of your children!
                    return false;
                }

                return true;
            }

            function itemOver(event, ui) {
            }

            function itemOut(event, ui) {
            }

            function itemDropped(event, ui) {

                var $target = $(this).closest("li");
                var $item = ui.draggable.closest("li");

                var $srcUL = $item.parent("ul");
                var $dstUL = $target.children("ul").first();

                let $newParentId = $(this).data('id');
                var $childId = window.elementId;

                // destination may not have a UL yet
                if ($dstUL.length == 0) {
                    $dstUL = $("<ul></ul>");
                    $target.append($dstUL);
                }

                $item.slideUp(50, function () {
                    $dstUL.append($item);

                    if ($srcUL.children("li").length == 0) {
                        $srcUL.remove();
                    }

                    $item.slideDown(50, function () {
                        $item.css('display', '');
                    });

                    var getEndpoint = "{{ route('categories.update', 'CATEGORY_ID') }}";
                    var url = getEndpoint.replace('CATEGORY_ID', $childId);

                    $.ajax({
                        method: "PUT",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'new_parent_id': $newParentId
                        }
                    });
                });

            }

            DragAndDrop.enable = function (selector) {
                $(selector).find(".node-cpe").draggable({
                    helper: "clone"
                });

                $(selector).find(".node-cpe, .node-facility").droppable({
                    activeClass: "active",
                    hoverClass: "hover",
                    accept: shouldAcceptDrop,
                    over: itemOver,
                    out: itemOut,
                    drop: itemDropped,
                    greedy: true,
                    tolerance: "pointer",
                });

            };

            return DragAndDrop;

        })(DragAndDrop || {});

        (function ($) {

            $.fn.beginEditing = function (whenDone) {
                if (!whenDone) {
                    whenDone = function () {

                    };
                }

                var $node = this;

                var $editor = $("<input type='text' style='width:auto; min-width: 25px;'></input>");
                var currentValue = $node.text();

                function commit() {
                    $editor.remove();
                    $node.text($editor.val());
                    whenDone($node);

                    var getEndpoint = "{{ route('categories.update', 'CATEGORY_ID') }}";
                    var url = getEndpoint.replace('CATEGORY_ID', window.elementId);

                    $.ajax({
                        method: "PUT",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'name': $editor.val()
                        }
                    });
                }

                function cancel() {
                    $editor.remove();
                    $node.text(currentValue);
                    whenDone($node);


                }

                $editor.val(currentValue);
                $editor.blur(function () {
                    commit();
                });
                $editor.keydown(function (event) {
                    if (event.which == 27) {
                        cancel();
                        return false;
                    } else if (event.which == 13) {
                        commit();
                        return false;
                    }
                });

                $node.empty();
                $node.append($editor);
                $editor.focus();
                $editor.select();

            };

        })(jQuery);

        $(function () {
            DragAndDrop.enable("#dragRoot");

            $(document).on("dblclick", "#dragRoot *[class*=node]", function () {
                $(this).beginEditing();
            });

        });
    </script>
@endsection
