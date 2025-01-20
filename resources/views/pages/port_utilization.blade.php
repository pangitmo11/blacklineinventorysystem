@extends('layouts.main')

<!-- Bootstrap 4 CSS -->
<!-- Bootstrap 4 CSS (required for DataTables and Select2 integration) -->
<link href="{{ asset('css/bootstrap_4.min.css') }}" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<!-- datatables CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css">
<style>
    .custom-cluster {
        background-color: #007bff;
        /* Primary color */
        color: white;
        /* Text color */
        border-radius: 50%;
        /* Make it circular */
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        font-size: 16px;
        font-weight: bold;
    }

    .custom-cluster span {
        line-height: 40px;
        /* Vertically center the text */
    }

    /* Fullscreen map adjustments */
    .leaflet-container:fullscreen {
        width: 100%;
        height: 100%;
    }

    .leaflet-container:-webkit-full-screen {
        width: 100%;
        height: 100%;
    }

    #mapModal .modal-content {
        padding: 0;
    }
</style>

@section('content')
    <div class="content">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Cards Section -->
        <div class="row justify-content-center">

            <!-- Total deployed ports Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Deployed Ports</h5>
                        <p class="card-text" id="deployed_ports_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total active ports Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Active Ports</h5>
                        <p class="card-text" id="active_ports_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total available ports Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Total Available Ports</h5>
                        <p class="card-text" id="available_ports_count" style="font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>

            <!-- Total utilization Percentage Card -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-center text-white shadow">
                    <div class="card-body">
                        <h5 class="card-title" style="font-size: 1rem;">Utilization Percentage</h5>
                        <p class="card-text" id="utilization_percentage" style="font-size: 1.5rem;">0%</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Buttons Section -->
        <div class="row align-items-center">
            <!-- Left-aligned buttons -->
            <div class="col-md-6 d-flex justify-content-start mb-2">
                <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#createports">
                    <i class="fas fa-plus"></i> Add
                </button>
                <button class="btn btn-sm btn-primary mr-2" id="import-data-button">
                    <i class="fas fa-file-excel"></i> Import Data
                </button>
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#mapModal">
                    <i class="fas fa-map-marker-alt"></i> Nap Map
                </button>
            </div>

        </div>

        <!-- Filter Card -->
        <div class="card mb-4 shadow">
            <div class="card-header">
                <i class="fas fa-filter"></i> Filter
            </div>
            <div class="card-body">
                <div class="row">

                    <!-- Municipality Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="filtermunicipality" class="form-label">Municipality</label>
                        <select id="filtermunicipality" onchange="fetchportutilizationdata()"
                            class="form-select filtermunicipality">
                            <option value="All" selected>All</option>
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>

                    <!-- Barangay Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="filterbarangay" class="form-label">Barangay</label>
                        <select id="filterbarangay" onchange="fetchportutilizationdata()"
                            class="form-select filterbarangay">
                            <option value="All" selected>All</option>
                        </select>
                    </div>

                    <!-- Brgy Code Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="filterbrgycode" class="form-label">Barangay Code</label>
                        <select id="filterbrgycode" onchange="fetchportutilizationdata()"
                            class="form-select filterbrgycode">
                            <option value="All" selected>All</option>
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!--Ports Utilization DataTable Section -->
        <div class="card shadow">
            <div class="card-header text-black">
                <strong>Ports Utilization Data</strong>
            </div>
            <div class="card-body">
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="portstable"
                        class="table table-sm display table-bordered table-vcenter js-dataTable-buttons text-center dataTable no-footer w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Municipality</th>
                                <th>Brgy Code</th>
                                <th>Barangay</th>
                                <th>Napcode</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th>Deployed</th>
                                <th>Active</th>
                                <th>Available</th>
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

        <!-- Add port Modal -->
        <div class="modal fade" id="createports" tabindex="-1" role="dialog" aria-labelledby="createstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Port</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="portsform" class="row p-3">

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Municipality</strong></label>
                                    <input type="text" oonkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="municipality" placeholder="Enter Municipality"
                                        data-name="ports_municipality" name="ports_municipality">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Barangay</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="barangay" placeholder="Enter Barangay"
                                        data-name="ports_barangay" name="ports_barangay">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Brgy Code</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="brgy_code" placeholder="Enter Brgy Code"
                                        data-name="ports_brgy_code" name="ports_brgy_code">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Nap Code</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="napcode" placeholder="Enter Nap Code"
                                        data-name="napcode" name="napcode">

                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Longitude</strong></label>
                                    <input type="text" class="form-control" id="longitude"
                                        placeholder="Enter Longitude (e.g., 124.7743)" data-name="ports_longitude"
                                        name="ports_longitude"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        pattern="^-?\d{1,3}\.\d{1,6}$" title="Enter a valid longitude (e.g., 124.7743)">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Latitude</strong></label>
                                    <input type="text" class="form-control" id="latitude"
                                        placeholder="Enter Longitude (e.g., 124.7743)" data-name="ports_latitude"
                                        name="ports_atitude"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        pattern="^-?\d{1,3}\.\d{1,6}$" title="Enter a valid latitude (e.g., 124.7743)">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Deployed</strong></label>
                                    <input type="number" class="form-control" id="no_of_deployed"
                                        placeholder="Enter No. of Deployed" data-name="ports_deployed"
                                        name="ports_deployed">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Active</strong></label>
                                    <input type="number" class="form-control" id="no_of_active"
                                        placeholder="Enter No. of Active" data-name="ports_active" name="ports_active">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Available</strong></label>
                                    <input type="number" class="form-control" id="no_of_available"
                                        placeholder="Enter No. of Available" data-name="ports_available"
                                        name="ports_available">
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

        <!-- Update port Modal -->
        <div class="modal fade" id="editports" tabindex="-1" role="dialog" aria-labelledby="createstocksLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Port</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editportsform" class="row p-3">

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Municipality</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editmunicipality" placeholder="Enter Municipality"
                                        data-name="ports_municipality" name="ports_municipality">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Barangay</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editbarangay" placeholder="Enter Barangay"
                                        data-name="ports_barangay" name="ports_barangay">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Brgy Code</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editbrgy_code" placeholder="Enter Brgy Code"
                                        data-name="ports_brgy_code" name="ports_brgy_code">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Nap Code</strong></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" id="editnapcode" placeholder="Enter Nap Code"
                                        data-name="napcode" name="napcode">

                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Longitude</strong></label>
                                    <input type="text" class="form-control" id="editlongitude"
                                        placeholder="Enter Longitude (e.g., 124.7743)" data-name="ports_longitude"
                                        name="ports_longitude"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        pattern="^-?\d{1,3}\.\d{1,6}$" title="Enter a valid longitude (e.g., 124.7743)">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>Latitude</strong></label>
                                    <input type="text" class="form-control" id="editlatitude"
                                        placeholder="Enter Longitude (e.g., 124.7743)" data-name="ports_latitude"
                                        name="ports_atitude"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        pattern="^-?\d{1,3}\.\d{1,6}$" title="Enter a valid latitude (e.g., 124.7743)">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Deployed</strong></label>
                                    <input type="number" class="form-control" id="editno_of_deployed"
                                        placeholder="Enter No. of Deployed" data-name="ports_deployed"
                                        name="ports_deployed">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Active</strong></label>
                                    <input type="number" class="form-control" id="editno_of_active"
                                        placeholder="Enter No. of Active" data-name="ports_active" name="ports_active">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="create_ports"><strong>No. of Available</strong></label>
                                    <input type="number" class="form-control" id="editno_of_available"
                                        placeholder="Enter No. of Available" data-name="ports_available"
                                        name="ports_available">
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

        <!--Nap Map Modal -->
        <div id="mapModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <div id="map" style="margin-top: 10px; width: 100%; height: 500px;"></div>
                        <!-- The map container -->
                        <!-- Legend for Availability -->
                        <div id="mapLegend" style=" font-size: 14px;">
                            <b>Availability Legend:</b>
                            <ul style="list-style: none; padding-left: 0; display: flex; gap: 20px;">
                                <li style="display: inline-flex; align-items: center;">
                                    <span
                                        style="display: inline-block; width: 20px; height: 20px; background-color: #ffa500; margin-right: 5px;"></span>
                                    Low Availability
                                </li>
                                <li style="display: inline-flex; align-items: center;">
                                    <span
                                        style="display: inline-block; width: 20px; height: 20px; background-color: #ff0000; margin-right: 5px;"></span>
                                    Full Nap (No Available)
                                </li>
                                <li style="display: inline-flex; align-items: center;">
                                    <span
                                        style="display: inline-block; width: 20px; height: 20px; background-color: #28a745; margin-right: 5px;"></span>
                                    High Availability
                                </li>
                            </ul>
                        </div>

                    </div>
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Marker Cluster JS (optional, for clustered markers) -->
    <script src="https://unpkg.com/leaflet.markercluster@1.5.1/dist/leaflet.markercluster.js"></script>

    <script src="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.js"></script>

    <script>
        $(document).ready(function() {

            fetchportutilizationdata();

            fetchAndPinMarkers(); // Call the function to fetch and pin markers

            fetchtotalsumdeployed();

            fetchtotalsumactive();

            fetchtotalsumavailable();

            fetchutilizationpercentage();

            updateLegend();

            $('#savebtn').click(function() {
                save_portutilization(event)
            })

            $('#mapModal').on('shown.bs.modal', function() { // Trigger when the modal is opened
                fetchAndPinMarkers(); // Call the function to fetch and pin markers
            })

            //triggers to fetch ports data to modal
            $('#portstable').on('click', '.edit', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                fetchstoredportsutilization(id);
            })

            $('#updatebtn').click(function(event) {
                update_portutilization()
            })

            // Fetch Municipalities
            $.ajax({
                url: '/municipalities',
                type: 'GET',
                success: function(response) {
                    let options = '<option value="All" selected>All</option>';
                    response.forEach(function(municipality) {
                        options += `<option value="${municipality}">${municipality}</option>`;
                    });
                    $('#filtermunicipality').html(options);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

            // Fetch Barangays based on selected municipality
            $('#filtermunicipality').change(function() {
                let municipality = $(this).val();
                console.log('Selected municipality:', municipality); // Debugging line
                if (municipality !== 'All') {
                    $.ajax({
                        url: '/barangays',
                        type: 'GET',
                        data: {
                            municipality: municipality
                        },
                        success: function(response) {
                            let options = '<option value="All" selected>All</option>';
                            response.forEach(function(barangay) {
                                options +=
                                    `<option value="${barangay}">${barangay}</option>`;
                            });
                            $('#filterbarangay').html(options);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#filterbarangay').html('<option value="All" selected>All</option>');
                }
            });

            // Fetch Brgy Codes based on selected barangay
            $('#filterbarangay').change(function() {
                let barangay = $(this).val();
                console.log('Selected barangay:', barangay); // Debugging line
                if (barangay !== 'All') {
                    $.ajax({
                        url: '/brgycodes',
                        type: 'GET',
                        data: {
                            barangay: barangay
                        },
                        success: function(response) {
                            let options = '<option value="All" selected>All</option>';
                            response.forEach(function(brgy_code) {
                                options +=
                                    `<option value="${brgy_code}">${brgy_code}</option>`;
                            });
                            $('#filterbrgycode').html(options);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#filterbrgycode').html('<option value="All" selected>All</option>');
                }
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
                            url: '/import-sheet-data', // Route to trigger the import function
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
                                fetchportutilizationdata
                                    (); // Fetch data and update table

                                fetchAndPinMarkers();

                                fetchtotalsumdeployed();
                                fetchtotalsumactive();
                                fetchtotalsumavailable();

                                fetchutilizationpercentage();

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

        // Function to fetch port utilization data
        function fetchportutilizationdata() {
            $.ajax({
                url: '/portutilization',
                type: 'GET',
                success: function(response) {
                    renderportutilization(response
                        .ports_utilization); // Corrected to access ports_utilization array
                    console.log(response, 'portutilization1');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Render the data in the table
        function renderportutilization(data) {
            console.log(data, 'dataports');

            // Ensure DataTable is destroyed before re-rendering
            var table = $('#portstable').DataTable({
                destroy: true, // Make sure we destroy the old table before creating a new one
                data: data, // Use the filtered data
                order: [
                    [0, 'asc']
                ], // Default sorting by the first column (municipality) in ascending order
                columns: [{
                        data: 'municipality',
                        render: data => data ? data : 'N/A',
                        orderable: true, // Allow sorting
                    },
                    {
                        data: 'brgy_code', // Access stock_materials as an array
                        render: data => data ? data : 'N/A',
                        orderable: true, // Allow sorting
                    },
                    {
                        data: 'barangay',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'napcode',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'longitude',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'latitude',
                        render: data => data ? data : 'N/A',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'no_of_deployed',
                        render: data => data ? data : '0',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'no_of_active',
                        render: data => data ? data : '0',
                        orderable: true // Allow sorting
                    },
                    {
                        data: 'no_of_available',
                        render: data => data ? data : '0',
                        orderable: true // Allow sorting
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="edit btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#editports" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>';
                        },
                        orderable: false, // Disable sorting for action buttons
                        width: '20%' // Set a fixed width for the action buttons
                    }
                ]
            });
        }


        function save_portutilization(event) {
            event.preventDefault(); // Prevent the default form submission

            var municipality = $('#municipality').val();
            var brgy_code = $('#brgy_code').val();
            var barangay = $('#barangay').val();
            var napcode = $('#napcode').val();
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var no_of_deployed = $('#no_of_deployed').val();
            var no_of_active = $('#no_of_active').val();
            var no_of_available = $('#no_of_available').val();

            // Check if input fields are empty
            if (!municipality || !brgy_code || !barangay || !napcode || !longitude || !latitude) {
                // Display a SweetAlert prompt message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all the fields.', // Prompt message
                });
                return; // Exit the function without submitting the form
            }

            $.ajax({
                url: '/portutilization', // Ensure this matches your route
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include the CSRF token in the headers
                },
                data: {
                    municipality: municipality,
                    brgy_code: brgy_code,
                    barangay: barangay,
                    napcode: napcode,
                    longitude: longitude,
                    latitude: latitude,
                    no_of_deployed: no_of_deployed,
                    no_of_active: no_of_active,
                    no_of_available: no_of_available,
                },
                success: function(response) {
                    console.log(response);
                    $('#portsform')[0].reset(); // Reset the form fields

                    fetchportutilizationdata(); // Fetch data and update table

                    fetchAndPinMarkers();

                    fetchtotalsumdeployed();
                    fetchtotalsumactive();
                    fetchtotalsumavailable();

                    fetchutilizationpercentage();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Port Utilization created successfully.',
                        showConfirmButton: false,
                        timer: 1500 // Automatically close after 1.5 seconds
                    });

                },
                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save Port Utilization. Please try again later.',
                    });
                    console.error(xhr.responseText);
                }
            });
        }

        // Fetch ports data for the edit modal
        function fetchstoredportsutilization(id) {
            console.log(id); // Debug: Check if the correct ID is being fetched

            $.ajax({
                url: '/portutilization/' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response); // Debug: Inspect the response data

                    var data = response.ports_utilization;
                    $('#editmunicipality').val(data.municipality);
                    $('#editbarangay').val(data.barangay);
                    $('#editbrgy_code').val(data.brgy_code);
                    $('#editnapcode').val(data.napcode);
                    $('#editlongitude').val(data.longitude);
                    $('#editlatitude').val(data.latitude);
                    $('#editno_of_deployed').val(data.no_of_deployed);
                    $('#editno_of_active').val(data.no_of_active);
                    $('#editno_of_available').val(data.no_of_available);
                    $('#editportsform').data('id', id);

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

        // Handle update button click event
        function update_portutilization() {
            event.preventDefault();
            var id = $('#editportsform').data('id'); // Retrieve the stored ID
            var municipality = $('#editmunicipality').val();
            var brgy_code = $('#editbrgy_code').val();
            var barangay = $('#editbarangay').val();
            var napcode = $('#editnapcode').val();
            var longitude = $('#editlongitude').val();
            var latitude = $('#editlatitude').val();
            var no_of_deployed = $('#editno_of_deployed').val();
            var no_of_active = $('#editno_of_active').val();
            var no_of_available = $('#editno_of_available').val();

            // Check if input fields are empty
            if (!municipality || !brgy_code || !barangay || !napcode || !longitude || !latitude) {
                // Display a SweetAlert prompt message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all the fields.', // Prompt message
                });
                return; // Exit the function without submitting the form
            }

            // AJAX request to update the building
            $.ajax({
                url: '/portutilization/' + id, // Adjust the URL based on your route
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Include the CSRF token in the headers
                },
                data: {
                    municipality: municipality,
                    brgy_code: brgy_code,
                    barangay: barangay,
                    napcode: napcode,
                    longitude: longitude,
                    latitude: latitude,
                    no_of_deployed: no_of_deployed,
                    no_of_active: no_of_active,
                    no_of_available: no_of_available,
                },
                success: function(response) {
                    console.log(response);

                    fetchportutilizationdata(); // Fetch data and update table

                    fetchAndPinMarkers();

                    fetchtotalsumdeployed();
                    fetchtotalsumactive();
                    fetchtotalsumavailable();

                    fetchutilizationpercentage();
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
                        text: 'Failed to update the building. Please try again later.',
                    });
                }
            });
        }

        function fetchAndPinMarkers() {
            if (!window.myMap) {
                // Initialize map centered on Mindanao
                window.myMap = L.map('map', {
                    fullscreenControl: true // Add fullscreen control
                }).setView([8.536, 125.139], 7); // Center map on Mindanao area

                // Add graphical tile layer (CartoDB Positron style)
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="https://carto.com/attributions">CartoDB</a>',
                    maxZoom: 19
                }).addTo(window.myMap);

                // Set max bounds to restrict panning to Mindanao area
                var bounds = [
                    [4.5, 120.5], // Southwest corner of Mindanao
                    [9.9, 126.5] // Northeast corner of Mindanao
                ];
                window.myMap.setMaxBounds(bounds);

                // Prevent zooming out beyond Mindanao
                window.myMap.on('zoomend', function() {
                    if (window.myMap.getZoom() < 7) {
                        window.myMap.setZoom(7);
                    }
                });

                // Restrict map panning within the bounds
                window.myMap.on('drag', function() {
                    window.myMap.panInsideBounds(bounds, {
                        animate: true
                    });
                });
            } else {
                // Resize map when modal is reopened
                setTimeout(() => {
                    window.myMap.invalidateSize();
                }, 200);
            }

            // Create a MarkerCluster group with a custom cluster icon
            var markers = L.markerClusterGroup({
                iconCreateFunction: function(cluster) {
                    var count = cluster.getChildCount();
                    return L.divIcon({
                        html: '<div class="custom-cluster"><span>' + count + '</span></div>',
                        className: '', // Clear default class
                        iconSize: [40, 40], // Size of the circle
                    });
                }
            });

            // Initialize counters for each availability status
            let lowAvailabilityCount = 0;
            let fullNapCount = 0;
            let highAvailabilityCount = 0;
            let totalMarkers = 0;

            // Make an AJAX request to fetch data from the server
            $.ajax({
                url: '/portutilization',
                method: 'GET',
                success: function(response) {
                    if (response && response.ports_utilization) {
                        response.ports_utilization.forEach(function(item) {
                            var latitude = parseFloat(item.latitude);
                            var longitude = parseFloat(item.longitude);

                            // Skip markers outside the Mindanao bounds
                            if (latitude < 4.5 || latitude > 9.5 || longitude < 121.5 || longitude >
                                127.5) {
                                return;
                            }

                            // Determine marker color based on availability logic
                            var markerColor = '#28a745'; // Default color (green for high availability)
                            if (item.no_of_available === 0) {
                                markerColor = '#ff0000'; // Red (Full Nap)
                                fullNapCount++;
                            } else if (item.no_of_available <= Math.ceil(item.no_of_deployed * 0.5)) {
                                markerColor = '#ffa500'; // Orange (Low availability)
                                lowAvailabilityCount++;
                            } else {
                                highAvailabilityCount++;
                            }

                            // Create a custom SVG marker icon
                            var customIcon = L.divIcon({
                                html: `
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30">
                                    <path fill="${markerColor}" d="M12 0C7.03 0 3 4.03 3 9c0 4.97 9 15 9 15s9-10.03 9-15c0-4.97-4.03-9-9-9zm0 12.5c-1.93 0-3.5-1.57-3.5-3.5S10.07 5.5 12 5.5s3.5 1.57 3.5 3.5S13.93 12.5 12 12.5z"></path>
                                </svg>
                            `,
                                className: '',
                                iconSize: [30, 30],
                                iconAnchor: [15, 30], // Anchor the icon at the tip of the pin
                            });

                            // Create a marker with the custom icon
                            var marker = L.marker([latitude, longitude], {
                                icon: customIcon
                            });

                            // Bind a popup to the marker with relevant data
                            marker.bindPopup(function() {
                                var popupContent = "<b>" + item.municipality + " - " + item
                                    .brgy_code + "</b><br>" +
                                    "Napcode: " + item.napcode + "<br>" +
                                    "<span style='color: #007bff;'>Deployed: " + item
                                    .no_of_deployed + "</span><br>" +
                                    "<span style='color: #ffc107;'>Active: " + item
                                    .no_of_active + "</span><br>" +
                                    "<span style='color: #28a745;'>Available: " + item
                                    .no_of_available + "</span>";

                                if (item.no_of_available === 0) {
                                    popupContent +=
                                        "<br><span style='color: red; font-weight: bold;'>Full Nap!</span>";
                                }

                                return popupContent;
                            });

                            // Add marker to the cluster group
                            markers.addLayer(marker);
                            totalMarkers++;
                        });

                        // Update the legend with percentages
                        updateLegend(totalMarkers, lowAvailabilityCount, fullNapCount, highAvailabilityCount);

                        // Add all markers to the map after the AJAX request is successful
                        window.myMap.addLayer(markers);
                    } else {
                        console.error("No port utilization data found in the response.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        // Function to update the availability legend with percentages using jQuery
        function updateLegend(totalMarkers, lowAvailabilityCount, fullNapCount, highAvailabilityCount) {
            let lowPercentage = ((lowAvailabilityCount / totalMarkers) * 100).toFixed(2);
            let fullNapPercentage = ((fullNapCount / totalMarkers) * 100).toFixed(2);
            let highPercentage = ((highAvailabilityCount / totalMarkers) * 100).toFixed(2);

            $('#mapLegend').html(`
                <b>Availability Legend:</b>
                <ul style="list-style: none; padding-left: 0; display: flex; gap: 20px;">
                    <li style="display: inline-flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #ffa500; margin-right: 5px;"></span>
                        Low Availability (<strong>${lowPercentage}%</strong>)
                    </li>
                    <li style="display: inline-flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #ff0000; margin-right: 5px;"></span>
                        Full Nap (No Available) (<strong>${fullNapPercentage}%</strong>)
                    </li>
                    <li style="display: inline-flex; align-items: center;">
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: #28a745; margin-right: 5px;"></span>
                        High Availability (<strong>${highPercentage}%</strong>)
                    </li>
                </ul>
            `);
        }


        function fetchtotalsumdeployed() {
            // Fetch total deployed ports
            $.ajax({
                url: '/total-deployed-ports',
                method: 'GET',
                success: function(response) {
                    // Update the card with the total deployed ports
                    $('#deployed_ports_count').text(response.total_deployed);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching total deployed ports:", error);
                }
            });
        }

        function fetchtotalsumactive() {
            // Fetch total deployed ports
            $.ajax({
                url: '/total-active-ports',
                method: 'GET',
                success: function(response) {
                    // Update the card with the total deployed ports
                    $('#active_ports_count').text(response.total_active);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching total deployed ports:", error);
                }
            });
        }

        function fetchtotalsumavailable() {
            // Fetch total deployed ports
            $.ajax({
                url: '/total-available-ports',
                method: 'GET',
                success: function(response) {
                    // Update the card with the total deployed ports
                    $('#available_ports_count').text(response.total_available);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching total deployed ports:", error);
                }
            });
        }

        function fetchutilizationpercentage() {
            // Fetch utilization percentage
            $.ajax({
                url: '/utilization-percentage', // Update with the new route URL
                method: 'GET',
                success: function(response) {
                    if (response.utilization_percentage !== undefined) {
                        // Update the card with the fetched percentage
                        $('#utilization_percentage').text(response.utilization_percentage + '%');
                    } else {
                        console.error('Utilization percentage not available.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching utilization percentage:', error);
                }
            });
        }

        // Fetch data based on selected filters
        function fetchportutilizationdata() {
            // Get filter values
            let municipality = $('#filtermunicipality').val();
            let barangay = $('#filterbarangay').val();
            let brgyCode = $('#filterbrgycode').val();

            console.log(municipality, barangay, brgyCode); // Check if filter values are correct

            $.ajax({
                url: '/portutilization',
                type: 'GET',
                data: {
                    municipality: municipality,
                    barangay: barangay,
                    brgy_code: brgyCode
                },
                success: function(response) {
                    console.log(response, 'portutilization1'); // Check if the response is correct
                    renderportutilization(response.ports_utilization);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        // Initialize Select2 for filter dropdowns
        $('#filtermunicipality, #filterbarangay, #filterbrgycode').select2({
            width: '100%',

        });
    </script>
@endpush
