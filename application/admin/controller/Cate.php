 <?php

namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Cate as Cat;
use think\Controller;
use think\Request;

class Cate extends Controller
{
   
    //渲染分类信息列表
    public function cateList()
    {
        
        $list = Cat::all();
 
        $cate = Cat::getCate();
        $this -> view -> assign('cate',$cate);
        $this -> view -> assign('selectid',0);
        return $this -> view -> fetch('cate_list');
    }


    //获取父类id的信息列表
    public function pidList(Request $request)
    {
        $pid = $request->param('pid');       
        if ($pid==0) {
            $list = Cat::all();
        } else {
            $list = Cat::all(['pid'=>$pid]);
        }
        
        
        $cate = Cat::getCate();
        $this -> view -> assign('cate',$cate);
        $this -> view -> assign('selectid',$pid);
        $this -> view -> assign('list',$list);
        return $this -> view -> fetch('cate_list');
    }

     //渲染班级添加界面
    public function CateAdd()
    {
        $cate = Cat::getCate();
        $this -> view -> assign('cate',$cate);
        return $this->view->fetch('cate_add');
    }

     //添加班级
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //更新当前记录
        $result = Cat::create($data);

    }

    //渲染班级编辑界面
    public function cateEdit(Request $request)
    {
        $cateid = $request -> param('id');
        //获取编辑的数据
        $result = Cat::get($cateid);
        $this -> view -> assign('info',$result);

        //获取所有分类的列表
        $cate = Cat::getCateData($cateid);

        $cate1=Cat::tree($cate);
        // halt($cate1);
        $this -> view -> assign('cate',$cate1);

        //渲染出当前的编辑模板
        return $this-> view -> fetch('cate_edit');

        
    }

    //班级更新
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request -> param();

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = Cat::update($data,$condition);


    }


     //删除操作
    public function deleteCate(Request $request)
    {
        $user_id = $request -> param('id');
        Cat::update(['is_delete'=>1],['id'=> $user_id]);
        Cat::destroy(['pid'=>$user_id]);
        Cat::destroy($user_id);

    }

   
   //恢复删除操作
    public function unDelete()
    {
        Cat::update(['delete_time'=>NULL],['is_delete'=>1]);
    }
    

    //批量删除
    public function del(Request $request)
    {   
        $del_id=$request -> param('id');
        Cat::update(['is_delete'=>1],['id'=> $user_id]);
        Cat::destroy($del_id);

    
         // $array=explode(separator,$string); 字符串变为数组
         // $string=implode(glue,$array);   数组变为字符串
         // 
         // dump($days_array);  GET的方式 打开网页地址测试控制器的结果。POST方式要用在线网页提交测试。
        
        
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
