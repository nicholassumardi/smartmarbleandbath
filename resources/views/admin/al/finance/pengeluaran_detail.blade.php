<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Pengeluaran</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah
					</button>
					<button type="button" class="btn bg-info btn-labeled btn-labeled-left mr-2" onclick="print()">
						<b><i class="icon-printer"></i></b> Cetak
					</button>
					<a href="https://smartmarbleandbath.com/admin/al/finance/pengeluaran" class="btn bg-secondary btn-labeled btn-labeled-left"><b><i class="icon-arrow-left7"></i></b> Back To All</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Pengeluaran</a>
					<span class="breadcrumb-item active">{{ $proyek->name }}</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Pengeluaran Proyek {{ $proyek->name }}</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Nama Proyek</th>
							<th>Kepada</th>
							<th>Tanggal</th>
							<th>Tipe</th>
							<th>Judul & Keterangan</th>
							<th>Nominal</th>
							<th>Bukti</th>
							<th>Progres</th>
							<th>Pembayaran</th>
							<th>Action</th>
						 </tr>
					  </thead>
					  <tfoot align="right">
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th style="font-size:20px;font-weight:800;">Total</th>
							<th style="font-size:20px;font-weight:800;"></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					  </tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Pengeluaran</h5>
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
									<h3>Tambah Satu Per Satu</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Proyek :<span class="text-danger">*</span></label>
										<input type="hidden" id="temp" name="temp">
										<select name="al_project_id" id="al_project_id" class="select2">
											<option value="{{ $proyek->id }}">{{ $proyek->name }}</option> 
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>PO (Optional) :</label>
										<select name="al_purchase_id" id="al_purchase_id" class="select2" onchange="getPO();">
											<option value="">-- Select one if from PO --</option>
											@foreach($po as $rowpo)
												<option value="{{ $rowpo->id }}" data-supplier="{{ $rowpo->alSupplier->name }}" data-nominal="{{ number_format($rowpo->grandtotal,2,',','.') }}">{{ $rowpo->code.' - '.$rowpo->alSupplier->name.' - Rp '.number_format($rowpo->grandtotal,2,',','.') }}</option> 
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Kepada :<span class="text-danger">*</span></label>
										<input type="text" name="to_person" id="to_person" class="form-control" placeholder="Ketik disini...">
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
										<input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Nominal :<span class="text-danger">*</span></label>
										<input type="text" name="nominal" id="nominal" class="form-control" onkeyup="formatRupiah(this);" value="0">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Judul :<span class="text-danger">*</span></label>
										<input type="text" name="title" id="title" class="form-control" placeholder="Judul pengeluaran">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tipe Pengeluaran :<span class="text-danger">*</span></label>
										<select name="al_type" id="al_type" class="form-control">
											<option value="1">HPP/PO</option>
											<option value="2">DK</option>
											<option value="3">Admin</option>
										</select>
									</div>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<label>Keterangan :</label>
										<textarea name="note" id="note" class="form-control" placeholder="Keterangan untuk breakdown perhitungan." rows="1"></textarea>
									</div>
								</div>
								<div class="col-md-12 mt-2">
									<hr>
									<h3>Detail Perhitungan</h3>
									<table class="table table-bordered table-striped">
										<thead class="bg-dark">
											<tr class="text-center">
												<th>No</th>
												<th>Keterangan</th>
												<th>Harga</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="detail_product">
											<tr class="text-center" id="rowadd">
												<td colspan="4"><a href="javascript:void(0);" class="btn btn-info btn-block" onclick="addDetail()"><i class="icon-plus3"></i> Tambah</a></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-12 mt-2">
									<hr>
									<h3>Tambah Dari RAB</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Proyek :<span class="text-danger">*</span></label>
										<select name="al_project_id_rab" id="al_project_id_rab" class="select2" onchange="getBudgetingExpense(this.value);">
											 <option value="">-- Pilih satu --</option>
											 <option value="{{ $proyek->id }}">{{ $proyek->name }}</option> 
										</select>
									</div>
								</div>
								<div class="col-md-12 mt-2">
									<h3>Detail Pengeluaran dari RAB</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered table-striped">
										<thead class="bg-dark">
											<tr class="text-center">
												<th>No</th>
												<th>Kepada (Tujuan Dana)</th>
												<th>Tanggal</th>
												<th>Total</th>
												<th>Keterangan</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="detail_product">
											<tr class="text-center" id="rowadd">
												<td colspan="6">
													Silahkan pilih Proyek terlebih dahulu...
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
			<div class="mr-auto" style="font-size:25px !important;">
				Helper <i class="icon-point-right mr-2 icon-2x"></i>
				Pengeluaran : <span class="badge badge-danger" id="helper-pengeluaran">0</span>
			</div>
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modal_pay" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
			<div class="form-group">
				<div class="row p-3">
					<div class="col-md-12">
						<form id="form_data_pay">
							<div class="alert alert-danger" id="validation_alert_pay" style="display:none;">
							  <ul id="validation_content_pay"></ul>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3>Informasi Pembayaran</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<h4>
										<dl class="row mb-0">
											<dd class="col-sm-3">Tagihan kepada</dd>
											<dt class="col-sm-9">: <span id="info_tagihan"></span></dt>
											<dd class="col-sm-3">Tanggal</dd>
											<dt class="col-sm-9">: <span id="info_tanggal"></span></dt>
											<dd class="col-sm-3">Proyek</dd>
											<dt class="col-sm-9">: <span id="info_proyek"></span></dt>
											<dd class="col-sm-3">Kewajiban Bayar</dd>
											<dt class="col-sm-9">: <span id="info_nominal"></span></dt>
										</dl>
									</h4>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Penerima :<span class="text-danger">*</span></label>
										<input type="hidden" name="temp_pay" id="temp_pay">
										<input type="text" name="pay_receiver" id="pay_receiver" class="form-control" placeholder="Ketik disini...">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tanggal :<span class="text-danger">*</span></label>
										<input type="date" name="pay_date" id="pay_date" class="form-control">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Bukti :</label>
										<input type="file" id="pay_file" name="pay_file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Nominal :<span class="text-danger">*</span></label>
										<input type="text" name="pay_nominal" id="pay_nominal" class="form-control" onkeyup="formatRupiah(this);" value="0">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Catatan :</label>
										<textarea name="pay_note" id="pay_note" class="form-control" placeholder="Catatan tambahan keterangan." rows="1"></textarea>
									</div>
								</div>
								<div class="col-md-12 mt-2 text-center">
									<button type="button" class="btn bg-primary btn-block btn_create" onclick="addPayment()"><i class="icon-plus3"></i> Tambah</button>
								</div>
								<div class="col-md-12 mt-2">
									<h3>Detail Pembayaran</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered table-striped">
										<thead class="bg-dark">
											<tr class="text-center">
												<th>Penerima</th>
												<th width="20%">Tanggal</th>
												<th>Bukti</th>
												<th>Nominal</th>
												<th>Keterangan</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="detail_payments">
											<tr class="text-center">
												<td colspan="6">
													Silahkan masukkan pembayaran terlebih dahulu...
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
         </div>
      </div>
   </div>
</div>

	<div class="modal fade" id="modal_pictures" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Dokumen pada Pengeluaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<input type="hidden" name="tempExpense" id="tempExpense">
				<p class="mb-3">Anda bisa mengunggah file untuk pengeluaran ini. <b>Ukuran maksimal : 1 Mb / 1024 Kb, Maksimal file : 5.</b></p>

				<p class="font-weight-semibold">Unggah lebih dari satu file :</p>
				<form action="{{ url('admin/al/finance/pengeluaran/add_pictures') }}" class="dropzone" id="dropzone_multiple">
					@csrf
				</form>
				<div class="row mt-3" id="list-images">
				
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>

<script>
	$(function() {
		loadDataTable();
		
		ckEditor('note');
		
		$('#detail_product').on('click', '#delete_detail_product', function() {
			$(this).closest('tr').remove();
			countPengeluaran();
		});
		
		$('#detail_product').on('click', '#delete_detail_product', function() {
			$(this).closest('tr').remove();
			countPengeluaran();
		});
		
		$('#modal_pay').on('hidden.bs.modal', function (e) {
			loadDataTable();
			$('#detail_payments').empty();
			$('#detail_payments').append(`
				<tr class="text-center">
					<td colspan="6">
						Silahkan masukkan pembayaran terlebih dahulu...
					</td>
				</tr>
			`);
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			loadDataTable();
			$('#detail_product').empty();
			$('#detail_product').append(`
				<tr class="text-center" id="rowadd">
					<td colspan="6">
						Silahkan pilih Proyek terlebih dahulu...
					</td>
				</tr>
			`);
			$('#al_project_id_rab').val('').trigger('change');
			$('#al_project_id').val($("#al_project_id option:first").val()).trigger('change');
			$('#form_data')[0].reset();
			$('#detail_product').empty();
			$('#detail_product').append(`
				<tr class="text-center" id="rowadd">
					<td colspan="4"><a href="javascript:void(0);" class="btn btn-info btn-block" onclick="addDetail()"><i class="icon-plus3"></i> Tambah</a></td>
				</tr>
			`);
			countPengeluaran();
			$('#temp').val('');
		});
		
		$('#modal_pictures').on('hidden.bs.modal', function (e) {
			loadDataTable();
		});
	});
	
	function getPO(){
		if($('#al_purchase_id').val()){
			$('#nominal').val($("#al_purchase_id").select2().find(":selected").data("nominal"));
			$('#to_person').val($("#al_purchase_id").select2().find(":selected").data("supplier"));
		}
	}
	
	var tempExpense = 0;
	
	function addPictures(id){
		$('#tempExpense').val(id);
		tempExpense = id;
		$('#list-images').empty();
		$.ajax({
			 url: '{{ url("admin/al/finance/pengeluaran/get_pictures") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempExpense
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				if(response.length > 0){
					$.each(response, function(i, val) {
						$('#list-images').append(`
							<div class="col-md-2 text-center" id="picture` + val.id + `">
								<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.filename + `"><img src="` + val.picture + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
								<p class="mt-3">
									<h4>` + val.name + `</h4>
								</p>
								<p class="mt-3">
									<button class="btn btn-danger btn-sm mr-3" onclick="destroyPicture(` + val.id + `);"><i class="icon-trash"></i></button>
									<a class="btn btn-success btn-sm" href="` + val.filename + `" target="_blank"><i class="icon-download"></i></a>
								</p>
							</div>
						`);
					});
				}else{
					$('#list-images').append(`
						<div class="col-md-12 text-center">
							<div class="alert alert-warning alert-styled-left">
								There is no pictures in this project.
							</div>
						</div>
					`);
				}
				
				loadingClose('.modal-content');
			 },
			 error: function() {
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
		
		$('#modal_pictures').modal('toggle');
	}
	
	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		maxFilesize: 5,
		maxFiles: 5,
		/* acceptedFiles: ".jpeg,.jpg,.png,.gif", */
		init: function() {
			this.on("sending", function(file, xhr, formData){
				formData.append('id', tempExpense);
			});
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}
			});
		}
	};
	
	function destroyPicture(val) {
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
				 url: '{{ url("admin/al/finance/pengeluaran/delete_picture") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : val },
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
						$('#picture' + val).remove();
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
	
	function addDetail(){
		var count = $('.rowdetail').length + 1;
		$('#rowadd').before(`
			<tr class="text-center rowdetail">
				<td>` + count + `.</td>
				<td><input type="text" class="form-control toinput" name="detail_keterangan[]"></td>
				<td><input type="text" class="form-control" name="detail_harga[]" onkeyup="formatRupiah(this);countPengeluaran();" value="0"></td>
				<td><button type="button" id="delete_detail_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
			</tr>
		`);
		
		setTimeout(function() { 
			$('.toinput').eq($('.rowdetail').length - 1).focus();
		}, 500);
		
		return false;
	}
	
	function countPengeluaran(){
		var totalPengeluaran = 0;
		$('input[name^="detail_harga"]').each(function(){
			totalPengeluaran += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'))
		});
		$('#helper-pengeluaran').text(formatRupiahIni(totalPengeluaran));
		$('#nominal').val(formatRupiahIni(totalPengeluaran));
	}
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/finance/pengeluaran/detail/".$proyek->id."/datatable") }}',
            type: 'GET',
            data: { al_project_id : $('#filter_proyek').val() },
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
		 "footerCallback": function ( row, data, start, end, display ) {
			  var api = this.api(), data;
	 
			  var intVal = function ( i ) {
				  return typeof i === 'string' ?
					  i.replaceAll('.','').replaceAll(',','.')*1 :
					  typeof i === 'number' ?
						  i : 0;
			  };
	 
			  sal = api
				  .column( 6 )
				  .data()
				  .reduce( function (a, b) {
					  return intVal(a) + intVal(b);
				  }, 0 );
	 
			$( api.column( 6 ).footer() ).html('Rp' + formatRupiahIni(sal.toFixed(0)));
		  },
		  "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
			{ name: 'project', orderable: false, className: 'text-center align-middle' },
            { name: 'to_person', className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
			{ name: 'type', className: 'text-center align-middle' },
            { name: 'title', className: 'text-center align-middle' },
            { name: 'nominal', className: 'text-center align-middle nowrap' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
			{ name: 'progress', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
			{ name: 'pay', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function print(){
		var proyek = {{ $proyek->id }};
		window.open("{{ url('admin/al/finance/pengeluaran/print/') }}?mode=" + proyek, "_blank");
	}
	
	function showPay(id,kepada,namaproyek,nominal,tanggal){
		$('#modal_pay').modal('toggle');
		$('#temp_pay').val(id);
		$('#info_tagihan').text(kepada);
		$('#info_tanggal').text(tanggal);
		$('#info_proyek').text(namaproyek);
		$('#info_nominal').text(nominal);
		
		$.ajax({
			 url: '{{ url("admin/al/finance/pengeluaran/get_payment") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: id
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				$('#detail_payments').empty();
				if(response.length > 0){
					$.each(response, function(i, val) {
						$('#detail_payments').append(`
							<tr class="text-center rowpayment row` + val.id + `">
								<td>` + val.receiver + `</td>
								<td><input type="date" value="` + val.dateraw + `" data-id="` + val.id + `" onchange="updateDatePayment(this);" class="form-control"></td>
								<td>` + val.attachment + `</td>
								<td>` + val.nominal + `</td>
								<td>` + val.note + `</td>
								<td><button type="button" class="btn bg-danger btn-sm" onclick="deletePayment(` + val.id + `)"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
				}else{
					$('#detail_payments').append(`
						<tr class="text-center">
							<td colspan="6">
								Silahkan masukkan pembayaran terlebih dahulu...
							</td>
						</tr>
					`);
				}
				
			 },
			 error: function() {
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function addPayment(){
		$.ajax({
		 url: '{{ url("admin/al/finance/pengeluaran/create_pay") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_pay')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_pay').hide();
			$('#validation_content_pay').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   notif('success', 'bg-success', response.message);
			   if($('.rowpayment').length > 0){
				   
			   }else{
				   $('#detail_payments').empty();
			   }
			   if(response.data){
					$('#detail_payments').append(`
						<tr class="text-center rowpayment row` + response.data.id + `">
							<td>` + response.data.receiver + `</td>
							<td>` + response.data.date + `</td>
							<td>` + response.data.attachment + `</td>
							<td>` + response.data.nominal + `</td>
							<td>` + response.data.note + `</td>
							<td><button type="button" class="btn bg-danger btn-sm" onclick="deletePayment(` + response.data.id + `)"><i class="icon-trash"></i></button></td>
						</tr>
					`);
			   }
			   
			   $('#pay_receiver').val('');
			   $('#pay_date').val('');
			   $('#pay_file').val('');
			   $('#pay_nominal').val('0');
			   $('#pay_note').val('');
			} else if(response.status == 422) {
			   $('#validation_alert_pay').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_pay').append(`
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
	
	function deletePayment(id) {
	  var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to delete payment?</h6><label>Deleted data can no longer be recovered.</label>',
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
				  url: '{{ url("admin/al/finance/pengeluaran/delete_payment") }}',
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
						 if(response.count == 0){
							$('#data_payment').append(`
								<tr class="text-center align-middle">
									<td class="bg-danger" colspan="9">There is no payment data.</td>
								</tr>
							`);
						 }
						$('.row' + id).remove();
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
	
	function getBudgetingExpense(val){
		if(val !== ''){
			$.ajax({
				 url: '{{ url("admin/al/finance/pengeluaran/get_budgeting_expense") }}',
				 type: 'GET',
				 dataType: 'JSON',
				 data: {
					id: val
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					if(response.data.length > 0){
						$('#detail_product').empty();
						$.each(response.data, function(i, val) {
							$('#detail_product').append(`
								<tr class="text-center rowdetail">
									<td>` + (i + 1) + `.</td>
									<td><input type="text" class="form-control" name="detail_to[]" value="` + (val.to_whom == "null" ? "-" : val.to_whom) + `"></td>
									<td><input type="date" class="form-control" name="detail_date[]" value="{{ date('Y-m-d') }}"></td>
									<td><input type="text" class="form-control" name="detail_total[]" readonly value="` + formatRupiahIni(val.total) + `"></td>
									<td><input type="text" class="form-control" name="detail_title[]" placeholder="Judul pengeluaran"></td>
									<td><textarea class="form-control" name="detail_note[]" rows="1">` + val.note + `</textarea></td>
									<td><button type="button" id="delete_detail_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
								</tr>
							`);
						});
					}
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
	
	function create(){
		CKEDITOR.instances['note'].updateElement();
		$.ajax({
		 url: '{{ url("admin/al/finance/pengeluaran/create") }}',
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
			 url: '{{ url("admin/al/finance/pengeluaran/show") }}',
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
				$('#to_person').val(response.data.to_person);
				$('#nominal').val(response.data.nominal);
				$('#title').val(response.data.title);
				$('#al_type').val(response.data.type);
				CKEDITOR.instances['note'].setData(response.data.note);
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
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus Pengeluaran ini?</h6><label>Pengeluaran yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/finance/pengeluaran/destroy") }}',
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
	
	function updateDatePayment(element){
		var id = $(element).data('id'), tgl = $(element).val();
		
		$.ajax({
		 url: '{{ url("admin/al/finance/pengeluaran/update_payment") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: { id : id, tgl : tgl },
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
	}
	
	function uploadPayProof(val,id){
		var fd = new FormData(), files = $('#filepay' + id)[0].files;
		if(files.length > 0 ){
           fd.append('file',files[0]);
		   fd.append('id', id);
		   
		   $.ajax({
			 url: '{{ url("admin/al/finance/pengeluaran/upload_pay_proof") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: fd,
			 contentType: false,
			 processData: false,
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
					$('#filepay' + id).parent().prepend(response.result);
					$('#proofPay' + id).remove();
					$('#filepay' + id).val('');
				}else {
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
	}
</script>