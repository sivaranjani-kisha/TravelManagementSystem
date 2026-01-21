<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\RouteModel;
use CodeIgniter\Database\Config;

class VehicleController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        $data['routes'] = (new RouteModel())->findAll();
        return view('vehicles/index', $data);
    }

    public function list()
{
    $db = Config::connect();

    $page  = $this->request->getGet('page') ?? 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $data = $db->query("
        SELECT v.id, v.vehicle_name, v.driver_name, v.vehicle_image,
               GROUP_CONCAT(r.route_name SEPARATOR ', ') AS routes
        FROM vehicles v
        LEFT JOIN vehicle_routes vr ON vr.vehicle_id = v.id
        LEFT JOIN routes r ON r.id = vr.route_id
        GROUP BY v.id
        ORDER BY v.id DESC
        LIMIT $limit OFFSET $offset
    ")->getResult();

    $total = $db->query("SELECT COUNT(*) AS total FROM vehicles")
                ->getRow()->total;

    return $this->response->setJSON([
        'data'  => $data,
        'total' => $total,
        'limit' => $limit
    ]);
}


    public function save()
    {
        $db = Config::connect();
        $vehicleModel = new VehicleModel();

        $id = $this->request->getPost('id');

        // image upload
        $imgName = null;
        $file = $this->request->getFile('vehicle_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imgName = $file->getRandomName();
            $file->move('public/uploads/vehicles', $imgName);
        }

        $data = [
            'vehicle_name' => $this->request->getPost('vehicle_name'),
            'driver_name'  => $this->request->getPost('driver_name'),
        ];
        if ($imgName) $data['vehicle_image'] = $imgName;

        if ($id) {
            $vehicleModel->update($id, $data);
            $db->table('vehicle_routes')->where('vehicle_id', $id)->delete();
            $vehicleId = $id;
        } else {
            $vehicleId = $vehicleModel->insert($data);
        }

        $routes = (array)$this->request->getPost('route_ids');
        foreach ($routes as $rid) {
            $db->table('vehicle_routes')->insert([
                'vehicle_id' => $vehicleId,
                'route_id'   => $rid
            ]);
        }

        return $this->response->setJSON(['status'=>'success']);
    }

    public function delete($id)
    {
        $db = Config::connect();
        $db->table('vehicle_routes')->where('vehicle_id', $id)->delete();
        (new VehicleModel())->delete($id);
        return $this->response->setJSON(['status'=>'deleted']);
    }
}
