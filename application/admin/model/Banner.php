<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Banner extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp=true;
    
    protected $dateFormat = "Y/m/d";

    protected $insert = [
        'status' => 1
    ];

    public function cate()
    {
    	return $this -> belongsTo('cate');
    }

}
