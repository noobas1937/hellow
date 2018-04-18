<?php

namespace app\admin\controller\activity;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Ad extends Backend
{
    
    /**
     * TbAd模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\AdDao');
        $this->view->assign('show_yn',$this->model->getShow_yn());
        $this->view->assign('link',$this->model->getLink());

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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('title');

            $adModel = new \gyo2o\model\Ad();
            $result = $adModel->getAds($where,$sort,$order,$offset,$limit);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $adModel = new \gyo2o\model\Ad();
        $row =  $adModel->getByid($ids);
        if (!$row)
            $this->error(__('No Results were found'));

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }try {
                $adModel->edit($row,$params);
                $result = $adModel->edit($row,$params);
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error($row->getError());
                }
            } catch (think\exception\PDOException $e) {
                $this->error($e->getMessage());
            }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;

                }
                try {

                    $adModel = new \gyo2o\model\Ad();
                    $result = $adModel->add($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error('error');
                    }
                }catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

}
