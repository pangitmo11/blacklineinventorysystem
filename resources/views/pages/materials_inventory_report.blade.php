@extends('layouts.main')

<!-- Bootstrap 4 CSS -->
<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ secure_asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ secure_asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ secure_asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ secure_asset('css/dataTables.bootstrap4.min.css') }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

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

    #searchBar {
        max-width: 300px;
        /* Limit width */
    }
</style>

@section('content')
    <div class="content">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Cards Section -->
        <div class="row justify-content-center">

            <!-- Total Bucket Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Materials</h5>
                        <p class="card-text" id="total_materials_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Bucket Per week Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Used Materials</h5>
                        <p class="card-text" id="total_used_materials_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Card with Hover and Clickable Modal Trigger -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Available Materials</h5>
                        <p class="card-text" id="total_available_materials_count" style="font-size: 1.5rem;">0</p>
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

                <div class="col-md-4 mb-3">
                    <label for="filterstatus" class="form-label">Status</label>
                    <select id="filterstatus" class="form-select filterstatus">
                        <option value="">All</option> <!-- Default: No filter -->
                        <option value="0">Activation</option>
                        <option value="1">Released</option>
                        <option value="2">Activated</option>
                        <option value="3">Repair</option>
                        <option value="4">Available</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Buttons Section -->
        <div class="row align-items-center">
            <!-- Left-aligned buttons -->
            <div class="col-md-8 d-flex justify-content-start flex-wrap">
                <div class="form-group">
                    <label for="daterangePicker">Filter by Date Range</label>
                    <div class="input-group">
                        <input type="text" id="daterangePicker" class="form-control" placeholder="Select date range"
                            readonly>
                    </div>
                </div>
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
                <button class="nav-link text-white bg-primary" id="perDay-tab" data-bs-toggle="tab" data-bs-target="#perDay"
                    type="button" role="tab">
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
                <div class="d-flex justify-content-end mb-3">
                    <input type="text" id="searchBar" class="form-control w-25"
                        placeholder="Search by date, description, or status..." />
                </div>
                <div class="table-responsive shadow p-3 bg-white rounded">
                    <table id="stocks_perday" class="table table-sm table-bordered text-center w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Date Delivery</th>
                                <th>Materials</th>
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
                                <th>Date Delivery</th>
                                <th>Materials</th>
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

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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

            filterMaterialsData();

            // Set default month and year to current
            let currentMonth = moment().month() + 1; // Get the current month (1-12)
            let currentYear = moment().year(); // Get the current year
            let startDate, endDate;


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

            // Filter data based on the selected month, year, and status
            $('#filtermonth, #filteryear, #filterstatus').change(function() {
                let selectedMonth = $('#filtermonth').val();
                let selectedYear = $('#filteryear').val();
                let selectedStatus = $('#filterstatus').val(); // Get the selected status
                filterMaterialsData(selectedMonth, selectedYear, selectedStatus);
            });

            filterMaterialsData(currentMonth, currentYear);

            // Initialize the date range picker
            $('#daterangePicker').daterangepicker({
                opens: 'left',
                autoUpdateInput: false, // Prevent auto-filling initially
                locale: {
                    format: 'MMMM DD, YYYY', // Set the display format
                    cancelLabel: 'Clear', // Text for the clear button
                },
            });

            // Handle the 'apply' event to display the selected date range and update the table
            $('#daterangePicker').on('apply.daterangepicker', function(ev, picker) {
                // Format start and end dates
                const startDate = picker.startDate.format('MMMM DD, YYYY');
                const endDate = picker.endDate.format('MMMM DD, YYYY');

                // Set the formatted range in the input
                $(this).val(`${startDate} - ${endDate}`);

                // Call the AJAX function to filter table data
                filterMaterialsDataByDateRange(
                    picker.startDate.format('YYYY-MM-DD'), // Pass formatted start date
                    picker.endDate.format('YYYY-MM-DD') // Pass formatted end date
                );
            });

            // Handle the 'cancel' event to clear the input and reset the table
            $('#daterangePicker').on('cancel.daterangepicker', function() {
                $(this).val(''); // Clear the input field

                // Reset the table to show all data
                filterMaterialsData(); // Call the function to load all data
            });
        });

        function filterMaterialsData(month, year, status) {
            $.ajax({
                url: '/get-materials-data',
                method: 'GET',
                data: {
                    month: month,
                    year: year,
                    status: status // Pass status to the server
                },
                success: function(response) {
                    console.log(response); // Handle response and update tables
                    populateTables(response);
                }
            });
        }

        function filterMaterialsDataByDateRange(startDate, endDate) {
            $.ajax({
                url: '/get-materials-data', // Backend endpoint
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    console.log(response); // Debug response
                    populateTables(response); // Update tables with filtered data
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                    alert('Failed to fetch data for the selected date range.');
                }
            });
        }

        function populateTables(data) {
            $('#perDayTable').empty();
            $('#perWeekTable').empty();

            function createRow(date, description, statusText) {
                return `<tr>
            <td class="table-date">${date}</td>
            <td class="table-description">${description}</td>
            <td class="table-status">${statusText}</td>
        </tr>`;
            }

            let perDayCount = 0;
            let usedMaterialsCount = 0; // For counting statuses 0, 1, 2, 3
            let totalAvailableMaterialsCount = 0; // For counting status 4

            function getStatusText(status) {
                let statusText;
                let statusClass;

                if (status === 0) {
                    statusText = 'Activation';
                    statusClass = 'badge badge-secondary text-light';
                } else if (status === 1) {
                    statusText = 'Released';
                    statusClass = 'badge badge-primary text-light';
                } else if (status === 2) {
                    statusText = 'Activated';
                    statusClass = 'badge badge-info text-light';
                } else if (status === 3) {
                    statusText = 'Repair';
                    statusClass = 'badge badge-danger text-light';
                } else if (status === 4) {
                    statusText = 'Available';
                    statusClass = 'badge badge-success text-light';
                } else {
                    statusText = 'Unknown';
                    statusClass = 'badge badge-secondary text-light';
                }

                return `<span class="${statusClass}">${statusText}</span>`;
            }

            // Populate perDayTable
            if (data.perDayData && typeof data.perDayData === 'object' && Object.keys(data.perDayData).length > 0) {
                Object.entries(data.perDayData).forEach(([date, items]) => {
                    if (Array.isArray(items) && items.length > 0) {
                        items.forEach(item => {
                            let formattedDate = moment(item.date_delivery).format('MMM DD, YYYY');
                            let status = getStatusText(item.stocks_level_status);
                            let row = createRow(formattedDate, item.description || 'No Description',
                            status);

                            $('#perDayTable').append(row);
                            perDayCount++;

                            // Increment counts based on status
                            if ([0, 1, 2, 3].includes(item.stocks_level_status)) {
                                usedMaterialsCount++;
                            } else if ([4].includes(item.stocks_level_status)) {
                                totalAvailableMaterialsCount++;
                            }
                        });
                    }
                });
            } else {
                $('#perDayTable').append('<tr><td colspan="3" class="text-center">No Data Available</td></tr>');
            }

            // Populate perWeekTable
            if (data.perWeekData && typeof data.perWeekData === 'object' && Object.keys(data.perWeekData).length > 0) {
                Object.entries(data.perWeekData).forEach(([week, weekData]) => {
                    let weekHeaderRow = `<tr class="week-header">
                <td colspan="3" class="font-weight-bold text-uppercase">${week}</td>
            </tr>`;
                    $('#perWeekTable').append(weekHeaderRow);

                    if (Array.isArray(weekData) && weekData.length > 0) {
                        weekData.forEach(item => {
                            let formattedDate = moment(item.date_delivery).format('MMM DD, YYYY');
                            let status = getStatusText(item.stocks_level_status);
                            let row = createRow(formattedDate, item.description || 'No Description',
                            status);

                            $('#perWeekTable').append(row);
                        });
                    }
                });
            } else {
                $('#perWeekTable').append('<tr><td colspan="3" class="text-center">No Data Available</td></tr>');
            }

            // Update card counts
            $('#total_materials_count').text(perDayCount); // Total materials
            $('#total_used_materials_count').text(usedMaterialsCount); // Count of statuses 0, 1, 2, 3
            $('#total_available_materials_count').text(totalAvailableMaterialsCount); // Count of status 4

            // Add search functionality
            $('#searchBar').off('input').on('input', function() {
                const query = $(this).val().toLowerCase();
                let isDataFound = false; // Flag to track if any data matches

                // Filter perDayTable
                $('#perDayTable tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const isMatch = rowText.includes(query);
                    $(this).toggle(isMatch); // Show/hide rows based on match
                    if (isMatch) isDataFound = true;
                });

                // Filter perWeekTable
                $('#perWeekTable tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const isMatch = rowText.includes(query);
                    $(this).toggle(isMatch); // Show/hide rows based on match
                    if (isMatch) isDataFound = true;
                });

                // If no rows match, display "No Data Available"
                if (!isDataFound) {
                    $('#perDayTable').html('<tr><td colspan="3" class="text-center">No Data Available</td></tr>');
                    $('#perWeekTable').html('<tr><td colspan="3" class="text-center">No Data Available</td></tr>');
                } else {
                    // Restore original content if search term is cleared or matches are found
                    if (query === "") {
                        // Re-populate the tables if search term is cleared
                        populateTables(data);
                    }
                }
            });
        }

        // Initialize Select2 for filter dropdowns
        $('#filtermonth, #filteryear, #filterstatus').select2({
            width: '100%',
        });
    </script>
@endpush
