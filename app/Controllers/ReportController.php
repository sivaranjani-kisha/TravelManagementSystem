<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;
use App\Models\VehicleModel;
use App\Models\RouteModel;

class ReportController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['vehicles'] = (new VehicleModel())->findAll();
        $data['routes']   = (new RouteModel())->findAll();

        return view('reports/index', $data);
    }

   public function fetch()
{
    $db = Config::connect();

    $vehicleIds = (array)$this->request->getPost('vehicle_ids');
    $routeIds   = (array)$this->request->getPost('route_ids');
    $driver     = $this->request->getPost('driver');

    $page  = $this->request->getPost('page') ?? 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $where = " WHERE 1=1 ";

    if (!empty($vehicleIds)) {
        $where .= " AND v.id IN (" . implode(',', array_map('intval',$vehicleIds)) . ")";
    }
    if (!empty($routeIds)) {
        $where .= " AND r.id IN (" . implode(',', array_map('intval',$routeIds)) . ")";
    }
    if (!empty($driver)) {
        $where .= " AND v.driver_name LIKE '%" . esc($driver) . "%'";
    }

    $data = $db->query("
        SELECT v.vehicle_name, v.driver_name,
               GROUP_CONCAT(DISTINCT r.route_name SEPARATOR ', ') AS routes,
               GROUP_CONCAT(DISTINCT p.pickup_name SEPARATOR ', ') AS pickups
        FROM vehicles v
        LEFT JOIN vehicle_routes vr ON vr.vehicle_id = v.id
        LEFT JOIN routes r ON r.id = vr.route_id
        LEFT JOIN route_pickups rp ON rp.route_id = r.id
        LEFT JOIN pickup_points p ON p.id = rp.pickup_id
        $where
        GROUP BY v.id
        ORDER BY v.id DESC
        LIMIT $limit OFFSET $offset
    ")->getResult();

    $total = $db->query("
        SELECT COUNT(DISTINCT v.id) AS total
        FROM vehicles v
        LEFT JOIN vehicle_routes vr ON vr.vehicle_id = v.id
        LEFT JOIN routes r ON r.id = vr.route_id
        $where
    ")->getRow()->total;

    return $this->response->setJSON([
        'data'  => $data,
        'total' => $total,
        'limit' => $limit
    ]);
}

}
