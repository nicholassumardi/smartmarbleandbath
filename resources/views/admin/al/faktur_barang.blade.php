<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Faktur Barang</span>
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
						<b><i class="icon-plus3"></i></b> Tambah Faktur
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">Faktur Barang</span>
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
							<th>No.Faktur</th>
							<th>Nama Proyek</th>
							<th>No.Sph</th>
							<th>Tanggal</th>
							<th>Nominal</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Faktur Barang</h5>
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
											<select name="al_sph_id" id="al_sph_id" class="select2" onchange="getNominalSph();">
												<option value="">-- Pilih proyek --</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Nominal :<span class="text-danger">*</span></label>
											<input type="text" name="nominal" id="nominal" class="form-control" onkeyup="formatRupiah(this)">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Tanggal :<span class="text-danger">*</span></label>
											<input type="date" name="date" id="date" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Nama Pejabat (TTD) :<span class="text-danger">*</span></label>
											<input type="text" name="sign_name" id="sign_name" class="form-control" placeholder="Ex: Maulana, S.T., M.Si.">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Posisi Pejabat (TTD) :<span class="text-danger">*</span></label>
											<input type="text" name="sign_position" id="sign_position" class="form-control" placeholder="Ex: Kolonel Laut (E) NRP 10119/P">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Catatan :<span class="text-danger">*</span></label>
											<textarea name="note" id="note" class="form-control" rows="1" placeholder="Catatan tambahan keterangan" rows="1"></textarea>
										</div>
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
	
	<script>
		var sphlist = {!! json_encode($sph,JSON_UNESCAPED_SLASHES) !!};

		$(function() {
			loadDataTable();
			
			select2ServerSide('#city_id', '{{ url("admin/select2/city") }}');
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#form_data')[0].reset();
				$('#temp').val('');
				$('#al_project_id').val('').trigger('change');
			});
		});

		function success() {
			$('#modal_form').modal('hide');
			loadDataTable();
		}

	   function loadDataTable() {
		  return $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'asc']],
			 ajax: {
				url: '{{ url("admin/al/faktur_barang/datatable") }}',
				type: 'GET',
				data: {
				   
				},
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
				{ name: 'code', className: 'text-center align-middle' },
				{ name: 'proyek', className: 'text-center align-middle' },
				{ name: 'sph', className: 'text-center align-middle' },
				{ name: 'date', className: 'text-center align-middle' },
				{ name: 'nominal', className: 'text-center align-middle' },
				{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
			 ]
		  }); 
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
		
		function getNominalSph(){
			if($('#al_sph_id').val() !== ''){
				sphlist.forEach(function(row) {
					if($('#al_sph_id').val() == row['id']){
						$('#nominal').val(formatRupiahIni(parseFloat(row['grandtotal'].toString().replaceAll('.','').replaceAll(',','.'))));
					}
				});
			}else{
				$('#nominal').val('0');
			}
		}
		
		function formatRupiahIni(angka){
			var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
		 
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
		 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			
			return rupiah;
		}
		
		function create() {
			  $.ajax({
				 url: '{{ url("admin/al/faktur_barang/create") }}',
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
					   success();
					   notif('success', 'bg-success', response.message);
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

		function show(id) {
		  $.ajax({
			 url: '{{ url("admin/al/faktur_barang/show") }}',
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
				$('#temp').val(id);
				$('#al_project_id').val(response.data.al_project_id).trigger('change');
				$('#al_sph_id').val(response.data.al_sph_id).trigger('change');
				$('#date').val(response.data.date);
				$('#sign_name').val(response.data.sign_name);
				$('#sign_position').val(response.data.sign_position);
				$('#note').val(response.data.note);
				setTimeout(function() {
					$('#nominal').val(response.data.nominal);
				}, 1000);
				
				
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
			 text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label>',
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
					  url: '{{ url("admin/al/faktur_barang/destroy") }}',
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
							$('#datatable_serverside').DataTable().ajax.reload(null, false);
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