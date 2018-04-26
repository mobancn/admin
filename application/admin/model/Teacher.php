<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Teacher extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp =true;

    protected $dateFormat = "Y/m/d";

    protected $delete_time="delete_time";

    // 定义自动完成的属性
    protected $insert = ['status' => 1];
    // 类型转换为时间或其他数据类型
    protected $type = [
        'hiredate'=>'timestamp'
    ];

    public function grade()
    {
    	return $this -> belongsTo('grade');
    }

    //数据修改器
    public function getDegreeAttr($value)
    {
    	$degree=[
    		1=>'专科',
            2=>'本科',
            3=>'研究生'
    	];
    	//根据表中数据返回对应值
    	return $degree[$value];
    }

   

}
