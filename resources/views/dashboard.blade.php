@extends('layouts.main')


@section('content')
<div class="content mt-4">
    <!-- Cards Section -->
    <div class=" row justify-content-center">
        <!-- Active Materials Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow" style="background-color: #28a745; color: white;">
                <div class="card-body">
                    <h5 class="card-title">Active Materials</h5>
                    <p class="card-text" style="font-size: 2rem;">150</p>
                </div>
            </div>
        </div>

        <!-- Inactive Materials Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow" style="background-color: #dc3545; color: white;">
                <div class="card-body">
                    <h5 class="card-title">Inactive Materials</h5>
                    <p class="card-text" style="font-size: 2rem;">75</p>
                </div>
            </div>
        </div>

        <!-- Total Materials Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow" style="background-color: #17a2b8; color: white;">
                <div class="card-body">
                    <h5 class="card-title">Total Materials</h5>
                    <p class="card-text" style="font-size: 2rem;">225</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row justify-content-center mt-2">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span style="font-size: 1.1rem; font-weight: bold;">Stocks Inventory Chart</span>
                    <select name="year" id="year" class="form-select mb-3" aria-label="Year" style="width: 100px;">
                        <option>2024</option>
                        <option>2023</option>
                        <option>2022</option>
                    </select>
                </div>

                <div class="card-body">
                    <!-- Chart.js canvas with adjusted size -->
                    <canvas id="myChart" width="1100" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts') <!-- Assuming your layout has a section for additional scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js example code to create a chart comparing Actual vs Target
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // 'bar' for bar chart, change to 'line' for a line chart
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'], // Labels for months or categories
            datasets: [{
                label: 'Target', // Target data label
                data: [15, 20, 10, 25, 30, 18], // Target values
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color for the target bars
                borderColor: 'rgb(9, 188, 24)', // Border color for target bars
                borderWidth: 1
            }, {
                label: 'Achieved', // Achieved data label
                data: [12, 19, 3, 5, 2, 3], // Achieved values
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color for the achieved bars
                borderColor: 'rgba(54, 162, 235, 1)', // Border color for achieved bars
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Ensures that the Y-axis starts from zero
                }
            }
        }
    });
</script>
@endpush
