<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Cost extends AbsData
{
    protected $table="cost";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'price'=> ['type'=>'float','required'=>true,'default'=>false,'encrypt'=>0],
        'price_excl_btw'=> ['type'=>'float','required'=>false,'default'=>0,'encrypt'=>0],
        'btw'=> ['type'=>'int','required'=>true,'default'=>21,'encrypt'=>0],
        'description'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'image'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'id_costcategory'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'date'=> ['type'=>'date','required'=>true,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'id_customer'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'id_offer'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'write_off'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'write_off_years'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'write_off_rest'=> ['type'=>'float','required'=>false,'default'=>0,'encrypt'=>0]
    ];
    public function get_total_customer_offer($id_customer,$id_offer){
        $conn=new Mysql();
        $result=$conn->fetchData("SELECT CAST(SUM(price) as decimal(12,2)) AS som FROM `{$this->table}` WHERE id_customer=? AND id_offer=? AND id_user=?",[$id_customer,$id_offer,$_SESSION['user']->id]);
        return $result[0]['som'];
    }
}