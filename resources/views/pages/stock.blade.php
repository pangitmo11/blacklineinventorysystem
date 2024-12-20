@extends('layouts.main')

<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

<style>
    .select2-container--open {
        z-index: 1050 !important;  /* Make sure it's above the modal */
    }

    .modal-body {
        overflow: auto; /* Allow scrolling if content overflows */
    }
</style>

@section('content')

<div class="content">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Cards Section -->
    <div class="row justify-content-center">
        <!-- Total Active Materials Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-center text-white shadow">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 1rem;">Total Active Materials</h5>
                    <p class="card-text" id="active-count" style="font-size: 1.5rem;">0</p>
                </div>
            </div>
        </div>

        <!-- Total Released Materials Card -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-center text-white shadow">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 1rem;">Total Released Materials</h5>
                    <p class="card-text" id="released-count" style="font-size: 1.5rem;">0</p>
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

    <!-- Filter Card -->
    <div class="card mb-4 shadow">
        <div class="card-header">
            <i class="fas fa-filter" style="margin-right: 5px;"></i>
            <strong>Filter</strong>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form id="filterForm">
                <div class="row">
                    <!-- Month Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="monthSelect">Month</label>
                        <select id="month_select" class="form-control select2">
                            <option value="All">All</option>
                            <option>January</option>
                            <option>February</option>
                            <option>March</option>
                            <option>April</option>
                            <option>May</option>
                            <option>June</option>
                            <option>July</option>
                            <option>August</option>
                            <option>September</option>
                            <option>October</option>
                            <option>November</option>
                            <option>December</option>
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="year_select">Year</label>
                        <select id="year_select" class="form-control select2">
                            <option value="All">All</option>
                            <option>2023</option>
                            <option>2024</option>
                            <option>2025</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="status_select">Status</label>
                        <select id="status_select" class="form-control select2">
                            <option value="All">All</option>
                            <option value="0">Active</option>
                            <option value="1">Released</option>
                            <option value="2">Activated</option>
                            <option value="3">Repair</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- buttons -->
    <div class="row">
        <!-- Left-aligned buttons -->
        <div class="col-md-8 d-flex justify-content-start flex-wrap">
            <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal" data-target="#createstocks">
                <i class="fas fa-plus"></i> Add
            </button>
            <button class="btn btn-sm btn-success mb-2 mr-2" id="activestocksbtn">
                <i class="fas fa-list"></i> Active
            </button>
            <button class="btn btn-sm btn-primary mb-2 mr-2" data-toggle="modal" data-target="#releasedstocks">
                <i class="fas fa-list"></i> Released
            </button>
            <button class="btn btn-sm btn-info mb-2 mr-2" data-toggle="modal" data-target="#installationstocks">
                <i class="fas fa-list"></i> Installation
            </button>
            <button class="btn btn-sm btn-danger mb-2" data-toggle="modal" data-target="#repairstocks">
                <i class="fas fa-list"></i> Repair
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

    <!-- Add stocks Modal -->
    <div class="modal fade" id="createstocks" tabindex="-1" role="dialog" aria-labelledby="createstocksLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
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
                                    <input class="form-check-input" type="radio" name="status" value="0" id="stocks_active" checked>
                                    <label class="form-check-label" for="stocks_active"><span class='badge bg-success'>Status as ACTIVE</span></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="1" id="stocks_released">
                                    <label class="form-check-label" for="stocks_released"><span class='badge bg-primary'>Status as RELEASED</span></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="2" id="stocks_activated">
                                    <label class="form-check-label" for="stocks_activated"><span class='badge bg-info'>Status as ACTIVATED</span></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="3" id="stocks_repair">
                                    <label class="form-check-label" for="stocks_repair"><span class='badge bg-danger'>Status as REPAIR</span></label>
                                </div>
                            </div>
                        </div>

                        <!-- Default form fields -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Serial No.</strong></label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                    class="form-control" id="stocks_serial_no" placeholder="Enter Serial No."
                                    data-name="stocks_serial_no" name="serial_no">
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
                                <label for="create_stocks"><strong>Description</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="stocks_description" placeholder="Enter Description"
                                    data-name="stocks_description" name="description">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Product Name</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="stocks_product_name" placeholder="Enter Product Name"
                                    data-name="stocks_product_name" name="product_name">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Team Tech</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="stocks_team_tech" placeholder="Enter Team Tech"
                                    data-name="team_tech_name" name="team_tech">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Date Active</strong></label>
                                <input type="date" class="form-control" id="stocks_date_active" placeholder="Enter Date"
                                    data-name="stocks_date" name="date">
                            </div>
                        </div>

                        <!-- Hidden fields for "Released" -->
                        <div id="releasedFields" class="d-none">
                            <hr>
                            <h6 class="text-primary" style="font-style: italic; margin-bottom: 10px;">Released Additional Fields</h6>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Date Released</strong></label>
                                    <input type="date" class="form-control" id="stocks_date_released" placeholder="Enter Date Released"
                                        data-name="stocks_date_released" name="date_released">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for "Activated" -->
                        <div id="activatedFields" class="d-none">
                            <hr>
                            <h6 style="font-style: italic; margin-bottom: 10px; color: #0dcaf0;">Activated Additional Fields</h6>
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
                                    <label for="create_stocks"><strong>Date Used</strong></label>
                                    <input type="date" class="form-control" id="stocks_date_used" placeholder="Enter Date Used"
                                        data-name="stocks_date_used" name="date_used">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for "Repair" -->
                        <div id="repairFields" class="d-none">
                            <hr>
                            <h6 style="font-style: italic; margin-bottom: 10px; color: #dc3545;">Repair Additional Fields</h6>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Ticket No.</strong></label>
                                    <input type="number" class="form-control" id="ticket_no" placeholder="Enter Ticket No."
                                        data-name="ticket_no" name="ticket">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Serial New No.</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="stocks_serial_new_no" placeholder="Enter New Serial No."
                                        data-name="stocks_serial_new_no" name="serial_new_no">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Date Repaired</strong></label>
                                    <input type="date" class="form-control" id="stocks_date_repaired" placeholder="Enter Date Repaired"
                                        data-name="stocks_date_repaired" name="date_repaired">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary btn-block" id="savebtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit stocks Modal -->
    <div class="modal fade" id="editcreatestocks" tabindex="-1" role="dialog" aria-labelledby="editcreatestocksLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
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
                                    <input class="form-check-input" type="radio" name="edit_status" value="0" id="editstocks_active">
                                    <label class="form-check-label" for="editstocks_active">
                                        <span class='badge bg-success'>Status as ACTIVE</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_status" value="1" id="editstocks_released">
                                    <label class="form-check-label" for="editstocks_released">
                                        <span class='badge bg-primary'>Status as RELEASED</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_status" value="2" id="editstocks_activated">
                                    <label class="form-check-label" for="editstocks_activated">
                                        <span class='badge bg-info'>Status as ACTIVATED</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_status" value="3" id="editstocks_repair">
                                    <label class="form-check-label" for="editstocks_repair">
                                        <span class='badge bg-danger'>Status as REPAIR</span>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Default form fields -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="editcreate_stocks"><strong>Serial No.</strong></label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                    class="form-control" id="editstocks_serial_no" placeholder="Enter Serial No."
                                    data-name="stocks_serial_no" name="serial_no">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="editcreate_stocks"><strong>Account No.</strong></label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                    class="form-control" id="editstocks_account_no" placeholder="Enter Account No."
                                    data-name="stocks_account_no" name="account_no">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Description</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="editstocks_description" placeholder="Enter Description"
                                    data-name="stocks_description" name="description">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="editcreate_stocks"><strong>Product Name</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="editstocks_product_name" placeholder="Enter Product Name"
                                    data-name="stocks_product_name" name="product_name">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="create_stocks"><strong>Team Tech</strong></label>
                                <input type="text" onkeyup="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');"
                                    class="form-control" id="editstocks_team_tech" placeholder="Enter Team Tech"
                                    data-name="team_tech_name" name="team_tech">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="editcreate_stocks"><strong>Date Active</strong></label>
                                <input type="date" class="form-control" id="editstocks_date_active" placeholder="Enter Date"
                                    data-name="stocks_date" name="date">
                            </div>
                        </div>

                        <!-- Hidden fields for "Released" -->
                        <div id="editreleasedFields" class="d-none">
                            <hr>
                            <h6 class="text-primary" style="font-style: italic; margin-bottom: 10px;">Released Additional Fields</h6>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_stocks"><strong>Date Released</strong></label>
                                    <input type="date" class="form-control" id="editstocks_date_released" placeholder="Enter Date Released"
                                        data-name="stocks_date_released" name="date_released">
                                </div>
                            </div>
                        </div>


                        <!-- Hidden fields for "Activated" -->
                        <div id="editactivatedFields" class="d-none">
                            <hr>
                            <h6 style="font-style: italic; margin-bottom: 10px; color: #0dcaf0;">Activated Additional Fields</h6>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>J.O No.</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editstocks_jo_no" placeholder="Enter J.O No."
                                        data-name="jo_account_no" name="jo_no">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Date Used</strong></label>
                                    <input type="date" class="form-control" id="editstocks_date_used" placeholder="Enter Date Used"
                                        data-name="stocks_date_used" name="date_used">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for "Repair" -->
                        <div id="editrepairFields" class="d-none">
                            <hr>
                            <h6 style="font-style: italic; margin-bottom: 10px; color: #dc3545;">Repair Additional Fields</h6>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Ticket No.</strong></label>
                                    <input type="number" class="form-control" id="editticket_no" placeholder="Enter Ticket No."
                                        data-name="ticket_no" name="ticket">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Serial New No.</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editstocks_serial_new_no" placeholder="Enter New Serial No."
                                        data-name="stocks_serial_new_no" name="serial_new_no">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="editcreate_stocks"><strong>Date Repaired</strong></label>
                                    <input type="date" class="form-control" id="editstocks_date_repaired" placeholder="Enter Date Repaired"
                                        data-name="stocks_date_repaired" name="date_repaired">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary btn-block" id="updatebtn">Save Changes</button>
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
                            <th>Date Active</th>
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

    <!-- Active Stocks Modal -->
    <div class="modal fade" id="activestocks" tabindex="-1" role="dialog" aria-labelledby="activestocksLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="activestocksLabel">Active Stocks</h5>
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
                                    <select id="activefilterMonth" class="form-select activefilterMonth">
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
                                    <select id="activefilterYear" class="form-select activefilterYear">
                                        <option value="All" selected>All</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="activestocksprintpdf">
                        <i class="fas fa-file-pdf"></i> Print PDF
                    </button>
                    <button class="btn btn-info mb-3" id="activestocksprintexcel">
                        <i class="fas fa-file-excel"></i> Print Excel
                    </button>

                    <!-- Table -->
                    <table id="activestockstable" class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Serial No.</th>
                                <th>Account No.</th>
                                <th>Date Active</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="activestockstableBody">
                            <!-- Table rows will be dynamically populated -->
                        </tbody>
                    </table>
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
                                <th>Account No.</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Serial No.</th>
                                <th>Team Tech</th>
                                <th>Date Released</th>
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
    <div class="modal fade" id="installationstocks" tabindex="-1" role="dialog" aria-labelledby="installationstocksLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
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

</div>

@endsection

@push('scripts') <!-- Assuming your layout has a section for additional scripts -->


<!-- jQuery (must be included first) -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

<!-- DataTables JS -->
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

<!-- Bootstrap JS (required for DataTables Bootstrap integration) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('js/select2.min.js') }}"></script>

<!-- jsPDF Library for PDF Export -->
<script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/jspdf.plugin.autotable.min.js') }}"></script>

<!-- SheetJS Library for Excel Export -->
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>

<!-- FileSaver.js for saving the Excel file -->
<script src="{{ asset('js/FileSaver.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('js/sweetalert2@11.min.js') }}"></script>

<!-- moment.js -->
<script src="{{ asset('js/moment.min.js') }}"></script>


<script>
    $(document).ready(function() {

        //triggers to fetch stocks data
        fetchstockdata();

        //triggers to fetch active stocks data
        fetchactivestocks();

        //triggers to fetch released stocks data
        fetchreleasedstocks();

        //triggers to fetch activated stocks data
        fetchactivatedstocks();

        //triggers to fetch repaired stocks data
        fetchrepairedstocks();

        // Trigger the function to fetch material counts on page load
        fetchMaterialCounts();

        // Initial fetch to populate the table on page load
        fetchAndRenderStocks();

        // Trigger fetchFilteredStocks when the month or year filter changes
        fetchFilteredactiveStocks();

        // Trigger fetchFilteredStocks when the month or year filter changes
        fetchFilteredreleasedStocks();

        // Fetch and populate filter options on page load
        fetchFilterOptions();

        // Trigger fetchFilteredStocks when the month or year filter changes
        fetchFilteredactivatedStocks();

        // Trigger fetchFilteredStocks when the month or year filter changes
        fetchFilteredrepairedStocks();

        //triggers to save or store stocks data
        $('#savebtn').click(function() {
            save_stocks(event)
        });

        //triggers to fetch stocks data to modal
        $('#stockstable').on('click', '.edit', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            fetchstoredstocks(id);
        })

        // Trigger button for updating stocks
        $('#updatebtn').click(function(event) {
            event.preventDefault(); // Prevent default button behavior
            update_stocks(event); // Pass the event object
        });

        // Trigger fetch and update on filter changes
        $('#month_select, #year_select, #status_select').change(function () {
            fetchAndRenderStocks();
        });

        // Trigger fetchFilteredStocks when the month or year filter changes
        $('#activefilterMonth, #activefilterYear').change(function() {
            fetchFilteredactiveStocks();
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


        // Load dynamic active years
        $.ajax({
            url: '/fetch-years',
            method: 'GET',
            success: function (years) {
                let yearSelect = $('#year_select, #activefilterYear');
                yearSelect.empty(); // Clear current options
                yearSelect.append('<option value="All">All</option>'); // Add default option

                years.forEach(year => {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                });
            },
            error: function (xhr) {
                console.error('Error fetching years:', xhr);
            }
        });

        // Load dynamic activated years
        $.ajax({
            url: '/fetch-activated-years',
            method: 'GET',
            success: function (years) {
                let yearSelect = $('#activatedfilterYear');
                yearSelect.empty(); // Clear current options
                yearSelect.append('<option value="All">All</option>'); // Add default option

                years.forEach(year => {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                });
            },
            error: function (xhr) {
                console.error('Error fetching years:', xhr);
            }
        });

        // Load dynamic repaired years
        $.ajax({
            url: '/fetch-repaired-years',
            method: 'GET',
            success: function (years) {
                let yearSelect = $('#repairedfilterYear');
                yearSelect.empty(); // Clear current options
                yearSelect.append('<option value="All">All</option>'); // Add default option

                years.forEach(year => {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                });
            },
            error: function (xhr) {
                console.error('Error fetching years:', xhr);
            }
        });

        // Trigger when the modal is opened to fetch active stocks
        $(document).on('click', '#activestocksbtn', function(e) {
            $('#activestocks').modal('show');
            fetchFilteredactiveStocks(); // Fetch the filtered data on modal open
        });

    });

        // Function to fetch data based on filters and update the DataTable
        function fetchAndRenderStocks() {
            const month = $('#month_select').val();
            const year = $('#year_select').val();
            const status = $('#status_select').val();

            $.ajax({
                url: '/fetch-stocks',
                method: 'GET',
                data: { month, year, status },
                success: function (response) {
                    renderstocks(response); // Refresh the DataTable with new data
                },
                error: function (xhr) {
                    console.error('Error fetching stocks:', xhr);
                }
            });
        }

        function fetchFilteredactiveStocks() {
            const month = $('#activefilterMonth').val();
            const year = $('#activefilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-active-stocks',  // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    // Call the render function with the filtered data
                    renderactivestocks(response.stocks);
                    console.log(response, 'Fetched Filtered active Stocks');

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching filtered stocks:', error);
                }
            });
        }

        function fetchFilteredreleasedStocks() {
            const team_tech = $('#releasedfilterTeamTech').val();
            const month = $('#releasedfilterMonth').val();
            const year = $('#releasedfilterYear').val();

            // Make the AJAX request to fetch filtered stocks
            $.ajax({
                url: '/filter-released-stocks',  // Make sure to replace this with your actual API endpoint
                method: 'GET',
                data: {
                    team_tech: team_tech,
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
                url: '/filter-activated-stocks',  // Make sure to replace this with your actual API endpoint
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
                url: '/filter-repaired-stocks',  // Make sure to replace this with your actual API endpoint
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

        function fetchFilterOptions() {
            // Make the AJAX request to fetch filter options
            $.ajax({
                url: '/filter-released-stocks-options',  // Make sure to replace this with your actual API endpoint for fetching filter options
                method: 'GET',
                success: function(response) {
                    // Populate team_tech filter options
                    const teamTechSelect = $('#releasedfilterTeamTech');
                    teamTechSelect.empty();
                    teamTechSelect.append('<option value="All">All</option>');
                    response.team_techs.forEach(function(team_tech) {
                        teamTechSelect.append('<option value="' + team_tech.team_tech + '">' + team_tech.team_tech + '</option>');
                    });

                    // Populate year filter options
                    const yearSelect = $('#releasedfilterYear');
                    yearSelect.empty();
                    yearSelect.append('<option value="All">All</option>');
                    response.years.forEach(function(year) {
                        yearSelect.append('<option value="' + year.year + '">' + year.year + '</option>');
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

            var account_no = $('#stocks_account_no').val();
            var serial_no = $('#stocks_serial_no').val();
            var product_name = $('#stocks_product_name').val();
            var description = $('#stocks_description').val();
            var team_tech = $('#stocks_team_tech').val();
            var date_active = $('#stocks_date_active').val();
            var date_released = $('#stocks_date_released').val();
            var date_used = $('#stocks_date_used').val();
            var date_repaired = $('#stocks_date_repaired').val();
            var ticket_no = $('#ticket_no').val();
            var serial_new_no = $('#stocks_serial_new_no').val();
            var j_o_no = $('#stocks_jo_no').val();

            // Check if input fields are empty
            if (!serial_no) {
                // Display a SweetAlert prompt message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill the required fields.', // Prompt message
                });
                return; // Exit the function without submitting the form
            }

            // Prepare data for the AJAX request
            var data = {
                product_name: product_name,
                description: description,
                team_tech: team_tech,
                account_no: account_no,
                serial_no: serial_no,
                date_active: date_active,
                date_released: date_released,
                date_used: date_used,
                date_repaired: date_repaired,
                status: status,
                ticket_no: ticket_no,
                serial_new_no: serial_new_no,
                j_o_no: j_o_no,
            };

            $.ajax({
                url: '/stocks', // This should match the route for storing stocks (POST /stocks)
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include the CSRF token in the headers
                },
                data: data,
                success: function(response) {
                    console.log(response);

                    // Check if the response is successful
                    if(response.status === 'success') {
                        fetchstockdata(); // Refresh stock data
                        fetchactivestocks(); // Refresh active stocks
                        fetchreleasedstocks(); // Refresh released stocks
                        fetchactivatedstocks(); // Refresh activated stocks
                        fetchrepairedstocks(); // Refresh repaired stocks
                        fetchMaterialCounts(); // Fetch material counts

                        // Hide the additional fields for Activated and Repair
                        $('#activatedFields').addClass('d-none');
                        $('#repairFields').addClass('d-none');

                        // Reset the form fields and close the modal
                        $('#stocksform')[0].reset();


                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Stock created successfully.',
                            showConfirmButton: false,
                            timer: 1500 // Automatically close after 1.5 seconds
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong while saving the stock.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save Stock. Please try again later.',
                    });
                    console.error(xhr.responseText);
                }
            });
        }

        // Fetch stock data for the edit modal
        function fetchstoredstocks(id) {
            console.log(id); // Debug: Check if the correct ID is being fetched

            // AJAX request to fetch data for the selected ID
            $.ajax({
                url: '/stocks/' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    var data = response.stocks;

                    // Store the fetched data in global variables
                    window.fetchedData = {
                        account_no: data.account_no,
                        serial_no: data.serial_no,
                        product_name: data.product_name,
                        description: data.description,
                        team_tech: data.team_tech,
                        date_active: data.date_active,
                        date_released: data.date_released,
                        j_o_no: data.j_o_no,
                        date_used: data.date_used,
                        ticket_no: data.ticket_no,
                        serial_new_no: data.serial_new_no,
                        date_repaired: data.date_repaired
                    };

                    // Set the status of the radio buttons based on the data
                    if (data.status == 0) {
                        $('#editstocks_active').prop('checked', true);
                    } else if (data.status == 1) {
                        $('#editstocks_released').prop('checked', true);
                    } else if (data.status == 2) {
                        $('#editstocks_activated').prop('checked', true);
                    } else if (data.status == 3) {
                        $('#editstocks_repair').prop('checked', true);
                    }

                    // Format dates specifically for <input type="date"> fields
                    function formatDateForInput(date) {
                        return date ? moment(date, moment.ISO_8601).format('YYYY-MM-DD') : ''; // Format for type="date"
                    }

                    // Set the values for the fields (dates, other fields)
                    $('#editstocks_account_no').val(window.fetchedData.account_no);
                    $('#editstocks_serial_no').val(window.fetchedData.serial_no);
                    $('#editstocks_product_name').val(window.fetchedData.product_name);
                    $('#editstocks_description').val(window.fetchedData.description);
                    $('#editstocks_team_tech').val(window.fetchedData.team_tech);
                    $('#editstocks_date_active').val(formatDateForInput(window.fetchedData.date_active));
                    $('#editstocks_date_released').val(formatDateForInput(window.fetchedData.date_released)); // Set the date in YYYY-MM-DD
                    $('#editstocks_jo_no').val(window.fetchedData.j_o_no);
                    $('#editstocks_date_used').val(formatDateForInput(window.fetchedData.date_used)); // Set the date in YYYY-MM-DD
                    $('#editticket_no').val(window.fetchedData.ticket_no);
                    $('#editstocks_serial_new_no').val(window.fetchedData.serial_new_no);
                    $('#editstocks_date_repaired').val(formatDateForInput(window.fetchedData.date_repaired)); // Set the date in YYYY-MM-DD

                    $('#editstocksform').data('id', id);

                    // Show the correct additional fields based on the fetched data's status
                    toggleStatusFields(data.status);

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

        // Function to show/hide additional fields based on status
        function toggleStatusFields(status) {
            // Show the appropriate additional fields, without clearing any data
            if (status == 2) { // Activated
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
            $('#editstocks_date_active').val(formatDateForInput(window.fetchedData.date_active));
            $('#editstocks_date_released').val(formatDateForInput(window.fetchedData.date_released));
            $('#editstocks_date_used').val(formatDateForInput(window.fetchedData.date_used));
            $('#editstocks_date_repaired').val(formatDateForInput(window.fetchedData.date_repaired));
        }

        // Handle status change to show/hide additional edit fields
        $('input[name="edit_status"]').change(function () {
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
            var description = $('#editstocks_description').val();
            var team_tech = $('#editstocks_team_tech').val();
            var account_no = $('#editstocks_account_no').val();
            var serial_no = $('#editstocks_serial_no').val();
            var date_active = $('#editstocks_date_active').val();
            var date_released = $('#editstocks_date_released').val();
            var ticket_no = $('#editticket_no').val();
            var serial_new_no = $('#editstocks_serial_new_no').val();
            var j_o_no = $('#editstocks_jo_no').val();
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
            }).then(function (result) {
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
                            description: description,
                            team_tech: team_tech,
                            account_no: account_no,
                            serial_no: serial_no,
                            date_active: date_active,
                            date_released: date_released,
                            date_used: date_used,
                            date_repaired: date_repaired,
                            status: status, // Pass selected status
                            ticket_no: ticket_no,
                            serial_new_no: serial_new_no,
                            j_o_no: j_o_no,
                        },
                        success: function(response) {
                            console.log(response);

                            fetchstockdata(); // Refresh stock data
                            fetchactivestocks(); // Refresh active stocks
                            fetchreleasedstocks(); // Refresh released stocks
                            fetchactivatedstocks(); // Refresh activated stocks
                            fetchrepairedstocks(); // Refresh repaired stocks
                            fetchMaterialCounts(); // Update material counts
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
                                        'content') // Include the CSRF token in the headers
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
                                        fetchstockdata(); // Assuming you have a fetchData function to reload the table data
                                        fetchMaterialCounts(); // Fetch material counts
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

        // Function to fetch material counts
        function fetchMaterialCounts() {
            $.ajax({
                url: '/stocks', // The endpoint in your Laravel app
                type: 'GET',
                success: function (response) {
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
                error: function (xhr, status, error) {
                    console.error("Failed to fetch material counts:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Unable to fetch data. Please try again later.',
                    });
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

        // Function to fetch active stocks
        function fetchactivestocks() {
            $.ajax({
                url: '/fetch-active-stocks',
                type: 'GET',
                success: function(response) {
                    console.log(response); // Log the response to confirm it's correct
                    renderactivestocks(response); // Pass the response directly to renderactivestocks
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log any errors
                }
            });
        }

        // Function to fetch active stocks
        function fetchreleasedstocks() {
            $.ajax({
                url: '/fetch-released-stocks',
                type: 'GET',
                success: function(response) {
                    console.log(response); // Log the response to confirm it's correct
                    renderreleasedstocks(response); // Pass the response directly to renderactivestocks
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log any errors
                }
            });
        }

        // Function to fetch activated stocks
        function fetchactivatedstocks() {
            $.ajax({
                url: '/fetch-activated-stocks',
                type: 'GET',
                success: function(response) {
                    renderactivatedstocks(response);
                    console.log(response, 'Fetched Repaired Stocks');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
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

        // Function to render the DataTable
        function renderstocks(data) {
            console.log(data);
            var table = $('#stockstable').DataTable({
                destroy: true,
                data: data.stocks,
                stripeClasses: [], // Disable striping
                order: [[0, 'asc']], // Default sorting by the first column (product_name) in ascending order
                columns: [
                    {
                        data: 'product_name',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'description',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
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
                        data: 'date_active',
                        render: data => data
                            ? new Date(data).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                            : 'N/A',
                        orderable: true // Allow sorting
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
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="edit btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editcreatestocks" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="delete btn btn-danger btn-sm" data-id="' + row.id +
                                '"><i class="fas fa-trash-alt"></i></button>';
                        },
                        orderable: false // Disable sorting for action buttons
                    }
                ]
            });
        }

        // Function to render active the DataTable
        function renderactivestocks(data) {
            console.log(data); // Log the data to check it

            var table = $('#activestockstable').DataTable({
                destroy: true,
                data: data, // Directly use the response data as an array
                order: [[0, 'asc']], // Default sorting by the first column (product_name) in ascending order
                columns: [
                    {
                        data: 'product_name',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'description',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'date_active',
                        render: data => data
                            ? new Date(data).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                            : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            var statusText, statusClass;
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
                            return `<div class="status-bg ${statusClass}">${statusText}</div>`;
                        },
                        className: 'text-nowrap',
                        orderable: true
                    }
                ]
            });
        }

        // Function to render released the DataTable
        function renderreleasedstocks(data) {
            console.log(data); // Log the data to check it

            var table = $('#releasedstockstable').DataTable({
                destroy: true,
                data: data, // Directly use the response data as an array
                order: [[0, 'asc']], // Default sorting by the first column (product_name) in ascending order
                columns: [
                    {
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'product_name',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'description',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'team_tech',
                        render: data => data ? data : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'date_released',
                        render: data => data
                            ? new Date(data).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                            : 'N/A',
                        orderable: true
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            var statusText, statusClass;
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
                            return `<div class="status-bg ${statusClass}">${statusText}</div>`;
                        },
                        className: 'text-nowrap',
                        orderable: true
                    }
                ]
            });
        }

        // Function to render the activated stocks DataTable
        function renderactivatedstocks(data) {
            console.log(data); // Inspect the data structure

            var table = $('#activatedstockstable').DataTable({
                destroy: true, // Destroy existing table and reinitialize
                data: data,
                order: [[0, 'asc']], // Default sorting by the first column (Account No.) in ascending order
                columns: [
                    {
                        data: 'account_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by account_no
                    },
                    {
                        data: 'j_o_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by j_o_no
                    },
                    {
                        data: 'serial_no',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting by serial_no
                    },
                    {
                        data: 'date_used',
                        render: data => data
                            ? new Date(data).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                            : 'N/A',
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

        // Function to render the repaired stocks DataTable
        function renderrepairedstocks(data) {
            console.log(data); // Inspect the data structure

            var table = $('#repairedstockstable').DataTable({
                destroy: true, // Destroy existing table and reinitialize
                data: data,
                order: [[0, 'asc']], // Default sorting by the first column (Account No.) in ascending order
                columns: [
                    {
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
                        render: data => data
                            ? new Date(data).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                            : 'N/A',
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


        // Handle status change to show/hide additional fields
        $('input[name="status"]').change(function () {
            const status = $(this).val();

            // Reset values in activated, repair, and released fields
            $('#stocks_jo_no').val('');
            $('#stocks_date_released').val('');
            $('#stocks_date_used').val('');
            $('#ticket_no').val('');
            $('#stocks_serial_new_no').val('');
            $('#stocks_date_repaired').val('');

            // Show/hide fields based on selected status
            if (status == "2") { // Activated
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
            $(tableSelector + ' thead th').each(function () {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function () {
                var row = [];
                $(this).find('td').each(function () {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return { headers, rows };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#stocksprintPDF').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var { headers, rows } = getTableData('#stockstable');

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
        $('#stocksprintExcel').click(function () {
            var wb = XLSX.utils.book_new();
            var { headers, rows } = getTableData('#stockstable');

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
            $(tableSelector + ' thead th').each(function () {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function () {
                var row = [];
                $(this).find('td').each(function () {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return { headers, rows };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#activestocksprintpdf').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var { headers, rows } = getTableData('#activestockstable');

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
        $('#activestocksprintexcel').click(function () {
            var wb = XLSX.utils.book_new();
            var { headers, rows } = getTableData('#activestockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "active_stocks_data.xlsx");
        });

        // Function to get table headers and rows
        function getTableData(tableSelector) {
            var headers = [];
            var rows = [];

            // Get headers
            $(tableSelector + ' thead th').each(function () {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function () {
                var row = [];
                $(this).find('td').each(function () {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return { headers, rows };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#releasedstocksprintpdf').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var { headers, rows } = getTableData('#releasedstockstable');

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
        $('#releasedstocksprintexcel').click(function () {
            var wb = XLSX.utils.book_new();
            var { headers, rows } = getTableData('#releasedstockstable');

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
            $(tableSelector + ' thead th').each(function () {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function () {
                var row = [];
                $(this).find('td').each(function () {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return { headers, rows };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#installationstocksprintpdf').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var { headers, rows } = getTableData('#activatedstockstable');

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
        $('#installationstocksprintexcel').click(function () {
            var wb = XLSX.utils.book_new();
            var { headers, rows } = getTableData('#activatedstockstable');

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
            $(tableSelector + ' thead th').each(function () {
                headers.push($(this).text());
            });

            // Get rows
            $(tableSelector + ' tbody tr').each(function () {
                var row = [];
                $(this).find('td').each(function () {
                    row.push($(this).text());
                });
                rows.push(row);
            });

            return { headers, rows };
        }

        // Preview PDF Button Click (opens PDF in new tab)
        $('#repairstocksprintpdf').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.text("Stocks Data", 20, 20);

            var { headers, rows } = getTableData('#repairstockstable');

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
        $('#repairstocksprintexcel').click(function () {
            var wb = XLSX.utils.book_new();
            var { headers, rows } = getTableData('#repairstockstable');

            // Create Excel sheet
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Stocks");

            // Save Excel file
            XLSX.writeFile(wb, "repair_stocks_data.xlsx");
        });

        // Initialize Select2 for filter dropdowns
        $('#month_select, #year_select, #status_select').select2({
            width: '100%',
        });

        // Initialize Select2 for filter dropdowns
        $('#activefilterMonth, #activefilterYear ').select2({
            width: '100%',
            dropdownParent: '#activestocks'  // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#releasedfilterMonth, #releasedfilterYear, #releasedfilterTeamTech').select2({
            width: '100%',
            dropdownParent: '#releasedstocks'  // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#activatedfilterMonth, #activatedfilterYear').select2({
            width: '100%',
            dropdownParent: '#installationstocks'  // Adjust the dropdown position if needed
        });

        // Initialize Select2 for filter dropdowns
        $('#repairedfilterMonth, #repairedfilterYear').select2({
            width: '100%',
            dropdownParent: '#repairstocks'  // Adjust the dropdown position if needed
        });

</script>
@endpush
