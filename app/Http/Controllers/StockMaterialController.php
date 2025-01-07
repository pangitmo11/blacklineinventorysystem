<?php

namespace App\Http\Controllers\registrar;

use App\Http\Controllers\Controller;
use App\Models\StockMaterial;
use App\Models\StocksLevel;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockMaterialController extends Controller
{
    public function index()
    {
        $stocksmaterial = StockMaterial::with(['stocksdescLevel:id,description'])
            ->get();

        return response()->json(['stocksmaterial' => $stocksmaterial]);
    }

}

