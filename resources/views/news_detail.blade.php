<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="single-post mb-0">
            <div class="entry clearfix">
               <div class="entry-title">
                  <h2>{{ $news->title }}</h2>
               </div>
               <div class="entry-meta">
                  <ul>
                     <li>
                        <i class="icon-calendar3"></i> 
                        {{ date('d F Y', strtotime($news->created_at)) }}
                     </li>
                     <li>
                        <a href="javascript:void(0);">
                           <i class="icon-user"></i> {{ $news->user->name }}</a>
                        </li>
                     <li>
                        <i class="icon-folder-open"></i> 
                        <a href="{{ url('news?category=' . $news->category->slug) }}">{{ $news->category->name }}</a>
                     </li>
                  </ul>
               </div>
               <div class="entry-image bottommargin">
                  <a href="{{ $news->image() }}">
                     <img src="{{ $news->image() }}" alt="{{ $news->title }}">
                  </a>
               </div>
               <div class="entry-content mt-0">
                  {!! $news->description !!}
               </div>
            </div>
            @if($related_news->count() > 0)
               <div class="line"></div>
               <h4>Related Posts:</h4>
               <div class="related-posts row posts-md col-mb-30">
                  @foreach($related_news as $rn)
                     <div class="entry col-12 col-md-6">
                        <div class="grid-inner row align-items-center gutter-20">
                           <div class="col-4 col-xl-5">
                              <div class="entry-image">
                                 <a href="{{ url('news/detail/' . $rn->slug) }}">
                                    <img src="{{ $rn->image() }}" alt="{{ $rn->title }}" style="max-width:242px; max-height:181px;">
                                 </a>
                              </div>
                           </div>
                           <div class="col-8 col-xl-7">
                              <div class="entry-title title-xs nott">
                                 <h3 class="limit-text-product">
                                    <a href="{{ url('news/detail/' . $rn->slug) }}">{{ $rn->title }}</a>
                                 </h3>
                              </div>
                              <div class="entry-meta">
                                 <ul>
                                    <li>
                                       <i class="icon-calendar3"></i> {{ date('d F Y', strtotime($rn->created_at)) }}
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               </div>
            @endif
         </div>
      </div>
   </div>
</section>