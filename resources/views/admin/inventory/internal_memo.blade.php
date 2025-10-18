<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Internal Memo</span>
                </h4>
            </div>
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
                        onclick="loadDataTable()">
                        <b><i class="icon-sync"></i></b> Refresh
                    </button>
                    <button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" onclick="reset()"
                        data-toggle="modal" data-target="#modal_form">
                        <b><i class="icon-plus3"></i></b> Add
                    </button>
                </div>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Data</a>
                    <span class="breadcrumb-item active">Project</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">List Internal Memos</h5>
                <div class="table-responsive">
                    <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                        <thead class="bg-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>User</th>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Proof</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_data">
                        <div class="alert alert-danger" id="validation_alert"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                            <ul id="validation_content"></ul>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-1 col-form-label">Title :<sup class="text-danger">*</sup></label>
                            <div class="col-lg-11">
                                <input type="hidden" name="temp" id="temp">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Enter title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-1 col-form-label">Date<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-1 col-form-label">Proof<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <input type="file" name="proof" id="proof" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-1 col-form-label">Note<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <textarea name="note" id="note" class="form-control" rows="1"
                                    placeholder="Enter description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
                        Close</button>
                    <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i
                            class="icon-plus3"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
        ckEditor('note');
        loadDataTable();
        $('#modal_form').on('hidden.bs.modal', function (e) {
            $('#version,#released_date').val('');
            $('#temp').val('');
            CKEDITOR.instances['note'].setData('');
        });
    });



    function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/inventory/internal_memo/datatable") }}',
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
            { name: null, searchable: false, className: 'text-center align-middle' },
			{ name: 'code', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'note', className: 'text-center align-middle' },
			{ name: 'proof', className: 'text-center align-middle' },
			{ name: null, className: 'text-center align-middle' },
         ]
      }); 
   }
    </script>