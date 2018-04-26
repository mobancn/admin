<?php

namespace app\admin\model;
use think\Collection;
use think\Model;
use traits\model\SoftDelete;

class Cate extends Model
{
    //使用软删除必须要添加create_time 和 update_time 和 delete_time 这三个字段，不然不能实现些功能。
    use SoftDelete;

    protected $autoWriteTimestamp=true;

    protected $delete_time='delete_time';

    protected $insert = [
        'cate_order' => 0
    ];

    //所有数据的树状结构的实现
    public static function getCate($pid=0, &$result=[], $blank=0)
    {
        //1.分类表查询:$pid
        $res = self::all(['pid'=>$pid]);

        //2.自定义分类名称前面的提示信息
        $blank += 2;

        //3.遍历分类表
        foreach ($res as $key => $value) {

            //3-1自定义分类名称的显示格式
            $cate_name = '|--'.$value->cate_name;
            $value->cate_name = str_repeat('&nbsp;',$blank).$cate_name;

            //3-2将查询到的当前记录保存到结果$result中
            $result[] = $value;

            //3-3关键:将当前记录的id，做为下一级分类的父id,$pid，继续递归调用本方法
            self::getCate($value->id, $result, $blank);
        }

        //4.返回查询结果,调用结果集类make方法打包当前结果,转为二维数组返回
        return Collection::make($result)->toArray();
    }


    //指定数据树状结构的实现
    public static function tree($res,$pid=0,$blank=0)
    {
        static $result=[];

        //2.自定义分类名称前面的提示信息
        $blank += 2;

        //3.遍历分类表
        foreach ($res as $key => $value) {

            //循环由0（即最顶层向下）开始查询
            if($value['pid']==$pid)
            {
                $cate_name = '|--'.$value->cate_name;
                $value->cate_name = str_repeat('&nbsp;',$blank).$cate_name;

                //3-2将查询到的当前记录保存到结果$result中
                $result[] = $value;
                self::tree($res,$value->id,$blank);
            }
 
        }

        //4.返回查询结果,调用结果集类make方法打包当前结果,转为二维数组返回
        return Collection::make($result)->toArray();
    }



    /**
     * 查找所属所有分类
     */
    public static function getCateData($id)
    {
        
        
        //查询所有子集的id
        $ids=self::getSon($id);
        //添加自己的id
        $ids[]=$id;
        //查询所有这些id的数据,
        // $res=self::all($ids);
        //查询上述id 以外的id数
        $res=self::where('id','not in',$ids)->select();
        

        return $res;

    }


    /**
     * 递归找子集
     **/
     
    public static  function getSon($id)
    {
        //初始化数组
        static $temp=[];
        $res=self::all();
        foreach ($res as  $v) {
            if($v['pid']==$id){
                $temp[]=$v['id'];
                self::getSon($v['id']);
            }
        }

        return $temp;

    }

  //   public function banner()
  // {
  //   return $this ->hasMany('banner');
  // }
   
  /**
   * 这是一对多关联查询  即一个分类，对应多遍文章、
   */
  public function article()
  {
    return $this ->hasMany('article');
  }
   
}
