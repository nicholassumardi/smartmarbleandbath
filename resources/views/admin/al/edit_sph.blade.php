<style>
	.media:hover{
		border-radius:10px;
		background-color:#ff7b52 !important;
		color:white !important;
	}
	
	.media-selected{
		border-radius:10px;
		background-color:#ff7b52 !important;
		color:white !important;
		border:1px solid black;
	}
	
	.media {
		padding:5px;
	}
</style>
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
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadData()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled mr-2 btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah SPH
					</button>
					<a href="{{ url('admin/al/sph_dkh') }}" class="btn bg-secondary btn-labeled btn-labeled-left"><b><i class="icon-arrow-left7"></i></b> Back To All</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="{{ url('admin/al/sph_dkh') }}" class="breadcrumb-item">Proyek</a>
					<span class="breadcrumb-item active">Detail SPH & DKH</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Informasi Proyek</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Nama Proyek</td>
										<td>: {{ $proyek->name }}</td>
									</tr>
									<tr>
										<td>Nomor</td>
										<td>: {{ $proyek->code }}</td>
									</tr>
									<tr>
										<td>Pelanggan</td>
										<td>: {{ $proyek->alCustomer->name }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td>Tanggal</td>
										<td>: {{ date('d M Y',strtotime($proyek->date)) }}</td>
									</tr>
									<tr>
										<td>PPN</td>
										<td>: {{ $proyek->is_ppn() }}</td>
									</tr>
									<tr>
										<td>Kota</td>
										<td>: {{ $proyek->city->name }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="row">
					<div class="col-md-2">
						<div class="card">
							<div class="card-header bg-primary header-elements-inline" style="background-color:#2196f3 !important;">
								<span class="card-title font-weight-semibold">Daftar SPH</span>
								<div class="header-elements">
									<div class="list-icons">
										<a class="list-icons-item" data-action="collapse"></a>
									</div>
								</div>
							</div>

							<div class="card-body" style="background-color:white !important;min-height:300px;">
								<ul class="media-list" id="media-list">
									
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-10">
						<div class="card">
							<div class="card-header bg-secondary header-elements-inline" style="background-color:#2196f3 !important;">
								<span class="card-title font-weight-semibold">Pratinjau SPH</span>
								<div class="header-elements">
									<div class="list-icons">
										<a class="list-icons-item" data-action="collapse"></a>
									</div>
								</div>
							</div>

							<div class="card-body" style="background-color:white !important;min-height:300px;" id="result-produk">
								<div class="alert alert-warning alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
									<span class="font-weight-semibold">Info!</span> Silahkan pilih SPH di sebelah kiri untuk menampilkan detail produk.
							    </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit SPH</h5>
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
								<h3>Informasi Utama</h3>
								<div class="form-group"><hr></div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label>Kode SPH :<span class="text-danger">*</span></label>
											<input type="hidden" name="temp" id="temp">
											<input type="hidden" name="project_id" id="project_id" value="{{ $proyek->id }}">
											<input type="text" name="kode" id="kode" class="form-control" value="{{ $proyek->generateSPH() }}">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Tanggal :<span class="text-danger">*</span></label>
											<input type="date" name="date" id="date" class="form-control" value="0" onkeyup="formatRupiah(this)">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>DK (%) :<span class="text-danger">*</span></label>
											<input type="number" name="dk" id="dk" class="form-control" value="25">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Admin (%) :<span class="text-danger">*</span></label>
											<input type="number" name="admin" id="admin" class="form-control" value="5">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>PPH :<span class="text-danger">*</span></label>
											<select name="pph" id="pph" class="form-control">
												<option value="1">Ya</option>
												<option value="0">Tidak</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Sumber Informasi :<span class="text-danger">*</span></label>
											<input type="text" name="source" id="source" class="form-control" placeholder="Ex: LPSE TNI AD dengan kode RUP...">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Masa Berlaku Penawaran (hari) :<span class="text-danger">*</span></label>
											<input type="number" name="period" id="period" class="form-control" value="30">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>No. Kontrak Proyek :<span class="text-danger">*</span></label>
											<input type="text" name="contract_no" id="contract_no" class="form-control" placeholder="Ex: KTR/09/02.63/I/2022">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Tanggal Kontrak Proyek :<span class="text-danger">*</span></label>
											<input type="date" name="contract_date" id="contract_date" class="form-control">
										</div>
									</div>
									<div class="col-md-12 mt-3">
										<div class="form-group">
											<label>Catatan (Muncul di SPH):</label>
											<textarea name="note" id="note" class="form-control" rows="1"></textarea>
										</div>
									</div>
								</div>
								<h3 class="mt-3">Detail Produk</h3>
								<div class="form-group"><hr></div>
								<div class="alert alert-info alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
									<span class="font-weight-semibold">Info!</span> Harga jual yang anda isi disini akan menjadi histori harga dan mengupdate harga jual di master.
								</div>
								<div class="row">
									<div class="col-md-9">
										<div class="form-group">
											<select name="al_product_id" id="al_product_id"></select>
										</div>
									</div>
									<div class="col-md-3">
										<button type="button" class="btn bg-success btn-block" onclick="addProduct()"><i class="icon-plus3"></i> Tambah</button>
									</div>
								</div>
								<h3 class="mt-3"></h3>
								<div class="form-group"><hr></div>
								<div class="table-responsive">
									<table id="datatable_serverside" class="table table-bordered table-striped">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="">Nama</th>
											<th width="">Qty</th>
											<th width="">Unit</th>
											<th width="">HPP</th>
											<th width="">HPP PTA</th>
											<th width="">Total HPP PTA</th>
											<th width="">TOTAL HPP</th>
											<th width="">DK(Rp)</th>
											<th width="">Admin(Rp)</th>
											<th width="">Profit(Rp)</th>
											<th width="">Profit(%)</th>
											<th width="">Action</th>
										 </tr>
									  </thead>
									  <tbody id="body-produk">
										<tr class="text-center">
											<td colspan="12">
												<div class="alert alert-warning alert-styled-left alert-dismissible">
													<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
													<span class="font-weight-semibold">Info!</span> Silahkan tambahkan barang dengan menekan tombol tambah.
												</div>
											</td>
										</tr>
									  </tbody>
									</table>
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
					PPH : <span class="badge badge-danger" id="helper-pph">0</span>
					&nbsp;
					Grandtotal : <span class="badge badge-primary" id="helper-grandtotal">0</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
	var isPPn = {{ $proyek->is_ppn }};
	
	$(function() {
		$('#al_product_id').select2({
		  placeholder: '-- Choose --',
		  minimumInputLength: 3,
		  allowClear: true,
		  cache: true,
		  dropdownParent: $('body').parent(),
		  ajax: {
			 url: '{{ url("admin/select2/al_product") }}',
			 type: 'GET',
			 dataType: 'JSON',
			 delay: 250,
			 data: function(params) {
				return {
				   search: params.term
				};
			 },
			 processResults: function(data) {
				return {
				   results: $.map(data.items, function (item) {
					 return {
						text: item.text,
						beli: item.beli,
						id: item.id,
						jual: item.jual,
						unit: item.unit,
						name: item.name
					 }
				   })
				}
			 }
		  }
	   });
		
		$('#pph').on('change', function() {
			countAll();
		});
		
		$('.sidebar-main-toggle').click();
		ckEditor('note');
		
		loadData();
		
		$('#body-produk').on('click', '#delete_product', function() {
			$(this).closest('tr').remove();
			countAll();
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data')[0].reset();
			$('#al_product_id').empty();
			$('#temp').val('');
			$('#helper-total').text('0');
			$('#helper-ppn').text('0');
			$('#helper-pph').text('0');
			$('#helper-grandtotal').text('0');
			$('#body-produk').empty();
			CKEDITOR.instances['note'].setData('');
			$('#body-produk').append(`
				<tr class="text-center">
					<td colspan="12">
						<div class="alert alert-warning alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Info!</span> Silahkan tambahkan barang dengan menekan tombol tambah.
						</div>
					</td>
				</tr>
			`);
		});
		
		enableSort();
	});
	
	function enableSort(){
		$("#datatable_serverside").sortable({
			items: 'tr:not(tr:first-child)',
			cursor: 'pointer',
			axis: 'y',
			dropOnEmpty: false,
			start: function (e, ui) {
				ui.item.addClass("selected");
			},
			stop: function (e, ui) {
				ui.item.removeClass("selected");
				$(this).find("tr").each(function (index) {
					if (index > 0) {
						$(this).find("td").eq(2).html(index);
					}
				});
			}
		});
	}
	
	function getSphProduct(element){
		
		$('.media-body').each(function(){
			$(this).parent().removeClass('media-selected');
		});
		
		if($(element).parent().hasClass('media-selected')){
			$(element).parent().removeClass('media-selected');
		}else{
			$(element).parent().addClass('media-selected');
			var idsph = $(element).data('sph');
			showSph(idsph);
		}
	}
	
	function loadData(){
		$('#result-produk').empty();
		$('#result-produk').append(`
			<div class="alert alert-warning alert-styled-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
				<span class="font-weight-semibold">Info!</span> Silahkan pilih SPH di sebelah kiri untuk menampilkan detail produk.
			</div>
		`);
		
		$.ajax({
         url: '{{ url("admin/al/sph_dkh/edit_sph/get_list_sph") }}',
         type: 'POST',
         dataType: 'JSON',
         data: {
            id: {{ $proyek->id }}
         },
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			loadingOpen('#media-list');
		 },
         success: function(response) {
            if(response.length > 0){
				$('#media-list').empty();
				$.each(response, function(i, val) {
					$('#media-list').append(`
						<li class="media">
							<div class="media-body" data-sph="` + val.id  + `" onclick="getSphProduct(this)">
								<div class="font-weight-semibold">Revision ` + val.revision + `</div>
								<span class="font-size-sm">Rp ` + formatRupiahIni(val.grandtotal.toString().replace('.',',')) + `</span>
							</div>

							<div class="ml-3 align-self-center">
								<div class="dropdown">
									<a href="#" class="text-default dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-more2"></i></a>
									<div class="dropdown-menu dropdown-menu-right">
										<a href="{{ url('admin/al/sph_dkh/sph/print') }}/` + val.id + `?mode=no_harga" class="dropdown-item" target="_blank"><i class="icon-printer2"></i> Cetak Tanpa Harga</a>
										<a href="{{ url('admin/al/sph_dkh/sph/print') }}/` + val.id + `" class="dropdown-item" target="_blank"><i class="icon-printer2"></i> Cetak Untuk AL</a>
										<a href="{{ url('admin/al/sph_dkh/sph/print_2') }}/` + val.id + `" class="dropdown-item" target="_blank"><i class="icon-printer2"></i> Cetak Untuk AL V.2</a>
										<a href="{{ url('admin/al/sph_dkh/sph/print_profit') }}/` + val.id + `" class="dropdown-item" target="_blank"><i class="icon-printer4"></i> Cetak + Profit</a>
										<a href="{{ url('admin/al/sph_dkh/sph/print_pembelian') }}/` + val.id + `" class="dropdown-item" target="_blank"><i class="icon-printer4"></i> Cetak HPP Saja</a>
										<a href="{{ url('admin/al/sph_dkh/sph/print_pembelian_2') }}/` + val.id + `" class="dropdown-item" target="_blank"><i class="icon-printer4"></i> Cetak HPP V.2</a>
										<a href="javascript:void(0);" class="dropdown-item" onclick="editSph(` + val.id + `)"><i class="icon-pencil7"></i> Edit</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:void(0);" class="dropdown-item" onclick="destroySph(` + val.id + `)"><i class="icon-trash-alt"></i> Hapus</a>
									</div>
								</div>
							</div>
						</li>
					`);
				});
			}else{
				$('#media-list').append(`
					<li class="media">
						<div class="media-body" data-sph="{{ $proyek->id }}">
							<div class="alert alert-warning alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">SPH Kosong.</span>
							</div>
						</div>
					</li>
				`);
			}
			
			loadingClose('#media-list');
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
	
	function editSph(id){
		$.ajax({
			 url: '{{ url("admin/al/sph_dkh/detail/edit") }}',
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
				$('#kode').val(response.data.code);
				$('#date').val(response.data.date);
				$('#dk').val(response.data.dk);
				$('#admin').val(response.data.admin);
				$('#pph').val(response.data.is_pph);
				$('#period').val(response.data.period);
				$('#source').val(response.data.source);
				$('#contract_no').val(response.data.contract_no);
				$('#contract_date').val(response.data.contract_date);
				CKEDITOR.instances['note'].setData(response.data.note);
				
				$('#body-produk').empty();
				
				$.each(response.detail, function(i, val) {
					
					var nominalhpp = 0, nominaldk = 0, nominaladmin = 0, nominalprofit = 0, percentprofit = 0;
					nominalhpp = val.buy_price * val.qty;
					nominaldk = val.total * parseFloat($('#dk').val() / 100);
					nominaladmin = val.total * parseFloat($('#admin').val() / 100);
					nominalprofit = val.total - nominalhpp - nominaldk - nominaladmin;
					percentprofit = parseFloat((nominalprofit / val.total * 100).toFixed(2));
					
					$('#body-produk').append(`
						`+(val.parent_name != undefined ? `
						<tr class="bg-primary">
							<td class="" data-popup="tooltip" colspan="12" title='` + val.product_description + `' data-html="true">` + val.parent_name + `</td>
						</tr>`: '') +
						`
						<tr class="text-center bg-primary">
							<tr>
								<input type="hidden" name="al_product_id[]" value="` + val.al_product_id + `">
								<td class="text-center" data-popup="tooltip" title='` + val.product_description + `' data-html="true">` + val.product_name + `</td>
								<td><input type="number" name="al_product_qty[]" value="` + val.qty + `" class="form-control" onkeyup="count(` + val.al_product_id + `)" id="al_product_qty` + val.al_product_id + `"></td>
								<td class="text-center">` + val.product_unit + `</td>
								<td><input type="text" value="` + formatRupiahIni(val.buy_price) + `" name="al_product_buy_price[]" class="form-control" onkeyup="formatRupiah(this);countBuy(` + val.al_product_id + `)" id="al_product_buy_price` + val.al_product_id + `"></td>
								<td><input type="text" value="` + formatRupiahIni(val.sell_price) + `" name="al_product_price[]" class="form-control" onkeyup="formatRupiah(this);countSale(` + val.al_product_id + `)" id="al_product_price` + val.al_product_id + `"></td>
								<td class="text-right">
									<input type="text" value="` + formatRupiahIni(val.total) + `" name="total_row" class="form-control" onkeyup="formatRupiah(this);countSaleTotal(` + val.al_product_id + `)" id="total` + val.al_product_id + `">
								</td>
								<td class="text-right"><span id="totalhpp` + val.al_product_id + `" name="total_row_hpp">` + formatRupiahIni(nominalhpp.toFixed(2).toString().replace('.',',')) + `</span></td>
								<td class="text-right"><span id="totaldk` + val.al_product_id + `" name="total_row_dk">` + formatRupiahIni(nominaldk.toFixed(2).toString().replace('.',',')) + `</span></td>
								<td class="text-right"><span id="totaladmin` + val.al_product_id + `" name="total_row_admin">` + formatRupiahIni(nominaladmin.toFixed(2).toString().replace('.',',')) + `</span></td>
								<td class="text-right"><span id="totalprofit` + val.al_product_id + `" name="total_row_profit">` + formatRupiahIni(nominalprofit.toFixed(2).toString().replace('.',',')) + `</span></td>
								<td class="text-right"><input type="number" value="` + percentprofit + `" class="form-control" id="percentprofit` + val.al_product_id + `"onkeyup="countPercentProfit(` + val.al_product_id + `)"></td>
								<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						</tr>
					`);
				});
				
				countAll();
				
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
	
	function countPercentProfit(id){
		var percentprofit = parseFloat($('#percentprofit' + id).val()), hpp = parseFloat($('#totalhpp' + id).text().replaceAll('.','')), dk = parseFloat($('#totaldk' + id).text().replaceAll('.','')), admin = parseFloat($('#totaladmin' + id).text().replaceAll('.',''));
		
		var percenthpp = 100 - (percentprofit + parseFloat($('#dk').val()) + parseFloat($('#admin').val()));
		var totaljual = (100 / percenthpp) * hpp;
		
		var nominalprofit = (totaljual * percentprofit) / 100;
		
		$('#totalprofit' + id).text(formatRupiahIni(nominalprofit.toFixed(0)));
		
		$('#total' + id).val(formatRupiahIni(totaljual.toFixed(2).replace('.',',')));
		
		var priceperqty = (totaljual / parseFloat($('#al_product_qty' + id).val())).toFixed(2);
		
		$('#totaldk' + id).text(formatRupiahIni((totaljual * parseFloat($('#dk').val()) / 100).toFixed(0)));
		$('#totaladmin' + id).text(formatRupiahIni((totaljual * parseFloat($('#admin').val()) /100).toFixed(0)));

		$('#al_product_price' + id).val(formatRupiahIni(priceperqty.replace('.',',')));
		countAll();
	}
	
	function countBuy(id){
		var totalrowbuy = $('#al_product_qty' + id).val() * parseFloat($('#al_product_buy_price' + id).val().replaceAll('.','').replaceAll(',','.'));
		
		$('#totalhpp' + id).html(formatRupiahIni(totalrowbuy.toFixed(2).toString().replace('.',',')));
		
		countPercentProfit(id);
	}
	
	function countSaleTotal(id){
		var totalrow = parseFloat($('#total' + id).val().replaceAll('.','').replaceAll(',','.'));
		
		$('#al_product_price' + id).val(formatRupiahIni((totalrow / parseInt($('#al_product_qty' + id).val())).toFixed(0)));
		
		$('#totaldk' + id).text(formatRupiahIni((totalrow * parseFloat($('#dk').val()) / 100).toFixed(0)));
		$('#totaladmin' + id).text(formatRupiahIni((totalrow * parseFloat($('#admin').val()) / 100).toFixed(0)));
		
		var profit = totalrow - parseFloat($('#totalhpp' + id).text().replaceAll('.','').replaceAll(',','.')) - parseFloat($('#totaldk' + id).text().replaceAll('.','').replaceAll(',','.')) - parseFloat($('#totaladmin' + id).text().replaceAll('.','').replaceAll(',','.'));
		
		$('#totalprofit' + id).text(formatRupiahIni(profit.toFixed(0)));
		
		$('#percentprofit' + id).val((profit / totalrow * 100).toFixed(2));
		
		countAll();
	}
	
	function addProduct(){
		if($('#al_product_id').val() !== ''){
			
			var adaproduk = false;
			
			$("input[name='al_product_id[]']").each(function() {
				if($('#al_product_id').val() == $(this).val()){
					adaproduk = true;
				}
			});
			
			if(adaproduk){
				notif('error', 'bg-warning', 'Produk sudah ditambahkan.');
			}else{
				if($('[name="total_row"]').length == 0){
					$('#body-produk').empty();
				}
			
				$('#body-produk').append(`
					<tr class="text-center">
						<input type="hidden" name="al_product_id[]" value="` + $('#al_product_id').val() + `">
						<td class="text-center">` + $("#al_product_id").select2('data')[0].name + `</td>
						<td><input type="number" name="al_product_qty[]" value="0" class="form-control" onkeyup="count(` + $('#al_product_id').val() + `)" id="al_product_qty` + $('#al_product_id').val() + `"></td>
						<td class="text-center">` + $("#al_product_id").select2('data')[0].unit + `</td>
						<td><input type="text" value="` + $("#al_product_id").select2('data')[0].beli + `" name="al_product_buy_price[]" class="form-control" onkeyup="formatRupiah(this);countBuy(` + $('#al_product_id').val() + `)" id="al_product_buy_price` + $('#al_product_id').val() + `"></td>
						<td><input type="text" value="` + $("#al_product_id").select2('data')[0].jual + `" name="al_product_price[]" class="form-control" onkeyup="formatRupiah(this);countSale(` + $('#al_product_id').val() + `)" id="al_product_price` + $('#al_product_id').val() + `"></td>
						<td class="text-right">
							<input type="text" value="0" name="total_row" class="form-control" onkeyup="formatRupiah(this);countSaleTotal(` + $('#al_product_id').val() + `)" id="total` + $('#al_product_id').val() + `">
						</td>
						<td class="text-right"><span id="totalhpp` + $('#al_product_id').val() + `" name="total_row_hpp">0</span></td>
						<td class="text-right"><span id="totaldk` + $('#al_product_id').val() + `" name="total_row_dk">0</span></td>
						<td class="text-right"><span id="totaladmin` + $('#al_product_id').val() + `" name="total_row_admin">0</span></td>
						<td class="text-right"><span id="totalprofit` + $('#al_product_id').val() + `" name="total_row_profit">0</span></td>
						<td class="text-right"><input type="number" value="0" class="form-control" id="percentprofit` + $('#al_product_id').val() + `"onkeyup="countPercentProfit(` + $('#al_product_id').val() + `)"></td>
						<td class="text-center"><button type="button" id="delete_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
					</tr>
				`);
				
				enableSort();
			} 
		}else{
			notif('error', 'bg-warning', 'Silahkan pilih produk sebelum menambahkan.');
		}
	}
	
	function countSale(id){
		var totalrow = $('#al_product_qty' + id).val() * parseFloat($('#al_product_price' + id).val().replaceAll('.','').replaceAll(',','.'));
		$('#total' + id).val(formatRupiahIni(totalrow.toFixed(0)));
		
		countSaleTotal(id);
	}
	
	function count(id){
		var totalrow = $('#al_product_qty' + id).val() * parseFloat($('#al_product_price' + id).val().replaceAll('.','').replaceAll(',','.'));
		$('#total' + id).val(formatRupiahIni(totalrow.toFixed(0)));
		var totalrowbuy = $('#al_product_qty' + id).val() * parseFloat($('#al_product_buy_price' + id).val().replaceAll('.','').replaceAll(',','.'));
		$('#totalhpp' + id).text(formatRupiahIni(totalrowbuy.toFixed(0)));
		
		countPercentProfit(id);
	}
	
	function countAll(){
		var total = 0, ppn = 0, pph = 0, grandtotal = 0;
		
		$('[name="total_row"]').each(function(){
			total += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		if(isPPn == 1){
			ppn = total * 0.11;
		}
		
		if($('#pph').val() == '1'){
			pph = total * 0.015;
		}
		
		grandtotal = total + ppn + pph;
		
		$('#helper-total').text(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
		$('#helper-ppn').text(formatRupiahIni(ppn.toFixed(2).toString().replace('.',',')));
		$('#helper-pph').text(formatRupiahIni(pph.toFixed(2).toString().replace('.',',')));
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
	
	function showSph(id) {
		$.ajax({
			 url: '{{ url("admin/al/sph_dkh/edit_sph/show") }}',
			 type: 'POST',
			 data: { id: id },
			 dataType: 'JSON',
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('#result-produk');
			 },
			 success: function(response) {
				loadingClose('#result-produk');
				if(response.html){
					$('#result-produk').html(response.html);
				}else{
					$('#result-produk').empty();
					$('#result-produk').html(`
						<div class="alert alert-warning alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Info!</span> Silahkan pilih SPH di sebelah kiri untuk menampilkan detail produk.
						</div>
					`);
				}
				
			 },
			 error: function() {
				loadingClose('#result-produk');
			 }
		});
	}
	
	function success(){
		$('#modal_form').modal('toggle');
		loadData();
	}
	
	function create(){
		CKEDITOR.instances['note'].updateElement();
		$.ajax({
		 url: '{{ url("admin/al/sph_dkh/edit_sph/create") }}',
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
	
	function destroySph(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus SPH ini?</h6><label>SPH yang terhapus tidak akan bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/sph_dkh/edit_sph/destroy") }}',
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
						location.reload();
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