<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Proyek</span>
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
						<b><i class="icon-plus3"></i></b> Tambah
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">Proyek</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Proyek</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Kode</th>
							<th>Nama Proyek</th>
							<th>Bidang Pekerjaan</th>
							<th>Lokasi Pekerjaan</th>
							<th>Pelanggan</th>
							<th>Tanggal</th>
							<th>PPN</th>
							<th>SPH & DKH</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Proyek</h5>
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
								<div class="col-md-3">
									<div class="form-group">
										<label>Nama Proyek :<span class="text-danger">*</span></label>
										<input type="hidden" name="temp" id="temp">
										<input type="text" name="name" id="name" class="form-control" placeholder="Ketik nama produk disini...">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Bidang Pekerjaan :<span class="text-danger">*</span></label>
										<input type="text" name="field_of_work" id="field_of_work" class="form-control" placeholder="Ex: Pengadaan Barang / Perawatan Alat Berat">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Lokasi Pekerjaan :<span class="text-danger">*</span></label>
										<input type="text" name="location" id="location" class="form-control" placeholder="Ex: Koarmada II / Mabes AL / Koarmada I">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Customer :<span class="text-danger">*</span></label>
										<select name="al_customer_id" id="al_customer_id" class="select2">
											 <option value="">-- Pilih satu --</option>
											 @foreach($customer as $c)
												<option value="{{ $c->id }}">{{ $c->name }}</option> 
											 @endforeach
										</select>
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
										<label>PPN :<span class="text-danger">*</span></label>
										<select name="ppn" id="ppn" class="form-control">
											 <option value="1">Ya</option>
											 <option value="0">Tidak</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Kota :<span class="text-danger">*</span></label>
										<select name="city_id" id="city_id"></select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Catatan :<span class="text-danger">*</span></label>
										<textarea name="note" id="note" class="form-control" placeholder="Catatan tambahan keterangan." rows="1"></textarea>
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
	$(function() {
		loadDataTable();
		select2ServerSide('#city_id', '{{ url("admin/select2/city") }}');
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#al_customer_id').val('').trigger('change');
			$('#form_data')[0].reset();
			$('#temp').val('');
			$('#city_id').empty().trigger('change');
			loadDataTable();
		});
	});
	
	function success(){
		$('#form_data')[0].reset();
		$('#modal_form').modal('toggle');
		$('#temp').val('');
		$('#city_id').empty().trigger('change');
		loadDataTable();
	}
	
	function create(){
      $.ajax({
         url: '{{ url("admin/al/sph_dkh/create") }}',
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
	
	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/sph_dkh/datatable") }}',
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
            { name: 'kode', className: 'text-center align-middle' },
			{ name: 'nama', className: 'text-center align-middle' },
			{ name: 'bidang', className: 'text-center align-middle' },
			{ name: 'lokasi', className: 'text-center align-middle' },
			{ name: 'customer', className: 'text-center align-middle' },
            { name: 'tanggal', className: 'text-center align-middle' },
			{ name: 'ppn', searchable: false, className: 'text-center align-middle' },
            { name: 'sph', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function show(id){
		$.ajax({
			 url: '{{ url("admin/al/sph_dkh/show") }}',
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
				$('#name').val(response.data.name);
				$('#field_of_work').val(response.data.field_of_work);
				$('#location').val(response.data.location);
				$('#al_customer_id').val(response.data.al_customer_id).trigger('change');
				$('#date').val(response.data.date);
				$('#ppn').val(response.data.is_ppn);
				$('#city_id').empty();
				$('#city_id').append(`
					<option value="` + response.data.city_id + `">` + response.data.city_name + `</option>
				`);
				$('#note').val(response.data.note);
				
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
	
	function destroy(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus Proyek ini?</h6><label>Proyek dan data turunan yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/sph_dkh/destroy") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : id },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					if(response.status == 200) {
						loadDataTable();
						notif('success', 'bg-success', response.message);
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
</script>