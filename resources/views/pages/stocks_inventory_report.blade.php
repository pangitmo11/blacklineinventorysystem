@extends('layouts.main')

<!-- Bootstrap 4 CSS -->
<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

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
                        <h5 class="card-title" style="font-size: 1rem;">Total Stocks</h5>
                        <p class="card-text" id="bucket_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Bucket Per week Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Week</h5>
                        <p class="card-text" id="bucket_perweek_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Card with Hover and Clickable Modal Trigger -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-center text-success shadow clickable-card" data-bs-toggle="modal"
                    data-bs-target="#remainingmodal">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Remaining</h5>
                        <p class="card-text" id="remaining_buckets" style="font-size: 1.5rem;">0%</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Remaining Stocks Modal -->
        <div class="modal fade" id="remainingmodal" tabindex="-1" role="dialog" aria-labelledby="remainingmodalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title text-black" id="remainingmodalLabel">Remaining Stocks</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="row justify-content-center">
                        <!-- Total remaining Stocks Card -->
                        <div class="col-lg-3 col-md-6 mt-3">
                            <div class="card bg-success text-center text-white shadow">
                                <div class="card-body">
                                    <h5 class="card-title" style="font-size: 1rem;">Total Remaining Stocks</h5>
                                    <p class="card-text" id="remaining_buckets_count" style="font-size: 1.5rem;">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <button class="btn btn-warning mb-3" style="margin-right: 5px;" id="repairstocksprintpdf">
                            <i class="fas fa-file-pdf"></i> Print PDF
                        </button>
                        <button class="btn btn-info mb-3" id="repairstocksprintexcel">
                            <i class="fas fa-file-excel"></i> Print Excel
                        </button>
                        <table id="remainingstockstable"
                            class="table table-sm display table-bordered table-responsive-md table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="remainingstockstableBody">
                                <!-- Table rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- filters --}}
        <div class="mb-4 shadow p-3 bg-white rounded">
            <div class="row">
                <!-- Month Filter -->
                <div class="col-md-4 mb-3">
                    <label for="filtermonth" class="form-label">Month</label>
                    <select id="filtermonth" class="form-select filtermonth">
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
                    <label for="filteryear" class="form-label">Year</label>
                    <select id="filteryear" class="form-select filteryear">
                        <!-- Year options will be dynamically populated -->
                    </select>
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

        <!-- Tabs for Per Day / Per Week -->
        <ul class="nav nav-tabs shadow-sm" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link text-white bg-primary" id="perDay-tab" data-bs-toggle="tab"
                    data-bs-target="#perDay" type="button" role="tab">
                    Per Day
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark bg-light" id="perWeek-tab" data-bs-toggle="tab"
                    data-bs-target="#perWeek" type="button" role="tab">
                    Per Week
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="reportTabsContent">
            <!-- Per Day Table -->
            <div class="tab-pane fade show active" id="perDay" role="tabpanel">
                <div class="table-responsive shadow p-3 bg-white rounded">
                    <table id="stocks_perday" class="table table-sm table-bordered text-center w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Date Released</th>
                                <th>Description</th>
                                <th>Serial No.</th>
                                <th>Status</th> <!-- Added Status Column -->
                            </tr>
                        </thead>
                        <tbody id="perDayTable">
                            <!-- Data will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Per Week Table -->
            <div class="tab-pane fade" id="perWeek" role="tabpanel">
                <div class="table-responsive shadow p-3 bg-white rounded">
                    <table id="stocks_perweek" class="table table-sm table-bordered text-center w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Date Release</th> <!-- Display Week Start and End -->
                                <th>Description</th>
                                <th>Serial No.</th>
                                <th>Status</th> <!-- Added Status Column -->
                            </tr>
                        </thead>
                        <tbody id="perWeekTable">
                            <!-- Data will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
            </div>
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

    <script>
        $(document).ready(function() {
            const tabs = $('#reportTabs .nav-link');

            // Set the default active tab on page load
            if (!tabs.hasClass('bg-primary')) {
                $('#perDay-tab').removeClass('text-dark bg-light').addClass('text-white bg-primary');
                $('#perDay').addClass('show active');
            }

            // Handle tab switching
            tabs.on('click', function() {
                // Remove active styling from all tabs
                tabs.removeClass('text-white bg-primary').addClass('text-dark bg-light');

                // Add active styling to the clicked tab
                $(this).removeClass('text-dark bg-light').addClass('text-white bg-primary');

                // Show the corresponding tab content
                const target = $(this).data('bs-target');
                $('.tab-pane').removeClass('show active'); // Hide all tab panes
                $(target).addClass('show active'); // Show the clicked tab pane
            });

            filterStockData();

            fetchremainingstocks();

            fetchTotalActiveDescriptions();

            // Set default month and year to current
            let currentMonth = moment().month() + 1; // Get the current month (1-12)
            let currentYear = moment().year(); // Get the current year

            // Set the default values for the filters
            $('#filtermonth').val(currentMonth); // Set default month
            $('#filteryear').val(currentYear); // Set default year

            // Fetch and populate the year dropdown based on the available data
            $.ajax({
                url: '/get-available-years', // Backend endpoint to fetch available years
                method: 'GET',
                success: function(response) {
                    // Populate the year dropdown with unique years
                    let years = response.years;
                    years.forEach(function(year) {
                        $('#filteryear').append(`<option value="${year}">${year}</option>`);
                    });
                }
            });


            // Filter data based on the selected month and year
            $('#filtermonth, #filteryear').change(function() {
                let selectedMonth = $('#filtermonth').val();
                let selectedYear = $('#filteryear').val();
                filterStockData(selectedMonth, selectedYear);
            });

            filterStockData(currentMonth, currentYear);

        });

        // Function to fetch and filter stock data
        function filterStockData(month, year) {
            $.ajax({
                url: '/get-stock-data',
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    console.log(response); // Handle response and update tables
                    populateTables(response);
                }
            });
        }

        function populateTables(data) {
            $('#perDayTable').empty();
            $('#perWeekTable').empty();

            // Helper function to create a row for the table
            function createRow(date, description, serialNo, status) {
                return `<tr>
            <td>${date}</td>
            <td>${description}</td>
            <td>${serialNo}</td>
            <td>${status}</td>
                </tr>`;
            }

            let perDayCount = 0;
            let perWeekCount = 0;

            // Populate Per Day Data
            if (data.perDayData && typeof data.perDayData === 'object' && Object.keys(data.perDayData).length > 0) {
                let sortedPerDayData = Object.entries(data.perDayData).sort(([dateA], [dateB]) => {
                    // Sort by the date key
                    return new Date(dateA) - new Date(dateB);
                });

                sortedPerDayData.forEach(function([date, items]) {
                    if (Array.isArray(items) && items.length > 0) {
                        items.forEach(function(item) {
                            let descriptions = Array.isArray(item.stock_materials) ?
                                item.stock_materials.map(mat => mat.stocksdesc_level?.description ||
                                    'No Description').join(', ') :
                                'No Description';

                            let formattedDate = item.date_released ?
                                moment(item.date_released).format('MMM DD, YYYY') :
                                moment(date).format('MMM DD, YYYY');

                            let status = item.status === 1 ?
                                '<span class="badge bg-primary">Release</span>' :
                                item.status;

                            let row = createRow(formattedDate, descriptions, item.serial_no, status);
                            $('#perDayTable').append(row);
                            perDayCount++; // Increment per day count
                        });
                    }
                });
            } else {
                $('#perDayTable').append('<tr><td colspan="4" class="text-center">No Data Available</td></tr>');
            }

            // Populate Per Week Data
            if (data.perWeekData && typeof data.perWeekData === 'object' && Object.keys(data.perWeekData).length > 0) {
                let sortedPerWeekData = Object.entries(data.perWeekData).sort(([weekA], [weekB]) => {
                    const weekNumberA = parseInt(weekA.replace('Week ', ''));
                    const weekNumberB = parseInt(weekB.replace('Week ', ''));
                    return weekNumberA - weekNumberB;
                });

                // Count total unique weeks
                perWeekCount = sortedPerWeekData.length;

                sortedPerWeekData.forEach(function([week, weekData]) {
                    let weekHeaderRow = `<tr class="week-header">
            <td colspan="4" class="font-weight-bold text-uppercase">${week}</td>
        </tr>`;
                    $('#perWeekTable').append(weekHeaderRow);

                    weekData.forEach(function(item) {
                        let descriptions = Array.isArray(item.stock_materials) ?
                            item.stock_materials.map(mat => mat.stocksdesc_level?.description ||
                                'No Description').join(', ') :
                            'No Description';

                        let dateReleased = moment(item.date_released).format('MMM DD, YYYY');

                        let status = item.status === 1 ?
                            '<span class="badge bg-primary">Release</span>' :
                            item.status;

                        let row = createRow(dateReleased, descriptions, item.serial_no, status);
                        $('#perWeekTable').append(row);
                    });
                });
            } else {
                $('#perWeekTable').append('<tr><td colspan="4" class="text-center">No Data Available</td></tr>');
                perWeekCount = 0; // No weeks available
            }

            // Update card counts
            $('#bucket_count').text(perDayCount); // Total stocks
            $('#bucket_perweek_count').text(perWeekCount); // Total per week (unique weeks)

        }

        // Function to fetch remaining stock data
        function fetchremainingstocks() {
            $.ajax({
                url: '/total-active-descriptions',
                type: 'GET',
                success: function(response) {
                    renderremainingstocks(response);
                    console.log(response, 'response1111111');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function renderremainingstocks(data) {
            console.log(data);
            var table = $('#remainingstockstable').DataTable({
                destroy: true,
                data: data.activeDescriptions,
                stripeClasses: [], // Disable striping
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (product_name) in ascending order
                columns: [{
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
                                statusClass = 'badge badge-secondary text-light';
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
                                statusText = 'Active';
                                statusClass = 'badge badge-success text-light';
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

        function fetchTotalActiveDescriptions() {
            $.ajax({
                url: '/total-active-descriptions', // The route for the controller
                type: 'GET',
                success: function(response) {
                    // Update the card's total count
                    $('#remaining_buckets_count').text(response
                        .totalActiveDescriptions); // Update with the correct JSON key
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch total active descriptions:', error);
                }
            });
        }


        // Initialize Select2 for filter dropdowns
        $('#filtermonth, #filteryear').select2({
            width: '100%',
        });
    </script>
@endpush
