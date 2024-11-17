<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Customer extends AbsData
{
    protected $table="customer";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'firstname'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'middlename'=> ['type'=>'string','required'=>false,'default'=>false,'encrypt'=>0],
        'lastname'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'street'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'housenumber'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'postalcode'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'city'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'country'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'email'=> ['type'=>'email','required'=>true,'default'=>false,'encrypt'=>1],
        'telephone'=> ['type'=>'string','required'=>false,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0]
    ];
}