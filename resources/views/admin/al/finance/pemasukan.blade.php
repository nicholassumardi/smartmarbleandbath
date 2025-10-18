<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Pemasukan</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
						onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal"
						data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Pemasukan</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Pemasukan</h5>
			</div>
			<div class="card-body">
				<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
					<span class="font-weight-semibold">Informasi!</span> Jika data diambil dari Faktur Barang, pastikan
					nomor kontrak pada SPH telah terisi.
				</div>
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>No</th>
								<th>No.Kwitansi</th>
								<th>Nama Proyek</th>
								<th>Terima Dari</th>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Nominal</th>
								<th>Bukti</th>
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
					<h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Pemasukan</h5>
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
												<select name="al_project_id" id="al_project_id" class="select2"
													onchange="getInvoice(this.value);">
													<option value="">-- Pilih satu --</option>
													@foreach($proyek as $p)
													<option value="{{ $p->id }}">{{ $p->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Faktur Barang (Jika ada) :</label>
												<select name="al_invoice_id" id="al_invoice_id" class="select2"
													onchange="getInvoiceTotal(this.value);">
													<option value="">-- Pilih proyek --</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Terima Dari :<span class="text-danger">*</span></label>
												<input type="text" name="from_person" id="from_person"
													class="form-control" placeholder="Ketik disini...">
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
												<label>Bukti :</label>
												<input type="file" id="file" name="file" class="form-control h-auto"
													accept="image/x-png,image/jpg,image/jpeg,application/pdf">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Nominal Lain - lain :<span class="text-danger">*</span></label>
												<input type="text" name="nominal_other" id="nominal_other" class="form-control" onkeyup="formatRupiah(this);" value="0">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Nominal :<span class="text-danger">*</span></label>
												<input type="text" name="nominal" id="nominal" class="form-control"
													onkeyup="formatRupiah(this);" value="0">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Catatan :</label>
												<textarea name="note" id="note" class="form-control"
													placeholder="Catatan tambahan keterangan." rows="1"></textarea>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i
							class="icon-plus3"></i> Save</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		var invoicelist = {!! json_encode($invoice,JSON_UNESCAPED_SLASHES) !!};

	$(function() {
		loadDataTable();
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			loadDataTable();
			$('#al_project_id').val('').trigger('change');
			$('#form_data')[0].reset();
			$('#temp').val('');
		});
	});
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/finance/pemasukan/datatable") }}',
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
			{ name: 'code', className: 'text-center align-middle' },
			{ name: 'project', orderable: false, className: 'text-center align-middle' },
            { name: 'from_person', className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
            { name: 'note', className: 'text-center align-middle' },
            { name: 'nominal', className: 'text-center align-middle nowrap' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function getInvoice(val){
			if(val !== ''){
				$('#al_invoice_id').empty();
				
				$('#al_invoice_id').append(`
					<option value="">-- Pilih salah satu --</option>
				`);
				
				invoicelist.forEach(function(row) {
					if(val == row['al_project_id']){
						$('#al_invoice_id').append(`
							<option value="` + row['id'] + `">` + row['code'] + ` Nominal Rp. ` + row['nominal'] + `</option>
						`);
					}
				});
			}else{
				$('#al_invoice_id').empty();
				$('#al_invoice_id').append(`
					<option value="">-- Pilih proyek --</option>
				`);
			}
		}
	
	function getInvoiceTotal(val){
		if(val !== ''){
			invoicelist.forEach(function(row) {
				if(val == row['id']){
					$('#nominal').val(row['nominal']);
					$('#from_person').val(row['customer']);
					$('#note').val(row['project_name'] + '. Sesuai Kontrak Nomor : ' + row['contract_no'] + ' tanggal ' + row['contract_date'] + row['ppn_info']);
				}
			});
		}else{
			$('#nominal').val('0');
			$('#from_person').val('');
			$('#note').val('');
		}
	}
	
	function create(){
		$.ajax({
		 url: '{{ url("admin/al/finance/pemasukan/create") }}',
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
			 url: '{{ url("admin/al/finance/pemasukan/show") }}',
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
				
				$('#date').val(response.data.date);
				$('#from_person').val(response.data.from_person);
				$('#nominal').val(response.data.nominal);
				$('#nominal_other').val(response.data.nominal_other);
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
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus Pemasukan ini?</h6><label>Pemasukan yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/finance/pemasukan/destroy") }}',
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
						notif('success', 'bg-success', response.message);
						loadDataTable();
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