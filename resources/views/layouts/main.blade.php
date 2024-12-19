<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @stack('styles') <!-- For any additional styles -->
</head>
<body>
    @include('layouts.sidebar') <!-- Assuming sidebar is included -->
    @include('layouts.navbar') <!-- Assuming navbar is included -->

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts') <!-- For any additional scripts -->


</body>
</html>
