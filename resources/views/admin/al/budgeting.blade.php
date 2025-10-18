<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">RAB</span>
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
						<b><i class="icon-plus3"></i></b> Tambah RAB
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">RAB</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data RAB</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Proyek</th>
							<th>Pelanggan</th>
							<th>Tanggal</th>
							<th>Tanggal Klaim</th>
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
								<div class="col-md-12">
									<h3>Informasi Utama</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Proyek :<span class="text-danger">*</span></label>
										<input type="hidden" id="temp" name="temp">
										<select name="al_project_id" id="al_project_id" class="select2">
											 <option value="">-- Pilih satu --</option>
											 @foreach($proyek as $p)
												<option value="{{ $p->id }}">{{ $p->name }}</option> 
											 @endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Nama RAB :<span class="text-danger">*</span></label>
										<input type="text" name="name" id="name" class="form-control" placeholder="Ketik nama budgeting disini...">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Customer :<span class="text-danger">*</span></label>
										<select name="al_customer_id" id="al_customer_id" class="select2" onchange="getCustomerSph(this)">
											 <option value="">-- Pilih satu --</option>
											 @foreach($customer as $c)
												<option value="{{ $c->id }}">{{ $c->name }}</option> 
											 @endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tanggal RAB :<span class="text-danger">*</span></label>
										<input type="date" name="date" id="date" class="form-control" value="0" onkeyup="formatRupiah(this)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tanggal Pencairan :<span class="text-danger">*</span></label>
										<input type="date" name="date_disbursement" id="date_disbursement" class="form-control" value="0" onkeyup="formatRupiah(this)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tipe RAB :<span class="text-danger">*</span></label>
										<select name="tipe_rab" id="tipe_rab" class="form-control">
											 <option value="1">PJK</option>
											 <option value="2">Real Pengadaan</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Catatan :<span class="text-danger">*</span></label>
										<textarea name="note" id="note" class="form-control" placeholder="Catatan tambahan keterangan." rows="1"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group"><hr></div>
							<div class="row">
								<div class="col-md-12">
									<h3>Penerimaan</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Pilih SPH : (Jika ada)</label>
										<select name="al_sph_id" id="al_sph_id" class="select2" onchange="getTotalSph();">
											 <option value="">-- Kosong --</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total Dicairkan :<span class="text-danger">*</span></label>
										<input type="text" name="total_claim" id="total_claim" class="form-control" value="0" onkeyup="formatRupiah(this);countProfit()">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total PPN :<span class="text-danger">*</span></label>
										<input type="text" name="total_ppn" id="total_ppn" class="form-control" value="0" onkeyup="formatRupiah(this);countProfit()" readonly>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total PPH :<span class="text-danger">*</span></label>
										<input type="text" name="total_pph" id="total_pph" class="form-control" value="0" onkeyup="formatRupiah(this);countProfit()" readonly>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total Deposit/HPP :<span class="text-danger">*</span></label>
										<input type="text" name="total_deposit" id="total_deposit" class="form-control" value="0" onkeyup="formatRupiah(this);countProfit()">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total DK :<span class="text-danger">*</span></label>
										<input type="text" name="total_dk" id="total_dk" class="form-control" value="0" onkeyup="formatRupiah(this);">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total Lain - lain :</span></label>
										<input type="text" name="total_other" id="total_other" class="form-control" value="0" onkeyup="formatRupiah(this);">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total Admin :<span class="text-danger">*</span></label>
										<input type="text" name="total_admin" id="total_admin" class="form-control" value="0" onkeyup="formatRupiah(this);">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Total Profit <i>(Autofill)</i> :<span class="text-danger">*</span></label>
										<input type="text" name="total_profit" id="total_profit" class="form-control" value="0" onkeyup="formatRupiah(this);" readonly>
									</div>
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
					Pemasukan : <span class="badge badge-success" id="helper-pemasukan">0</span>
					&nbsp;
					Pajak (PPN&PPH) : <span class="badge badge-danger" id="helper-pajak">0</span>
					&nbsp;
					Pengeluaran : <span class="badge badge-danger" id="helper-pengeluaran">0</span>
					&nbsp;
					Profit : <span class="badge badge-info" id="helper-profit">0</span>
				</div>
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
		$('#detail_product').on('click', '#delete_detail_product', function() {
			$(this).closest('tr').remove();
			countPengeluaran();
			countProfit();
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data')[0].reset();
			$('#al_customer_id').val('').trigger('change');
			$('#al_sph_id').empty();
			$('#al_sph_id').append(`<option value="">-- Kosong --</option>`);
			$('#temp').val('');
			$('.rowdetail').remove();
			loadDataTable();
			$('#helper-pengeluaran, #helper-pemasukan').text('0');
		});
	});
	
	function getTotalSph(){
		if($('#al_sph_id').val() !== ''){
			$('#total_claim').val(formatRupiahIni($('#al_sph_id').find(':selected').data('total')));
			$('#total_ppn').val(formatRupiahIni($('#al_sph_id').find(':selected').data('ppn')));
			$('#total_pph').val(formatRupiahIni($('#al_sph_id').find(':selected').data('pph')));
			$('#total_deposit').val(formatRupiahIni($('#al_sph_id').find(':selected').data('hpp')));
			$('#total_dk').val(formatRupiahIni($('#al_sph_id').find(':selected').data('dk')));
			$('#total_admin').val(formatRupiahIni($('#al_sph_id').find(':selected').data('admin')));
		}else{
			$('#total_claim').val('0');
			$('#total_deposit').val('0');
		}
		
		countProfit();
	}
	
	function countProfit(){
		var result = parseFloat($('#total_claim').val().replaceAll('.','').replaceAll(',','.')) - parseFloat($('#total_ppn').val().replaceAll('.','').replaceAll(',','.')) -
		parseFloat($('#total_pph').val().replaceAll('.','').replaceAll(',','.')) -
		parseFloat($('#total_deposit').val().replaceAll('.','').replaceAll(',','.')) - parseFloat($('#total_dk').val().replaceAll('.','').replaceAll(',','.')) - parseFloat($('#total_admin').val().replaceAll('.','').replaceAll(',','.'));
		
		var expense = parseFloat($('#total_deposit').val().replaceAll('.','').replaceAll(',','.')) + parseFloat($('#total_dk').val().replaceAll('.','').replaceAll(',','.')) + parseFloat($('#total_admin').val().replaceAll('.','').replaceAll(',','.'));
		
		$('#total_profit').val(formatRupiahIni(result));
		
		$('#helper-profit').text(formatRupiahIni(result));
		$('#helper-pemasukan').text($('#total_claim').val());
		$('#helper-pajak').text(formatRupiahIni(parseFloat($('#total_ppn').val().replaceAll('.','').replaceAll(',','.')) + parseFloat($('#total_pph').val().replaceAll('.','').replaceAll(',','.'))));
		$('#helper-pengeluaran').text(formatRupiahIni(expense));
	}
	
	function addExpenses(){
		var count = $('.rowdetail').length + 1;
		$('#rowadd').before(`
			<tr class="text-center rowdetail">
				<td>` + count + `.</td>
				<td><input type="text" class="form-control toinput" name="detail_to[]"></td>
				<td><textarea class="form-control" name="detail_note[]" rows="1"></textarea></td>
				<td><input type="number" class="form-control" name="detail_qty[]" value="0" id="detail_qty` + count + `" onkeyup="countQty(this,` + count + `);"></td>
				<td><input type="text" class="form-control" name="detail_price[]" onkeyup="formatRupiah(this);countPrice(this,` + count + `);" value="0" id="detail_price` + count + `"></td>
				<td><input type="text" class="form-control" name="detail_total[]" readonly value="0" id="detail_total` + count + `"></td>
				<td><button type="button" id="delete_detail_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
			</tr>
		`);
		
		setTimeout(function() { 
			$('.toinput').eq($('.rowdetail').length - 1).focus();
		}, 500);
		
		return false;
	}
	
	function countQty(element,no){
		var result = parseFloat($('#detail_price' + no).val().replaceAll('.','').replaceAll(',','.')) * parseFloat(element.value);
		$('#detail_total' + no).val(formatRupiahIni(result));
		countPengeluaran();
	}
	
	function countPrice(element,no){
		var result = parseFloat($('#detail_qty' + no).val()) * parseFloat(element.value.replaceAll('.','').replaceAll(',','.'));
		$('#detail_total' + no).val(formatRupiahIni(result));
		countPengeluaran();
	}
	
	function countPengeluaran(){
		var totalPengeluaran = 0;
		$('input[name^="detail_total"]').each(function(){
			totalPengeluaran += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'))
		});
		$('#helper-pengeluaran').text(formatRupiahIni(totalPengeluaran));
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
	
	function getCustomerSph(element){
		if(element.value !== ''){
			$('#al_sph_id').empty();
			$('#al_sph_id').append(`
				<option value="">-- Kosong --</option>
			`);
			$.ajax({
				 url: '{{ url("admin/al/rab/get_customer_sph") }}',
				 type: 'GET',
				 dataType: 'JSON',
				 data: {
					id: element.value
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					if(response.length > 0){
						$.each(response, function(i, val) {
							$('#al_sph_id').append(`
								<option value="` + val.id + `" data-total="` + val.grandtotal + `" data-hpp="` + val.hpp + `" data-dk="` + val.dk + `" data-admin="` + val.admin + `" data-ppn="` + val.ppn + `" data-pph="` + val.pph + `">` + val.name + `</option>
							`);
						});
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
	}
	
	function success(){
		$('#form_data')[0].reset();
		$('#al_customer_id').val('').trigger('change');
		$('#al_sph_id').empty();
		$('#al_sph_id').append(`<option value="">-- Kosong --</option>`);
		$('#modal_form').modal('toggle');
		$('#temp').val('');
		$('.rowdetail').remove();
		$('#helper-pengeluaran, #helper-pemasukan').text('0');
		loadDataTable();
	}
	
	function create(){
      $.ajax({
         url: '{{ url("admin/al/rab/create") }}',
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
            url: '{{ url("admin/al/rab/datatable") }}',
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
			{ name: 'proyek', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'customer', orderable: false, className: 'text-center align-middle' },
            { name: 'tanggal', className: 'text-center align-middle' },
			{ name: 'tanggal_klaim', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function show(id){
		$.ajax({
			 url: '{{ url("admin/al/rab/show") }}',
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
				$('#name').val(response.data.name);
				$('#al_customer_id').val(response.data.al_customer_id).trigger('change');
				$('#date').val(response.data.date);
				$('#date_disbursement').val(response.data.date_disbursement);
				$('#tipe_rab').val(response.data.type);
				$('#note').val(response.data.note);
				setTimeout(function() { 
					$('#al_sph_id').val(response.data.al_sph_id).trigger('change');
				}, 1000);
				setTimeout(function() {
					$('#total_claim').val(formatRupiahIni(response.data.total_claim));
					$('#total_deposit').val(formatRupiahIni(response.data.total_deposit));
					$('#total_profit').val(formatRupiahIni(response.data.total_profit));
					$('#total_admin').val(formatRupiahIni(response.data.total_admin));
					$('#total_dk').val(formatRupiahIni(response.data.total_dk));
					$('#total_other').val(formatRupiahIni(response.data.total_other));
				}, 2000);
				
				$('.rowdetail').remove();
				
				$.each(response.detail, function(i, val) {
					$('#rowadd').before(`
						<tr class="text-center rowdetail">
							<td>` + (i + 1) + `</td>
							<td><input type="text" class="form-control toinput" name="detail_to[]" value="` + (val.to_whom == 'null' ? '' : val.to_whom) + `"></td>
							<td><textarea class="form-control" name="detail_note[]" rows="1">` + val.note + `</textarea></td>
							<td><input type="number" class="form-control" name="detail_qty[]" id="detail_qty` + i + `" onkeyup="countQty(this,` + i + `);" value="` + val.qty + `"></td>
							<td><input type="text" class="form-control" name="detail_price[]" onkeyup="formatRupiah(this);countPrice(this,` + i + `);" id="detail_price` + i + `" value="` + formatRupiahIni(val.price) + `"></td>
							<td><input type="text" class="form-control" name="detail_total[]" readonly id="detail_total` + i + `" value="` + formatRupiahIni(val.total) + `"></td>
							<td><button type="button" id="delete_detail_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
						</tr>
					`);
				});
				
				setTimeout(function() {
					countPengeluaran();
					countProfit();
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
	
	function destroy(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus RAB ini?</h6><label>RAB yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/rab/destroy") }}',
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
					   title: 'Error!',
					   text: 'Please contact developer to delete this rab.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
</script>