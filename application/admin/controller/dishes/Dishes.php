<?php

namespace app\admin\controller\dishes;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 餐品管理
 *
 * @icon fa fa-circle-o
 */
class Dishes extends Backend
{
    
    /**
     * Dishes模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('gyo2o\\dao\\DishesDao');
        $this->view->assign("dishesTypeList", $this->model->getDishestypelist());
        $this->view->assign("dishesStatusList", $this->model->getDishesstatuslist());
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams('name');
            $disheModel = new \gyo2o\model\Dishes();
            $result = $disheModel->getDishes($where,$sort,$order,$offset,$limit);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage的实现方法
     *
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    protected function selectpage()
    {
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
                        $query->where($n, "like", "%{$v}%", $andor);
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
                ->where("dishes_status",'正常')
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
