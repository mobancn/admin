<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{
   	
	use SoftDelete;

	protected $autoWriteTimestamp=true;

	protected $dateFormat = "Y/m/d";

	protected $insert=['status'=>1];

	/*
	一对多关联，定义相对的关联 ，即文章表关联
	 */
	public function cate()
    {
    	return $this -> belongsTo('cate');
    }

    /*
	一对多关联，定义相对的关联 ，即文章表关联
	 */
	public function tag()
    {
    	return $this -> belongsTo('tag');
    }
}
