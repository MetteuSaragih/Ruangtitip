{{-- Paginasi sederhana tanpa Tailwind. Kebutuhan: $p (LengthAwarePaginator). --}}
@if ($p->total() > 0)
  <div class="pager">
    <span>Menampilkan {{ $p->firstItem() }}-{{ $p->lastItem() }} dari {{ $p->total() }}</span>
    @if ($p->hasPages())
      <nav aria-label="Halaman">
        @if ($p->onFirstPage())
          <span class="pg off">Sebelumnya</span>
        @else
          <a href="{{ $p->url($p->currentPage() - 1) }}" rel="prev">Sebelumnya</a>
        @endif
        <span class="pg on">{{ $p->currentPage() }} / {{ $p->lastPage() }}</span>
        @if ($p->hasMorePages())
          <a href="{{ $p->url($p->currentPage() + 1) }}" rel="next">Berikutnya</a>
        @else
          <span class="pg off">Berikutnya</span>
        @endif
      </nav>
    @endif
  </div>
@endif
