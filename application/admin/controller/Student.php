<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Student as Stu;
use app\admin\model\Cate;
use think\Controller;
use think\Request;

class Student extends Controller
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


    //渲染学生信息列表
    public function stuList()
    {
        //获取记录
        $studentList=Stu::all();
        //获取记录数量
        $count = Stu::count();

        //给结果集对象数组中的每个模板对象添加班级关联数据,非常重要
        foreach ($studentList as $value) {
            $value -> grade = $value -> grade -> name;
        }

        $cate=Cate::getCate();
        $this -> view -> assign('cate',$cate);
        $this -> view -> assign('studentList', $studentList);
        // $this -> view -> assign('count', $count);

        return $this -> view -> fetch('student_list');
    }


     //渲染班级添加界面
    public function studentAdd()
    {
        //渲染添加模板
        $this->view->assign('gradeList',\app\admin\model\Grade::all());

        return $this->view->fetch('student_add');
    }

     //添加班级
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //更新当前记录
        $result = Stu::create($data);

        // //设置返回数据的初始值
        // $status = 0;
        // $message = '添加失败,请检查';

        // //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        // if (true == $result) {
        //     $status = 1;
        //     $message = '恭喜, 添加成功~~';
        // }

        // //自动转为json格式返回
        // return ['status'=>$status, 'message'=>$message];
    }

    //渲染班级编辑界面
    public function studentEdit(Request $request)
    {
       $student_id = $request -> param('id');
        $result = Stu::get($student_id);

        //获取关联表:grade数据
        $result -> grade = $result -> grade->name;

        //给当前教师编辑页面模板赋值
        $this->view->assign('info',$result);

        //将班级表中所有数据赋值给当前模板
        $this->view->assign('gradeList',\app\admin\model\Grade::all());

        //渲染出当前的编辑模板
        return $this->view->fetch('student_edit');

        
    }

    //班级更新
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request -> param();

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = Stu::update($data,$condition);

        // //设置返回数据给前端ajax处理
        // $status = 0;
        // $message = '更新失败,请检查';

        // //检测更新结果,将结果返回给student_edit模板中的ajax提交回调处理
        // if (true == $result) {
        //     $status = 1;
        //     $message = '恭喜, 更新成功~~';
        // }
        // return ['status'=>$status, 'message'=>$message];
    }


     //软删除操作
    public function deleteStudent(Request $request)
    {
        $user_id = $request -> param('id');
        Stu::update(['is_delete'=>1],['id'=> $user_id]);
        Stu::destroy($user_id);

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
        $result = Stu::get($grade_id);

        //启用和禁用处理
        if($result->getData('status') == 1) {
            Stu::update(['status'=>0],['id'=>$grade_id]);
        } else {
            Stu::update(['status'=>1],['id'=>$grade_id]);
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
