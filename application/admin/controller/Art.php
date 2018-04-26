<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Base;
use app\admin\model\Article;
use app\admin\model\Cate;
use app\admin\model\Tag;

class Art extends Base
{
     //渲染资源列表界面
    public function tlist(Request $request)
    {

        $list= Article::all();
        //关联分类数据，并传入cate 这个字段
        foreach ($list as $value) {
            $value -> cate = $value -> cate -> cate_name;
        }

        $this -> view -> assign('list',$list);

        
        return $this -> view -> fetch('list');
    }

    //渲染添加界面.
    public function tadd(Request $request)
    {   
        $cate=Cate::getCate();
        $this -> view -> assign('cate',$cate);
        $tag=Tag::all();
        $this -> view -> assign('tag',$tag);

        return $this -> view -> fetch('add');
    }

    //渲染编辑界面
    public function tedit(Request $request)
    {
        $id = $request -> param('id');
        $res = Article::get($id);

        
        //给当前编辑页面模板赋值
        $this->view->assign('info',$res);

        //将标签字符串变为数组，并用in_array()函数查询里面的数据
        $tag1=explode(',',$res['tag']);
        $this->view->assign('tag1',$tag1);

        $cate=Cate::getCate();
        $this -> view -> assign('cate',$cate);
        $tag=Tag::all();
        $this -> view -> assign('tag',$tag);


        //渲染出当前的编辑模板
        return $this->view->fetch('edit');
    }

    //渲染回收站界面.
    public function recycle(Request $request)
    {   
        $list= Article::onlyTrashed()->select();
        $this -> view -> assign('list',$list);
        return $this -> view -> fetch('recycle');
    }


    //添加
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data=$request -> param();
        
        $res = implode(",",$data['tag1']);

        //二维数据添加一个元素
        $data['tag']=$res;

        //删除二维数组一个元素
        unset($data['tag1']);

        $result = Article::create($data);
    }
  

    //更新
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request -> param();

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = Article::update($data,$condition);
    }


    //软删除操作
    public function tdelete(Request $request)
    {
        $id = $request -> param('id');
        Article::update(['is_delete'=>1],['id'=> $id]);
        Article::destroy($id);
    }

    //彻底删除操作
    public function ydelete(Request $request)
    {
        $id = $request -> param('id');
        Article::destroy($id,true);
    }

    //彻底批量删除操作
    public function delall(Request $request)
    {
        $id = $request -> param('id');
        Article::destroy($id,true);
    }

    //清空操作
    public function delso()
    {
        Article::destroy(['is_delete'=>1],true);
    }


    //恢复所有操作
    public function unDelete()
    {
        Article::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

    //单个恢复
    public function undel(Request $request)
    {
        $id = $request -> param('id');
        Article::update(['delete_time'=>NULL,'is_delete'=>NULL],['id'=>$id]);
    }

    //批量恢复
    public function undelall(Request $request)
    {
        $id = $request -> param('id');
        //接收字符串，分为数组
        $del_arr=explode(',', $id);
        //遍历数组，更新数据
        foreach ($del_arr as $value) {
            Article::update(['delete_time'=>NULL,'is_delete'=>NULL],['id'=>$value]);
        }

        
    }

    //状态变更
    public function setStatus(Request $request)
    {
        //获取当前的班级ID
        $id = $request -> param('id');

        //查询
        $res = Article::get($id);

        //启用和禁用处理
        if($res->getData('status') == 1) {
            Article::update(['status'=>0],['id'=>$id]);
        } else {
            Article::update(['status'=>1],['id'=>$id]);
        }
    }

    //批量软删除
    public function del(Request $request)
    {   
        $del_id=$request -> param('id');
        //接收字符串，分为数组
        $del_arr=explode(',', $del_id);
        //遍历数组，更新数据
        foreach ($del_arr as $value) {
            Article::update(['is_delete'=>1],['id'=>$value]);
        }
        //直接软删除操作
        Article::destroy($del_id);      
    }

    //上传 图片
     public function savePic(Request $request)
    {
         // 获取表单上传文件
        $files = $request->file();
        foreach($files as $file){
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            // echo $info->getExtension(); 
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo date('Ymd').'/';
            echo $info->getFilename(); 
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
        }

    }


    //修改文章排序
    public function changeSort(Request $request)
    {
        $id=$request -> param('id');
        $sort=$request -> param('sort');
        Article::update(['sort'=>$sort],['id'=>$id]);
    }

    //测试
    public function test()
    {   
        $cate=Cate::getCate();
        $this -> view -> assign('cate',$cate);
        $tag=Tag::all();
        $this -> view -> assign('tag',$tag);
        return $this -> view -> fetch('test');
    }

}
