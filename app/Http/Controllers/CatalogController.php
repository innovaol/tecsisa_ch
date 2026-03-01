<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\System;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $locations = Location::with('parent')->get();
        $systems = System::all();
        $equipments = Equipment::with(['location', 'system'])->get();

        return view('catalog.index', compact('locations', 'systems', 'equipments'));
    }
}
