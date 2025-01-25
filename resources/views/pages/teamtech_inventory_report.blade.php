@extends('layouts.main')

<!-- Bootstrap 4 CSS -->
<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

<link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" />

<style>
    /* Add hover effect to the card */
    .clickable-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .clickable-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        /* Change cursor to indicate it's clickable */
    }

    /* Style active tab */
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }

    /* Style inactive tabs */
    .nav-tabs .nav-link {
        background-color: #f8f9fa;
        color: #000;
    }

    /* Hover effect for table rows */
    table tbody tr:hover {
        background-color: #f1f1f1;
    }

</style>

@section('content')
    <div class="content">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Cards Section -->
        <div class="row justify-content-center">

            <!-- Total Bucket Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Techs</h5>
                        <p class="card-text" id="tech_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total techs assigned Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Techs Assigned</h5>
                        <p class="card-text" id="tech_assigned_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total techs unassigned Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-secondary text-center text-white shadow clickable-card" data-toggle="modal"
                    data-target="#unassignedmodal">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Techs Unassigned</h5>
                        <p class="card-text" id="tech_unassigned_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Remaining Stocks Modal -->
        <div class="modal fade" id="unassignedmodal" tabindex="-1" role="dialog" aria-labelledby="unassignedmodalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title text-black" id="unassignedmodalLabel">Unassigned Techs</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="repairstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="repairstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="unassignedtechstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tech Name</th>
                                </tr>
                            </thead>
                            <tbody id="unassignedtechstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons Section -->
        <div class="row align-items-center">
            <!-- Left-aligned buttons -->
            <div class="col-md-8 d-flex justify-content-start flex-wrap">

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

        <!-- Tabs for each tech -->
        <ul class="nav nav-tabs shadow-sm" id="reportTabs" role="tablist">
            <!-- Tabs will be dynamically populated here -->
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="reportTabsContent">
            <!-- Techs Table (this will be dynamically populated) -->
        </div>

    </div>
@endsection

@push('scripts')
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

    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            fetchtotalassignedtechs();

            fetchTechDetails();
        });

        function fetchTechDetails() {
            $.ajax({
                url: '/team-tech-details', // Your backend API
                method: 'GET',
                success: function(response) {
                    var teamTechDetails = response.teamTechDetails;

                    // Clear existing tabs and tab content
                    $('#reportTabs').empty();
                    $('#reportTabsContent').empty();

                    // Loop through techs and create tabs and corresponding content
                    teamTechDetails.forEach(function(tech, index) {
                        var tabId = 'techTab' + tech.tech_name_id;
                        var contentId = 'techContent' + tech.tech_name_id;

                        // Create tab button
                        var tab = `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${index === 0 ? 'text-white bg-primary active' : 'text-dark bg-light'}"
                            id="${tabId}-tab" data-bs-toggle="tab" data-bs-target="#${contentId}"
                            type="button" role="tab">
                            ${tech.tech_name}
                        </button>
                    </li>
                `;
                        $('#reportTabs').append(tab);

                        // Create tab content (table)
                        var tableContent = `
                    <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="${contentId}" role="tabpanel">
                        <div class="table-responsive shadow p-3 bg-white rounded">
                            <table class="table table-sm table-bordered text-center w-100" id="techtable-${tech.tech_name_id}">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Serial No</th>
                                        <th>Materials</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tech.stocks.length > 0 ? tech.stocks.map(function (stock) {
                                        return `
                                                <tr>
                                                    <td>${stock.serial_no}</td>
                                                    <td>${stock.descriptions.map(function (desc) {
                                                        return `${desc.description}`;
                                                    }).join(', ')}</td>
                                                </tr>
                                            `;
                                    }).join('') : '<tr><td colspan="2">No stocks available</td></tr>'}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                        $('#reportTabsContent').append(tableContent);
                    });

                    // Manually trigger the activation of the first tab to fix active state issues
                    $('#reportTabs .nav-link:first').tab('show');

                    // Custom handling for tab switching and active state color
                    $('#reportTabs').on('click', '.nav-link', function(e) {
                        e.preventDefault(); // Prevent default tab switch behavior

                        // Remove active classes from all tabs
                        $('#reportTabs .nav-link').removeClass('text-white bg-primary active');
                        $('#reportTabs .nav-link').addClass('text-dark bg-light');

                        // Add active class to the clicked tab
                        $(this).removeClass('text-dark bg-light').addClass(
                            'text-white bg-primary active');

                        // Show the corresponding tab content
                        var target = $(this).attr('data-bs-target');
                        $('.tab-pane').removeClass('show active');
                        $(target).addClass('show active');
                    });
                },
                error: function(error) {
                    console.error("Error fetching team tech details:", error);
                }
            });
        }

        function fetchtotalassignedtechs() {
            // Fetch total deployed ports
            $.ajax({
                url: '/tech-assigned-count',
                method: 'GET',
                success: function(response) {
                    // Update the card with the total deployed ports
                    $('#tech_assigned_count').text(response.assignedCount);
                    $('#tech_unassigned_count').text(response.unassignedCount);
                    $('#tech_count').text(response.totalTechs);

                    populateUnassignedTechs(response.unassignedTechs);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching total deployed ports:", error);
                }
            });
        }

        function populateUnassignedTechs(data) {
            $('#unassignedtechstable').DataTable({
                destroy: true, // Make sure we destroy the old table before creating a new one
                data: data, // Use the filtered data
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (municipality) in ascending order
                columns: [{
                    data: 'tech_name',
                    render: data => data ? data : 'N/A',
                    orderable: true // Allow sorting
                }]
            });
        }
    </script>
@endpush
