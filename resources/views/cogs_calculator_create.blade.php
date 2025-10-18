<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
<style>
    th {
        font-size: 12px;
    }

    .invoice-box {
        font-size: 16px;
        font-family: 'Lato', sans-serif;
        color: #555;
        page-break-after: always;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 0px;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 0px;
    }

    .invoice-box table tr.heading td {
        background: #cf9604;
        border-bottom: 1px solid #cf9604;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 0px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .invoice-box.rtl {
        direction: rtl;
        font-family: 'Lato', sans-serif;
    }

    .invoice-box.rtl table {
        text-align: right;
    }

    .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
    }
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Create New COGS</span>
                </h4>
            </div>
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <a href="{{ url('cogs_calculator') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
                        <b><i class="icon-arrow-left7"></i></b> Back To List
                    </a>
                </div>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('cogs_calculator') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> COGS
                        Calculator</a>
                    <span class="breadcrumb-item active">Create</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        @if($errors->any())
        <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @elseif(session('success'))
        <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Success!</span> {{ session('success') }}
        </div>
        @elseif(session('failed'))
        <div class="alert bg-danger text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Failed!</span> {{ session('failed') }}
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="" method="POST" id="form_data">
                    @csrf
                    <section class="py-5 header">
                        <div class="container-fluid py-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <!-- Tabs nav -->
                                    <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab"
                                        role="tablist" aria-orientation="vertical">
                                        <a class="nav-link mb-3 p-3 shadow active" id="v-pills-home-tab"
                                            data-toggle="pill" href="#v-pills-home" role="tab"
                                            aria-controls="v-pills-home" aria-selected="true">
                                            <i class="fa fa-user-circle-o mr-2"></i>
                                            <span class="font-weight-bold small text-uppercase">Products</span></a>

                                        <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill"
                                            href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                                            aria-selected="false">
                                            <i class="fa fa-calendar-minus-o mr-2"></i>
                                            <span class="font-weight-bold small text-uppercase">EMKL</span></a>

                                        <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"
                                            href="#v-pills-messages" role="tab" aria-controls="v-pills-messages"
                                            aria-selected="false">
                                            <i class="fa fa-star mr-2"></i>
                                            <span class="font-weight-bold small text-uppercase">Imports</span></a>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <!-- Tabs content -->
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade shadow rounded bg-white show active p-5"
                                            id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                            <h4 class="font-italic mb-4">Product Specification</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Exclusive Partner :<span
                                                                class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="text" name="partners" id="partners"
                                                                class="form-control"
                                                                placeholder="Please input partners name here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Name/ Brand :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="text" name="name" id="name"
                                                                class="form-control"
                                                                placeholder="Please input brand/ product name here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Width :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="width" id="width"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Weight :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="weight" id="weight"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Kg</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Water Absorbtion :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="weight" id="weight"
                                                                class="form-control" placeholder="0" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Thickness :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="thickness" id="thickness"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">mm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Box :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="box_pcs" id="box_pcs"
                                                                class="form-control" onkeyup="formula()"
                                                                placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">/ Pcs
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>SQM :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="carton_sqm" id="carton_sqm"
                                                                class="form-control" placeholder="0" disabled>
                                                            <div class="form-control-feedback font-weight-bold">/ Carton
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Cubic Meters :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="cubic_meter" id="cubic_meter"
                                                                class="form-control" placeholder="0" disabled>
                                                            <div class="form-control-feedback font-weight-bold">/ Stock
                                                                Unit</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-profile"
                                            role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                            <h4 class="font-italic mb-4"><b>EMKL</b></h4>
                                            <h4 class="font-italic mb-4"><b>1. SEA CUSTOMS CLEARANCE</b></h4>
                                            <h4 class="font-italic mb-4">Handling</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                       <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                       <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                       <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                       <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="font-italic mb-4"><b>2. OTHER COST</b></h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Length :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="length" id="length"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Width :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="width" id="width"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Cm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Weight :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="weight" id="weight"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">Kg</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Water Absorbtion :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="weight" id="weight"
                                                                class="form-control" placeholder="0" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Thickness :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="thickness" id="thickness"
                                                                class="form-control" placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">mm</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Box :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="box_pcs" id="box_pcs"
                                                                class="form-control" onkeyup="formula()"
                                                                placeholder="0">
                                                            <div class="form-control-feedback font-weight-bold">/ Pcs
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>SQM :</label>
                                                        <div class="position-relative">
                                                            <input type="number" name="carton_sqm" id="carton_sqm"
                                                                class="form-control" placeholder="0" disabled>
                                                            <div class="form-control-feedback font-weight-bold">/ Carton
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <label>Cubic Meters :<span class="text-danger">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="number" name="cubic_meter" id="cubic_meter"
                                                                class="form-control" placeholder="0" disabled>
                                                            <div class="form-control-feedback font-weight-bold">/ Stock
                                                                Unit</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-messages"
                                            role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                            <h4 class="font-italic mb-4">Imports</h4>
                                            <p class="font-italic text-muted mb-2">Lorem ipsum dolor sit amet,
                                                consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                                et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                                                dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <div class="card">
                                    <div class="card-header bg-secondary header-elements-inline"
                                        style="background-color:#2196f3 !important;">
                                        <span class="card-title font-weight-semibold">Report</span>
                                        <div class="header-elements">
                                            <div class="list-icons">
                                                <a class="list-icons-item" data-action="collapse"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body" style="background-color:white !important;min-height:300px;"
                                        id="result-produk">
                                        <!doctype html>
                                        <html lang="en">

                                        <body>
                                            <div class="invoice-box">
                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td colspan="2">
                                                            <table>
                                                                <tr>
                                                                    <td style="text-align:center;">
                                                                        <img src="{{ url('website/logo_al_rev_3.jpg') }}"
                                                                            width="auto" height="100px">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100%">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            width="100%"
                                                                            style="margin-left: 50px; margin-top: 100px">
                                                                            <tr>
                                                                                <td width="30%"><b>Exclusive Partner</b>
                                                                                </td>
                                                                                <td style="text-align:left !important;">
                                                                                    : </u></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="30%"><b>Brand</b></td>
                                                                                <td style="text-align:left !important;">
                                                                                    : 1 (Satu) berkas</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="30%"><b>Specification</b>
                                                                                </td>
                                                                                <td style="text-align:left !important;">
                                                                                    : Penawaran Harga</td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table><br>
                                                <table cellpadding="3" cellspacing="0"
                                                    style="width:100%; font-size:13px;">
                                                    <tr>
                                                    </tr>
                                                </table>
                                            </div>
                                        </body>

                                        </html>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="form-group text-right">
                        <button type="reset" id="btn_reset" class="btn bg-danger btn-labeled btn-labeled-left">
                            <b><i class="icon-sync"></i></b> Reset Form
                        </button>
                        <button type="submit" id="btn_submit" class="btn bg-primary btn-labeled btn-labeled-left">
                            <b><i class="icon-plus3"></i></b> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script>
        $(function() {
      ckEditor('terms');

      $('#btn_submit').click(function() {
         $('#btn_reset').attr('disabled', true);
         $('#btn_submit').attr('disabled', true);
         $('#btn_submit').html('<b><i class="icon-spinner4 spinner"></i></b> Processed ...');
         $('#form_data').submit();
      });
   });
    </script>