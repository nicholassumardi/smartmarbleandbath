<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="text-right">
            <div class="row justify-content-end">
               <div class="col-md-2">
                  <form action="" method="GET">
                     <div class="form-group">
                        <select name="type" id="type" class="form-control font-size-13" onchange="loadingOpen('body'); $(this).parents('form').submit();">
                           <option value="">Latest</option>
                           <option value="used" {{ $type == 'used' ? 'selected' : '' }}>Used</option>
                           <option value="will_be_end" {{ $type == 'will_be_end' ? 'selected' : '' }}>Will Be End</option>
                           <option value="expired" {{ $type == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                     </div>
                  </form>
               </div>
            </div>
            @if($voucher->count() > 0)
               <div class="row gutter-30">
                  @foreach($voucher as $v)
                     <div class="col-md-6 text-left">
                        <div class="card">
                           <div class="card-body">
                              <p class="text-right mb-2 font-size-12">
                                 @if($type == 'latest' || $type == 'will_be_end')
                                    @if($v->voucherable)
                                       <a href="{{ url('product?' . strtolower($v->voucherType()) . '=' . ($v->voucherable_type == 'brands' ? $v->voucherable->code : $v->voucherable->slug)) }}" class="text-teal font-weight-medium">Use Now <i class="icon-arrow-right2"></i></a>
                                    @else
                                       <a href="{{ url('product') }}" class="text-teal font-weight-medium">Use Now <i class="icon-arrow-right2"></i></a>
                                    @endif
                                 @endif
                              </p>
                              <h6 class="card-title mb-2">{{ $v->name }}</h6>
                              <div class="mb-1">
                                 <span class="font-size-11">
                                    Code : {{ $v->code }}
                                 </span>
                              </div>
                              <div class="mb-1">
                                 <span class="font-size-11 text-uppercase">
                                    {{ $v->voucherType() }} | {{ $v->percentage }}%
                                 </span>
                              </div>
                              <span class="text-teal font-size-12 font-weight-medium">
                                 @if($type == 'used')
                                    Used On {{ date('d F Y', strtotime($v->usedVoucher()->created_at)) }}.   
                                 @elseif($type == 'will_be_end')
                                    Will Be End On {{ date('d F Y', strtotime($v->finish_date)) }}.   
                                 @elseif($type == 'expired')
                                    Has Expired
                                 @else
                                    Expired On {{ date('d F Y', strtotime($v->finish_date)) }}.   
                                 @endif   
                              </span>
                              <p class="text-right mt-2 mb-0 font-size-12">
                                 <a href="javascript:void(0);" class="text-primary font-weight-medium" data-toggle="modal" data-target="#modal_{{ $v->id }}">Terms & Conditions</a>
                              </p>
                           </div>
                        </div>
                        <div class="modal fade" id="modal_{{ $v->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                       <div>{{ $v->name }}</div>
                                       <span class="text-teal font-size-12">
                                          @if($type == 'used')
                                             Used On {{ date('d F Y', strtotime($v->usedVoucher()->created_at)) }}.   
                                          @elseif($type == 'will_be_end')
                                             Will Be End On {{ date('d F Y', strtotime($v->finish_date)) }}.   
                                          @elseif($type == 'expired')
                                             Has Expired
                                          @else
                                             Expired On {{ date('d F Y', strtotime($v->finish_date)) }}.   
                                          @endif   
                                       </span>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Code :</div>
                                       <span class="text-muted font-size-12">{{ $v->code }}</span>
                                    </p>
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Voucher :</div>
                                       <span class="text-muted font-size-12">{{ $v->voucherType() }}</span>
                                    </p>
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Type :</div>
                                       <span class="text-muted font-size-12">{{ $v->voucherType() }}</span>
                                    </p>
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Discount :</div>
                                       <span class="text-muted font-size-12">{{ $v->percentage }}%</span>
                                    </p>
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Description :</div>
                                       <span class="text-muted font-size-12">
                                          @if($v->voucherable)
                                             Only valid for purchases of the {{ $v->voucherable->name }} {{ strtolower($v->voucherType()) }}, with a {{ $v->percentage }}% discount.
                                          @else
                                             Valid for all brands and category with a {{ $v->percentage }}% discount.
                                          @endif
                                          Minimum purchase {{ number_format($v->minimum, 0, ',', '.') }} and maximum discount {{ number_format($v->maximum, 0, ',', '.') }}.
                                       </span>
                                    </p>
                                    <p class="m-0 p-0">
                                       <div class="font-weight-bold font-size-12">Terms & Conditions</div>
                                       <span class="text-muted font-size-12">{!! $v->terms !!}</span>
                                    </p>
                                 </div>
                                 <div class="modal-footer">
                                    @if($type == 'will_be_end' || empty($type))
                                       @if($v->voucherable)
                                          <a href="{{ url('product?' . strtolower($v->voucherType()) . '=' . ($v->voucherable_type == 'brands' ? $v->voucherable->code : $v->voucherable->slug)) }}" class="button button-small bg-teal col-12 text-center">Use Now</a>
                                       @else
                                          <a href="{{ url('product') }}" class="button button-small bg-teal col-12 text-center">Use Now</a>
                                       @endif
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               </div>
               <div class="mt-4">
                  {{ $voucher->withQueryString()->onEachSide(1)->links('pagination') }}
               </div>
            @else
               <div class="alert alert-warning font-size-13 text-center">Voucher not found</div>
            @endif
         </div>
      </div>
   </div>
</section>