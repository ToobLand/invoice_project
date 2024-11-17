<?php 
namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;

class Costcategory extends AbsData
{
    
    protected $table="costcategory";
    protected $fields=[
        'id'=> ['type'=>'int','required'=>false,'default'=>false,'encrypt'=>0],
        'title'=> ['type'=>'string','required'=>true,'default'=>false,'encrypt'=>0]
    ];
    public function read()
    {
        $conn=new Mysql();

        return $this;
    }

    
}