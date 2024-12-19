<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reuse Sidebar Styles */
        .common-bg {
            background-color: #343a40;
            color: white;
        }

        .navbar {
            width: calc(100% - 250px); /* Adjust width to fit the sidebar */
            margin-left: 250px; /* Align with the sidebar */
            position: fixed;
            top: 0;
            z-index: 1000;
            height: 56px; /* Height of the navbar */
        }

        .content {
            margin-top: 56px; /* Margin to push the content below the navbar */
        }

        .navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .navbar .navbar-nav .nav-link {
            font-size: 1rem;
            color: white;
        }

        .navbar .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        .content {
            padding-top: 60px; /* Space for the fixed navbar */
        }

        /* Logo styling */
        .navbar .nav-item img {
            width: 40px;  /* Adjust size of the logo */
            height: 40px;
            border-radius: 50%;
            border: 2px solid white; /* White outline */
        }

        @media (max-width: 768px) {
            .navbar {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg common-bg">
        <div class="container-fluid">
            <!-- Dynamically display the page title using Laravel's request() helper -->
            <a class="navbar-brand" href="#">{{ ucfirst(request()->route()->getName()) }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <!-- Logo inside a circle with white outline -->
                        <a class="nav-link" href="#">
                            <img src="{{ asset('images/user.jpg') }}" alt="Logo">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
