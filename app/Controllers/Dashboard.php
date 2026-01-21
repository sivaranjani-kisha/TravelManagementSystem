<?php

namespace App\Controllers;

use App\Models\RouteModel;
use App\Models\VehicleModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Session check (login protection)
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $routeModel   = new RouteModel();
        $vehicleModel = new VehicleModel();

        $data['routeCount']   = $routeModel->countAll();
        $data['vehicleCount'] = $vehicleModel->countAll();

        return view('dashboard/index', $data);
    }
}
