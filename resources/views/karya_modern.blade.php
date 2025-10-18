@php
use App\Models\ProductShading;
@endphp
<style>
	#tableData tbody tr.selected {
		background-color: lightcoral;
		color: white;
	}

	/* .table {
      font-size: 9px;
    } */

	.table-fixed thead {
		width: 100%;
		position: sticky;
		top: 0;
		background-color: #f8f9fa;
		/* Optional: Change background color */
	}
</style>
<section id="page-title" class="page-title-parallax page-title-dark" data-bottom-top="background-position:0px 300px;"
	data-top-bottom="background-position:0px -300px;">
	<div class="container clearfix">
		<h1>Karya Modern Product List</h1>
		<span>Search by item type, name, code, or warehouse code.</span>
	</div>
</section>
<section id="content">
	<div class="content-wrap" style="padding: 40px 0;">
		<div class="container clearfix">

			<div class="row col-mb-50">
				<div class="col-md-12">
					<div class="form-widget">
						<div class="form-result"></div>

						<form action="" id="template-jobform" name="template-jobform" class="row mb-0" method="get">

							<div class="form-process">
								<div class="css3-spinner">
									<div class="css3-spinner-scaler"></div>
								</div>
							</div>
							<div class="col-md-4 form-group">
								<label for="template-jobform-fname">Type or Name</label>
								<input type="text" id="search" name="search" class="sm-form-control"
									value="{{ $search }}" placeholder="Ex: tempatsabun etc" />
							</div>

							<div class="col-md-4 form-group">
								<label for="template-jobform-lname">Code</label>
								<input type="text" id="code" name="code" class="sm-form-control" value="{{ $code }}"
									placeholder="Ex: 136.00013 etc" />
							</div>

							<div class="col-md-4 form-group">
								<label for="warehouse">Warehouse</label>
								<select class="sm-form-control" name="warehouse" id="warehouse">
									<option value="">-- All Warehouse --</option>
									@foreach($warehouselist as $row)
									@php
									$selected = '';
									if($row->code == $warehouse){
									$selected = 'selected';
									}
									@endphp
									<option value="{{ $row->code }}" {{ $selected }}>{{ $row->name }}</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-4 form-group">
								<a class="button button-3d button-large button-green w-100 m-0 text-center"
									id="btnSelectRow">Select All</a>
							</div>

							<div class="col-md-1 form-group">
								<a class="button button-3d button-large button-green w-100 m-0 text-center"
									id="btnSelectRow"><i class="icon-pdf"></i></a>
							</div>

							<div class="col-12 form-group">
								<button class="button button-3d button-large w-100 m-0" type="submit"
									value="apply">Search</button>
							</div>
						</form>
					</div>
					<div class="text-center">
						<a href="{{ url('product/karya_modern') }}"
							class="button button-3d button-rounded button-red w-100 m-0"><i
								class="icon-line-rotate-cw"></i>Reset</a>
					</div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped w-100 table-fixed" id="tableData">
							<thead class="bg-dark text-white">
								<tr class="text-center">
									<th>No</th>
									<th>Name</th>
									<th>Stock</th>
									<th>Shading</th>
									<th>Warehouse</th>
									<th>Tipe</th>
									<th>Code</th>
									<th>SMB Code</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								@if($items->count() > 0)
								@foreach($items as $key => $i)
								@php
								$data =
								ProductShading::where('warehouse_code',$i->kode_gudang)->where('stock_code',$i->kode_item)->first();
								$image = $data ? '<div class="slider-wrap" data-lightbox="gallery">
									<div class="slide" data-thumb="'.$data->product->type->image().'"><a
											href="'.$data->product->type->image().'" title="'.$i->nama.'"
											data-lightbox="gallery-item" class="image-link"><img
												src="'.$data->product->type->image().'" alt="'.$i->nama.'"
												class="img-fluid img-thumbnail" alt="'.$i->nama.'" width="150px"></a>
									</div>
								</div>' : '<button class="button button-3d button-rounded button-red">None</button>' ;

								$smb_code = $data ? $data->product->code() : '';
								@endphp
								<tr class="text-center pick">
									<td class="align-middle">{{ $key + $items->firstItem() }}</td>
									<td class="align-middle">{!! $image.'<div class="mt-3">'.$i->nama.'</div>' !!}</td>
									<td class="align-middle">{{ (int)$i->stok }}</td>
									<td class="align-middle">{{ $i->shading }}</td>
									<td class="align-middle">{{ $i->kode_gudang.' - '.$i->nama_gudang }}</td>
									<td class="align-middle">{{ $i->tipe_item }}</td>
									<td class="align-middle">{{ $i->kode_item }}</td>
									<td class="align-middle">{{ $smb_code }}</td>
									<td class="align-middle">{{ $i->keterangan }}</td>
								</tr>
								@endforeach
								@else
								<tr class="text-center">
									<td class="align-middle" colspan="9">
										<div class="font-italic">Data not found</div>
									</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
					<div class="mt-4">
						{{ $items->withQueryString()->onEachSide(1)->links('admin.pagination') }}
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<script src="{{ asset('template/front-office/js/plugins.min.js') }}"></script>
<script src="{{ asset('template/front-office/js/plugins.lightbox.js') }}"></script>
<script>
	$(document).ready(function() {
	  $('.image-link').magnificPopup({type:'image'});
	  
	  $('#tableData tbody').on('click', 'tr', function () {
				if ($(this).hasClass("pick")) {
					$(this).toggleClass('selected');
				}else {
					notif('error', 'bg-danger', 'Bill cannot be selected.');
				}
		});

		$('#btnSelectRow').click(function (e) { 
			$('tr').toggleClass('selected')
			if($(this).text() == 'Select All'){
				$(this).text('Unselect All');
				$(this).removeClass('button-green');
				$(this).addClass('button-red');

			}else{
				$(this).text('Select All');
				$(this).removeClass('button-red');
				$(this).addClass('button-green');
			}
		});
	});
</script>