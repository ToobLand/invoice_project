<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Hour extends AbsData
{
    protected $table="hour";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'id_offer'=> ['type'=>'int','required'=>true,'default'=>0,'encrypt'=>0],
        'id_customer'=> ['type'=>'int','required'=>true,'default'=>0,'encrypt'=>0],
        'hour'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'date'=> ['type'=>'date','required'=>true,'default'=>false,'encrypt'=>0]
    ];
    public function get_total_customer_offer($id_customer,$id_offer){
        $conn=new Mysql();
        $result=$conn->fetchData("SELECT SUM(hour) AS som FROM `{$this->table}` WHERE id_customer=? AND id_offer=? AND id_user=?",[$id_customer,$id_offer,$_SESSION['user']->id]);
        return $result[0]['som'];
    }
}