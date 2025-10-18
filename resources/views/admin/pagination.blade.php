@if($paginator->hasPages())
    <nav class="table-responsive">
        <ul class="justify-content-center pagination pagination-separated pagination-rounded align-self-center">
            @if($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                        &larr; &nbsp; Prev
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        &larr; &nbsp; Prev
                    </a>
                </li>
            @endif

            @foreach($elements as $element)
                @if(is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <a class="page-link" href="javascript:void(0);">{{ $element }}</a>
                    </li>
                @endif

                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" syle="border: none !important;" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        Next &nbsp; &rarr;
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <a class="page-link" href="javascript:void(0);" aria-hidden="true">
                        Next &nbsp; &rarr;
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
