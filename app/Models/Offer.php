<?php 
namespace App\Models;

use App\Database\AbsData;

class Offer extends AbsData
{
    protected $table="offer";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>0,'encrypt'=>0],
        'title'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0],
        'google_id'=> ['type'=>'string','required'=>false,'default'=>'','encrypt'=>0],
        'id_customer'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0],
        'id_user'=> ['type'=>'int','required'=>true,'default'=>false,'encrypt'=>0]
    ];
}