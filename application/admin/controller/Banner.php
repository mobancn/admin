<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Banner as Ban;
use app\admin\model\Cate;

use think\Controller;
use think\Request;

class Banner extends Controller
{
    //检测名称是否可用,必须配合jquery.validation组件
    public function checkName(Request $request)
    {
        $userName = trim($request -> param('name'));
        
        if (Stu::get(['name'=> $userName])) {
            exit ("false");
        }
        else{
            exit ("true");
        }
    }


    //渲染信息列表
    public function bList()
    {
        //获取记录
        $list=Ban::all();


        // //给结果集对象数组中的每个模板对象添加类目关联数据,非常重要
        foreach ($list as $value) {
            $value -> cate = $value -> cate -> cate_name;
        }

        $this -> view -> assign('list', $list);
        // $this -> view -> assign('count', $count);

        return $this -> view -> fetch('banner_list');
    }


     //渲染班级添加界面
    public function bAdd()
    {
        //渲染添加模板
        $cate=\app\admin\model\Cate::getCate();
        $this->view->assign('cate',$cate);

        return $this->view->fetch('banner_add');
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

    //添加
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data=$request -> param();

        Ban::create($data);
        
    }




    //渲染编辑界面
    public function sedit(Request $request)
    {
        $student_id = $request -> param('id');
        $result = Ban::get($student_id);


        //给当前教师编辑页面模板赋值
        $this->view->assign('info',$result);

        //渲染添加模板
        $cate=\app\admin\model\Cate::getCate();
        $this->view->assign('cate',$cate);

        //渲染出当前的编辑模板
        return $this->view->fetch('banner_edit');

        
    }

    //班级更新
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request -> param();

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = Ban::update($data,$condition);
    }


     //软删除操作
    public function deleteStudent(Request $request)
    {
        $user_id = $request -> param('id');
        Ban1::update(['is_delete'=>1],['id'=> $user_id]);
        Ban1::destroy($user_id);

    }

    //恢复删除操作
    public function unDelete()
    {
        Stu::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

    //班级状态变更
    public function setStatus(Request $request)
    {
        //获取当前的班级ID
        $grade_id = $request -> param('id');

        //查询
        $result = Ban1::get($grade_id);

        //启用和禁用处理
        if($result->getData('status') == 1) {
            Ban1::update(['status'=>0],['id'=>$grade_id]);
        } else {
            Ban1::update(['status'=>1],['id'=>$grade_id]);
        }
    }

    //批量删除
    public function del(Request $request)
    {   
        $del_id=$request -> param('id');
        Stu::update(['is_delete'=>1],['id'=> $user_id]);
        Stu::destroy($del_id);

    
         // $array=explode(separator,$string); 字符串变为数组
         // $string=implode(glue,$array);   数组变为字符串
         // 
         // dump($days_array);  GET的方式 打开网页地址测试控制器的结果。POST方式要用在线网页提交测试。
        
        
    }

   
}
