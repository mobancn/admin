<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Student extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp =true;

    protected $dateFormat = "Y/m/d";

    protected $delete_time="delete_time";

    protected $type =["start_time"=>"timestamp"];

    protected function getSexAttr($value)
    {
    	// 旧的获取数据方式
        // $sex=[1=>"男",0=>"女"];
    	// return $sex[$value];
        
        //新的获取数据方式,相应模板的数据
        $text = [1=>"男",0=>"女"];
        return ['val'=> $value,'text' => $text[$value]];
        

    }

    public function grade()
    {
    	return $this -> belongsTo('grade');
    }
    
}
