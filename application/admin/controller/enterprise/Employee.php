<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\dao\IdentityDao;
use gyo2o\model\TbEmployee;
use think\Controller;
use think\Request;
use think\Config;

/**
 * 人员身份管理
 *
 * @icon fa fa-circle-o
 */
class Employee extends Backend
{

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $enterprise = new TbEmployee();
            $result = $enterprise->get_list($this->buildparams('name,contact_moblie,idcard'));
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {

        if ($this->request->isPost())
        {
            $enterprise = new TbEmployee();
            $params = $this->request->post("row/a");
//            $validate = $this->validate($params,'admin/TbEmployee.add');
//            if($validate === true){
                $data['create_name'] = session('admin.id');
                $data['create_date'] = date('Y-m-d H:i:s');
                $result = $enterprise->add($params);
                if(is_array($result)){
                    return json($result);
                }
                $this->result_return($result);
//            }else{
//                $this->error($validate);
//            }

        }
        return $this->view->fetch();
    }

    public function edit($ids = '')
    {
        $enterprise = new TbEmployee();

        if($this->request->isPost()){
            $params = $this->request->post("row/a", [], 'strip_tags');
//            $validate = $this->validate($params,'admin/TbEmployee.edit');
//            if($validate === true){

                $params['update_date'] = date('Y-m-d H:i:s');
                $params['update_name'] = session('admin.id');
                $result = $enterprise->edit($ids,$params);
                $this->result_return($result);
//            }else{
//                $this->error($validate);
//            }
        }

        $row = $enterprise->get_one($ids);
        if(!$row){
            $this->error('Parameter can not be empty');
        }

        $this->view->assign('row',$row);
        return $this->view->fetch();
    }

    public function del($ids = ''){
        if ($this->request->isPost())
        {
            if($ids){
                $enterprise = new TbEmployee();
                $result = $enterprise->del($ids);
                $this->result_return($result);
            }else{
                $this->error('参数错误');
            }
        }
        $this->error();
    }

    public function identity_list(){
        if ($this->request->isAjax())
        {
            $enterprise = new IdentityDao();
            $pkey_value = $this->request->request('pkey_value');
            if($pkey_value){
                $result = $enterprise->get_all($pkey_value);
            }else{
                $result = $enterprise->get_all();
            }
            if($result){
                return json(['list'=>$result]);
            }else{
                return json(['total'=>0,'rows'=>[]]);
            }
        }
    }

    public function excel(){
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        $path = $file->getRealPath();
        $employeeModel = new TbEmployee();
        $result = $employeeModel->importExcel($path);

        if($result){
            $this->success('导入成功', null,['code'=>100000]);
        }else{
            $this->error($employeeModel->getError(), null);
        }

    }

    public function newadd(){
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        $path = $file->getRealPath();
        $employeeModel = new TbEmployee();
        $result = $employeeModel->addemployee($path);

        if($result){
            $this->success('导入成功', null,['code'=>100000]);
        }else{
            $this->error($employeeModel->getError(), null);
        }
    }

    //解绑
    public function unbind($id){
        Config::set('default_return_type', 'json');
        if(empty($id)){
            $this->error('解绑失败！');
        }
        $employeeModel = new TbEmployee();
        $result = $employeeModel->unbind($id);
        if($result){
            $this->success('解绑成功', null,['code'=>100000]);
        }else{
            $this->error('解绑失败！', null);
        }

    }

}
