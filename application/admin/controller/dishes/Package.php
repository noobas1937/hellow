<?php

namespace app\admin\controller\dishes;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Session;

/**
 * 套餐管理
 *
 * @icon fa fa-circle-o
 */
class Package extends Backend
{
    
    /**
     * Package模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\PackageDao');
        $this->modelValidate = true;
        $this->modelSceneValidate = true;

    }
    

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('name');

            $package = new \gyo2o\model\Package();
            $result = $package->getPackages($where,$sort,$order,$offset,$limit);


            return json($result);
        }
        return $this->view->fetch();
    }


    public function del($ids=""){
        //如果套餐下有餐品不允许删除

        if(strpos($ids,',')!==false){
            $this->error('不可批量删除');
        }
        $packageModel = new \gyo2o\model\Package();
        if(!$packageModel->isEmpty($ids)){
            $this->error('改套餐下有餐品');
        }else{
            parent::del($ids);
        }

    }


}
