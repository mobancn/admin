<?php

namespace app\admin\controller;

use app\admin\model\Tag as Tags;
use app\admin\controller\Base;
use think\Controller;
use think\Request;

class Tag extends Base
{
    //渲染资源列表界面
    public function tlist(Request $request)
    {

        $list= Tags::all();
        $this -> view -> assign('list',$list);

        
        return $this -> view -> fetch('list');
    }

    //渲染添加界面.
    public function tadd(Request $request)
    {   
        // dump($request->param());
        return $this -> view -> fetch('add');
    }

    //渲染编辑界面
    public function tedit(Request $request)
    {
        $id = $request -> param('id');
        $res = Tags::get($id);

        //给当前编辑页面模板赋值
        $this->view->assign('info',$res);

        //渲染出当前的编辑模板
        return $this->view->fetch('edit');
    }

    //渲染回收站界面.
    public function recycle(Request $request)
    {   
        $list= Tags::onlyTrashed()->select();
        $this -> view -> assign('list',$list);
        return $this -> view -> fetch('recycle');
    }


    //添加
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //更新当前记录
        $result = Tags::create($data);
    }
  

    //更新
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request -> param();

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = Tags::update($data,$condition);
    }


    //软删除操作
    public function tdelete(Request $request)
    {
        $id = $request -> param('id');
        Tags::update(['is_delete'=>1],['id'=> $id]);
        Tags::destroy($id);
    }

    //彻底删除操作
    public function ydelete(Request $request)
    {
        $id = $request -> param('id');
        Tags::destroy($id,true);
    }

    //彻底批量删除操作
    public function delall(Request $request)
    {
        $id = $request -> param('id');
        Tags::destroy($id,true);
    }

    //清空操作
    public function delso()
    {
        Tags::destroy(['is_delete'=>1],true);
    }


    //恢复所有操作
    public function unDelete()
    {
        Tags::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

    //单个恢复
    public function undel(Request $request)
    {
        $id = $request -> param('id');
        Tags::update(['delete_time'=>NULL,'is_delete'=>NULL],['id'=>$id]);
    }

    //批量恢复
    public function undelall(Request $request)
    {
        $id = $request -> param('id');
        //接收字符串，分为数组
        $del_arr=explode(',', $id);
        //遍历数组，更新数据
        foreach ($del_arr as $value) {
            Tags::update(['delete_time'=>NULL,'is_delete'=>NULL],['id'=>$value]);
        }

        
    }

    //状态变更
    public function setStatus(Request $request)
    {
        //获取当前的班级ID
        $id = $request -> param('id');

        //查询
        $res = Tags::get($id);

        //启用和禁用处理
        if($res->getData('status') == 1) {
            Tags::update(['status'=>0],['id'=>$id]);
        } else {
            Tags::update(['status'=>1],['id'=>$id]);
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
            Tags::update(['is_delete'=>1],['id'=>$value]);
        }
        //直接软删除操作
        Tags::destroy($del_id);      
    }

    //测试方法
    public function test()
    {
        $del_id="10,9,8";
        $del_arr=explode(',', $del_id);
        foreach ($del_arr as $value) {
            Tags::update(['is_delete'=>1],['id'=>$value]);
        }
        
    }

}
