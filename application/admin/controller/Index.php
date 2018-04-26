<?php

// 记得添加应该类
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\User;
use think\Session;
use think\Request;

class Index extends Base
{
    public function index()
    {
    	$this -> isLogin();
        return $this -> view -> fetch('index');
    }

	//管理员列表
    public function adminList()
    {
    	$this -> isLogin();

        //count 返回记录总数
    	$this -> view -> count=user::count();

    	$username=Session::get('user_info.name');

    	if ($username=="admin")
    	{
    		$list=user::paginate(5);
            $page = $list->render();
    	} 
    	else
    	{
    		$list=user::all(['name'=>$username]);
    	}
    	
    	$this ->view -> assign('username',$username);
    	$this ->view -> assign('list',$list);

    	return $this -> view -> fetch('admin_list');
    }

    //添加管理员页面
    public function adminAdd()
    {
        
    	return $this -> view -> fetch('admin_add');
    }


   
    //渲染编辑管理员界面
    public function adminEdit(Request $request)
    {
        $user_id = $request -> param('id');
        $result = User::get($user_id);
        $this->view->assign('user_info',$result->getData());
        return $this->view->fetch('banner_edit');
    }

    //删除管理员
    public function deleteAdmin()
    {

    }


    //管理员状态变更
    public function setStatus(Request $request)
    {
        $user_id =$request ->param('id');
        $result=User::get($user_id);
        if ($result->getdata('status')==1)
        {
        	User::update(['status'=>0],['id'=>$user_id]);
        }
        else
        {
        	User::update(['status'=>1],['id'=>$user_id]);
        }
    }
    
    ///////////////////////////////////////





    //检测用户名是否可用
    public function checkUserName(Request $request)
    {
        $userName = trim($request -> param('name'));
        $status = 1;
        $message = '用户名可用';
        if (User::get(['name'=> $userName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '用户名重复,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }


    //检测用户邮箱是否可用
    public function checkUserEmail(Request $request)
    {
        $userEmail = trim($request -> param('email'));
        $status = 1;
        $message = '邮箱可用';
        if (User::get(['email'=> $userEmail])) {
            //查询表中找到了该邮箱,修改返回值
            $status = 0;
            $message = '邮箱重复,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }

       //添加操作
    public function addUser(Request $request)
     {
     	// param()要按表单顺序添加数据，不按顺序获取不了.
        $data = $request -> param();
        $status = 1;
        $message = "添加成功";

        $rule = [
            'name|用户名' => "require",
            'password|密码' => "require",
        ];

        $result = $this -> validate($data, $rule);

         if ($result === true) {
            $user = User::create($request -> param());
            if ($user === null) {
                $status = 0;
                $message = '添加失败~~';
            }
        }


        return ['status'=>$status, 'message'=>$message];
    }

     //更新数据操作
    public function editUser(Request $request)
    {
        //获取表单返回的数据
//      
        if ($request->isajax(true))
        {
        	$data = array_filter($request->param());  //过滤数组的空值
        	$map=['id'=>$request->param('id')];   //数据查询条件
        	$result=User::update($data,$map);      //更新数据  （obj,where)
        	$sta=1;
        	$message="更新成功";

        	if (is_null($result))
        	{
        		$sta=0;
        		$message="更新失败";
        	}
        }

        return ['sta'=>$sta, 'message'=>$message];

        //如果是admin用户,更新当前session中用户信息user_info中的角色role,供页面调用
        // if (Session::get('user_info.name') == 'admin') {
        //     Session::set('user_info.role', $data['role']);
        // }
     
    }



    //删除操作
    public function deleteUser(Request $request)
    {
        $user_id = $request -> param('id');
        User::update(['is_delete'=>1],['id'=> $user_id]);
        User::destroy($user_id);

    }


    //恢复删除操作
    public function unDelete()
    {
        User::update(['delete_time'=>NULL],['is_delete'=>1]);
    }


    //批量删除
    public function del(Request $request)
    {   
        $del_id=$request -> param('id');
        User::update(['is_delete'=>1],['id'=> $user_id]);
        User::destroy($del_id);

    
         // $array=explode(separator,$string); 字符串变为数组
         // $string=implode(glue,$array);   数组变为字符串
         // 
         // dump($days_array);  GET的方式 打开网页地址测试控制器的结果。POST方式要用在线网页提交测试。
        
        
    }
}
