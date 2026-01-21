<?php
namespace App\Models;

use CodeIgniter\Model;

class PickupModel extends Model
{
    protected $table = 'pickup_points';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pickup_name','description'];
    
}
