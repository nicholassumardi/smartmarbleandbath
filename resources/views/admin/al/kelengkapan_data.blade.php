<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Kelengkapan Data</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('website/progress_tni_al.jpg') }}" data-src="" data-magnify="gallery" data-group="a" data-caption="Progres TNI AL" class="btn bg-info btn-labeled mr-2 btn-labeled-left">
						<b><i class="icon-split"></i></b> Progres TNI AL
					</a>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">Kelengkapan Data</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Kelengkapan Data</h5>
			</div>
			<div class="card-body">
				<div class="alert alert-info border-0 alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
					<span class="font-weight-semibold">Info!</span> Tekan nama proyek untuk membuka <b>Daftar Dokumen</b> untuk dichecklist.
				</div>
				<div class="row">
					@foreach($proyek as $p)
						<div class="col-md-3 pt-3">
							<a href="javascript:void(0);" class="holderChange" data-id="{{ $p->id }}"  data-target="#collapse{{ $p->id }}" data-toggle="collapse">
								<div class="row">
									<div class="col-3 holderIcon" id="holderIcon{{ $p->id }}">
										<i class="icon-folder icon-3x"></i>
									</div>
									<div class="col-9">
										<h5>{{ strlen($p->code.' - '.$p->name) > 50 ? substr($p->code.' - '.$p->name,0,50)."..." : $p->code.' - '.$p->name }}</h5>
										<h6>Jumlah : <span id="jumlahdocument{{ $p->id }}">{{ count($p->alDocumentProject) }}</span> Dokumen</h6>
									</div>
								</div>
							</a>
							<div class="collapse holderCollapse" id="collapse{{ $p->id }}">
								<div class="mt-3 border">
									<div class="card">
										<div class="card-body">
											<ul class="media-list mb-3">
												@foreach($document as $d)
													<li class="media">
														<div class="mr-3">
															<input type="checkbox" class="form-input-styled" id="document{{ $p->id.$d->id }}" onclick="createDocumentChecklist({{ $p->id }},{{ $d->id }},this)" name="document{{ $p->id }}" {{ $p->checkDocument($d->id) }}>
														</div>

														<div class="media-body">
															<h6 class="media-title">
																<label for="document{{ $p->id.$d->id }}" class="font-weight-semibold cursor-pointer mb-0">{{ $d->document_name }}</label>
															</h6>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<a class="btn bg-teal-400 btn-labeled btn-labeled-left btn-block" href="javascript:void(0);" onclick="return checkAvailable({{ $p->id }});">
									<b><i class="icon-printer4"></i></b> Cetak
								</a>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			$('.content-wrapper').click(function() {
				resetHolder();
			});
			
			$('.holderChange').click(function(event) {
				resetHolder();
				$('#collapse' + $(this).data('id')).addClass('show');
				$('#holderIcon' + $(this).data('id')).empty();
				$('#holderIcon' + $(this).data('id')).append(`
					<i class="icon-folder-open icon-3x"></i>
				`);
				event.stopPropagation();
			});
			
			$('.holderCollapse').click(function(event) {
				event.stopPropagation();
			});
		});
		
		function checkAvailable(project){
			if($('#jumlahdocument' + project).text() == '0'){
				notif('error', 'bg-danger', 'Proyek ini belum memiliki dokumen, silahkan checklist terlebih dahulu.');
			}else{
				window.open('{{ url("admin/al/kelengkapan_data/project/print") }}/' + project, '_blank');
			}
		}
		
		function createDocumentChecklist(project,doc,element){
			
			var nilai = 0;
			
			if($(element).is(':checked')){
				nilai = 1;
			}
			
			$.ajax({
				  url: '{{ url("admin/al/kelengkapan_data/add_document") }}',
				  type: 'POST',
				  dataType: 'JSON',
				  data: {
					 project: project,
					 doc: doc,
					 value: nilai
				  },
				  headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  },
				  success: function(response) {
					 if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						
						var total = 0;
						$("input[name='document" + project + "']").each(function() {
							if( $(this).is(':checked') ){
								total++;
							}
						});
						
						$('#jumlahdocument' + project).text(total);
						
					 } else {
						notif('error', 'bg-danger', response.message);
					 }
				  },
				  error: function() {
					 swalInit.fire({
						title: 'Server Error',
						text: 'Please contact developer',
						type: 'error'
					 });
				  }
			});
		}
		
		function resetHolder(){
			$('.holderIcon').each(function() {
				$('.holderCollapse').removeClass('show');
				$('.holderIcon').empty();
				$('.holderIcon').append(`
					<i class="icon-folder icon-3x"></i>
				`);
			});
		}
		
		function destroy(id) {
		  var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Apakah ingin dihapus?</h6><label>Data yang terhapus tidak bisa dikembalikan.</label>',
			 timeout: false,
			 modal: true,
			 layout: 'center',
			 closeWith: 'button',
			 type: 'confirm',
			 buttons: [
				Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
				   notyConfirm.close();
				}),
				Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				   $.ajax({
					  url: '{{ url("admin/al/kelengkapan_data/destroy") }}',
					  type: 'POST',
					  dataType: 'JSON',
					  data: {
						 id: id
					  },
					  headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					  success: function(response) {
						 if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							notyConfirm.close();
							location.reload();
						 } else {
							notif('error', 'bg-danger', response.message);
						 }
					  },
					  error: function() {
						 swalInit.fire({
							title: 'Server Error',
							text: 'Please contact developer',
							type: 'error'
						 });
					  }
				   });
				})
			 ]
		  }).show();
		}
	</script>