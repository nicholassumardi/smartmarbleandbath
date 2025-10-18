<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Ledger</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Ledger</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
			<div class="card-header header-elements-inline">
				<h2 class="card-title font-weight-bold">Filter</h2>
			</div>
         <div class="card-body">
            <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Branch :</label>
						<select name="filter_branch" id="filter_branch" class="form-control">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Source :</label>
                     <select name="filter_coa_id" id="filter_coa_id" class="select2">
                        <option value="">All</option>
                        @foreach($coa as $c)
                           <option value="{{ $c->id }}">[{{ $c->code }}] {{ $c->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Date :</label>
                     <div class="input-group">
                        <input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
                        <div class="input-group-prepend">
                           <span class="input-group-text">To</span>
                        </div>
                        <input type="date" name="filter_finish_date" id="filter_finish_date" class="form-control">
                     </div>
                  </div>
               </div>
            </div>
            <div class="form-group text-right">
               <button type="button" onclick="filter()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
			   <button type="button" onclick="filter('reset')" class="btn bg-danger"><i class="icon-sync"></i></button>
            </div>
         </div>
      </div>
      <div class="mb-3">
         <h6 class="mb-0 font-weight-semibold text-center text-uppercase">
            <span id="string_filter_periode"></span>
         </h6>
      </div>
		<div class="card">
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>Details</th>
                        <th>No</th>
                        <th>Source</th>
                        <th>Beginning</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Ending</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>

<script>
   $(function() {
      $('.sidebar-main-toggle').click();
      filter();

      $('#datatable_serverside tbody').on('click', 'td.details-control', function() {
         loadingOpen('#datatable_serverside');
         var tr    = $(this).closest('tr');
         var badge = tr.find('span.badge');
         var icon  = tr.find('i');
         var row   = table.row(tr);

         if(row.child.isShown()) {
            loadingClose('#datatable_serverside');
            row.child.hide();
            tr.removeClass('shown');
            badge.first().removeClass('badge-danger');
            badge.first().addClass('badge-success');
            icon.first().removeClass('icon-minus3');
            icon.first().addClass('icon-plus3');
         } else {
            loadingClose('#datatable_serverside');
            row.child(rowDetail(row.data())).show();
            tr.addClass('shown');
            badge.first().removeClass('badge-success');
            badge.first().addClass('badge-danger');
            icon.first().removeClass('icon-plus3');
            icon.first().addClass('icon-minus3');
         }
      });
	  
	  @if($coa_id)
		$('#filter_branch').val('{{ $branch }}');
		$('#filter_coa_id').val('{{ $coa_id }}').trigger('change');
		$('#filter_start_date').val('{{ $start_date }}');
		$('#filter_finish_date').val('{{ $end_date }}');
		setTimeout(function(){
			filter();
		}, 500);
	  @endif
   });

   function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/report/accounting/ledger/row_detail") }}',
         type: 'GET',
         async: false,
         data: {
            id: $(data[0]).data('id'),
			branch: $('#filter_branch').val(),
            start_date: $('#filter_start_date').val(),
            finish_date: $('#filter_finish_date').val()
         },
         success: function(response) {
            content += response;
         },
         error: function() {
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });

      return content;
   }

   function resetFilter() {
	  $('#filter_branch').val('1');
      $('#filter_coa_id').val(null).trigger('change');
      $('#filter_start_date').val(null);
      $('#filter_finish_date').val(null);
   }

   function filter(param = null) {
      if(param == 'reset') {
         resetFilter();
      }

	  var branch  = $('#filter_branch option:selected').text();
      var start_date  = $('#filter_start_date').val();
      var finish_date = $('#filter_finish_date').val();

      if(start_date && finish_date && branch) {
         $('#string_filter_periode').html('Periode <br>' + $.dateString(start_date) + ' - ' + $.dateString(finish_date) + '<br>Branch : ' + branch);
      } else if(start_date && branch) {
         $('#string_filter_periode').html('Periode <br>' + $.dateString(start_date) + '<br>Branch : ' + branch);
      } else if(finish_date && branch) {
         $('#string_filter_periode').html('Periode <br>' + $.dateString(finish_date) + '<br>Branch : ' + branch);
      } else {
         $('#string_filter_periode').html('All Periode');
      }

      window.table = loadDataTable();
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
            url: '{{ url("admin/report/accounting/ledger/datatable") }}',
            type: 'GET',
            data: {
				branch: $('#filter_branch').val(),
				coa_id: $('#filter_coa_id').val(),
				start_date: $('#filter_start_date').val(),
				finish_date: $('#filter_finish_date').val()
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
            { name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'align-middle' },
            { name: 'beginning', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'debit', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'credit', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'ending', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }
</script>