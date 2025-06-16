@if(!$tasks->isEmpty())
    @php
        // Get current filter parameters
        $currentParams = [];
        if(request()->has('status') && request()->input('status')) {
            $currentParams['status'] = request()->input('status');
        }
        if(request()->has('search') && request()->input('search')) {
            $currentParams['search'] = request()->input('search');
        }
    @endphp

    <div class="pagination">
        @if($tasks->previousPageUrl())
            @php
                $prevParams = array_merge($currentParams, ['page' => $tasks->currentPage() - 1]);
                $prevUrl = route('tasks.index', $prevParams);
            @endphp
            <a href="{{ $prevUrl }}" class="pagination-btn pagination-link">« Previous</a>
        @else
            <span class="pagination-btn disabled">« Previous</span>
        @endif
        
        <span class="pagination-info">Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}</span>
        
        @if($tasks->nextPageUrl())
            @php
                $nextParams = array_merge($currentParams, ['page' => $tasks->currentPage() + 1]);
                $nextUrl = route('tasks.index', $nextParams);
            @endphp
            <a href="{{ $nextUrl }}" class="pagination-btn pagination-link">Next »</a>
        @else
            <span class="pagination-btn disabled">Next »</span>
        @endif
    </div>
@endif