<?php

namespace App\Controllers;

use App\Models\PickupModel;

class PickupController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('pickups/index');
    }

   public function list()
{
    $model = new PickupModel();

    $page  = $this->request->getGet('page') ?? 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $data = $model->orderBy('id','DESC')
                  ->findAll($limit, $offset);

    $total = $model->countAll();

    return $this->response->setJSON([
        'data' => $data,
        'total' => $total,
        'limit' => $limit
    ]);
}

    public function save()
    {
        $model = new PickupModel();
        $id = $this->request->getPost('id');

        $data = [
            'pickup_name' => $this->request->getPost('pickup_name'),
            'description' => $this->request->getPost('pickupdesc'),
        ];

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }

        return $this->response->setJSON(['status'=>'success']);
    }

    public function edit($id)
    {
        $model = new PickupModel();
        return $this->response->setJSON($model->find($id));
    }

    public function delete($id)
    {
        $model = new PickupModel();
        $model->delete($id);
        return $this->response->setJSON(['status'=>'deleted']);
    }
}
