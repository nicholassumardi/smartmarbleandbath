<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Surat Jalan</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('website/progress_tni_al.jpg') }}" data-src="" data-magnify="gallery" data-group="a" data-caption="Progres TNI AL" class="btn bg-info btn-labeled mr-2 btn-labeled-left">
						<b><i class="icon-split"></i></b> Progres TNI AL
					</a>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah SJ
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">Surat Jalan</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Surat Jalan</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>No. SJ</th>
							<th>Nama Proyek</th>
							<th>No.Sph</th>
							<th>Tgl.Kirim</th>
							<th>Tgl.Diterima</th>
							<th>Kepada</th>
							<th>Action</th>
						 </tr>
					  </thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Surat Jalan <span id="title_po"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="form-group">
					<div class="row p-3">
						<div class="col-md-12">
							<form id="form_data">
								<div class="alert alert-danger" id="validation_alert" style="display:none;">
								  <ul id="validation_content"></ul>
								</div>
								<div class="row">
									<div class="col-md-12">
										<h3>Informasi Utama</h3>
										<hr>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Proyek :<span class="text-danger">*</span></label>
											<input type="hidden" id="temp" name="temp">
											<select name="al_project_id" id="al_project_id" class="select2" onchange="getSph(this.value);">
												 <option value="">-- Pilih satu --</option>
												 @foreach($proyek as $p)
													<option value="{{ $p->id }}">{{ $p->name }}</option> 
												 @endforeach
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>SPH :<span class="text-danger">*</span></label>
											<select name="al_sph_id" id="al_sph_id" class="select2" onchange="getSphProduct(this.value);">
												<option value="">-- Pilih proyek --</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Kepada :<span class="text-danger">*</span></label>
											<input type="text" name="to_whom" id="to_whom" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Supir :<span class="text-danger">*</span></label>
											<input type="text" name="driver" id="driver" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Tipe Kendaraan :<span class="text-danger">*</span></label>
											<input type="text" name="vehicle_type" id="vehicle_type" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Nomor Kendaraan :<span class="text-danger">*</span></label>
											<input type="text" name="vehicle_no" id="vehicle_no" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Kota TTD :<span class="text-danger">*</span></label>
											<select name="city_id" id="city_id"></select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Tanggal Kirim :<span class="text-danger">*</span></label>
											<input type="date" name="date_sent" id="date_sent" class="form-control">
										</div>
									</div>
									<!-- <div class="col-md-3">
										<div class="form-group">
											<label>Tanggal Diterima :<span class="text-danger">*</span></label>
											<input type="date" name="date_received" id="date_received" class="form-control">
										</div>
									</div> -->
									<div class="col-md-12">
										<div class="form-group">
											<label>Catatan :<span class="text-danger">*</span></label>
											<textarea name="note" id="note" class="form-control" rows="1" placeholder="Catatan tambahan keterangan" rows="1"></textarea>
										</div>
									</div>
								</div>
								<div class="form-group"><hr></div>
								<div class="row">
									<div class="col-md-12">
										<h3>Detail Produk</h3>
										<hr>
									</div>
									<div class="col-md-12 mt-3">
										<table class="table table-bordered table-striped">
											<thead class="bg-dark">
												<tr class="text-center">
													<th width="5%">No</th>
													<th width="25%">Produk</th>
													<th width="25%">Spesifikasi</th>
													<th width="10%">Qty</th>
													<th width="5%">Unit</th>
													<th width="20%">Keterangan</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											<tbody id="detail_product">
												<tr class="text-center">
													<td colspan="7">
														<div class="alert alert-warning alert-styled-left alert-dismissible">
															<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
															<span class="font-weight-semibold">Kosong!</span> Silahkan pilih proyek dan sph.
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div id="modal_date_received" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">Surat Jalan No. <b id="modal_title_received_date"></b></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal-body-received">
					<div class="form-group"><hr></div>
					<h5 class="card-title">
						<b>Tanggal Barang Diterima Customer</b> 
					</h5>
					<div class="row">
					   <div class="col-md-6">
						  <div class="form-group">
							<input type="hidden" id="tempreceived">
							<input type="date" name="received_date" id="received_date" class="form-control" placeholder="Pilih tanggal diterima customer...">
						  </div>
					   </div>
					   <div class="col-md-6">
						  <div class="form-group">
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="received_proof" name="received_proof" class="form-control h-auto filereceived" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
					   </div>
					</div>
					<h5 class="card-title">Pratinjau Bukti</h5>
					<div class="form-group text-center" id="previewImgReceived">
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addReceivedDate()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /small modal -->
	
	<script>
		var sphlist = {!! json_encode($sph,JSON_UNESCAPED_SLASHES) !!};

		$(function() {
			select2ServerSide('#city_id', '{{ url("admin/select2/city") }}');
			ckEditor('note');
			loadDataTable();
			
			$('#detail_product').on('click', '#delete_product', function() {
				$(this).closest('tr').remove();
			});
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				loadDataTable();
				$('#al_project_id').val('').trigger('change');
				$('#al_sph_id').empty();
				$('#al_sph_id').append(`
					<option value="">-- Pilih proyek --</option>
				`);
				$('#date_sent').val('');
				$('#driver').val('');
				$('#vehicle_type').val('');
				$('#vehicle_no').val('');
				$('#city_id').empty();
				$('#temp').val('');
				$('#detail_product').empty();
				$('#detail_product').append(`
					<tr class="text-center">
						<td colspan="7">
							<div class="alert alert-warning alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Kosong!</span> Silahkan pilih proyek dan sph.
							</div>
						</td>
					</tr>
				`);
				CKEDITOR.instances['note'].setData('');
			});
			
			$('#modal_date_received').on('hidden.bs.modal', function (e) {
				$('#modal_title_received_date').html('');
				$('#tempreceived').val('');
			});
			
			$(".filereceived").on('change', function () {
				if($(this)[0].files[0].type == 'application/pdf'){
					$('#previewImgReceived').empty();
					$('#previewImgReceived').append(`
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					`);
				}else{
					if (typeof (FileReader) != "undefined") {

						var image_holder = $("#previewImgReceived");
						image_holder.empty();

						var reader = new FileReader();
						reader.onload = function (e) {
							$("<img />", {
								"src": e.target.result,
								"class": "thumb-image",
								"width": "300px"
							}).appendTo(image_holder);
						};
						image_holder.show();
						reader.readAsDataURL($(this)[0].files[0]);
					} else {
						alert("This browser does not support FileReader.");
					}
				}
				
			});
		});
		
		function addReceivedDate(){
			var id = $('#tempreceived').val(), date = $('#received_date').val();
			var fd = new FormData(), files = $('#received_proof')[0].files;
			var arrProduct = [], arrQty = [];
			fd.append('date',date);
			fd.append('id',id);
			if(files.length > 0 ){
			   fd.append('file',files[0]);
			}
			
			if(date !== ''){
			
				$.ajax({
					url: '{{ url("admin/al/surat_jalan/add_received_date") }}',
					type: 'POST',
					dataType: 'JSON',
					data: fd,
					contentType: false,
					processData: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#modal-body-received');
					},
					success: function(response) {
						if(response.status == '200'){
							loadingClose('#modal-body-received');
							notif('success', 'bg-success', response.message);
							$('#modal_date_received').modal('toggle');
							loadDataTable();
						}else{
							swalInit.fire('Server Error!', response.message, 'error');
						}
						loadingClose('#modal-body-received');
					},
					error: function() {
						loadingClose('#modal-body-received');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
				
			}else{
				swalInit.fire('Server Error!', 'Please choose date first!', 'error');
			}
		}
		
		function loadDataTable() {
		  return $('#datatable_serverside').DataTable({
			 stateSave: true,
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/al/surat_jalan/datatable") }}',
				type: 'GET',
				data: { },
				beforeSend: function() {
				   loadingOpen('#datatable_serverside');
				},
				complete: function() {
				   loadingClose('#datatable_serverside');
				},
				error: function() {
				   loadingClose('#datatable_serverside');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'kode', className: 'text-center align-middle' },
				{ name: 'project', className: 'text-center align-middle' },
				{ name: 'sph', className: 'text-center align-middle' },
				{ name: 'tgl_kirim', className: 'text-center align-middle' },
				{ name: 'tgl_terima', className: 'text-center align-middle' },
				{ name: 'kepada', className: 'text-center align-middle' },
				{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
			 ]
		  }); 
		}
		
		function updateReceivedProof(iddelivery,codedelivery){
			$('#modal_title_received_date').html(codedelivery);
			$('#tempreceived').val(iddelivery);
			$('#modal_date_received').modal('toggle');
		}
		
		function getSph(val){
			if(val !== ''){
				$('#al_sph_id').empty();
				
				$('#al_sph_id').append(`
					<option value="">-- Pilih salah satu --</option>
				`);
				
				sphlist.forEach(function(row) {
					if(val == row['al_project_id']){
						$('#al_sph_id').append(`
							<option value="` + row['id'] + `">` + row['code'] + ` Revision ` + row['revision'] + ` Grandtotal Rp. ` + row['grandtotal'] + `</option>
						`);
					}
				});
			}else{
				$('#al_sph_id').empty();
				$('#al_sph_id').append(`
					<option value="">-- Pilih proyek --</option>
				`);
			}
		}
		
		function getSphProduct(val){
			if(val !== ''){
				$.ajax({
					 url: '{{ url("admin/al/surat_jalan/get_sph_product") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: {
						id: val
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.modal-body');
					 },
					 success: function(response) {
						if(response.data.length > 0){
							$('#detail_product').empty();
							
							$.each(response.data, function(i, val) {
								$('#detail_product').append(`
									<tr class="rowproduct">
										<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
										<td class="text-center">` + (i + 1) + `.</td>
										<td class="text-center">` + val.al_product_name + `</td>
										<td class="text-center">` + val.al_product_spec + `</td>
										<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" class="form-control"></td>
										<td class="text-center">` + val.unit + `</td>
										<td class="text-center"><input type="text" name="al_product_note[]" class="form-control"></td>
										<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
									</tr>
								`);
							});
						}
						
						loadingClose('.modal-body');
					 },
					 error: function() {
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					 }
				 });
			}else{
				$('#detail_product').empty();
				$('#detail_product').append(`
					<tr class="text-center">
						<td colspan="7">
							<div class="alert alert-warning alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Kosong!</span> Silahkan pilih proyek dan sph.
							</div>
						</td>
					</tr>
				`);
			}
		}
		
		function create(){
			CKEDITOR.instances['note'].updateElement();
			$.ajax({
			 url: '{{ url("admin/al/surat_jalan/create") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert').hide();
				$('#validation_content').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   notif('success', 'bg-success', response.message);
				   $('#modal_form').modal('toggle');
				} else if(response.status == 422) {
				   $('#validation_alert').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content').append(`
							<li>` + val + `</li>
						 `);
					  });
					});
				} else {
				   notif('error', 'bg-danger', response.message);
				}
			 },
			 error: function() {
				$('.modal-body').scrollTop(0);
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
			});
		}
		
		function show(id){
			$.ajax({
				 url: '{{ url("admin/al/surat_jalan/show") }}',
				 type: 'GET',
				 dataType: 'JSON',
				 data: {
					id: id
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#temp').val(response.data.id);
					$('#al_project_id').val(response.data.al_project_id).trigger('change');
					
					$('#to_whom').val(response.data.to_whom);
					$('#driver').val(response.data.driver);
					$('#vehicle_type').val(response.data.vehicle_type);
					$('#vehicle_no').val(response.data.vehicle_no);
					$('#city_id').empty();
					$('#city_id').append(`
						<option value="` + response.data.city_id + `">` + response.data.city_name + `</option>
					`);
					
					$('#date_sent').val(response.data.date_sent);
					
					CKEDITOR.instances['note'].setData(response.data.note);
					
					setTimeout(function(){
						$('#al_sph_id').val(response.data.al_sph_id).trigger('change');
					}, 1000);
					
					setTimeout(function(){
						$('#detail_product').empty();
						$.each(response.detail, function(i, val) {
							$('#detail_product').append(`
								<tr class="rowproduct">
									<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
									<td class="text-center">` + (i + 1) + `.</td>
									<td class="text-center">` + val.al_product_name + `</td>
									<td class="text-center">` + val.al_product_spec + `</td>
									<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" class="form-control"></td>
									<td class="text-center">` + val.unit + `</td>
									<td class="text-center"><input type="text" name="al_product_note[]" class="form-control" value="` + (val.note == 'null' ? val.note : '') + `"></td>
									<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
								</tr>
							`);
						});
					}, 2000);
					
					$('#modal_form').modal('toggle');
				 },
				 error: function() {
					cancel();
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
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
					  url: '{{ url("admin/al/surat_jalan/destroy") }}',
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