<?php

namespace app\admin\model;

use traits\model\SoftDelete;
use think\Model;

class Tag extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp =true;
    // 定义自动完成的属性
    protected $insert = ['status' => 1];
    //格式化时间
    protected $dateFormat = "Y/m/d";

    //一对多关联数据
    protected function article()
    {
    	return $this -> hasMany('article');
    }
}
