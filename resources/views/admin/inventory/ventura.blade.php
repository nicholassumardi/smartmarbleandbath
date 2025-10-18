<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Ventura</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-indigo-400 btn-labeled mr-2 btn-labeled-left" onclick="exportData()">
						<b><i class="icon-file-excel"></i></b> Export
					</button>
					<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left" onclick="printData()">
						<b><i class="icon-printer2"></i></b> Print
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Inventory</a>
					<span class="breadcrumb-item active">Ventura</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
        <div class="card">
            <div class="card-body">
				<div class="alert alert-info alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Attention!</span> This information was taken from Ventura's real time stock. Please confirm before using this information to Ventura.</a>.
				</div>
                <form action="">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group mb-0">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search type item or name" value="{{ $search }}">
								<input type="text" name="code" id="code" class="form-control" placeholder="Search code item" value="{{ $code }}">
								<input type="text" name="warehouse" id="warehouse" class="form-control" placeholder="Search warehouse code" value="{{ $warehouse }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <button type="submit" class="btn bg-success col-12"><i class="icon-search4"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
		<div class="card">
			<div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="bg-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tipe</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Warehouse</th>
                                <th>Shading</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($items->count() > 0)
                                @foreach($items as $key => $i)
                                    <tr class="text-center">
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $i->tipe_item }}</td>
                                        <td class="align-middle">{{ $i->kode_item }}</td>
                                        <td class="align-middle">{{ $i->nama }}</td>
                                        <td class="align-middle">{{ $i->kode_gudang.' - '.$i->nama_gudang }}</td>
                                        <td class="align-middle">{{ $i->shading }}</td>
                                        <td class="align-middle">{{ (int)$i->stok }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td class="align-middle" colspan="7">
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
<script>
	function exportData(){
		var search = $('#search').val(), warehouse = $('#warehouse').val(), code = $('#code').val();
		
		window.location = "{{ url('admin/inventory/ventura/export') }}?search=" + search + "&warehouse=" + warehouse + "&code=" + code;
   }
   
   function printData(){
		var search = $('#search').val(), page = {{ Request::get('page') ? Request::get('page') : '1' }}, warehouse = $('#warehouse').val(), code = $('#code').val();
		
		window.open("{{ url('admin/inventory/ventura/print') }}?search=" + search + "&warehouse=" + warehouse + "&page=" + page + "&code=" + code, "_blank");
   }
</script>