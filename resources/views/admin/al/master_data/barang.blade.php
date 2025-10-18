<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<style>
	.select2-container{ width: 100% !important; }
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Barang</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<span class="breadcrumb-item active">Barang</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Barang</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Kategori</th>
							<th>Supplier</th>
							<th>Keterangan</th>
							<th>Beli</th>
							<th>Jual</th>
							<th>Unit</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah / Edit Barang & Kategori</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
			<div class="form-group">
				<div class="row p-3">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-top nav-justified mt-3">
							<li class="nav-item"><a href="#barang-tab" class="nav-link active" data-toggle="tab">Barang</a></li>
							<li class="nav-item"><a href="#induk-tab" class="nav-link" data-toggle="tab">Kelompok (Muncul di SPH & DKH)</a></li>
							<li class="nav-item"><a href="#kategori-tab" class="nav-link" data-toggle="tab">Kategori</a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade show active" id="barang-tab">
								<form id="form_data">
									<div class="alert alert-danger" id="validation_alert" style="display:none;">
									  <ul id="validation_content"></ul>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Nama :<span class="text-danger">*</span></label>
												<input type="hidden" name="temp" id="temp">
												<input type="text" name="name" id="name" class="form-control" placeholder="Ketik nama produk disini...">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Supplier :<span class="text-danger">*</span></label>
												<select name="al_supplier_id" id="al_supplier_id" class="select2">
													 <option value="">-- Pilih satu --</option>
													 @foreach($supplier as $sup)
														<option value="{{ $sup->id }}">{{ $sup->name }}</option> 
													 @endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Kelompok (Jika ada) :</label>
												<select name="al_product_parent_id" id="al_product_parent_id" class="select2">
													 <option value="">-- Kosong --</option>
													 @foreach($parent as $p)
														<option value="{{ $p->id }}">{{ $p->name }}</option> 
													 @endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Kategori :<span class="text-danger">*</span></label>
												<select name="al_category_id" id="al_category_id" class="select2">
													 <option value="">-- Pilih satu --</option>
													 @foreach($category as $cat)
														<option value="{{ $cat->id }}">{{ $cat->name }}</option> 
													 @endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Harga Beli (IDR):<span class="text-danger">*</span></label>
												<input type="text" name="buy_price" id="buy_price" class="form-control" value="0" onkeyup="formatRupiah(this)">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Harga Jual (IDR):<span class="text-danger">*</span></label>
												<input type="text" name="sell_price" id="sell_price" class="form-control" value="0" onkeyup="formatRupiah(this)">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Satuan :<span class="text-danger">*</span></label>
												<select id="unit" name="unit" class="custom-select" required>
													<option value="1">Buah</option>
													<option value="2">Kotak</option>
													<option value="3">Meter</option>
													<option value="4">Set</option>
													<option value="5">Roll</option>
													<option value="6">Coil</option>
												</select>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Deskripsi :<span class="text-danger">*</span></label>
												<textarea name="description" id="description" class="form-control" rows="1" placeholder="Enter description"></textarea>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="induk-tab">
								<div class="row justify-content-center">
									<div class="col-md-6">
										<form id="form_data_induk">
										   <div class="alert alert-danger" id="validation_alert_induk" style="display:none;">
											  <ul id="validation_content_induk"></ul>
										   </div>
										   <div class="form-group">
											  <label>Nama Kelompok :<span class="text-danger">*</span></label>
											  <input type="hidden" name="tempInduk" id="tempInduk">
											  <input type="text" name="name_induk" id="name_induk" class="form-control" placeholder="Masukkan nama induk produk disini.">
										   </div>
										</form>
										<div class="row">
											<div class="col-md-4 mx-auto mt-2">
												<button type="button" class="btn bg-primary btn-block" onclick="create_induk()"><i class="icon-plus3"></i> Tambah Kelompok</button>
											</div>
										</div>
										<div class="form-group"><hr></div>
										<h5 class="card-title">List Data Kelompok Produk</h5>
										<div class="table-responsive">
										   <table id="datatable_serverside_induk" class="table table-bordered table-striped">
											  <thead class="bg-dark">
												 <tr class="text-center">
													<th>No</th>
													<th>Name</th>
													<th>Action</th>
												 </tr>
											  </thead>
										   </table>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="kategori-tab">
								<div class="row justify-content-center">
									<div class="col-md-6">
										<form id="form_data_kategori">
										   <div class="alert alert-danger" id="validation_alert_kategori" style="display:none;">
											  <ul id="validation_content_kategori"></ul>
										   </div>
										   <div class="form-group">
											  <label>Nama Kategori :<span class="text-danger">*</span></label>
											  <input type="hidden" name="tempKategori" id="tempKategori">
											  <input type="text" name="name_category" id="name_category" class="form-control" placeholder="Masukkan kategori disini.">
										   </div>
										   <div class="form-group">
												<label>Parent :<span class="text-danger">*</span></label>
												<select name="al_category_parent" id="al_category_parent" class="select2">
													 <option value="0">is Parent</option>
													 @foreach($category as $cat)
														<option value="{{ $cat->id }}">{{ $cat->name }}</option> 
													 @endforeach
												</select>
										   </div>
										</form>
										<div class="row">
											<div class="col-md-4 mx-auto mt-2">
												<button type="button" class="btn bg-primary btn-block" onclick="create_kategori()"><i class="icon-plus3"></i> Tambah Kategori</button>
											</div>
										</div>
										<div class="form-group"><hr></div>
										<h5 class="card-title">List Data Kategori</h5>
										<div class="table-responsive">
										   <table id="datatable_serverside_category" class="table table-bordered table-striped">
											  <thead class="bg-dark">
												 <tr class="text-center">
													<th>No</th>
													<th>Name</th>
													<th>Parent</th>
													<th>Action</th>
												 </tr>
											  </thead>
										   </table>
										</div>
									</div>
								</div>
							</div>
						</div>
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

<div class="modal fade" id="modal_pictures" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Add Project Photos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" name="tempproduct" id="tempproduct">
			<p class="mb-3">Anda bisa mengupload file gambar dengan syarat <b>Ukuran maksimum : 1 Mb / 1024 Kb, Jumlah maksimal : 3.</b></p>

			<p class="font-weight-semibold">Multiple file upload :</p>
			<form action="{{ url('admin/al/master_data/barang/add_pictures') }}" class="dropzone" id="dropzone_multiple">
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
		ckEditor('description');
		loadDataTable();
		loadDataTableCategory();
		loadDataTableInduk();
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#name,#sell_price,#buy_price').val('');
			$('#unit').val('1');
			/* $('#al_supplier_id').val('').trigger('change'); */
			/* $('#al_product_parent_id').val('').trigger('change'); */
			/* $('#al_category_id').val('').trigger('change'); */
			$('#temp').val('');
			CKEDITOR.instances['description'].setData('');
		});
		
		$('#modal_pictures').on('hidden.bs.modal', function (e) {
			loadDataTable();
		});
		
	});
	
	function loadDataTableCategory() {
      $('#datatable_serverside_category').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/master_data/barang/datatable_category") }}',
            type: 'GET',
            data: {
               
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside_category');
            },
            complete: function() {
               loadingClose('#datatable_serverside_category');
            },
            error: function() {
               loadingClose('#datatable_serverside_category');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
			{ name: 'nama', className: 'text-center align-middle' },
			{ name: 'parent', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function loadDataTableInduk() {
      $('#datatable_serverside_induk').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/master_data/barang/datatable_parent") }}',
            type: 'GET',
            data: {
               
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside_induk');
            },
            complete: function() {
               loadingClose('#datatable_serverside_induk');
            },
            error: function() {
               loadingClose('#datatable_serverside_induk');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
			{ name: 'nama', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
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
            url: '{{ url("admin/al/master_data/barang/datatable") }}',
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
            { name: 'kategori', orderable: false, className: 'text-center align-middle' },
            { name: 'supplier', orderable: false, className: 'text-center align-middle' },
			{ name: 'description', className: 'text-center align-middle' },
            { name: 'buy', className: 'text-center align-middle' },
			{ name: 'sell', className: 'text-center align-middle' },
			{ name: 'unit', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	var tempproduct = 0;
	
	function addPictures(id){
		$('#tempproduct').val(id);
		tempproduct = id;
		$('#list-images').empty();
		$.ajax({
			 url: '{{ url("admin/al/master_data/barang/get_pictures") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempproduct
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
								<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.image + `"><img src="` + val.image + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
								<p class="mt-3">
									<button class="btn btn-danger btn-sm" onclick="destroyPicture(` + val.id + `);"><i class="icon-trash"></i></button>
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
	
	function success(){
		$('#modal_form').modal('toggle');
		loadDataTable();
	}
	
	function create(){
		CKEDITOR.instances['description'].updateElement();
      $.ajax({
         url: '{{ url("admin/al/master_data/barang/create") }}',
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
	
	function create_kategori(){
		
		$.ajax({
         url: '{{ url("admin/al/master_data/barang/create_category") }}',
         type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_kategori')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_kategori').hide();
            $('#validation_content_kategori').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_kategori')[0].reset();
				$('#tempKategori').val('');
				$('#al_category_id').empty();
				$('#al_category_id').append(`<option value="">-- Pilih satu --</option>`);
				$.each(response.data, function(i, val) {
					$('#al_category_id').append(`
						<option value="` + val.id + `">` + val.name + `</option>
					`);
				});
				
	
				
				loadDataTableCategory();
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_kategori').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_kategori').append(`
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
		
		return false;
	}
	
	function create_induk(){
		
		$.ajax({
         url: '{{ url("admin/al/master_data/barang/create_parent") }}',
         type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_induk')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_kategori').hide();
            $('#validation_content_kategori').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_induk')[0].reset();
				$('#tempInduk').val('');
				$('#al_product_parent_id').empty();
				$('#al_product_parent_id').append(`<option value="">-- Kosong --</option>`);
				$.each(response.data, function(i, val) {
					$('#al_product_parent_id').append(`
						<option value="` + val.id + `">` + val.name + `</option>
					`);
				});
				
				loadDataTableInduk();
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_induk').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_induk').append(`
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
		
		return false;
	}
	
	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		maxFilesize: 1,
		maxFiles: 3,
		acceptedFiles: ".jpeg,.jpg,.png,.gif",
		init: function() {
			this.on("sending", function(file, xhr, formData){
				formData.append('id', tempproduct);
			});
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}
			});
		}
	};
	
	function show(id) {
      $.ajax({
         url: '{{ url("admin/al/master_data/barang/show") }}',
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
            $('#modal_form').modal('toggle');
			$('#temp').val(id);
			$('#name').val(response.name);
			$('#al_product_parent_id').val(response.al_product_parent_id).trigger('change');
			$('#al_supplier_id').val(response.al_supplier_id).trigger('change');
			$('#al_category_id').val(response.al_category_id).trigger('change');
			CKEDITOR.instances['description'].setData(response.description);
			$('#buy_price').val(response.buy_price);
			$('#sell_price').val(response.sell_price);
			$('#unit').val(response.unit);
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
	
	function showCategory(id) {
      $.ajax({
         url: '{{ url("admin/al/master_data/barang/show_category") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
			$('#validation_alert_kategori').hide();
            $('#validation_content_kategori').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
			$('.modal-body').animate({ scrollTop: 0 }, 'slow');
			$('#tempKategori').val(id);
          if(response.parent_id != 0){
            $('#al_category_parent').val(response.parent_id).trigger('change.select2');
         }else{
            $('#al_category_parent').val(response.parent_id).trigger('change');
         }
			$('#name_category').val(response.name);
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
	
	function showInduk(id) {
      $.ajax({
         url: '{{ url("admin/al/master_data/barang/show_parent") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
			$('#validation_alert_induk').hide();
            $('#validation_content_induk').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
			$('.modal-body').animate({ scrollTop: 0 }, 'slow');
			$('#tempInduk').val(id);
			$('#name_induk').val(response.name);
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
	
	
	function destroy(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus data produk?</h6><label>Data yang terhapus tidak akan bisa dikembalikan.</label>',
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
                  url: '{{ url("admin/al/master_data/barang/destroy") }}',
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
   
   function destroyCategory(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus data kategori?</h6><label>Data yang terhapus tidak akan bisa dikembalikan.</label>',
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
                  url: '{{ url("admin/al/master_data/barang/destroy_category") }}',
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
                        $('#datatable_serverside_category').DataTable().ajax.reload(null, false);
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
   
   function destroyInduk(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus data induk?</h6><label>Data yang terhapus tidak akan bisa dikembalikan.</label>',
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
                  url: '{{ url("admin/al/master_data/barang/destroy_parent") }}',
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
                        $('#datatable_serverside_induk').DataTable().ajax.reload(null, false);
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
   
   function destroyPicture(val) {
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Apakah anda yakin ingin menghapus gambar?</h6><label>Gambar yang terhapus tidak bisa dikembalikan.</label>',
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
				 url: '{{ url("admin/al/master_data/barang/delete_picture") }}',
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
</script>