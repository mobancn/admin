<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Teacher as Tea;
use think\Controller;
use think\Request;

class Teacher extends Controller
{
   //教师列表
    public function  teaList()
    {
        //获取所有教师表teacher数据
        $teacher = Tea::all();

        //获取记录数量
        $count = Tea::count();
        //遍历teacher表
        foreach ($teacher as $value){
            $data = [
                'id' => $value->id,  //主键
                'name' => $value->name,  //姓名
                'degree' => $value->degree,  //学历
                'school' => $value->school,  //毕业学校
                'mobile' => $value->mobile,  //手机号
                'hiredate' => $value->hiredate,  //入职时间
                'status' => $value->status,  //当前启用状态
                //用关联方法grade属性方式访问grade表中数据
                'grade' => isset($value->grade->name)? $value->grade->name : '<span style="color:red;">未分配</span>',
            ];
            //每次关联查询结果,保存到数组   $teacher中
            $teacherList[] = $data;
        }


        $this -> view -> assign('teacherList', $teacherList);
        $this -> view -> assign('count', $count);


        return $this -> view -> fetch('teacher_list');
        
    }

     //渲染班级添加界面
    public function teacherAdd()
    {
        //渲染添加模板
        $this->view->assign('gradeList',\app\admin\model\Grade::all());

        return $this->view->fetch('teacher_add');
    }

     //添加班级
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //更新当前记录
        $result = Tea::create($data);

        //设置返回数据的初始值
        $status = 0;
        $message = '添加失败,请检查';

        //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 添加成功~~';
        }

        //自动转为json格式返回
        return ['status'=>$status, 'message'=>$message];
    }

    //渲染班级编辑界面
    public function teacherEdit(Request $request)
    {
       $teacher_id = $request -> param('id');
        $result = Tea::get($teacher_id);


        //给当前教师编辑页面模板赋值
        $this->view->assign('info',$result);

        //将班级表中所有数据赋值给当前模板
        $this->view->assign('gradeList',\app\admin\model\Grade::all());

        //渲染出当前的编辑模板
        return $this->view->fetch('teacher_edit');

        
    }

    //班级更新
    public function doEdit(Request $request)
    {
        //从提交表单中排除关联字段teacher字段
        $data = $request -> except('teacher');
//        $data = $request -> param();  如果全部获取,提交会提示缺少字段teacher

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录
        $result = Tea::update($data,$condition);

        //设置返回数据
        $status = 0;
        $message = '更新失败,请检查';

        //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 更新成功~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }


     //软删除操作
    public function deleteTeacher(Request $request)
    {
        $user_id = $request -> param('id');
        Tea::update(['is_delete'=>1],['id'=> $user_id]);
        Tea::destroy($user_id);

    }

    //恢复删除操作
    public function unDelete()
    {
        Tea::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

    //班级状态变更
    public function setStatus(Request $request)
    {
        //获取当前的班级ID
        $grade_id = $request -> param('id');

        //查询
        $result = Tea::get($grade_id);

        //启用和禁用处理
        if($result->getData('status') == 1) {
            Tea::update(['status'=>0],['id'=>$grade_id]);
        } else {
            Tea::update(['status'=>1],['id'=>$grade_id]);
        }
    }

    //批量删除
    public function del(Request $request)
    {   
        $del_id=$request -> param('id');
        Tea::destroy($del_id);

    
         // $array=explode(separator,$string); 字符串变为数组
         // $string=implode(glue,$array);   数组变为字符串
         // 
         // dump($days_array);  GET的方式 打开网页地址测试控制器的结果。POST方式要用在线网页提交测试。
        
        
    }


}
