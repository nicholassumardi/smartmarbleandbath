<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Pembelian Barang</span>
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
						<b><i class="icon-plus3"></i></b> Tambah PO
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<span class="breadcrumb-item active">PO</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data PO</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>No. PO</th>
							<th>Nama Proyek</th>
							<th>No.Sph</th>
							<th>Tanggal</th>
							<th>Supplier</th>
							<th>Grandtotal</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Pembelian <span id="title_po"></span></h5>
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
										<select name="al_sph_id" id="al_sph_id" class="select2" onchange="getSupplier();">
											<option value="">-- Pilih proyek --</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Supplier :<span class="text-danger">*</span></label>
										<select name="al_supplier_id" id="al_supplier_id" class="select2" onchange="getSupplierProduct(this.value);">
											 <option value="">-- Pilih satu --</option>
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
										<label>PPN / Tidak :<span class="text-danger">*</span></label>
										<select name="is_ppn" id="is_ppn" class="form-control" onchange="countAll();">
											 <option value="1">Ya</option>
											 <option value="0">Tidak</option>
										</select>
									</div>
								</div>
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
												<th width="10%">Produk</th>
												<th width="40%">Spesifikasi</th>
												<th width="10%">Qty</th>
												<th width="5%">Unit</th>
												<th width="10%">Harga/Qty</th>
												<th width="10%">Total</th>
												<th width="10%">Action</th>
											</tr>
										</thead>
										<tbody id="detail_product">
											<tr class="text-center">
												<td colspan="8">
													<div class="alert alert-warning alert-styled-left alert-dismissible">
														<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
														<span class="font-weight-semibold">Kosong!</span> Silahkan pilih proyek, sph dan supplier.
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
			<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Total : <span class="badge badge-success" id="helper-total">0</span>
					&nbsp;
					PPN : <span class="badge badge-danger" id="helper-ppn">0</span>
					&nbsp;
					Grandtotal : <span class="badge badge-danger" id="helper-grandtotal">0</span>
				</div>
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<script>
	var sphlist = {!! json_encode($sph,JSON_UNESCAPED_SLASHES) !!};
	var sphproduct;

	$(function() {
		ckEditor('note');
		loadDataTable();
		
		$('#detail_product').on('click', '#delete_product', function() {
			$(this).closest('tr').remove();
			countAll();
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			loadDataTable();
			$('#helper-total').text('0');
			$('#helper-ppn').text('0');
			$('#helper-grandtotal').text('0');
			$('#title_po').text('');
			$('#al_project_id').val('').trigger('change');
			$('#al_sph_id').empty();
			$('#al_sph_id').append(`
				<option value="">-- Pilih proyek --</option>
			`);
			$('#al_supplier_id').empty();
			$('#al_supplier_id').append(`
				<option value="">-- Pilih satu --</option>
			`);
			$('#date').val('');
			$('#is_ppn').val('1');
			$('#note').val('');
			$('#temp').val('');
			$('#detail_product').empty();
			$('#detail_product').append(`
				<tr class="text-center">
					<td colspan="8">
						<div class="alert alert-warning alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Kosong!</span> Silahkan pilih proyek, sph dan supplier.
						</div>
					</td>
				</tr>
			`);
		});
	});
	
	function countRow(id){
		var totalrow = $('#al_product_qty' + id).val() * parseFloat($('#al_product_price' + id).val().replaceAll(".","").replaceAll(",","."));
		$('#al_product_total' + id).text(formatRupiahIni(totalrow.toFixed(2).toString().replace('.',',')));
		countAll();
	}
	
	function countAll(){
		var total = 0, ppn = 0, grandtotal = 0;
		$('input[name^="al_product_qty"]').each(function(i, val){
			total += $(this).val() * parseFloat($('input[name^="al_product_price"]').eq(i).val().replaceAll(".","").replaceAll(",","."));
		});
		
		if($('#is_ppn').val() == '1'){
			ppn = total * 0.11;
		}
		
		grandtotal = total + ppn;
		
		$('#helper-total').text(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
		$('#helper-ppn').text(formatRupiahIni(ppn.toFixed(2).toString().replace('.',',')));
		$('#helper-grandtotal').text(formatRupiahIni(grandtotal.toFixed(2).toString().replace('.',',')));
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
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/al/po/datatable") }}',
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
            { name: 'project', orderable: false, className: 'text-center align-middle' },
			{ name: 'sph', orderable: false, className: 'text-center align-middle' },
            { name: 'date', className: 'text-center align-middle' },
            { name: 'supplier', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function getSupplierProduct(val){
		$('#detail_product').empty();
		$.each(sphproduct, function(i, val) {
			$('#detail_product').append(`
				<tr class="rowproduct" data-supplier="` + val.al_supplier_id + `">
					<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
					<td class="text-center">` + (i + 1) + `.</td>
					<td class="text-center">` + val.al_product_name + `</td>
					<td class="text-center"><textarea name="al_product_description[]" class="form-control editable" id="editable` + val.al_product_id + `" rows="1" placeholder="Enter description">` + val.al_product_spec + `</textarea></td>
					<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" id="al_product_qty` + val.al_product_id + `" class="form-control" onkeyup="countRow(` + val.al_product_id + `);"></td>
					<td class="text-center">` + val.unit + `</td>
					<td><input type="text" name="al_product_price[]" value="` + val.buy_price + `" id="al_product_price` + val.al_product_id + `" class="form-control" onkeyup="formatRupiah(this);countRow(` + val.al_product_id + `);"></td>
					<td class="text-right" id="al_product_total` + val.al_product_id + `">` + val.total + `</td>
					<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
				</tr>
			`);
			
			
		});
		
		$.each(sphproduct, function(i, val) {
			ckEditor('editable' + val.al_product_id);
		});
		
		if(val !== ''){
			$('.rowproduct').each(function(){
				if($(this).data('supplier') == val){
					/* do nothing */
				}else{
					$(this).remove();
				}
			});
		}
		
		countAll();
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
	
	function getSupplier(){
		if($('#al_sph_id') !== ''){
			$('#al_supplier_id').empty();
			
			$.ajax({
				 url: '{{ url("admin/al/po/get_supplier_sph") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					id: $('#al_sph_id').val()
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-body');
				 },
				 success: function(response) {
					if(response.data.length > 0){
						sphproduct = response.data;
						
						$('#detail_product').empty();
						
						$.each(response.data, function(i, val) {
							$('#detail_product').append(`
								<tr class="rowproduct" data-supplier="` + val.al_supplier_id + `">
									<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
									<td class="text-center">` + (i + 1) + `.</td>
									<td class="text-center">` + val.al_product_name + `</td>
									<td class="text-center"><textarea name="al_product_description[]" class="form-control editable" id="editable` + val.al_product_id + `" rows="1" placeholder="Enter description">` + val.al_product_spec + `</textarea></td>
									<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" id="al_product_qty` + val.al_product_id + `" class="form-control" onkeyup="countRow(` + val.al_product_id + `);"></td>
									<td class="text-center">` + val.unit + `</td>
									<td><input type="text" name="al_product_price[]" value="` + val.buy_price + `" id="al_product_price` + val.al_product_id + `" class="form-control" onkeyup="formatRupiah(this);countRow(` + val.al_product_id + `);"></td>
									<td class="text-right" id="al_product_total` + val.al_product_id + `">` + val.total + `</td>
									<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
								</tr>
							`);
						});
						
						$.each(response.data, function(i, val) {
							ckEditor('editable' + val.al_product_id);
						});
					}
					
					if(response.supplier.length > 0){
						$('#al_supplier_id').empty();
						$('#al_supplier_id').append(`
							<option value="">-- Pilih satu --</option>
						`);
						
						$.each(response.supplier, function(i, val) {
							$('#al_supplier_id').append(`
								<option value="` + val.al_supplier_id + `">` + val.al_supplier_code + ` ` + val.al_supplier_name + `</option>
							`);
						});
					}
					
					countAll();
					
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
			$('#al_supplier_id').empty();
			$('#al_supplier_id').append(`
				<option value="">-- Pilih satu --</option>
			`);
		}
	}
	
	function create(){
		CKEDITOR.instances['note'].updateElement();
		$('.editable').each(function(){
			CKEDITOR.instances[$(this).attr("id")].updateElement();
		});
		
		$.ajax({
		 url: '{{ url("admin/al/po/create") }}',
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
			 url: '{{ url("admin/al/po/show") }}',
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
				$('#title_po').text(response.data.code);
				$('#al_project_id').val(response.data.al_project_id).trigger('change');
				
				$('#date').val(response.data.date);
				$('#is_ppn').val(response.data.is_ppn);
				CKEDITOR.instances['note'].setData(response.data.note);
				
				setTimeout(function(){
					$('#al_sph_id').val(response.data.al_sph_id).trigger('change');
				}, 500);
				
				setTimeout(function(){
					$('#al_supplier_id').val(response.data.al_supplier_id).trigger('change');
					$('#detail_product').empty();
					$.each(response.detail, function(i, val) {
						$('#detail_product').append(`
							<tr class="rowproduct" data-supplier="` + val.al_supplier_id + `">
								<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
								<td class="text-center">` + (i + 1) + `.</td>
								<td class="text-center">` + val.al_product_name + `</td>
								<td class="text-center"><textarea name="al_product_description[]" class="form-control editable" id="editable` + val.al_product_id + `" rows="1" placeholder="Enter description">` + val.al_product_spec + `</textarea></td>
								<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" id="al_product_qty` + val.al_product_id + `" class="form-control" onkeyup="countRow(` + val.al_product_id + `);"></td>
								<td class="text-center">` + val.unit + `</td>
								<td><input type="text" name="al_product_price[]" value="` + val.buy_price + `" id="al_product_price` + val.al_product_id + `" class="form-control" onkeyup="formatRupiah(this);countRow(` + val.al_product_id + `);"></td>
								<td class="text-right" id="al_product_total` + val.al_product_id + `">` + val.total + `</td>
								<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
					$.each(response.detail, function(i, val) {
						ckEditor('editable' + val.al_product_id);
					});
					
					countAll();
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
	
	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/po/datatable") }}',
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
			{ name: 'customer', orderable: false, className: 'text-center align-middle' },
            { name: 'tanggal', className: 'text-center align-middle' },
			{ name: 'supplier', className: 'text-center align-middle' },
			{ name: 'grandtotal', className: 'text-right align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function destroy(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus PO ini?</h6><label>PO yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/po/destroy") }}',
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
						notyConfirm.close();
						loadDataTable();
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