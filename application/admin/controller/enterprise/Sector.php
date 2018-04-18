<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\model\Enterprise;
use gyo2o\model\TbSector;
use think\Controller;

/**
 * 部门管理
 *
 * @icon fa fa-circle-o
 */
class Sector extends Backend
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

            $enterprise = new TbSector();
            $result = $enterprise->get_list($this->buildparams('name'));
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {

        if ($this->request->isPost())
        {

            $enterprise = new TbSector();
            $params = $this->request->post("row/a");
            $validate = $this->validate($params,'admin/TbSector.add');
            if($validate === true){
                $result = $enterprise->add($params);
                $this->result_return($result);
            }else{
                $this->error($validate);
            }

        }
        return $this->view->fetch();
    }

    public function edit($ids = '')
    {
        $enterprise = new TbSector();

        if($this->request->isPost()){
            $params = $this->request->post("row/a", [], 'strip_tags');
            $validate = $this->validate($params,'admin/TbSector.edit');
            if($validate === true){
                $params['update_date'] = date('Y-m-d H:i:s');
                $result = $enterprise->edit($ids,$params);
                $this->result_return($result);
            }else{
                $this->error($validate);
            }
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
                $enterprise = new TbSector();
                $result = $enterprise->del($ids);
                $this->result_return($result);
            }else{
                $this->error('参数错误');
            }
        }
        $this->error();
    }

    public function company_list(){
        if ($this->request->isAjax())
        {
            $enterprise = new Enterprise();
            $result = $enterprise->get_all();
            if($result){
                return json(['list'=>$result]);
            }else{
                return json(['total'=>0,'rows'=>[]]);
            }
        }

    }

    public function get_company_all(){
        if ($this->request->isAjax())
        {
            $enterprise = new TbSector();
            $id = input('post.enterprise_id');
            $result = $enterprise->get_company_all($id);
            if($result){
                return json(['list'=>$result]);
            }else{
                return json(['total'=>0,'rows'=>[]]);
            }
        }
    }

    public function sector_list(){

        if ($this->request->isAjax())
        {
            $enterprise = new TbSector();
            $pkey_value = $this->request->request('pkey_value');
            $enterprise_id = $_GET['enterprise_id'];

            if($pkey_value){
                $result = $enterprise->get_all($pkey_value);
            }else{
                if($enterprise_id){
                    $result = $enterprise->get_company_all($enterprise_id);
                }else{
                    $result = $enterprise->get_all();
                }
            }

            if($result){
                return json(['list'=>$result]);
            }else{
                return json(['total'=>0,'rows'=>[]]);
            }
        }
    }
}
