<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Invoicepost extends AbsData
{
    
    protected $table="invoicepost";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'id_invoice'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'title'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'amount'=> ['type'=>'string','required'=>false,'default'=>'1','encrypt'=>0],
        'price_incl_btw'=> ['type'=>'float','required'=>true,'default'=>false,'encrypt'=>0],
        'price_excl_btw'=> ['type'=>'float','required'=>true,'default'=>false,'encrypt'=>0],
        'btw'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'position'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0]
    ];
}