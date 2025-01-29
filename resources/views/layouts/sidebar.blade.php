<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sidebar Example</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
            }

            /* Common Styles for Sidebar and Navbar */
            .common-bg {
                background-color: #343a40;
                color: white;
            }

            .sidebar {
                height: 100vh;
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                padding-top: 20px;
            }

            .sidebar img {
                width: 150px;
                height: 150px;
                object-fit: cover;
                border-radius: 50%;
                border: 3px solid white;
                margin: 0 auto 30px auto;
            }

            .sidebar .nav-item {
                width: 100%;
            }

            .sidebar .nav-link {
                color: white;
                font-size: 18px;
                display: flex;
                align-items: center;
                padding: 10px 14px;
                text-decoration: none;
                transition: background-color 0.3s;
            }

            .sidebar .nav-link:hover {
                color: white;
                background-color: #495057;
            }

            .sidebar .nav-link.active {
                background-color: #007bff;
                color: white;
            }

            .sidebar .nav-link i {
                font-size: 20px;
                margin-right: 10px;
            }

            /* Adjust Content Area */
            .content {
                margin-left: 250px;
                padding: 20px;
            }

            @media (max-width: 768px) {
                .sidebar {
                    width: 200px;
                }

                .content {
                    margin-left: 0;
                }
            }
        </style>
    </head>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <body>

        <!-- Sidebar -->
        <div class="sidebar common-bg">
            <div class="d-flex justify-content-center">
                <img src="{{ secure_asset('images/Meta3.jpg') }}" alt="Logo">
            </div>
            <hr>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        aria-label="Go to dashboard">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('stock') }}" class="nav-link {{ request()->routeIs('stock') ? 'active' : '' }}"
                        aria-label="View stocks">
                        <i class="fas fa-box"></i><span>Stocks</span>
                    </a>
                </li>

                <!-- Reports Dropdown -->
                <li class="nav-item">
                    <a href="#reportsSubmenu" class="nav-link d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle reports menu">
                        <span><i class="fas fa-chart-line"></i> Reports</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="collapse list-unstyled ps-3" id="reportsSubmenu">
                        <li>
                            <a href="{{ route('materials_inventory_reports') }}"
                                class="nav-link {{ request()->routeIs('materials_inventory_reports') ? 'active' : '' }}"
                                aria-label="View materials inventory reports">
                                <i class="fas fa-file-alt"></i><span>Materials Inventory Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teamtech_inventory_reports') }}"
                                class="nav-link {{ request()->routeIs('teamtech_inventory_reports') ? 'active' : '' }}"
                                aria-label="View teamtech inventory report">
                                <i class="fas fa-file-invoice"></i><span>Teamtech Inventory Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stocks_inventory_reports') }}"
                                class="nav-link {{ request()->routeIs('stocks_inventory_reports') ? 'active' : '' }}"
                                aria-label="View stocks inventory report">
                                <i class="fas fa-file-invoice"></i><span>Stocks Inventory Report</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
