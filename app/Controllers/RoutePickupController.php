<?php

namespace App\Controllers;

use App\Models\RouteModel;
use App\Models\PickupModel;
use CodeIgniter\Database\Config;

class RoutePickupController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['routes']  = (new RouteModel())->findAll();
        $data['pickups'] = (new PickupModel())->findAll();

        return view('route_pickups/index', $data);
    }

    public function save()
    {
        $db = Config::connect();

        $route_id   = $this->request->getPost('route_id');
        $pickup_ids = $this->request->getPost('pickup_ids');

        // delete old mapping
        $db->table('route_pickups')->where('route_id', $route_id)->delete();

        // insert new mapping
        foreach ($pickup_ids as $pid) {
            $db->table('route_pickups')->insert([
                'route_id'  => $route_id,
                'pickup_id' => $pid
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

   public function list()
{
    $db = \Config\Database::connect();

    $page  = $this->request->getGet('page') ?? 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $data = $db->query("
        SELECT r.id, r.route_name,
               GROUP_CONCAT(p.pickup_name SEPARATOR ', ') AS pickups
        FROM routes r
        JOIN route_pickups rp ON rp.route_id = r.id
        JOIN pickup_points p ON p.id = rp.pickup_id
        GROUP BY r.id
        ORDER BY r.id DESC
        LIMIT $limit OFFSET $offset
    ")->getResult();

    $total = $db->query("
        SELECT COUNT(DISTINCT route_id) AS total
        FROM route_pickups
    ")->getRow()->total;

    return $this->response->setJSON([
        'data'  => $data,
        'total' => $total,
        'limit' => $limit
    ]);
}

    public function delete($route_id)
    {
        $db = Config::connect();
        $db->table('route_pickups')->where('route_id', $route_id)->delete();

        return $this->response->setJSON(['status'=>'deleted']);
    }
}
