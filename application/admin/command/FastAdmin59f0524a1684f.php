<?php

namespace app\admin\command;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 订单配送
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
{

    /**
     * Order模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Order');
        $this->view->assign("dishesTypeList", $this->model->getDishestypelist());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    //订单列表
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
//            if ($this->request->request('pkey_name'))
//            {
//                return $this->selectpage();
//            }
//            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
//            $total = $this->model
//                ->where($where)
//                ->order($sort, $order)
//                ->count();
//
//            $list = $this->model
//                ->where($where)
//                ->order($sort, $order)
//                ->limit($offset, $limit)
//                ->select();
//
//
//            //按站点分组统计
//            $order = new \app\admin\model\Order();
//            $list = $order->field('date date,site.name sname,count(*) num,dishes_type,site.id sid')->join('rider r','r.id = rider_id')
//            ->join('site','r.site_id = site.id')->group('date,site_id')->order('date desc')->select();
            $date = !empty($this->request->get('date'))?$this->request->get('date'):date('Y-m-d',time());

            //按站点统计人数
            $rider = \think\Db::name('rider')->field('site_id,count(id) pnu')->group('site_id')->select();

            //按站点统计订餐情况
            $order = \think\Db::name('order')->field('site_id,dishes_type,count(*) cnu,site.name')->join('rider','rider_id = rider.id')->join('site','rider.site_id=site.id')
                ->group('site_id,dishes_type')->where("date='$date'")->select();

            //  获取当天餐品名
            $dishes = \think\Db::name('packagedetail')->field('dishes_id,dishes1_id,dishes2_id')->where("date = '$date'")->select();
            $names = array();
            $names['A'] = \think\Db::name('dishes')->find($dishes[0]['dishes_id'])['name'];
            $names['B'] = \think\Db::name('dishes')->find($dishes[0]['dishes1_id'])['name'];
            $names['C'] = \think\Db::name('dishes')->find($dishes[0]['dishes2_id'])['name'];

            //以站点ID为键整理数据
            $sorder = array();
            foreach ($order as $value){
                $sorder[$value['site_id']][] = $value;
            }

            //统计各站点各菜品下单数没有下单的默认选择餐品A
            $list = array();
            foreach ($rider as $key =>&$value){
                $siteid = $value['site_id'];
                if(isset($sorder[$siteid])){
                    //计算默认订餐人数
                    $count = 0;
                    $typea = 0;
                    $typez = 0;
                    $typeb = 0;
                    $typec = 0;
                    foreach ($sorder[$siteid] as $k=>$sv){
                        if($sv['dishes_type']=='A'){
                            $typea = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == 'B'){
                            $typeb = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == 'C'){
                            $typec = $sv['cnu'];
                        }elseif ($sv['dishes_type'] == "Z"){
                            $typez = $sv['cnu'];
                        }
                        $count += $sv['cnu'];
                    }
                    //默认点餐人数
                    $default = $value['pnu'] - $count;
                    //站点总点餐数
                    $dnu = $value['pnu'] - $typez;
                    //餐品A点餐人数
                    $anu = $typea + $default;
                    $bnu = $typeb;
                    $cnu = $typec;
                    //拼装站点点餐数据
                    $temp = ['date'=>$date,'sname'=>$sorder[$siteid][0]['name'],'pnu'=>$value['pnu'],'dnu'=>$dnu];
                    $list[] = array_merge($temp,['ctype'=>'餐品A','cname'=>$names['A'],'cnu'=>$anu]);
                    $list[] = array_merge($temp,['ctype'=>'餐品B','cname'=>$names['B'],'cnu'=>$bnu]);
                    $list[] = array_merge($temp,['ctype'=>'餐品C','cname'=>$names['C'],'cnu'=>$cnu]);
                }else{
                    //站点全部默认点餐
                    $sname = get_site_name($siteid);
                    $list[] = ['date'=>$date,'sname'=>$sname,'pnu'=>$value['pnu'],'dnu'=>$value['pnu'],'ctype'=>'餐品A','cname'=>$names['A'],'cnu'=>$value['pnu']];
                }

            }
//            var_dump($list);die;
//            $order = \think\Db::name('order')->field('date date,site.name sname,count(*) num,dishes_type,site.id sid')->join('rider r','r.id = rider_id')
//            ->join('site','r.site_id = site.id')->group('date,site_id')->order('date desc')->select();


            $result = array("total" => 3, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

}
