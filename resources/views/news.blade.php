<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row gutter-40 col-mb-80">
            <div class="postcontent col-lg-9">
               <div id="posts" class="row grid-container gutter-30">
                  @if($news->count() > 0)
                     @foreach($news as $n)
                        <div class="entry col-12">
                           <div class="grid-inner">
                              <div class="entry-image">
                                 <a href="{{ $n->image() }}" data-lightbox="image">
                                    <img src="{{ $n->image() }}" style="max-width:1920px; max-height:445px;" alt="{{ $n->title }}">
                                 </a>
                              </div>
                              <div class="entry-title">
                                 <h2>
                                    <a href="{{ url('news/detail/' . $n->slug) }}">{{ $n->title }}</a>
                                 </h2>
                              </div>
                              <div class="entry-meta">
                                 <ul>
                                    <li>
                                       <i class="icon-calendar3"></i> 
                                       {{ date('d F Y', strtotime($n->created_at)) }}
                                    </li>
                                    <li>
                                       <a href="javascript:void(0);">
                                          <i class="icon-user"></i> {{ $n->user->name }}</a>
                                       </li>
                                    <li>
                                       <i class="icon-folder-open"></i> 
                                       <a href="{{ url('news?category=' . $n->category->slug) }}">{{ $n->category->name }}</a>
                                    </li>
                                 </ul>
                              </div>
                              <div class="entry-content">
                                 <a href="{{ url('news/detail/' . $n->slug) }}" class="more-link">Read More</a>
                              </div>
                           </div>
                        </div>
                     @endforeach
                  {{ $news->withQueryString()->onEachSide(1)->links('pagination') }}
                  @else
                     <div class="entry col-12">
                        <div class="text-center">
                           <div class="alert alert-warning">Post not found</div>
                        </div>
                     </div>
                  @endif
               </div>
            </div>
            <div class="sidebar col-lg-3">
               <div class="sidebar-widgets-wrap">
                  <div class="widget widget-twitter-feed clearfix">
                     <h4 class="text-uppercase">Category</h4>
                     <ul class="iconlist twitter-feed" data-username="envato" data-count="2">
                        <li class="mb-2">
                           <a href="{{ url('news') }}" class="font-size-13">All</a>
                        </li>
                        @foreach($category as $c)
                           <li class="mb-2">
                              <a href="{{ url('news?category=' . $c->slug) }}" class="font-size-13">{{ $c->name }}</a>
                           </li>
                        @endforeach
                     </ul>
                  </div>
                  <div class="widget clearfix">
                     <h4>Tag Cloud</h4>
                     <div class="tagcloud">
                        @foreach($tags as $t)
                           <a href="{{ url('news?tags=' . $t->tags) }}">{{ strtolower($t->tags) }}</a>
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>