<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 下午 3:07
 */

namespace gyo2o\model;

use gyo2o\BaseModel;

class ApiList extends BaseModel
{
    public $api = [
        'base.ad.list' => 'ad/ajax/ad_list', //获取广告位
        'item.class.list' => 'item/ajax/class_list', //获取分类列表
        'base.news.list' => 'ad/ajax/news_list', //获取头条新闻
        'item.hot.list' => 'item/ajax/hot_list', //热卖单品
        'item.newest.list' => 'item/ajax/newest_list', //新品推荐
        'item.detail.info' => 'item/ajax/item_detail', //商品详情
        'item.cart.add' => 'item/ajax/add_cart', //添加购物车
        'item.cart.del' => 'item/ajax/del_cart', //删除购物车商品
        'item.eval.info' => 'item/ajax/item_eval', //商品评价详情
        'item.cart.list' => 'item/ajax/cart_list', //购物车商品
        'item.cate.list' => 'item/ajax/cate_list', //获取分类的所有商品
        'item.post.add_favorites' =>'item/ajax/add_favorites',//添加收藏
        'item.post.remove_favorites' =>'item/ajax/remove_favorites',//取消收藏
        'item.post.favorites_list' =>'item/ajax/favorites_list',//收藏列表
        'order.confirm.pay_record' => 'order/ajax/order_confirm', //生成支付流水
        'order.add.pay' => 'order/ajax/add_order', //下单调用支付
        'order.confirm.detail' => 'order/ajax/confirm_data', //下单之前订单详情
        'user.address.list' => 'user/ajax/user_address', //地址列表
        'user.address.add' => 'user/ajax/add_address', //添加地址
        'user.address.del' => 'user/ajax/remove_address', //删除地址
        'user.address.save' => 'user/ajax/save_address', //编辑地址
        'user.address.info' => 'user/ajax/address_detail', //查看地址详细
        'user.post.openid' => 'user/ajax/get_openid', //根据openid获取个人信息
        'data.province.list' => 'user/ajax/province_list', //省列表
        'data.province.pid' => 'user/ajax/get_pid', //根据父id查询
        'data.set.user_status' => 'user/ajax/set_user_status', //设置用户属性
        'order.get.list' => 'order/ajax/order_list', //查询全部订单
        'order.get.status_list' => 'order/ajax/get_status_list', //根据状态查询订单
        'order.get.detail' => 'order/ajax/get_detail', //查看订单详情
        'user.post.telcode' =>'user/ajax/telcode', //获取手机验证码
        'user.post.setmobile' =>'user/ajax/setmobile', //设置手机号
        'user.post.setnickname' => 'user/ajax/setnickname', //设置昵称
        'user.post.setsex' => 'user/ajax/setsex', //设置昵称
        'user.post.setbirth' => 'user/ajax/setbirth', //设置昵称
        'upload.post.upload' => 'upload/ajax/upload', //设置性别
        'user.post.set_head_img' => 'user/ajax/set_head_img', //设置性别
        'order.post.comment' =>'order/ajax/comment',//评价
        'search.post.index' =>'item/search/index',//搜索页
        'search.post.search' =>'item/search/search',//搜索列表
        'search.post.del'=>'item/search/del_search_all',//删除历史搜索
        'order.post.cancel'=>'order/ajax/cnacle',//取消订单
        'user.post.set_mobile' => 'user/ajax/set_mobile', //设置手机号
        'user.post.user_id' => 'user/ajax/get_user_id', //根据user_id获取信息
        'user.get.alipay_uid' => 'user/ajax/aliplaysing',//支付宝获取OPen_id.
        'service.wxpay.notify' => 'service/wxpay/notify', //微信回调
        'order.pay.list' => 'order/ajax/get_pay_list', //购买成功订单
        'order.next.order_pay' => 'order/ajax/order_pay', //订单再支付
        'user.get.evallist' => 'user/ajax/evallist',//用户评论列表
        'order.post.refund' => 'order/ajax/refund',//退款申请
        'lucky.get.luckydrawinfo'=>'lucky/ajax/luckydrawinfo',//大转盘抽奖数据
        'lucky.get.luckydraw'=>'lucky/ajax/luckydraw',//大转盘抽奖
        'user.get.employee' => 'user/ajax/getemployee', //绑定员工信息
        'user.get.luckdrawrecord'=>'user/ajax/getluckdrawrecord',//获取单个活动用户中奖纪录
        'lucky.get.getprize' =>'lucky/ajax/getprize',//领取积分奖励
        'lucky.get.luckyapply' =>'lucky/ajax/luckyapply',//积分夺宝报名
        'lucky.get.luckyapplyinfo' => 'lucky/ajax/luckyapplyinfo',//积分夺宝活动信息
        'lucky.get.luckeprizerecord' => 'lucky/ajax/luckyprizerecord',//最新中奖纪录
        'lucky.get.luckyhistory' =>'lucky/ajax/luckyHistory',//所有积分夺宝历史中奖纪录
        'lucky.get.luckdrawprizerecord'=>'lucky/ajax/luckdrawprizerecord',//单个抽奖活动所有用户中奖纪录（根据抽奖活动ID）
        'lucky.get.newsttwo'=>'lucky/ajax/getlastluckyapplyinfo',//最新夺宝活动
        'lucky.get.luckyapplyrecord' => 'lucky/ajax/luckyApplyRecord',//用户参与的夺宝活动
        'lucky.get.luckynumber' => 'lucky/ajax/getLuckyNumber',//获取用户夺宝幸运码
        'lucky.get.luckyticket' => 'lucky/ajax/getLuckyTicket',//获取用户抽奖券
        'user.get.alldrawrecord' => 'user/ajax/getAllDrawRecord',//用户所有抽奖活动中奖纪录
        'lucky.get.openid' => 'lucky/ajax/getOpenid',//获取用户小程序openID.
        'user.get.userinfo' =>'user/ajax/getUserinfo',//根据身份证号码用户确认信息.
        'user.post.userconfirm'=>'user/ajax/userInfoConfirm',//激活确认
        'user.get.usercenter'=>'user/ajax/usercenter',//个人中心数据
        'user.post.conversion' =>'user/ajax/conversion',//奋斗金兑换现金
        'user.post.withdraw' => 'user/ajax/withdraw',//提现申请
        'user.get.salarydetail' =>'user/ajax/salarydetail',//工资明细
        'user.get.pointdetail'=>'user/ajax/pointsdetail',//奋斗金明细
        'user.get.balancedetail'=>'user/ajax/balanceDetail',//现金明细
        'user.post.userpointconfirm'=>'user/ajax/userPointConfirm',//工资员工确认
        'user.post.singlsalarydetail'=>'user/ajax/singleSalaryDetail',//单条工资明细
        'lucky.get.newyeardrawinfo'=>'lucky/ajax/newyearDrawInfo',//年会活动信息
        'lucky.get.fdjcount'=>'lucky/ajax/fdjCount',//奋斗金获取总数
        'wechatweb.post.userbind'=>'user/ajax/userbind',//微信公众号用户绑定员工信息
        'user.get.employeeinfo'=>'user/ajax/getemployeeinfo',//获取员工信息
        'user.get.myinfo'=>'user/ajax/myinfo',//个人信息
        'lucky.get.newluckyapply' =>'lucky/ajax/newluckyapply',//使用夺宝券的积分夺宝报名
        'lucky.get.luckyparam' =>'lucky/ajax/luckyparam',//夺宝参数计算
        'user.get.verifycode'=>'user/ajax/verifycode',//校验手机验证码
        'user.post.newopenid' => 'user/ajax/newget_openid', //根据openid获取个人信息
        'user.post.employee.apply' => 'user/ajax/employee_apply',//员工申请认证
        'user.post.employee.bank' => 'user/ajax/employee_bank',//员工提交银行卡资料
        'api.post.area' => 'api/ajax/ajax_area',//获取地区
        'api.post.bank.list' => 'api/ajax/bank_list',//银行列表
        'api.post.describe.list' => 'api/ajax/describe_list',//职位列表
    ];


    public $describe = [
        ['id'=>1,'name'=>'骑手','level' => 4,'area_id' => [9]],
        ['id'=>2,'name'=>'站长','level' => 4,'area_id' => [9]],
        ['id'=>3,'name'=>'COO','level' => 1,'area_id' => 2],
        ['id'=>4,'name'=>'董事长助理','level' => 1,'area_id' => 2],
        ['id'=>5,'name'=>'副总经理','level' => 1,'area_id' => 2],
        ['id'=>6,'name'=>'人事部负责人','level' => 1,'area_id' => 3],
        ['id'=>7,'name'=>'薪酬绩效主管','level' => 1,'area_id' => 3],
        ['id'=>8,'name'=>'薪酬绩效专员','level' => 1,'area_id' => 3],
        ['id'=>9,'name'=>'培训与人才发展岗','level' => 1,'area_id' => 3],
        ['id'=>10,'name'=>'关怀与企业文化岗','level' => 1,'area_id' => 3],
        ['id'=>11,'name'=>'HRBP岗','level' => 1,'area_id' => 3],
        ['id'=>12,'name'=>'行政部负责人','level' => 1,'area_id' => 4],
        ['id'=>13,'name'=>'物料管理员岗','level' => 1,'area_id' => 4],
        ['id'=>14,'name'=>'采购员岗','level' => 1,'area_id' => 4],
        ['id'=>15,'name'=>'宿舍管理岗','level' => 1,'area_id' => 4],
        ['id'=>16,'name'=>'数据主管','level' => 1,'area_id' => 5],
        ['id'=>17,'name'=>'数据分析岗','level' => 1,'area_id' => 5],
        ['id'=>18,'name'=>'督导主管','level' => 1,'area_id' => 6],
        ['id'=>19,'name'=>'督导岗','level' => 1,'area_id' => 6],
        ['id'=>20,'name'=>'客服督导岗','level' => 1,'area_id' => 6],
        ['id'=>21,'name'=>'招聘主管','level' => 1,'area_id' => 7],
        ['id'=>22,'name'=>'招聘专员','level' => 1,'area_id' => 7],
        ['id'=>23,'name'=>'成本会计主管','level' => 1,'area_id' => 8],
        ['id'=>24,'name'=>'出纳岗','level' => 1,'area_id' => 8],
        ['id'=>25,'name'=>'会计岗','level' => 1,'area_id' => 8],
        ['id'=>26,'name'=>'大区经理','level' => 2,'area_id' => [9]],
        ['id'=>27,'name'=>'区域经理','level' => 3,'area_id' => [69]],
        ['id'=>28,'name'=>'百胜负责人','level' => 1,'area_id' => 69],
        ['id'=>29,'name'=>'城市经理','level' => 4,'area_id' => [69]]
    ];


    public $bank_list = [
        '中国工商银行',
        '招商银行',
        '中国农业银行',
        '中国建设银行',
        '中国银行',
        '中国民生银行',
        '中国光大银行',
        '中信银行',
        '交通银行',
        '中国人民银行',
        '华夏银行',
        '中国邮政储蓄银行',
    ];


}