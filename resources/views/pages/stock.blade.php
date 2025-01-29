@extends('layouts.main')

<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ secure_asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ secure_asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ secure_asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ secure_asset('css/dataTables.bootstrap4.min.css') }}">

<style>
    .select2-container--open {
        z-index: 1050 !important;
        /* Make sure it's above the modal */
    }

    .modal-body {
        overflow: auto;
        /* Allow scrolling if content overflows */
    }
</style>

@section('content')
    <div class="content">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Cards Section -->
        <div class="row justify-content-center">

            <!-- Total Released Materials Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Released Materials</h5>
                        <p class="card-text" id="released-count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Activision Materials Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-secondary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Activation Materials</h5>
                        <p class="card-text" id="active-count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Activated Materials Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Activated Materials</h5>
                        <p class="card-text" id="activated-count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Repair Materials Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-danger text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Repair Materials</h5>
                        <p class="card-text" id="repair-count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- buttons -->
        <div class="row">
            <!-- Left-aligned buttons -->
            <div class="col-md-8 d-flex justify-content-start flex-wrap">
                <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal" data-target="#createstocks">
                    <i class="fas fa-plus"></i> Add
                </button>
                <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal" data-target="#teamtechmodal">
                    <i class="fas fa-list"></i> Team Tech
                </button>
                <button class="btn btn-sm btn-success mb-2 mr-2" data-toggle="modal" data-target="#stockslevel">
                    <i class="fas fa-list"></i> Stocks
                </button>
                <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal" data-target="#releasedstocks">
                    <i class="fas fa-list"></i> Released
                </button>
                <button class="btn btn-sm btn-info mb-2 mr-2" data-toggle="modal" data-target="#installationstocks">
                    <i class="fas fa-list"></i> Installation
                </button>
                <button class="btn btn-sm btn-danger mb-2 mr-2" data-toggle="modal" data-target="#repairstocks">
                    <i class="fas fa-list"></i> Repair
                </button>
                <button class="btn btn-sm btn-warning mb-2" data-toggle="modal" data-target="#dmurstocks">
                    <i class="fas fa-list"></i> DMUR
                </button>
            </div>

            <!-- Right-aligned buttons -->
            <div class="col-md-4 d-flex justify-content-end flex-wrap">
                <button class="btn btn-sm btn-success mb-2 mr-2" id="import-data-button">
                    <i class="fas fa-file-excel"></i> Import Data
                </button>
                <button class="btn btn-sm btn-warning mb-2 mr-2" id="stocksprintPDF">
                    <i class="fas fa-file-pdf"></i> Print PDF
                </button>
                <button class="btn btn-sm btn-info mb-2" id="stocksprintExcel">
                    <i class="fas fa-file-excel"></i> Print Excel
                </button>
            </div>
        </div>

        <!-- Add stocks Modal -->
        <div class="modal fade" id="createstocks" tabindex="-1" role="dialog" aria-labelledby="createstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Stock</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="stocksform" class="row p-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="1"
                                            id="stocks_released">
                                        <label class="form-check-label" for="stocks_released"><span
                                                class='badge bg-primary'>Status as RELEASED</span></label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="0"
                                            id="stocks_activation">
                                        <label class="form-check-label" for="stocks_activation"><span
                                                class='badge bg-success'>Status as ACTIVATION</span></label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="2"
                                            id="stocks_activated">
                                        <label class="form-check-label" for="stocks_activated"><span
                                                class='badge bg-info'>Status as ACTIVATED</span></label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="3"
                                            id="stocks_repair">
                                        <label class="form-check-label" for="stocks_repair"><span
                                                class='badge bg-danger'>Status as REPAIR</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Team Tech</strong></label>
                                    <select class="form-control icon-input" name="tech_name" id="stocks_tech_name"
                                        style="width: 100%;">
                                    </select>
                                </div>
                            </div>

                            <!-- Default form fields -->
                            <div class="col-md-12 mb-3">
                                <div class="form-group" id="quantity_field">
                                    <label for="create_stocks"><strong>Materials</strong></label>
                                    <select class="form-control icon-input" name="description_name[]"
                                        id="stocks_description" style="width: 100%;" multiple="multiple">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Product Name</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="stocks_product_name" placeholder="Enter Product Name"
                                        data-name="stocks_product_name" name="product_name">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Serial No.</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="stocks_serial_no" placeholder="Enter Serial No."
                                        data-name="stocks_serial_no" name="serial_no">
                                </div>
                            </div>

                            {{-- <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Date Active</strong></label>
                                    <input type="date" class="form-control" id="stocks_date_active"
                                        placeholder="Enter Date" data-name="stocks_date" name="date">
                                </div>
                            </div> --}}

                            <!-- Hidden fields for "Released" -->
                            <div id="releasedFields" class="d-none">
                                <hr>
                                <h6 class="text-primary" style="font-style: italic; margin-bottom: 10px;">Released
                                    Additional Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Date Released</strong></label>
                                        <input type="date" class="form-control" id="stocks_date_released"
                                            placeholder="Enter Date Released" data-name="stocks_date_released"
                                            name="date_released">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields for "Activated" -->
                            <div id="activatedFields" class="d-none">
                                <hr>
                                <h6 style="font-style: italic; margin-bottom: 10px; color: #000000;"><span
                                        class="text-success">Activation</span>/<span class="text-info">Activated</span>
                                    Additional Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>J.O No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="stocks_jo_no" placeholder="Enter J.O No."
                                            data-name="jo_account_no" name="jo_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>SAR No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="stocks_sar_no" placeholder="Enter SAR No."
                                            data-name="stocks_sar_no" name="sar_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Subscriber Name</strong></label>
                                        <input type="text"
                                            onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                            class="form-control" id="stocks_subscriber_name"
                                            placeholder="Enter Subscriber Name" data-name="stocks_subscriber_name"
                                            name="subsname">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Subscriber Account No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="stocks_subscriber_account_no"
                                            placeholder="Enter Subscriber Account No."
                                            data-name="stocks_subscriber_account_no" name="subacc">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Account No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="stocks_account_no" placeholder="Enter Account No."
                                            data-name="stocks_account_no" name="account_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Date Used</strong></label>
                                        <input type="date" class="form-control" id="stocks_date_used"
                                            placeholder="Enter Date Used" data-name="stocks_date_used" name="date_used">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields for "Repair" -->
                            <div id="repairFields" class="d-none">
                                <hr>
                                <h6 style="font-style: italic; margin-bottom: 10px; color: #dc3545;">Repair Additional
                                    Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Ticket No.</strong></label>
                                        <input type="number" class="form-control" id="ticket_no"
                                            placeholder="Enter Ticket No." data-name="ticket_no" name="ticket">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Serial New No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="stocks_serial_new_no"
                                            placeholder="Enter New Serial No." data-name="stocks_serial_new_no"
                                            name="serial_new_no">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Date Repaired</strong></label>
                                        <input type="date" class="form-control" id="stocks_date_repaired"
                                            placeholder="Enter Date Repaired" data-name="stocks_date_repaired"
                                            name="date_repaired">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary btn-block"
                                    id="savebtn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit stocks Modal -->
        <div class="modal fade" id="editcreatestocks" tabindex="-1" role="dialog"
            aria-labelledby="editcreatestocksLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Stock</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editstocksform" class="row p-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_status" value="1"
                                            id="editstocks_released">
                                        <label class="form-check-label" for="editstocks_released">
                                            <span class='badge bg-primary'>Status as RELEASED</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_status" value="0"
                                            id="editstocks_activation">
                                        <label class="form-check-label" for="editstocks_activation">
                                            <span class='badge bg-success'>Status as ACTIVATION</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_status" value="2"
                                            id="editstocks_activated">
                                        <label class="form-check-label" for="editstocks_activated">
                                            <span class='badge bg-info'>Status as ACTIVATED</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_status" value="3"
                                            id="editstocks_repair">
                                        <label class="form-check-label" for="editstocks_repair">
                                            <span class='badge bg-danger'>Status as REPAIR</span>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_status" value="5"
                                            id="editstocks_not_yet_release">
                                        <label class="form-check-label" for="editstocks_not_yet_release">
                                            <span class='badge bg-secondary'>Status as NOT YET RELEASE</span>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Team Tech</strong></label>
                                    <select class="form-control icon-input" name="tech_name" id="editstocks_tech_name"
                                        style="width: 100%;">
                                    </select>
                                </div>
                            </div>

                            <!-- Default form fields -->
                            <div class="col-md-12 mb-3">
                                <div class="form-group" id="quantity_field">
                                    <label for="editcreate_stocks"><strong>Materials</strong></label>
                                    <select class="form-control" style="width: 100%" id="editstocks_description"
                                        name="description_name[]" multiple="multiple">
                                        <option disabled selected> Select Materials</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Serial No.</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editstocks_serial_no" placeholder="Enter Serial No."
                                        data-name="stocks_serial_no" name="serial_no" readonly>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Product Name</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editstocks_product_name"
                                        placeholder="Enter Product Name" data-name="stocks_product_name"
                                        name="product_name">
                                </div>
                            </div>

                            {{-- <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Date Active</strong></label>
                                    <input type="date" class="form-control" id="editstocks_date_active"
                                        placeholder="Enter Date" data-name="stocks_date" name="date">
                                </div>
                            </div> --}}

                            <!-- Hidden fields for "Released" -->
                            <div id="editreleasedFields" class="d-none">
                                <hr>
                                <h6 class="text-primary" style="font-style: italic; margin-bottom: 10px;">Released
                                    Additional Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Date Released</strong></label>
                                        <input type="date" class="form-control" id="editstocks_date_released"
                                            placeholder="Enter Date Released" data-name="stocks_date_released"
                                            name="date_released">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields for "Activated" -->
                            <div id="editactivatedFields" class="d-none">
                                <hr>
                                <h6 style="font-style: italic; margin-bottom: 10px; color: #000000;"><span
                                        class="text-success">Activation</span>/<span class="text-info">Activated</span>
                                    Additional Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>J.O No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="editstocks_jo_no" placeholder="Enter J.O No."
                                            data-name="jo_account_no" name="jo_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>SAR No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="editstocks_sar_no" placeholder="Enter SAR No."
                                            data-name="stocks_sar_no" name="sar_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Subscriber Name</strong></label>
                                        <input type="text"
                                            onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                            class="form-control" id="editstocks_subscriber_name"
                                            placeholder="Enter Subscriber Name" data-name="stocks_subscriber_name"
                                            name="subsname">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="create_stocks"><strong>Subscriber Account No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="editstocks_subscriber_account_no"
                                            placeholder="Enter Subscriber Account No."
                                            data-name="stocks_subscriber_account_no" name="subacc">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="editcreate_stocks"><strong>Account No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="editstocks_account_no"
                                            placeholder="Enter Account No." data-name="stocks_account_no"
                                            name="account_no">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="editcreate_stocks"><strong>Date Used</strong></label>
                                        <input type="date" class="form-control" id="editstocks_date_used"
                                            placeholder="Enter Date Used" data-name="stocks_date_used" name="date_used">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields for "Repair" -->
                            <div id="editrepairFields" class="d-none">
                                <hr>
                                <h6 style="font-style: italic; margin-bottom: 10px; color: #dc3545;">Repair Additional
                                    Fields</h6>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="editcreate_stocks"><strong>Ticket No.</strong></label>
                                        <input type="number" class="form-control" id="editticket_no"
                                            placeholder="Enter Ticket No." data-name="ticket_no" name="ticket">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="editcreate_stocks"><strong>Serial New No.</strong></label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                            class="form-control" id="editstocks_serial_new_no"
                                            placeholder="Enter New Serial No." data-name="stocks_serial_new_no"
                                            name="serial_new_no">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="editcreate_stocks"><strong>Date Repaired</strong></label>
                                        <input type="date" class="form-control" id="editstocks_date_repaired"
                                            placeholder="Enter Date Repaired" data-name="stocks_date_repaired"
                                            name="date_repaired">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary btn-block" id="updatebtn">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--Stocks DataTable Section -->
        <div class="card shadow">
            <div class="card-header text-black">
                <strong>Stocks Data</strong>
            </div>
            <div class="card-body">
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="stockstable"
                        class="table table-sm display table-bordered table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Account No.</th>
                                <th>Serial No.</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- The table rows will be dynamically populated by DataTable -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stocks level Modal -->
        <div class="modal fade" id="stockslevel" tabindex="-1" role="dialog" aria-labelledby="stockslevelLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="stockslevelLabel">Stocks Level</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Cards Section -->
                        <div class="row justify-content-center">

                            <!-- Total Materials Card -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-success text-center text-white shadow">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1rem;">Total Materials</h5>
                                        <p class="card-text" id="totalstockslevel" style="font-size: 1.5rem;">0</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Released Materials Card -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-primary text-center text-white shadow">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1rem;">Total Used Materials</h5>
                                        <p class="card-text" id="usedcountmaterials" style="font-size: 1.5rem;">0</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Available Materials Card -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-success text-center text-white shadow">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1rem;">Total Available Materials</h5>
                                        <p class="card-text" id="availablestockslevel" style="font-size: 1.5rem;">0</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- buttons -->
                        <div class="row">
                            <!-- Left-aligned buttons -->
                            <div class="col-md-8 d-flex justify-content-start flex-wrap">
                                <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal"
                                    data-target="#createstockslevel">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>

                            <!-- Right-aligned buttons -->
                            <div class="col-md-4 d-flex justify-content-end flex-wrap">
                                <button class="btn btn-sm btn-warning mb-2 mr-2" id="stocklevelsprintPDF">
                                    <i class="fas fa-file-pdf"></i> Print PDF
                                </button>
                                <button class="btn btn-sm btn-info mb-2" id="stockslevelprintExcel">
                                    <i class="fas fa-file-excel"></i> Print Excel
                                </button>
                            </div>
                        </div>

                        <!-- Add stocks level Modal -->
                        <div class="modal fade" id="createstockslevel" tabindex="-1" role="dialog"
                            aria-labelledby="createstockslevelLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered shadow">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Create New Stocks level</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="stockslevelform" class="row p-3">
                                            <div class="col-md-12">
                                                <!-- Delivery Date Input -->
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="delivery_date">Delivery Date</label>
                                                    <input type="date" class="form-control" id="delivery_date"
                                                        name="delivery_date" required>
                                                </div>

                                                <div class="form-group">
                                                    <div class="mb-3">
                                                        <label class="form-label">Materials & Quantities</label>
                                                        <table class="table table-bordered" id="DescriptionTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Materials</th>
                                                                    <th>Quantity</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Dynamic rows will be added here -->
                                                            </tbody>
                                                        </table>
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            id="addRowBtn">Add Row</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary btn-block"
                                                    id="savestockslevelbtn">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- edit stocks level Modal -->
                        <div class="modal fade" id="editcreatestockslevel" tabindex="-1" role="dialog"
                            aria-labelledby="createstockslevelLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered shadow">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Update Stocks Material</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editstockslevelform" class="row p-3">
                                            <div class="col-md-12">
                                                <div class="form-group">

                                                    <!-- Delivery Date Input -->
                                                    <div class="form-group">
                                                        <label class="form-label" for="delivery_date">Delivery
                                                            Date</label>
                                                        <input type="date" class="form-control" id="editdelivery_date"
                                                            name="editdelivery_date" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="editDescription">Material Description</label>
                                                        <input type="text" id="editDescription" class="form-control"
                                                            placeholder="Enter material description"
                                                            onkeyup="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary btn-block"
                                                    id="updatestockslevelbtn">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="card shadow">
                            <div class="card-header text-black">
                                <strong>Stocks Level</strong>
                            </div>
                            <div class="card-body">

                                <!-- Table -->
                                <table id="stocksleveltable"
                                    class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Delivery Date</th>
                                            <th>Materials</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="stocksleveltableBody">
                                        <!-- Table rows will be dynamically populated -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- team tech Modal -->
        <div class="modal fade" id="teamtechmodal" tabindex="-1" role="dialog" aria-labelledby="teamtechmodalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="teamtechmodalLabel">Team Tech</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <!-- Cards Section -->
                        <div class="row justify-content-center mb-2">

                            <!-- Total Activision Materials Card -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-secondary text-center text-white shadow">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1rem;">Total Tech</h5>
                                        <p class="card-text" id="tech_count" style="font-size: 1.5rem;">0</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- buttons -->
                        <div class="row mb-2">
                            <!-- Left-aligned buttons -->
                            <div class="col-md-8 d-flex justify-content-start flex-wrap">
                                <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal"
                                    data-target="#createtechteam">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>

                            <!-- Right-aligned buttons -->
                            <div class="col-md-4 d-flex justify-content-end flex-wrap">
                                <button class="btn btn-sm btn-warning mb-2 mr-2" id="stocksprintPDF">
                                    <i class="fas fa-file-pdf"></i> Print PDF
                                </button>
                                <button class="btn btn-sm btn-info mb-2" id="stocksprintExcel">
                                    <i class="fas fa-file-excel"></i> Print Excel
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="teamtechtable"
                                class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tech Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="teamtechtableBody">
                                    <!-- Table rows will be dynamically populated -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Add team tech Modal -->
                        <div class="modal fade" id="createtechteam" tabindex="-1" role="dialog"
                            aria-labelledby="createtechteamLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered shadow">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Create Team Tech</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="teamtechform" class="row p-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>Tech Name</strong></label>
                                                        <input type="text"
                                                            onkeyup="this.value = this.value.toUpperCase();"
                                                            class="form-control" id="tech_name"
                                                            placeholder="Enter Tech Name" data-name="tech_name"
                                                            name="tech_name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary btn-block"
                                                    id="saveteamtechbtn">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update team tech Modal -->
                        <div class="modal fade" id="editteamtechmodal" tabindex="-1" role="dialog"
                            aria-labelledby="createtechteamLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered shadow">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Update Team Tech</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editteamtechform" class="row p-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="mb-3">
                                                        <label class="form-label"><strong>Tech Name</strong></label>
                                                        <input type="text"
                                                            onkeyup="this.value = this.value.toUpperCase();"
                                                            class="form-control" id="edittech_name"
                                                            placeholder="Enter Tech Name" data-name="tech_name"
                                                            name="tech_name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary btn-block"
                                                    id="updateteamtechbtn">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Release Stocks Modal -->
        <div class="modal fade" id="releasedstocks" tabindex="-1" role="dialog" aria-labelledby="releasedstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="releasedstocksLabel">Released Stocks</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Filter Card -->
                        <div class="card mb-4 shadow">
                            <div class="card-header">
                                <i class="fas fa-filter"></i> Filter
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <!-- Team Tech Filter -->
                                    <div class="col-md-4 mb-3">
                                        <label for="releasedfilterTeamTech" class="form-label">Team Tech</label>
                                        <select id="releasedfilterTeamTech" class="form-select releasedfilterTeamTech">
                                            <option value="All" selected>All</option>
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>

                                    <!-- Month Filter -->
                                    <div class="col-md-4 mb-3">
                                        <label for="releasedfilterMonth" class="form-label">Month</label>
                                        <select id="releasedfilterMonth" class="form-select releasedfilterMonth">
                                            <option value="All" selected>All</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <!-- Year Filter -->
                                    <div class="col-md-4 mb-3">
                                        <label for="releasedfilterYear" class="form-label">Year</label>
                                        <select id="releasedfilterYear" class="form-select releasedfilterYear">
                                            <option value="All" selected>All</option>
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="releasedstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="releasedstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="releasedstockstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date Released</th>
                                    <th>Materials</th>
                                    <th>Quantity</th>
                                    <th>Team Tech</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="releasedstockstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- installation Stocks Modal -->
        <div class="modal fade" id="installationstocks" tabindex="-1" role="dialog"
            aria-labelledby="installationstocksLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="installationstocksLabel">Installation Stocks</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Filter Card -->
                        <div class="card mb-4 shadow">
                            <div class="card-header">
                                <i class="fas fa-filter"></i> Filter
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Month Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterMonth" class="form-label">Month</label>
                                        <select id="activatedfilterMonth" class="form-select activatedfilterMonth">
                                            <option value="All" selected>All</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <!-- Year Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterYear" class="form-label">Year</label>
                                        <select id="activatedfilterYear" class="form-select activatedfilterYear">
                                            <option value="All" selected>All</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="installationstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="installationstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="activatedstockstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Account No.</th>
                                    <th>J.O No.</th>
                                    <th>Serial No.</th>
                                    <th>Date Used</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="installationstockstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repair Stocks Modal -->
        <div class="modal fade" id="repairstocks" tabindex="-1" role="dialog" aria-labelledby="repairstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="repairstocksLabel">Repair Stocks</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Filter Card -->
                        <div class="card mb-4 shadow">
                            <div class="card-header">
                                <i class="fas fa-filter"></i> Filter
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Month Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterMonth" class="form-label">Month</label>
                                        <select id="repairedfilterMonth" class="form-select repairedfilterMonth">
                                            <option value="All" selected>All</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <!-- Year Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterYear" class="form-label">Year</label>
                                        <select id="repairedfilterYear" class="form-select repairedfilterYear">
                                            <option value="All" selected>All</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="repairstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="repairstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="repairedstockstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Account No.</th>
                                    <th>Ticket No.</th>
                                    <th>Serial New No.</th>
                                    <th>Serial Old No.</th>
                                    <th>Date Repaired</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="repairstockstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- dmur Stocks Modal -->
        <div class="modal fade" id="dmurstocks" tabindex="-1" role="dialog" aria-labelledby="repairstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="repairstocksLabel">DMUR Stocks</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Filter Card -->
                        <div class="card mb-4 shadow">
                            <div class="card-header">
                                <i class="fas fa-filter"></i> Filter
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Month Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterMonth" class="form-label">Month</label>
                                        <select id="dmurfilterMonth" class="form-select dmurfilterMonth">
                                            <option value="All" selected>All</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <!-- Year Filter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="filterYear" class="form-label">Year</label>
                                        <select id="dmurfilterYear" class="form-select dmurfilterYear">
                                            <option value="All" selected>All</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="dmurstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="dmurstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="dmurstockstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date Used</th>
                                    <th>SAR No.</th>
                                    <th>Ticket No.</th>
                                    <th>J.O No.</th>
                                    <th>Materials</th>
                                    <th>Subscriber Account No.</th>
                                    <th>Subscriber Name</th>
                                    <th>Tech Name</th>
                                </tr>
                            </thead>
                            <tbody id="dmurstockstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- Assuming your layout has a section for additional scripts -->

    <!-- jQuery (must be included first) -->
    <script src="{{ secure_asset('js/jquery-3.6.0.min.js') }}"></script>

    <!-- DataTables JS -->
    <script src="{{ secure_asset('js/jquery.dataTables.min.js') }}"></script>

    <!-- Bootstrap JS (required for DataTables Bootstrap integration) -->
    <script src="{{ secure_asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ secure_asset('js/select2.min.js') }}"></script>

    <!-- jsPDF Library for PDF Export -->
    <script src="{{ secure_asset('js/jspdf.umd.min.js') }}"></script>
    <script src="{{ secure_asset('js/jspdf.plugin.autotable.min.js') }}"></script>

    <!-- SheetJS Library for Excel Export -->
    <script src="{{ secure_asset('js/xlsx.full.min.js') }}"></script>

    <!-- FileSaver.js for saving the Excel file -->
    <script src="{{ secure_asset('js/FileSaver.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ secure_asset('js/sweetalert2@11.min.js') }}"></script>

    <!-- moment.js -->
    <script src="{{ secure_asset('js/moment.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            //triggers to fetch stocks data
            fetchstockdata();

            //triggers to fetch stocks level data
            fetchstocksleveldata();

            fetchteamtechdata();

            //triggers to fetch released stocks data
            fetchreleasedstocks();

            //triggers to fetch activated stocks data
            fetchactivatedstocks();

            //triggers to fetch repaired stocks data
            fetchrepairedstocks();

            //triggers to fetch dmur stocks data
            fetchdmurstocks();

            // Trigger the function to fetch material counts on page load
            fetchMaterialCounts();

            // Call the function to fetch and update the total quantity
            fetchTotalMaterials();

            // Fetch the count on page load
            fetchTechCount();

            // Call the function to fetch and update the total quantity
            fetchTotalDescriptionsExcludingActive()

            // Call the function to fetch and update the total quantity
            fetchTotalActiveDescriptions();

            // Trigger fetchFilteredStocks when the month or year filter changes
            fetchFilteredreleasedStocks();

            // Fetch and populate filter options on page load
            fetchFilterOptions();

            // Trigger fetchFilteredStocks when the month or year filter changes
            fetchFilteredactivatedStocks();

            // Trigger fetchFilteredStocks when the month or year filter changes
            fetchFilteredrepairedStocks();

            // Trigger fetchFilteredStocks when the month or year filter changes
            fetchFiltereddmurStocks();

            // Trigger fetchFilteredStocks when the month or year filter changes
            selectstocksleveldescription();

            selectteamtech();

            //triggers to save or store stocks data
            $('#savebtn').click(function() {
                save_stocks(event)
            });

            // Trigger save function
            $('#savestockslevelbtn').click(function() {
                save_stocks_level(event);
            });

            // Trigger save function
            $('#saveteamtechbtn').click(function() {
                save_team_tech(event);
            });

            //triggers to fetch stocks data to modal
            $('#stockstable').on('click', '.edit', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                fetchstoredstocks(id);
            })

            //triggers to fetch stocks level data to modal
            $('#stocksleveltable').on('click', '.editstockslevel', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                fetchstoredstockslevel(id);
            })

            //triggers to fetch team tech data to modal
            $('#teamtechtable').on('click', '.editteamtech', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                fetchstoredteamtech(id);
            })

            // Trigger button for updating stocks
            $('#updatebtn').click(function(event) {
                event.preventDefault(); // Prevent default button behavior
                update_stocks(event); // Pass the event object
            });

            // Trigger button for updating stocks level
            $('#updatestockslevelbtn').click(function(event) {
                event.preventDefault(); // Prevent default button behavior
                update_stocks_level(event); // Pass the event object
            });

            $('#updateteamtechbtn').click(function(event) {
                event.preventDefault();
                update_teamtech()
            });

            // Trigger fetch and update on filter changes
            $('#month_select, #year_select, #status_select').change(function() {
                fetchAndRenderStocks();
            });

            // Trigger fetchFilteredStocks when the team tech month, year filter changes
            $('#releasedfilterTeamTech, #releasedfilterMonth, #releasedfilterYear').change(function() {
                fetchFilteredreleasedStocks();
            });

            // Trigger fetchFilteredStocks when the month, year filter changes
            $('#activatedfilterMonth, #activatedfilterYear').change(function() {
                fetchFilteredactivatedStocks();
            });


            // Trigger fetchFilteredStocks when the month, year filter changes
            $('#repairedfilterMonth, #repairedfilterYear').change(function() {
                fetchFilteredrepairedStocks();
            });

            // Trigger fetchFilteredStocks when the month, year filter changes
            $('#dmurfilterMonth, #dmurfilterYear').change(function() {
                fetchFiltereddmurStocks();
            });

            // Load dynamic active years
            $.ajax({
                url: '/fetch-years',
                method: 'GET',
                success: function(years) {
                    let yearSelect = $('#year_select, #activefilterYear');
                    yearSelect.empty(); // Clear current options
                    yearSelect.append('<option value="All">All</option>'); // Add default option

                    years.forEach(year => {
                        yearSelect.append(`<option value="${year}">${year}</option>`);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching years:', xhr);
                }
            });

            // Load dynamic activated years
            $.ajax({
                url: '/fetch-activated-years',
                method: 'GET',
                success: function(years) {
                    let yearSelect = $('#activatedfilterYear, #dmurfilterYear');
                    yearSelect.empty(); // Clear current options
                    yearSelect.append('<option value="All">All</option>'); // Add default option

                    years.forEach(year => {
                        yearSelect.append(`<option value="${year}">${year}</option>`);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching years:', xhr);
                }
            });

            // Load dynamic repaired years
            $.ajax({
                url: '/fetch-repaired-years',
                method: 'GET',
                success: function(years) {
                    let yearSelect = $('#repairedfilterYear');
                    yearSelect.empty(); // Clear current options
                    yearSelect.append('<option value="All">All</option>'); // Add default option

                    years.forEach(year => {
                        yearSelect.append(`<option value="${year}">${year}</option>`);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching years:', xhr);
                }
            });

            // Trigger when the modal is opened to fetch active stocks
            $(document).on('click', '#stockslevelbtn', function(e) {
                $('#stockslevel').modal('show');
            });

            $(document).on('click', '#installationbtn', function(e) {
                $('#installationstocks').modal('show');
            });

            // Remove a row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // Add a new row in the table
            $('#addRowBtn').click(function() {
                const newRow = `
                <tr>
                    <td><input type="text" class="form-control description-input" placeholder="Material" onkeyup="this.value = this.value.toUpperCase();"></td>
                    <td><input type="number" class="form-control quantity-input" placeholder="Quantity" min="1"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                </tr>
            `;
                $('#DescriptionTable tbody').append(newRow);
            });

            $('#import-data-button').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will start importing data from the Google Sheet.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, import it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/import-stocks-sheet-data', // Route to trigger the import function
                            method: 'GET',
                            beforeSend: function() {
                                // Show a loader or disable the button
                                $('#import-data-button')
                                    .prop('disabled', true)
                                    .html(
                                        '<i class="fas fa-spinner fa-spin"></i> Importing...'
                                    );
                            },
                            success: function(response) {
                                fetchstockdata();

                                Swal.fire(
                                    'Imported!',
                                    response.message,
                                    'success'
                                );
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error',
                                    xhr.responseText ||
                                    'Failed to import data.',
                                    'error'
                                );
                            },
                            complete: function() {
                                // Re-enable the button
                                $('#import-data-button')
                                    .prop('disabled', false)
                                    .html(
                                        '<i class="fas fa-file-excel"></i> Import Data'
                                    );
                            }
                        });
                    }
                });
            });
        });


        function fetchFilteredreleasedStocks() {
            const tech_name_id = $('#releasedfilterTeamTech').val();
            const month = $('#releasedfilterMonth').val();
            const year = $('#releasedfilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-released-stocks', // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    tech_name_id: tech_name_id,
                    month: month,
                    year: year
                },
                success: function(response) {
                    // Call the render function with the filtered data
                    renderreleasedstocks(response.stocks);
                    console.log(response, 'Fetched Filtered active Stocks');

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filtered stocks:', error);
                }
            });
        }

        function fetchFilteredactivatedStocks() {
            const month = $('#activatedfilterMonth').val();
            const year = $('#activatedfilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-activated-stocks', // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    // Call the render function with the filtered data
                    renderactivatedstocks(response.stocks);
                    console.log(response, 'Fetched Filtered active Stocks');

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filtered stocks:', error);
                }
            });
        }

        function fetchFilteredrepairedStocks() {
            const month = $('#repairedfilterMonth').val();
            const year = $('#repairedfilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-repaired-stocks', // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    // Call the render function with the filtered data
                    renderrepairedstocks(response.stocks);
                    console.log(response, 'Fetched Filtered active Stocks');

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filtered stocks:', error);
                }
            });
        }

        function fetchFiltereddmurStocks() {
            const month = $('#dmurfilterMonth').val();
            const year = $('#dmurfilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-dmur-stocks', // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    // Call the render function with the filtered data
                    renderdmurstocks(response.stocks);
                    console.log(response, 'Fetched Filtered active Stocks');

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filtered stocks:', error);
                }
            });
        }

        function fetchFilterOptions() {
            // Make the AJAX request to fetch filter options
            $.ajax({
                url: '/filter-released-stocks-options', // Make sure to replace this with your actual API endpoint for fetching filter options
                method: 'GET',
                success: function(response) {
                    // Populate team_tech filter options
                    const teamTechSelect = $('#releasedfilterTeamTech');
                    teamTechSelect.empty();
                    teamTechSelect.append('<option value="All">All</option>');
                    response.team_techs.forEach(function(team_tech) {
                        teamTechSelect.append('<option value="' + team_tech.tech_name_id + '">' +
                            team_tech
                            .tech_name + '</option>');
                    });

                    // Populate year filter options
                    const yearSelect = $('#releasedfilterYear');
                    yearSelect.empty();
                    yearSelect.append('<option value="All">All</option>');
                    response.years.forEach(function(year) {
                        yearSelect.append('<option value="' + year.year + '">' + year.year +
                            '</option>');
                    });

                    console.log(response, 'Fetched Filter Options');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filter options:', error);
                }
            });
        }

        // Function to save stocks
        function save_stocks(event) {
            event.preventDefault(); // Prevent the default form submission

            // Get the selected status from the radio buttons
            var status = $('input[name="status"]:checked').val();

            // Get values from form fields
            var account_no = $('#stocks_account_no').val();
            var serial_no = $('#stocks_serial_no').val();
            var product_name = $('#stocks_product_name').val();
            var description_id = $('#stocks_description').val(); // This will return an array
            var tech_name_id = $('#stocks_tech_name').val();
            var subsname = $('#stocks_subscriber_name').val();
            var subsaccount_no = $('#stocks_subscriber_account_no').val();
            var date_released = $('#stocks_date_released').val();
            var date_used = $('#stocks_date_used').val();
            var date_repaired = $('#stocks_date_repaired').val();
            var ticket_no = $('#ticket_no').val();
            var serial_new_no = $('#stocks_serial_new_no').val();
            var j_o_no = $('#stocks_jo_no').val();
            var sar_no = $('#stocks_sar_no').val();

            // Check if required fields are empty
            if (!product_name || !status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill all required fields.',
                });
                return; // Exit the function without submitting the form
            }

            // Prepare data for the AJAX request
            var data = {
                product_name: product_name,
                description_id: description_id, // Multi-select field, send as array
                tech_name_id: tech_name_id,
                subsname: subsname,
                subsaccount_no: subsaccount_no,
                account_no: account_no,
                serial_no: serial_no,
                date_released: date_released,
                date_used: date_used,
                date_repaired: date_repaired,
                status: status,
                ticket_no: ticket_no,
                serial_new_no: serial_new_no,
                j_o_no: j_o_no,
                sar_no: sar_no
            };

            // Send the data via AJAX
            $.ajax({
                url: '/stocks', // This should match the route for storing stocks (POST /stocks)
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content'), // Include the CSRF token in the headers
                },
                data: data,
                success: function(response) {
                    console.log(response);

                    // Check if the response is successful
                    if (response.status === 'success') {
                        // Refresh stock data
                        fetchstockdata();
                        fetchstocksleveldata();
                        fetchreleasedstocks();
                        fetchactivatedstocks();
                        fetchrepairedstocks();
                        fetchdmurstocks();
                        fetchMaterialCounts();
                        fetchTotalMaterials();
                        fetchTotalActiveDescriptions();
                        fetchTotalDescriptionsExcludingActive();
                        selectstocksleveldescription();

                        // Reset the form fields
                        $('#stocksform')[0].reset();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Stock created successfully.',
                            showConfirmButton: false,
                            timer: 1500, // Automatically close after 1.5 seconds
                        });
                    } else {
                        // Handle failure response
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong while saving the stock.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save Stock. Please try again later.',
                    });
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to save stocks level
        function save_stocks_level(event) {
            event.preventDefault(); // Prevent the default form submission

            let rows = [];
            const deliveryDate = $('#delivery_date').val(); // Get the delivery date

            // Check if the delivery date is provided
            if (!deliveryDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a delivery date.',
                });
                return;
            }

            // Loop through each row in the table and collect data
            $('#DescriptionTable tbody tr').each(function() {
                const description = $(this).find('.description-input').val().trim();
                const quantity = parseInt($(this).find('.quantity-input').val());

                if (description && quantity && quantity > 0) {
                    for (let i = 0; i < quantity; i++) {
                        rows.push({
                            description: description,
                            date_delivery: deliveryDate // Add the delivery date to each row
                        });
                    }
                }
            });

            // Check if there are rows to save
            if (rows.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please add at least one material with a valid quantity.',
                });
                return;
            }

            // AJAX request to save the stock data
            $.ajax({
                url: '/stockslevel', // Ensure this matches your actual route for storing stocks
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include the CSRF token in the headers
                },
                data: {
                    rows: rows, // Pass rows along with the delivery date
                },
                success: function(response) {
                    // Handle success response
                    $('#stockslevelform')[0].reset(); // Reset the form fields
                    $('#DescriptionTable tbody').empty(); // Clear the table rows

                    fetchstocksleveldata(); // Refresh the stock data table
                    fetchTotalMaterials(); // Refresh the total materials count
                    fetchTotalActiveDescriptions(); // Refresh the total active descriptions count
                    selectstocksleveldescription(); // Refresh the select2 dropdowns
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Stock Materials created successfully.',
                        showConfirmButton: false,
                        timer: 1500 // Automatically close after 1.5 seconds
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save stock. Please try again later.',
                    });
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        // Function to save team tech data
        function save_team_tech(event) {
            event.preventDefault(); // Prevent the default form submission

            // Get the form data
            const techname = $('#tech_name').val().trim();

            // Check if required fields are empty
            if (!techname) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill all required fields.',
                });
                return; // Exit the function without submitting the form
            }

            // AJAX request to save the team tech data
            $.ajax({
                url: '/teamtech', // Ensure this matches your actual route for storing team tech data
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include the CSRF token in the headers
                },
                data: {
                    tech_name: techname,
                },
                success: function(response) {
                    // Handle success response
                    $('#teamtechform')[0].reset(); // Reset the form fields

                    fetchteamtechdata(); // Call function to refresh the data (if applicable)

                    // Fetch the count on page load
                    fetchTechCount();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Team Tech created successfully.',
                        showConfirmButton: false,
                        timer: 1500 // Automatically close after 1.5 seconds
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save team tech. Please try again later.',
                    });
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        // Fetch stock data for the edit modal
        function formatDateForInput(date) {
            return date ? moment(date, moment.ISO_8601).format('YYYY-MM-DD') : ''; // Format for type="date"
        }

        // Function to toggle status fields based on the status value
        function toggleStatusFields(status) {
            // Your logic to show or hide fields based on the status
            if (status === 1) {
                $('#someField').show();
            } else {
                $('#someField').hide();
            }
        }

        // Function to show/hide additional fields based on status
        function toggleStatusFields(status, data) {
            // Show the appropriate additional fields, without clearing any data
            if (status == 2 || status == 0) { // Activated or Activation
                $('#editactivatedFields').removeClass('d-none');
                $('#editrepairFields').addClass('d-none');
                $('#editreleasedFields').addClass('d-none');
            } else if (status == 3) { // Repair
                $('#editrepairFields').removeClass('d-none');
                $('#editactivatedFields').addClass('d-none');
                $('#editreleasedFields').addClass('d-none');
            } else if (status == 1) { // Released
                $('#editreleasedFields').removeClass('d-none');
                $('#editactivatedFields').addClass('d-none');
                $('#editrepairFields').addClass('d-none');
            } else {
                $('#editactivatedFields, #editrepairFields, #editreleasedFields').addClass('d-none');
            }

            // Restore the previously fetched data into the inputs
            $('#editstocks_date_released').val(formatDateForInput(data.date_released));
            $('#editstocks_date_used').val(formatDateForInput(data.date_used));
            $('#editstocks_date_repaired').val(formatDateForInput(data.date_repaired));
        }

        function fetchstoredstocks(id) {
            console.log(id); // Debug: Check if the correct ID is being fetched

            $.ajax({
                url: '/stocks/' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response); // Debug: Inspect the response data

                    var data = response.stocks;

                    // Set the status of the radio buttons based on the data
                    switch (data.status) {
                        case 0:
                            $('#editstocks_activation').prop('checked', true);
                            break;
                        case 1:
                            $('#editstocks_released').prop('checked', true);
                            break;
                        case 2:
                            $('#editstocks_activated').prop('checked', true);
                            break;
                        case 3:
                            $('#editstocks_repair').prop('checked', true);
                            break;
                        case 5:
                            $('#editstocks_not_yet_release').prop('checked', true);
                            break;
                        default:
                            console.warn('Unknown status: ', data.status);
                    }

                    // Add options based on the fetched data
                    if (data.stock_materials && data.stock_materials.length > 0) {
                        data.stock_materials.forEach(material => {
                            $('#editstocks_description').append(
                                `<option value="${material.description_id}">${material.stocksdesc_level.description}</option>`
                            );
                        });
                    }

                    // Set the selected values
                    let descriptionIds = data.stock_materials.map(material => material.description_id);
                    console.log(descriptionIds, 'data'); // Debug: Check the description IDs

                    // Set value for select2
                    $('#editstocks_description').val(descriptionIds).trigger(
                        'change'); // Trigger change only after setting value

                    // Set the values for other fields
                    $('#editstocks_account_no').val(data.account_no);
                    $('#editstocks_serial_no').val(data.serial_no);
                    $('#editstocks_product_name').val(data.product_name);


                    if (data.tech_name) {
                        $('#editstocks_tech_name').append(
                            `<option value="${data.tech_name.id}" selected>${data.tech_name.tech_name}</option>`
                        );
                    } else {
                        $('#editstocks_tech_name').append(
                            '<option disabled selected>No Technician Assigned</option>');
                    }

                    $('#editstocks_subscriber_name').val(data.subsname);
                    $('#editstocks_subscriber_account_no').val(data.subsaccount_no);
                    $('#editstocks_date_active').val(formatDateForInput(data.date_active));
                    $('#editstocks_date_released').val(formatDateForInput(data.date_released));
                    $('#editstocks_jo_no').val(data.j_o_no);
                    $('#editstocks_sar_no').val(data.sar_no);
                    $('#editstocks_date_used').val(formatDateForInput(data.date_used));
                    $('#editticket_no').val(data.ticket_no);
                    $('#editstocks_serial_new_no').val(data.serial_new_no);
                    $('#editstocks_date_repaired').val(formatDateForInput(data.date_repaired));

                    $('#editstocksform').data('id', id);

                    // Show the correct additional fields based on the fetched data's status
                    toggleStatusFields(data.status, data); // Pass 'data' as an argument
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch data. Please try again later.',
                    });
                }
            });
        }

        // Fetch ports data for the edit modal
        function fetchstoredteamtech(id) {
            console.log(id); // Debug: Check if the correct ID is being fetched

            $.ajax({
                url: '/teamtech/' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response); // Debug: Inspect the response data

                    var data = response.teamtech;
                    $('#edittech_name').val(data.tech_name);
                    $('#editteamtechform').data('id', id);

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch data. Please try again later.',
                    });
                }
            });
        }

        // Fetch stock data for the edit modal
        function fetchstoredstockslevel(id) {
            console.log(id); // Debug: Check if the correct ID is being fetched

            // AJAX request to fetch data for the selected ID
            $.ajax({
                url: '/stockslevel/' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    var data = response.stockslevel;

                    // Store the fetched data in global variables
                    window.fetchedData = {
                        description: data.description,
                        date_delivery: data.date_delivery,
                    };

                    $('#editstockslevelform').data('id', id);

                    $('#editDescription').val(window.fetchedData.description);
                    $('#editdelivery_date').val(window.fetchedData.date_delivery);


                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch data. Please try again later.',
                    });
                }
            });
        }

        // Handle status change to show/hide additional edit fields
        $('input[name="edit_status"]').change(function() {
            const status = $(this).val();

            // Show/hide fields based on selected status
            toggleStatusFields(status);
        });

        // Function to update stocks
        function update_stocks(event) {
            // Ensure the default button behavior is stopped
            if (event) event.preventDefault();

            var id = $('#editstocksform').data('id'); // Retrieve the stored ID
            var status = $('input[name="edit_status"]:checked').val(); // Fetch selected status

            var product_name = $('#editstocks_product_name').val();
            var description_id = $('#editstocks_description').val();
            var tech_name_id = $('#editstocks_tech_name').val();
            var subsname = $('#editstocks_subscriber_name').val();
            var subsaccount_no = $('#editstocks_subscriber_account_no').val();
            var account_no = $('#editstocks_account_no').val();
            var serial_no = $('#editstocks_serial_no').val();
            var date_released = $('#editstocks_date_released').val();
            var ticket_no = $('#editticket_no').val();
            var serial_new_no = $('#editstocks_serial_new_no').val();
            var j_o_no = $('#editstocks_jo_no').val();
            var sar_no = $('#editstocks_sar_no').val();
            var date_used = $('#editstocks_date_used').val();
            var date_repaired = $('#editstocks_date_repaired').val();

            // Validation
            if (!product_name) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill the required fields.',
                });
                return;
            }

            // Show a confirmation dialog before saving
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Proceed with AJAX request to update the stock
                    $.ajax({
                        url: '/stocks/' + id,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            product_name: product_name,
                            description_id: description_id,
                            tech_name_id: tech_name_id,
                            subsname: subsname,
                            subsaccount_no: subsaccount_no,
                            account_no: account_no,
                            serial_no: serial_no,
                            date_released: date_released,
                            date_used: date_used,
                            date_repaired: date_repaired,
                            status: status, // Pass selected status
                            ticket_no: ticket_no,
                            serial_new_no: serial_new_no,
                            j_o_no: j_o_no,
                            sar_no: sar_no,
                        },
                        success: function(response) {
                            console.log(response);

                            // Refresh stock data
                            fetchstockdata();
                            fetchstocksleveldata();
                            fetchreleasedstocks();
                            fetchactivatedstocks();
                            fetchrepairedstocks();
                            fetchdmurstocks();
                            fetchMaterialCounts();
                            fetchTotalMaterials();
                            fetchTotalActiveDescriptions();
                            fetchTotalDescriptionsExcludingActive();
                            selectstocksleveldescription();


                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Your changes have been saved.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to update the stock. Please try again later.',
                            });
                        }
                    });
                }
            });
        }

        // Function to update stocks
        function update_stocks_level(event) {
            // Ensure the default button behavior is stopped
            if (event) event.preventDefault();

            var id = $('#editstockslevelform').data('id'); // Retrieve the stored ID

            var description = $('#editDescription').val();
            var date_delivery = $('#editdelivery_date').val();

            // Validation
            if (!description) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill the required fields.',
                });
                return;
            }

            // Show a confirmation dialog before saving
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Proceed with AJAX request to update the stock
                    $.ajax({
                        url: '/stockslevel/' + id,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            description: description,
                            date_delivery: date_delivery,
                        },
                        success: function(response) {
                            console.log(response);

                            fetchstocksleveldata(); // Refresh stock data

                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Your changes have been saved.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to update the stock. Please try again later.',
                            });
                        }
                    });
                }
            });
        }

        // Handle update button click event
        function update_teamtech() {
            event.preventDefault();

            var id = $('#editteamtechform').data('id'); // Retrieve the stored ID
            var tech_name = $('#edittech_name').val();

            // Validation
            if (!tech_name) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill the required fields.',
                });
                return;
            }

            // AJAX request to update the team tech data
            $.ajax({
                url: '/teamtech/' + id, // Adjust the URL based on your route
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include the CSRF token in the headers
                },
                data: {
                    tech_name: tech_name,
                },
                success: function(response) {
                    console.log(response);

                    // Refresh team tech data
                    fetchteamtechdata();

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500 // Automatically close after 1.5 seconds
                    });

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to update the team tech. Please try again later.',
                    });
                }
            });
        }

        // Function to delete stocks
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Enter Password',
                        input: 'password',
                        inputPlaceholder: 'Enter Password',
                        inputAttributes: {
                            maxlength: 5,
                            autocapitalize: 'off',
                            autocorrect: 'off'
                        }
                    }).then(password => {
                        if (password.value === '12345') {
                            $.ajax({
                                url: '/stocks/' + id, // Adjust the URL based on your route
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content'
                                    ) // Include the CSRF token in the headers
                                },
                                success: function(response) {
                                    if (response.status === "success") {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: response.message,
                                            showConfirmButton: false,
                                            timer: 1500 // Automatically close after 1.5 seconds
                                        });
                                        // Refresh or update the table data here
                                        // Refresh stock data
                                        fetchstockdata();
                                        fetchstocksleveldata();
                                        fetchreleasedstocks();
                                        fetchactivatedstocks();
                                        fetchrepairedstocks();
                                        fetchdmurstocks();
                                        fetchMaterialCounts();
                                        fetchTotalMaterials();
                                        fetchTotalActiveDescriptions();
                                        fetchTotalDescriptionsExcludingActive();
                                        selectstocksleveldescription();

                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Failed to delete the record. Please try again later.',
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Handle error response
                                    console.error(xhr.responseText);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Failed to delete the record. Please try again later.',
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Wrong Password. Cannot delete.',
                            });
                        }
                    });
                }
            });
        });

        // Function to delete stocks level
        $(document).on('click', '.deletestockslevel', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/stockslevel/' + id, // Adjust the URL based on your route
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        success: function(response) {
                            if (response.status === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500 // Automatically close after 1.5 seconds
                                });
                                fetchstocksleveldata(); // Refresh stock data
                                fetchTotalMaterials();
                                fetchTotalDescriptionsExcludingActive();
                                fetchTotalActiveDescriptions();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete the record. Please try again later.',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the record. Please try again later.',
                            });
                        }
                    });
                }
            });
        });


        // Function to fetch material counts
        function fetchMaterialCounts() {
            $.ajax({
                url: '/stocks', // The endpoint in your Laravel app
                type: 'GET',
                success: function(response) {
                    // Initialize counts
                    let activeCount = 0;
                    let releasedCount = 0;
                    let activatedCount = 0;
                    let repairCount = 0;

                    // Iterate through the stocks to count statuses
                    response.stocks.forEach(stock => {
                        switch (stock.status) {
                            case 0:
                                activeCount++;
                                break;
                            case 1:
                                releasedCount++;
                                break;
                            case 2:
                                activatedCount++;
                                break;
                            case 3:
                                repairCount++;
                                break;
                        }
                    });

                    // Update the card text with the counts
                    $('#active-count').text(activeCount);
                    $('#released-count').text(releasedCount);
                    $('#activated-count').text(activatedCount);
                    $('#repair-count').text(repairCount);
                },
                error: function(xhr, status, error) {
                    console.error("Failed to fetch material counts:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Unable to fetch data. Please try again later.',
                    });
                }
            });
        }

        function fetchTotalMaterials() {
            $.ajax({
                url: '/total-stocks-level', // The route for the controller
                type: 'GET',
                success: function(response) {
                    // Update the card's total count
                    $('#totalstockslevel').text(response.totalDescriptions); // Update with the correct JSON key
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch total materials:', error);
                }
            });
        }

        // Function to fetch and update the tech count
        function fetchTechCount() {
            $.ajax({
                url: '/tech-count', // Replace with your route URL
                method: 'GET',
                success: function(response) {
                    if (response.totalTechs !== undefined) {
                        // Update the card with the fetched count
                        $('#tech_count').text(response.totalTechs);
                    } else {
                        console.error('Invalid response format:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch tech count:', error);
                }
            });
        }

        function fetchTotalActiveDescriptions() {
            $.ajax({
                url: '/total-active-descriptions', // The route for the controller
                type: 'GET',
                success: function(response) {
                    // Update the card's total count
                    $('#availablestockslevel').text(response
                        .totalActiveDescriptions); // Update with the correct JSON key
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch total active descriptions:', error);
                }
            });
        }

        function fetchTotalDescriptionsExcludingActive() {
            $.ajax({
                url: '/total-descriptions-excluding-active', // The route for the controller
                type: 'GET',
                success: function(response) {
                    // Update the UI with the total sum of descriptions
                    $('#usedcountmaterials').text(response.totalCount); // Display the total sum
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch total descriptions:', error);
                }
            });
        }

        // Function to fetch stock data
        function fetchstockdata() {
            $.ajax({
                url: '/stocks',
                type: 'GET',
                success: function(response) {
                    renderstocks(response);
                    console.log(response, 'response1111111');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to fetch stock data
        function fetchstocksleveldata() {
            $.ajax({
                url: '/stockslevel',
                type: 'GET',
                success: function(response) {
                    renderstockslevel(response);
                    console.log(response, 'response1111111');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to fetch team tech data
        function fetchteamtechdata() {
            $.ajax({
                url: '/teamtech',
                type: 'GET',
                success: function(response) {
                    renderteamtech(response);
                    console.log(response, 'responseteamtech');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to fetch active stocks
        function fetchreleasedstocks() {
            $.ajax({
                url: '/fetch-released-stocks',
                type: 'GET',
                success: function(response) {
                    console.log(response, 'Released Stocks Data'); // Log the response for debugging
                    renderreleasedstocks(response); // Use response.releasedStocks directly
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log errors for debugging
                }
            });
        }

        // Function to fetch activated stocks
        function fetchactivatedstocks() {
            $.ajax({
                url: '/activated-stocks', // Endpoint to fetch activated stocks
                type: 'GET', // HTTP GET request
                success: function(response) {
                    renderactivatedstocks(response); // Call render function with fetched data
                    console.log(response, 'Fetched Activated Stocks'); // Log the response for debugging
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching activated stocks:', xhr.responseText); // Log any errors
                }
            });
        }

        // Function to fetch activated stocks
        function fetchrepairedstocks() {
            $.ajax({
                url: '/fetch-repaired-stocks',
                type: 'GET',
                success: function(response) {
                    renderrepairedstocks(response);
                    console.log(response, 'Fetched Repaired Stocks');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to fetch dmur stocks
        function fetchdmurstocks() {
            $.ajax({
                url: '/dmur-stocks', // Endpoint to fetch activated stocks
                type: 'GET', // HTTP GET request
                success: function(response) {
                    renderdmurstocks(response); // Call render function with fetched data
                    console.log(response, 'Fetched Dmur Stocks'); // Log the response for debugging
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching dmur stocks:', xhr.responseText); // Log any errors
                }
            });
        }

        function renderstocks(data) {
            console.log(data);
            var table = $('#stockstable').DataTable({
                destroy: true,
                data: data.stocks,
                stripeClasses: [], // Disable striping
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (product_name) in ascending order
                columns: [{
                        data: 'product_name',
                        render: data => data ? data : 'N/A',
                        orderable: true, // Allow sorting
                    },
                    {
                        data: 'stock_materials', // Access stock_materials as an array
                        render: function(data) {
                            // Check if stock_materials is an array and map over it to create a numbered list
                            return data && Array.isArray(data) ?
                                data.map((item, index) =>
                                    `${index + 1}. ${item.stocksdesc_level.description}`).join(
                                    '<br>') // Join descriptions with line breaks
                                :
                                'N/A'; // If no data, return 'N/A'
                        },
                        orderable: true, // Allow sorting
                        createdCell: function(td) {
                            $(td).css('text-align', 'left'); // Align content to the left
                        }
                    },
                    {
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            var statusText;
                            var statusClass;

                            if (data == 0) {
                                statusText = 'Activation';
                                statusClass = 'badge badge-success text-light';
                            } else if (data == 1) {
                                statusText = 'Released';
                                statusClass = 'badge badge-primary text-light';
                            } else if (data == 2) {
                                statusText = 'Activated';
                                statusClass = 'badge badge-info text-light';
                            } else if (data == 3) {
                                statusText = 'Repair';
                                statusClass = 'badge badge-danger text-light';
                            } else if (data == 5) {
                                statusText = 'Not yet release';
                                statusClass = 'badge badge-secondary text-light';
                            }

                            return '<div class="status-bg ' + statusClass + '">' + statusText + '</div>';
                        },
                        className: 'text-nowrap',
                        orderable: true // Allow sorting by status
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="edit btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editcreatestocks" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="delete btn btn-danger btn-sm" data-id="' + row.id +
                                '"><i class="fas fa-trash-alt"></i></button>';
                        },
                        orderable: false, // Disable sorting for action buttons
                        width: '11%' // Set a fixed width for the action buttons
                    }
                ]
            });
        }

        // Function to render active the DataTable
        function renderstockslevel(data) {
            console.log(data); // Log the data to check it

            var table = $('#stocksleveltable').DataTable({
                destroy: true,
                data: data.stockslevel,
                stripeClasses: [], // Disable striping
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (product_name) in ascending order
                columns: [{
                        data: 'date_delivery',
                        render: data =>
                            data ?
                            new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                            }) : 'N/A', // Format the date or display 'N/A'
                    },
                    {
                        data: 'description',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'stocks_level_status',
                        render: function(data, type, row) {
                            var statusText;
                            var statusClass;

                            if (data == 0) {
                                statusText = 'Activation';
                                statusClass = 'badge badge-success text-light';
                            } else if (data == 1) {
                                statusText = 'Released';
                                statusClass = 'badge badge-primary text-light';
                            } else if (data == 2) {
                                statusText = 'Activated';
                                statusClass = 'badge badge-info text-light';
                            } else if (data == 3) {
                                statusText = 'Repair';
                                statusClass = 'badge badge-danger text-light';
                            } else if (data == 4) {
                                statusText = 'Available';
                                statusClass = 'badge badge-success text-light';
                            } else {
                                statusText = 'Unknown';
                                statusClass = 'badge badge-secondary text-light';
                            }

                            return '<div class="status-bg ' + statusClass + '">' + statusText + '</div>';
                        },
                        className: 'text-nowrap',
                        orderable: true // Allow sorting by status
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="editstockslevel btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editcreatestockslevel" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="deletestockslevel btn btn-danger btn-sm" data-id="' + row
                                .id +
                                '"><i class="fas fa-trash-alt"></i></button>';
                        },
                        orderable: false, // Disable sorting for action buttons
                    }
                ]
            });
        }

        // Function to render team tech in the DataTable
        function renderteamtech(data) {
            console.log(data);

            var table = $('#teamtechtable').DataTable({
                destroy: true, // Destroy any existing table instance
                data: data.teamtech, // Use the array of team tech
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column
                columns: [{
                        data: 'tech_name',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="editteamtech btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editteamtechmodal" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="delete btn btn-danger btn-sm" data-id="' + row.id +
                                '"><i class="fas fa-trash-alt"></i></button>';
                        },
                        orderable: false, // Disable sorting for action buttons
                        width: '11%' // Set a fixed width for the action buttons
                    }
                ]
            });
        }

        // Function to render released stocks in the DataTable
        function renderreleasedstocks(data) {
            console.log(data, 'Data passed to DataTable');

            $('#releasedstockstable').DataTable({
                destroy: true, // Destroy any existing instance of the DataTable
                data: data, // Use the fetched data directly
                order: [
                    [0, 'asc']
                ], // Sort by date_released in ascending order
                columns: [{
                        data: 'date_released',
                        render: data =>
                            data ?
                            new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                            }) : 'N/A', // Format the date or display 'N/A'
                    },
                    {
                        data: 'stock_materials', // Stock materials data
                        render: function(data) {
                            // Ensure stock_materials is an array and contains stocksdesc_level
                            return data && Array.isArray(data) ?
                                data
                                .map(
                                    (item, index) =>
                                    `${index + 1}. ${
                                          item?.stocksdesc_level?.description || 'N/A'
                                      }`
                                )
                                .join('<br>') // Join descriptions with line breaks
                                :
                                'N/A';
                        },
                        orderable: false,
                        createdCell: function(td) {
                            $(td).css('text-align', 'left'); // Align content to the left
                        },
                    },
                    {
                        data: 'total_quantity',
                        render: data => (data ? data : 'N/A'), // Display total quantity or 'N/A'
                        orderable: true,
                    },
                    {
                        data: 'tech_name', // Technician's name
                        render: data => (data?.tech_name || 'N/A'), // Display tech_name or 'N/A'
                        orderable: true,
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            let statusText, statusClass;
                            switch (data) {
                                case 0:
                                    statusText = 'Active';
                                    statusClass = 'badge badge-success text-light';
                                    break;
                                case 1:
                                    statusText = 'Released';
                                    statusClass = 'badge badge-primary text-light';
                                    break;
                                case 2:
                                    statusText = 'Activated';
                                    statusClass = 'badge badge-info text-light';
                                    break;
                                case 3:
                                    statusText = 'Repair';
                                    statusClass = 'badge badge-danger text-light';
                                    break;
                                default:
                                    statusText = 'Unknown';
                                    statusClass = 'badge badge-secondary text-light';
                                    break;
                            }
                            return `<span class="${statusClass}">${statusText}</span>`;
                        },
                        className: 'text-nowrap',
                        orderable: true,
                    },
                ],
            });
        }



        function renderactivatedstocks(data) {
            console.log(data); // Inspect the data structure for debugging

            // Initialize DataTable
            var table = $('#activatedstockstable').DataTable({
                destroy: true,
                data: data,
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column
                columns: [{
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'j_o_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'date_used',
                        render: data => data ?
                            new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            var statusText;
                            var statusClass;

                            if (data == 0) {
                                statusText = 'Activation';
                                statusClass = 'badge badge-success text-light';
                            } else if (data == 1) {
                                statusText = 'Released';
                                statusClass = 'badge badge-primary text-light';
                            } else if (data == 2) {
                                statusText = 'Activated';
                                statusClass = 'badge badge-info text-light';
                            } else if (data == 3) {
                                statusText = 'Repair';
                                statusClass = 'badge badge-danger text-light';
                            } else {
                                statusText = 'Unknown';
                                statusClass = 'badge badge-secondary text-light';
                            }

                            return '<div class="' + statusClass + '">' + statusText + '</div>';
                        },
                        className: 'text-nowrap',
                        orderable: true
                    }
                ]
            });
        }

        // Function to render the repaired stocks DataTable
        function renderrepairedstocks(data) {
            console.log(data); // Inspect the data structure

            var table = $('#repairedstockstable').DataTable({
                destroy: true, // Destroy existing table and reinitialize
                data: data,
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (Account No.) in ascending order
                columns: [{
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by account_no
                    },
                    {
                        data: 'ticket_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by j_o_no
                    },
                    {
                        data: 'serial_new_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by serial_no
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by serial_no
                    },
                    {
                        data: 'date_repaired',
                        render: data => data ?
                            new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : 'N/A',
                        orderable: true // Allow sorting by date_used
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            var statusText;
                            var statusClass;

                            if (data == 0) {
                                statusText = 'Active';
                                statusClass = 'badge badge-success text-light';
                            } else if (data == 1) {
                                statusText = 'Released';
                                statusClass = 'badge badge-primary text-light';
                            } else if (data == 2) {
                                statusText = 'Activated';
                                statusClass = 'badge badge-info text-light';
                            } else if (data == 3) {
                                statusText = 'Repair';
                                statusClass = 'badge badge-danger text-light';
                            } else {
                                statusText = 'Unknown';
                                statusClass = 'badge badge-secondary text-light';
                            }

                            return '<div class="status-bg ' + statusClass + '">' + statusText + '</div>';
                        },
                        className: 'text-nowrap',
                        orderable: true // Allow sorting by status
                    }
                ]
            });
        }

        // Function to render the dmur stocks DataTable
        function renderdmurstocks(data) {
            console.log(data); // Inspect the data structure for debugging

            // Initialize DataTable
            var table = $('#dmurstockstable').DataTable({
                destroy: true,
                data: data,
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column
                columns: [{
                        data: 'date_used',
                        render: data => data ?
                            new Date(data).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'sar_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'ticket_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'j_o_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'stock_materials', // Access stock_materials as an array
                        render: function(data) {
                            // Check if stock_materials is an array and map over it to create a numbered list
                            return data && Array.isArray(data) ?
                                data.map((item, index) =>
                                    `${index + 1}. ${item.stocksdesc_level.description}`).join(
                                    '<br>') // Join descriptions with line breaks
                                :
                                'N/A'; // If no data, return 'N/A'
                        },
                        orderable: true, // Allow sorting
                        createdCell: function(td) {
                            $(td).css('text-align', 'left'); // Align content to the left
                        }
                    },
                    {
                        data: 'subsaccount_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'subsname',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'tech_name', // Technician's name
                        render: data => (data?.tech_name || 'N/A'), // Display tech_name or 'N/A'
                        orderable: true,
                    },
                ]
            });
        }

        // Handle status change to show/hide additional fields
        $('input[name="status"]').change(function() {
            const status = $(this).val();

            // Reset values in activated, repair, and released fields
            $('#stocks_jo_no').val('');
            $('#stocks_date_released').val('');
            $('#stocks_date_used').val('');
            $('#ticket_no').val('');
            $('#stocks_serial_new_no').val('');
            $('#stocks_date_repaired').val('');

            // Show/hide fields based on selected status
            if (status == "2" || status == "0") { // Activated or Activation
                $('#activatedFields').removeClass('d-none');
                $('#repairFields').addClass('d-none');
                $('#releasedFields').addClass('d-none');
            } else if (status == "3") { // Repair
                $('#repairFields').removeClass('d-none');
                $('#activatedFields').addClass('d-none');
                $('#releasedFields').addClass('d-none');
            } else if (status == "1") { // Released
                $('#releasedFields').removeClass('d-none');
                $('#activatedFields').addClass('d-none');
                $('#repairFields').addClass('d-none');
            } else {
                $('#activatedFields, #repairFields, #releasedFields').addClass('d-none');
            }
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#stocksprintPDF').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#stockstable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#stocksprintExcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#stockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "stocks_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#stockslevelprintpdf').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Levels Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#stocksleveltable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#stockslevelprintexcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#stocksleveltable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks Level");

            // Save Excel file
            XLSX.writeFile(wb, "stocks_leves_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#releasedstocksprintpdf').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#releasedstockstable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#releasedstocksprintexcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#releasedstockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "released_stocks_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#installationstocksprintpdf').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#activatedstockstable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#installationstocksprintexcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#activatedstockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "activated_stocks_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#repairstocksprintpdf').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#repairstockstable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#repairstocksprintexcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#repairstockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "repair_stocks_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function() {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return {
                headers,
                rows
            };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#dmurstocksprintpdf').click(function() {
            const {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var {
                headers,
                rows
            } = getTableData('#dmurstockstable');

            // Generate the PDF table
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
            });

            // Get PDF data as a Blob URL
            var pdfPreview = doc.output('bloburl');

            // Open PDF in a new tab
            var pdfWindow = window.open(pdfPreview, '_blank');
            pdfWindow.focus();
        });

        // Print Excel Button Click
        $('#dmurstocksprintexcel').click(function() {
            var wb = XLSX.utils.book_new();
            var {
                headers,
                rows
            } = getTableData('#dmurstockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "dmur_stocks_data.xlsx");
        });

        // Initialize Select2 for filter dropdowns
        $('#month_select, #year_select, #status_select').select2({
            width: '100%',
        });

        // Initialize Select2 for filter dropdowns
        $('#stockslevelfilterMonth, #stockslevelfilterYear ').select2({
            width: '100%',
            dropdownParent: '#stockslevel' // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#releasedfilterMonth, #releasedfilterYear, #releasedfilterTeamTech').select2({
            width: '100%',
            dropdownParent: '#releasedstocks' // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#activatedfilterMonth, #activatedfilterYear').select2({
            width: '100%',
            dropdownParent: '#installationstocks' // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#repairedfilterMonth, #repairedfilterYear').select2({
            width: '100%',
            dropdownParent: '#repairstocks' // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#dmurfilterMonth, #dmurfilterYear').select2({
            width: '100%',
            dropdownParent: '#dmurstocks' // Adjust the dropdown position if needed
        });

        // Initialize Select2 on the description dropdown for create stocks modal
        $('#stocks_description').select2({
            dropdownParent: $('#createstocks') // Specify the parent for the dropdown
        });

        // Initialize Select2 on the description dropdown for edit stocks modal
        $('#editstocks_description').select2({
            dropdownParent: $('#editcreatestocks') // Specify the parent for the dropdown
        });

        // Initialize Select2 on the description dropdown for create stocks modal
        $('#stocks_tech_name').select2({
            dropdownParent: $('#createstocks') // Specify the parent for the dropdown
        });

        // Initialize Select2 on the description dropdown for edit stocks modal
        $('#editstocks_tech_name').select2({
            dropdownParent: $('#editcreatestocks') // Specify the parent for the dropdown
        });

        function selectstocksleveldescription() {
            // AJAX request to fetch the stocks levels
            $.ajax({
                url: '/stockslevel', // Ensure this URL is correct
                type: 'GET',
                success: function(response) {
                    console.log(response); // Log the response for debugging

                    // Check if the 'stockslevel' data is available in the response
                    if (response && response.stockslevel) {
                        var stockslevel = response.stockslevel; // Assuming the data is in response.stockslevel
                        var select = $('#stocks_description, #editstocks_description');

                        // Clear previous options
                        select.empty();

                        // Add a default "Select Materials" option
                        select.append('<option selected disabled>Select Materials</option>');

                        // Loop through the stockslevel data and append options to the select dropdown
                        $.each(stockslevel, function(index, item) {
                            // Only include items where stocks_level_status is 4 (active)
                            if (item.stocks_level_status === 4) {
                                // Append the description only
                                select.append('<option value="' + item.id + '">' + item.description +
                                    '</option>');
                            }
                        });

                        // Refresh Select2 after appending options
                        select.trigger('change'); // This is to refresh the Select2 dropdown
                    } else {
                        console.error("No stockslevel data found in the response");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching stock information:", error);
                }
            });
        }

        function selectteamtech() {
            // AJAX request to fetch team tech data
            $.ajax({
                url: '/teamtech', // Ensure this URL is correct
                type: 'GET',
                success: function(response) {
                    console.log(response); // Log the response for debugging

                    // Check if the 'teamtech' data is available in the response
                    if (response && response.teamtech) {
                        var teamtech = response.teamtech; // The data is in response.teamtech
                        var select = $('#stocks_tech_name, #editstocks_tech_name');

                        // Clear previous options
                        select.empty();

                        // Add a default "Select Team Tech" option
                        select.append('<option selected disabled>Select Team Tech</option>');

                        // Loop through the teamtech data to populate the select options
                        teamtech.forEach(function(tech) {
                            // Assuming 'id' and 'tech_name' are the correct properties from your TeamTech model
                            select.append('<option value="' + tech.id + '">' + tech.tech_name +
                                '</option>');
                        });

                        // Refresh Select2 after appending options
                        select.trigger('change'); // This is to refresh the Select2 dropdown
                    } else {
                        console.error("No teamtech data found in the response");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching teamtech information:", error);
                }
            });
        }
    </script>
@endpush
