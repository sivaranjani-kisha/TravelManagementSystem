<?php

namespace App\Controllers;

use App\Models\RouteModel;

class RouteController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('routes/index');
    }

  public function list()
{
    $model = new RouteModel();

    $page  = $this->request->getGet('page') ?? 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $data = $model->orderBy('id','DESC')
                  ->findAll($limit, $offset);

    $total = $model->countAll();

    return $this->response->setJSON([
        'data'  => $data,
        'total' => $total,
        'limit' => $limit
    ]);
}


    public function save()
    {
        $model = new RouteModel();
        $id = $this->request->getPost('id');

        $data = [
            'route_name' => $this->request->getPost('route_name')
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
        $model = new RouteModel();
        return $this->response->setJSON($model->find($id));
    }

    public function delete($id)
    {
        $model = new RouteModel();
        $model->delete($id);
        return $this->response->setJSON(['status'=>'deleted']);
    }
}
