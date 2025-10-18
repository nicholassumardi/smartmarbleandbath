<style>
	#datatable_serverside tbody tr.selected {
		background-color: green;
		color:white;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Approval</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn btn-warning mr-2"><b id="countSelected">0</b> Selected</button>
					<button type="button" class="btn bg-info btn-labeled mr-2 btn-labeled-left d-none" id="btn-approve" onclick="multiApprove()">
						<b><i class="icon-stack-check"></i></b> Approve
					</button>
					<button onclick="selectAllRow()" class="btn btn-primary mr-2">(Un) Select All Rows</button>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh Data
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">Approval</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
				<div class="form-group">
				   <label>Date :</label>
				   <div class="input-group">
					  <input type="hidden" name="filter_temp" id="filter_temp">
					  <input type="date" name="filter_start_date" id="filter_start_date" max="{{ date('Y-m-d') }}" class="form-control">
					  <div class="input-group-prepend">
						 <span class="input-group-text">To</span>
					  </div>
					  <input type="date" name="filter_finish_date" id="filter_finish_date" max="{{ date('Y-m-d') }}" class="form-control">
				   </div>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
				   <div class="text-right">
					  <button type="button" onclick="loadDataTable()" class="btn bg-teal mr-2"><i class="icon-filter4"></i> Search</button>
					  <button type="button" onclick="resetFilter()" class="btn bg-danger mr-2"><i class="icon-sync"></i></button>
				   </div>
				   <div class="alert bg-info text-white alert-styled-left alert-dismissible mt-3">Multi approval <b>cannot</b> be used to <b>Purchase Request, Multi Payment Project, Purchase From Stock, Other Receivable Payments</b> approval.</div>
				</div>
				<span class="mb-3">Searchable field, column only for Date & From and Ref. Code</span>
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Type</th>
							<th>Ref</th>
							<th>Date</th>
							<th>Approval</th>
							<th>Customer</th>
							<th>From</th>
							<th>Action</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>

<script>
   $(function() {
      loadDataTable();
	  
		$('#datatable_serverside tbody').on('click', 'tr', function () {
			if($(this).find('.pick').data('id')){
				$(this).toggleClass('selected');
				
				var arrId = [];
			
				$('#datatable_serverside tr.selected').each(function(){
					if($(this).find('.pick').data('id')){
						arrId.push($(this).find('.pick').data('id'));
					}
				});
				
				$('#filter_temp').val(arrId.join());
				
				countSelected();
			}else{
				notif('error', 'bg-danger', 'This approval type is not allowed to be selected.');
			}
		});
   });
   
	function countSelected(){
		var count = 0;
		$('#datatable_serverside tr.selected').each(function(){
			count += 1;
		});
		
		$('#countSelected').text(count);
		
		if($('#datatable_serverside tr.selected').length > 0){
			$('#btn-approve').removeClass('d-none');
		}else{
			$('#btn-approve').addClass('d-none');
		}
	}
	
	function multiApprove(){
		if($('#datatable_serverside tr.selected').length > 0){
			var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Are sure you want to approve?</h6><label>Approved data can no longer be reversed.</label>',
			 timeout: false,
			 modal: true,
			 layout: 'center',
			 closeWith: 'button',
			 type: 'confirm',
			 buttons: [
				Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
				   notyConfirm.close();
				}),
				Noty.button('<i class="icon-checkmark"></i>', 'btn bg-success ml-1', function() {
					$.ajax({
					 url: '{{ url("admin/approval/multi_approve") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: { val: $('#filter_temp').val() },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.content');
					 },
					 success: function(response) {
						 if(response.status == '200'){
							 notif('success', 'bg-success', response.message);
							 loadDataTable();
							 loadingClose('.content');
							 notyConfirm.close();
							 $('#datatable_serverside tr.selected').each(function(){
								$(this).removeClass('selected');
							 });
							 countSelected();
						 }
					 }
					});
				})
			 ]
		  }).show();
		}else{
			notif('error', 'bg-danger', 'Please at least check or choose 1 row.');
		}
	}

   function resetFilter() {
      $('#filter_start_date').val(null);
      $('#filter_finish_date').val(null);
      $('input[name="filter_type"][value=""]').prop('checked', true);
      loadDataTable();
   }
   
	function selectAllRow(){
		$('#datatable_serverside tbody tr').each(function(){
			if($(this).find('.pick').data('id')){
				$(this).trigger('click');
			}
		});
		countSelected();
	}

   function loadDataTable() {
      $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         //order: [[4, 'asc']],
         ajax: {
            url: '{{ url("admin/approval/datatable") }}',
            type: 'GET',
            data: {
               type: $('input[name="filter_type"]:checked').val(),
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
            { name: 'id', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'approvalable_type', orderable: false, className: 'text-center align-middle' },
            { name: 'ref', orderable: false, searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'approved_by', orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'customer', orderable: false, searchable: false, className: 'text-center nowrap align-middle' },
			{ name: 'from', orderable: false, searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
		 "createdRow": function( row, data, dataIndex){
			if( data[8] !== ''){
				$(row).addClass('bg-danger');
			}
		 },
		 "columnDefs": [ {
			"targets": 0,
			"orderable": false
		 }]
      }); 
   }
   
   /* function goApprove(val){
	   $.ajax({
         url: '{{ url("admin/approval/project") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { val:val },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#datatable_serverside');
         },
         success: function(response) {
			 if(response.status == '200'){
				 loadingClose('#datatable_serverside');
				 loadDataTable();
			 }
         }
      });
	  
	  return false;
   } */
</script>