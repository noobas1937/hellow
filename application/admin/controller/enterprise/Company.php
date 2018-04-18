<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use gyo2o\model\Enterprise;
use think\Controller;
use think\Request;

/**
 * 企业管理
 *
 * @icon fa fa-circle-o
 */
class Company extends Backend
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
            $enterprise = new Enterprise();
            $result = $enterprise->get_list($this->buildparams('name'));
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {

        if ($this->request->isPost())
        {
            $enterprise = new Enterprise();
            $params = $this->request->post("row/a");
            $validate = $this->validate($params,'admin/TbEnterprise.add');
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
        $enterprise = new Enterprise();

        if($this->request->isPost()){
            $params = $this->request->post("row/a", [], 'strip_tags');
            $validate = $this->validate($params,'admin/TbEnterprise.edit');
            if($validate === true){
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
                $enterprise = new Enterprise();
                $result = $enterprise->del($ids);
                $this->result_return($result);
            }else{
                $this->error('参数错误');
            }
        }
        $this->error();
    }

}
