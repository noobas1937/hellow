<?php

namespace app\admin\controller\lucky;

use app\common\controller\Backend;

use function fast\e;
use gyo2o\model\LuckyTicketRecordModel;
use think\Controller;
use think\Request;

/**
 * 抽奖券记录管理
 *
 * @icon fa fa-circle-o
 */
class Luckyticketrecord extends Backend
{
    
    /**
     * TbLuckyTicketRecord模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('\\gyo2o\\dao\\LuckyTicketRecordDao');
        $this->searchFields = ['employee_id'];

    }

    public function index($ids=null)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax())
        {
            $luckyDrawAwardModel = new \gyo2o\model\LuckyTicketRecordModel();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $result = $luckyDrawAwardModel->getRecord($where, $sort, $order, $offset, $limit,$ids);
            return json($result);
        }

        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $ticketid = input('ids');
            $params['ticket_id'] = $ticketid;
            $tickRecordModel = new LuckyTicketRecordModel();
            if($params['type']==1) {
                //按员工发放
                if(empty($params['employee_id'])){
                    $this->error('员工必选');
                }
                $result = $tickRecordModel->addByEmployee($ticketid, $params['employee_id']);
            }else if($params['type'] == 2){
                //按部门发放
                if(empty($params['enterprise_id']) || empty($params['sector_id'])){
                    $this->error('企业或部门未选');
                }
                $result = $tickRecordModel->addBySector($ticketid,$params['enterprise_id'],$params['sector_id']);
            }

            $this->result_return($result);
        }
        return $this->view->fetch();
    }

    public function getEmployee(){
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('pkey_name'))
        {
            $this->model = model('\\gyo2o\\dao\\EmployeeDao');
            //设置过滤方法
            $this->request->filter(['strip_tags', 'htmlspecialchars']);

            //搜索关键词,客户端输入以空格分开,这里接收为数组
            $word = (array) $this->request->request("q_word/a");
            //当前页
            $page = $this->request->request("page");
            //分页大小
            $pagesize = $this->request->request("per_page");
            //搜索条件
            $andor = $this->request->request("and_or");
            //排序方式
            $orderby = (array) $this->request->request("order_by/a");
            //显示的字段
            $field = $this->request->request("field");
            //主键
            $primarykey = $this->request->request("pkey_name");
            //主键值
            $primaryvalue = $this->request->request("pkey_value");
            //搜索字段
            $searchfield = (array) $this->request->request("search_field/a");
            //自定义搜索条件
            $custom = (array) $this->request->request("custom/a");
            $order = [];
            foreach ($orderby as $k => $v)
            {
                $order[$v[0]] = $v[1];
            }
            $field = $field ? $field : 'name';

            //如果有primaryvalue,说明当前是初始化传值
            if ($primaryvalue !== null)
            {
                $where = [$primarykey => ['in', $primaryvalue]];
            }
            else
            {
                $where = function($query) use($word, $andor, $field, $searchfield, $custom) {
                    foreach ($word as $k => $v)
                    {
                        foreach ($searchfield as $m => $n)
                        {
                            if($m==0){
                                $query->where($n, "like", "%{$v}%");
                            }
                            $query->whereOr($n, "like", "%{$v}%");

                        }
                    }
                    if ($custom && is_array($custom))
                    {
                        foreach ($custom as $k => $v)
                        {
                            $query->where($k, '=', $v);
                        }
                    }
                };
            }
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds))
            {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = [];
            $total = $this->model->where($where)->count();
            if ($total > 0)
            {
                if (is_array($adminIds))
                {
                    $this->model->where($this->dataLimitField, 'in', $adminIds);
                }
                $list = $this->model->where($where)
                    ->order($order)
                    ->page($page, $pagesize)
                    ->field("{$primarykey},{$field}")
                    ->field("password,salt", true)
                    ->select();
            }
            //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
            return json(['list' => $list, 'total' => $total]);
        }
    }


}
