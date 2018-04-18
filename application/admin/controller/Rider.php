<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use EasyWeChat\Staff\Session;
use gyo2o\model\Order;
use think\Controller;
use think\Loader;
use think\Request;

/**
 * 骑手管理
 *
 * @icon fa fa-circle-o
 */
class Rider extends Backend
{
    
    /**
     * Rider模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = Loader::model('gyo2o\\dao\\RiderDao');
        $this->view->assign("sexList", $this->model->getSexlist());
        $this->view->assign("typeList", $this->model->getTypelist());
        $this->view->assign("stationmasterList", $this->model->getStationmasterlist());
        $this->view->assign("workstatusList", $this->model->getWorkstatuslist());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('name,mobile');
            //获取骑手列表
            $riderModel = new \gyo2o\model\Rider();
            $result = $riderModel->getRiders($where,$sort,$order,$offset,$limit);
//            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds))
        {
            if (!in_array($row[$this->dataLimitField], $adminIds))
            {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $starttime = $params['starttime'];
                    $endtime = $params['endtime'];
                    if(!empty($starttime)){
                        $params['open_id'] = $starttime.'|'.$endtime;
                        if(strtotime($starttime)>=strtotime($endtime)){
                            $this->error('休假开始时间大于结束时间');
                        }
                        $dates = [];
                        $i = 0;
                        while (strtotime("+$i day",strtotime($starttime))<=strtotime($endtime)){
                            $dates[] = date('Y-m-d',strtotime("+$i day",strtotime($starttime)));
                            $i++;
                        }
                        //休假时期取消订单
                        $order = new Order();
                        $res = $order->cancel($ids,$dates);
                        if(!$res){
                            $this->error('系统繁忙稍后再试');
                        }
                    }

                    $result = $row->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($row->getError());
                    }
                }
                catch (think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


}
