{{-- Our own pagination links (used by every ->links() call, see AppServiceProvider). --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm disabled">← Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm" rel="prev">← Previous</a>
        @endif

        <span class="muted">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm" rel="next">Next →</a>
        @else
            <span class="btn btn-sm disabled">Next →</span>
        @endif
    </nav>
@endif
