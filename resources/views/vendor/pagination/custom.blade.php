<style>
    .pagination a,
    .pagination span {
        margin: 0 5px;
    }

    .pagination a {
        text-decoration: none;
        color: rgb(192, 192, 192)
    }

    .pagination .active {
        font-weight: bold;
    }
</style>
<div class="pagination">
    @if ($paginator->onFirstPage())
        <span class="disabled">« «</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">« «&nbsp;</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">&nbsp;» »</a>
    @else
        <span class="disabled">» »</span>
    @endif
</div>
