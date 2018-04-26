<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Grade extends Model
{

  use SoftDelete;

  protected $autoWriteTimestamp=true;

  protected $dateFormat='Y/m/d';

  protected $delete_time='delete_time';

  // 定义自动完成的属性
  protected $insert = ['status' => 1];


  public function Teacher()
  {
  	return $this ->hasOne('Teacher');
  }

  public function student()
  {
  	return $this ->hasMany('student');
  }
   
}
