<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 下午 5:08
 */

namespace gyo2o\dao;


use think\Db;
use think\Model;

class ItemDao extends Model
{
    private $rec_yn = ['不推荐','推荐'];
    private $is_hot = ['非热卖','热卖'];
    private $show_yn = ['显示','不显示'];
    private $from_sale = ['未下架','下架'];

    protected $table = 'tb_item';
    protected $append = ['province_name','city_name','rec_yn_text','is_hot','from_sale_text','show_yn_text'];

    const SALE = 0;
    const UN_DEL = 0;
    const HOT = 1;
    const N_HOT = 0;
    const SHOW = 0;
    const REC_Y = 1;
    const REC_N = 0;

    /**
     * @param $city_id
     * @param int $page_size
     * @param string $order
     * @return array
     */
    public function get_hot_list($city_id,$page_size = 5,$order = 'seq desc'){
        $map = array(
            'sale_hot' => self::HOT,
            'pac_num' => 1,
            'from_sale' => self::SALE,
            'tuan_num' => array('lt', 2),
            'city_id' => $city_id,
            'del_flag' => self::UN_DEL,
            'show_yn'=>self::SHOW,
        );
        return $this->where($map)->order($order)->paginate($page_size)->toArray();
    }

    /**
     * @param $city_id
     * @param int $page_size
     * @param string $order
     * @return array
     */
    public function get_newest_list($city_id,$page_size = 5,$order = 'seq desc,create_date desc'){
        $map = array(
            'sale_hot' => self::N_HOT,
            'rec_yn' => self::REC_Y,
            'from_sale' => self::SALE,
            'city_id' => $city_id,
            'del_flag' => self::UN_DEL,
            'show_yn'=>self::SHOW,
        );
        return $this->where($map)->order($order)->paginate($page_size)->toArray();
    }

    public function get_bt_item_id($item_id){
        $map = [
            'id' => $item_id
        ];
        return $this->where($map)->find();
    }

    public function get_by_product($product_id){
        $where = array();
        $where['del_flag'] = self::UN_DEL;
        if ($product_id)
            $where['product_id'] = $product_id;
        return $this->where($where)->select();
    }


    public function getProvincenameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['province_id'])->find()['name'];
    }

    public function getCitynameAttr($value,$data){
        return Db::table('tb_data_dictionary')->where('id',$data['city_id'])->find()['name'];
    }

    public function getRecyntextAttr($valeu,$data){
        return $this->rec_yn[$data['rec_yn']];
    }

    public function getIshotAttr($value,$data){
        return $this->is_hot[$data['sale_hot']];
    }

    public function getShowyntextAttr($value,$data){
        return $this->show_yn[$data['show_yn']];
    }

    public function getFromsaletextAttr($value,$data){
        return $this->from_sale[$data['from_sale']];
    }

    public function getRecyn(){
        return $this->rec_yn;
    }

    public function getIshot(){
        return $this->is_hot;
    }

    public function getShowyn(){
        return $this->show_yn;
    }

    public function getFromsale(){
        return $this->from_sale;
    }

    //根据ID集获取列表
    public function getByIds($where,$sort,$order,$offset,$limit,$ids)
    {
        return $this->where('id','in',$ids)->where($where)->where('del_flag',0)->order($sort,$order)->limit($offset,$limit)->select();
    }

    //根据条件和ID集合统计总记录数
    public function getTotal($where,$ids)
    {
        return $this->where('id','in',$ids)->where($where)->where('del_flag',0)->count();
    }

    //根据$where条件获取记录列表
    public function getItems($where,$sort,$order,$offset,$limit){
        return $this->where($where)->where('del_flag',0)->order($sort,$order)->limit($offset,$limit)->select();
    }

    //获取一条
    public function getById($id){
        return $this->where('id',$id)->find();
    }

    /**
     * 根据商品id数组获取未下架商品详情
     * @param $items
     *
     * @return mixed
     */
    public function get_list_items($items, $page, $pageSize,$city_id = null)
    {
        $map['id'] = array('in', join(',', $items));
        $map['from_sale'] = self::SALE;
        $map['del_flag'] = self::UN_DEL;
        $map['show_yn'] = self::SHOW;
        if($city_id){
            $map['city_id'] = $city_id;
        }

        return $this->where($map)->order('seq desc')->page($page)->paginate($pageSize)->toArray();
    }

    /**
     * 根据商品id，判断商品是否存在
     * @param $itemId
     *
     * @return bool
     */
    public function is_exist_by_id($itemId,$show_yn = false)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'from_sale' => self::SALE,
            'id' => $itemId,

        );
        if(false != $show_yn){
            $map['show_yn']= self::SHOW;
        }
        $data = $this->where($map)->find()->toArray();
        if ($data && is_array($data))
            return true;
        else
            return false;
    }

    public function count_search_by_title($word,$city_id)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'from_sale' => self::SALE,
            'title' => array('like', $word),
            'show_yn'=>self::SHOW,
            'city_id'=>$city_id,
        );
        return $this->where($map)->count();
    }

    public function search_by_title($word, $page, $listRows,$city_id)
    {
        $map = array(
            'del_flag' => self::UN_DEL,
            'from_sale' => self::SALE,
            'title' => array('like', $word),
            'show_yn'=>self::SHOW,
            'city_id'=>$city_id,
        );
        return $this->where($map)->page($page, $listRows)->select();
    }

    public function set_inventory($item_id, $pay_num)
    {
        $map = array(
            'id' => $item_id
        );
        return $this->where($map)->setDec("inventory", $pay_num);
    }

    public function get_by_item($itemid)
    {
        $where = array();
        $where['del_flag'] = self::UN_DEL;
        //$where['show_yn'] = self::SHOW;
        if ($itemid)
            $where['id'] = $itemid;
        return $this->where($where)->find();
    }
}