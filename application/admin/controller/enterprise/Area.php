<?php

namespace app\admin\controller\enterprise;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use fast\Tree;

/**
 * 员工区域管理
 *
 * @icon fa fa-circle-o
 */
class Area extends Backend
{
    
    /**
     * TbArea模型对象
     */
    protected $model = null;
    protected $rulelist = [];


    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\AreaDao');
        // 必须将结果集转换为数组
        $data = collection($this->model->order('level', 'desc')->select())->toArray();

        Tree::instance()->init($data,'pid');
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $ruledata = [0 => __('None')];
        foreach ($this->rulelist as $k => &$v)
        {
//            if (!$v['ismenu'])
//                continue;
            $ruledata[$v['id']] = $v['name'];
        }
        $this->view->assign('ruledata', $ruledata);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $list = $this->rulelist;
            $total = count($this->rulelist);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params)
            {
                $this->model->create($params);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params)
            {
                $row->save($params);
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v)
            {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            }
            $delIds = array_unique($delIds);
            $count = $this->model->where('id', 'in', $delIds)->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }

}
