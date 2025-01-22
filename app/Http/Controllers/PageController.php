<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function stocks_page()
    {
        return view('pages.stock');  // This should return the page view, not JSON
    }

    public function port_utilization()
    {
        return view('pages.port_utilization');
    }

    public function reports()
    {
        return view('pages.reports');
    }

    public function stocks_inventory_reports()
    {
        return view('pages.stocks_inventory_report');
    }
}

