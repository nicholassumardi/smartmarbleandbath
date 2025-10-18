<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row gutter-20 justify-content-center">
            <div class="postcontent col-lg-12">
               <div id="faqs" class="faqs">
                  <div id="faqs-list" class="fancy-title title-bottom-border">
                     <h3>Order Tracking</h3>
                  </div>
               </div>
            </div>
			<div class="col-lg-8">
				<div class="card bg-light">
					<div class="card-body" style="height:100px;">
						<div class="row justify-content-center">
							<div class="col-md-8 form-group">
								<input type="text" id="project-number" name="project-number" value="" class="form-control-lg required" placeholder="Enter Order No. Ex: PJ/22/11/29/xxxxxx" style="width:100%;"/>
							</div>
							<div class="col-md-4 form-group text-center">
								<button class="btn btn-secondary btn-block btn-lg" id="submit-tracking">Search</button>
							</div>
						</div>
					</div>
				</div>
			</div>
         </div>
      </div>
   </div>
</section>
<script>
	$('#submit-tracking').on('click', function(e){
		var no = $('#project-number').val();
		
		$.ajax({
			url: '{{ url("information/tracking/get_url") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				no: no
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response) {
				if(response.status == '200'){
					window.location.href = response.url
				}
			},
            error: function() {
                
            }
		});
	});
</script>