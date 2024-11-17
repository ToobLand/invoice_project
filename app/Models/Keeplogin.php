<?php 
namespace App\Models;

use App\Database\AbsData;

class Keeplogin extends AbsData
{
    protected $table="keeplogin";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'device'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'hash'=> ['type'=>'string','required'=>false,'default'=>'','encrypt'=>0],
        'date'=> ['type'=>'date','required'=>true,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0]
    ];
}