<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         @if($points->count() > 0)
            @foreach($points as $p)
               <div class="card mb-4">
                  <div class="card-body mb-0">
                     <div class="card-title">
                        <div class="font-weight-bold">{{ $p->points > 0 ? 'Get Points' : 'Redeem Points' }}</div>
                        <div class="font-size-14">
                           {{ $p->points > 0 ? 'Purchase from order' : 'Obtained from order' }} 
                           <span class="font-italic">{{ $p->order->number }}</span>
                        </div>
                        <div class="font-size-12 text-muted">{{ date('d F Y | H:i', strtotime($p->created_at)) }}</div>
                     </div>
                     <span class="float-right">
                        <h4 class="{{ $p->points > 0 ? 'text-teal' : 'text-danger' }} mb-0">{{ number_format($p->points, 0, ',', '.') }}</h4>
                     </span>
                  </div>
               </div>
            @endforeach
            <div class="mt-4">
               {{ $points->withQueryString()->onEachSide(1)->links('pagination') }}
            </div>
         @else
            <div class="alert alert-warning font-size-13 text-center">Points not found</div>
         @endif
      </div>
   </div>
</section>