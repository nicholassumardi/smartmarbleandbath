<section id="content">
   <div class="content-wrap">
      <div class="container">
         @if($wishlist->count() > 0)
            <div id="shop" class="shop row grid-container gutter-30">
               @foreach($wishlist as $w)
                  <div class="product col-lg-3 col-6 mb-4">
                     <div class="grid-inner border">
                        <div class="bg-light">
                           <div class="p-2 font-weight-bold text-center" style="font-size:13px;">
                              {{ $w->product->type->category->name }}
                           </div>
                        </div>
                        <div class="product-image">
                           <a href="{{ url('product/detail/' . Str::slug($w->product->name()) . '?q=' . base64_encode($w->product->id)) }}">
                              <img src="{{ $w->product->type->image() }}" alt="{{ $w->product->name() }}" class="img-fluid product-thumbnail">
                           </a>
                           <div class="sale-flash badge {{ $w->product->availability()->color }} p-2">{{ $w->product->availability()->status }}</div>
                        </div>
                        <div class="product-desc p-3">
                           <div class="product-price font-weight-bold">
                              <ins class="text-dark">
                                 <h1 style="font-size:17px;" class="mb-0 font-weight-bold">Rp {{ number_format($w->product->price(), 0, ',', '.') }}</h1>
                              </ins>
                           </div>
                           <div class="product-title">
                              <h4 class="mb-0 font-weight-normal limit-text-list-product">
                                 <a href="{{ url('product/detail/' . Str::slug($w->product->name()) . '?q=' . base64_encode($w->product->id)) }})) }}" class="font-wight-semibold text-danger" style="font-size:13.5px;">{{ $w->product->name() }}</a>
                              </h4>
                           </div>
                           <div class="product-price font-weight-semibold mt-3">
                              <span>
                                 <div class="row no-gutters">
                                    <div class="col-md-5 mb-2">
                                       <a href="{{ url('product/wishlist_destroy/' . base64_encode($w->id)) }}" class="btn btn-danger btn-sm col-12" style="border-radius:0;">Remove</a>
                                    </div>
                                    <div class="col-md-7">
                                       <a href="{{ url('product/wishlist_to_cart/' . base64_encode($w->id)) }}" class="btn btn-dark btn-sm col-12" style="border-radius:0;">Move To Cart</a>
                                    </div>
                                 </div>
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
               @endforeach
            </div>
         @else
            <div class="alert alert-warning">
               <div class="text-center">You don't have a product on the wishlist</div>
            </div>
         @endif
         {{ $wishlist->withQueryString()->onEachSide(1)->links('pagination') }}
      </div>
   </div>
</section>