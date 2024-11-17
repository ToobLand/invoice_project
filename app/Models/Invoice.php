<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Invoice extends AbsData
{
    protected $table="invoice";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'number'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'id_customer'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'payment_link'=> ['type'=>'string','required'=>false,'default'=>false,'encrypt'=>0],
        'file_link'=> ['type'=>'string','required'=>false,'default'=>'','encrypt'=>0],
        'payed'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'date_send'=> ['type'=>'date','required'=>true,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'id_offer'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'price_incl_btw'=> ['type'=>'float','required'=>false,'default'=>0,'encrypt'=>0],
        'price_excl_btw'=> ['type'=>'float','required'=>false,'default'=>0,'encrypt'=>0]
    ];
    public function get_total_customer_offer($id_customer,$id_offer){
        $conn=new Mysql();
        $result=$conn->fetchData("SELECT CAST(SUM(price_incl_btw) as decimal(12,2)) AS som FROM `{$this->table}` WHERE id_customer=? AND id_offer=? AND id_user=?",[$id_customer,$id_offer,$_SESSION['user']->id]);
        return $result[0]['som'];
    }
   
}