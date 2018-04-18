<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 下午 2:15
 */

namespace gyo2o\dao;


use think\Model;

class UserInfoDao extends Model
{
    private $sex = ['未知','男','女'];
    protected $table = 'tb_user_info';
    protected $append = ['sex_text'];

    public function sexList(){
        return $this->sex;
    }
    public function getSextextAttr($value,$data){
        if(empty($data['sex'])){
            return '未知';
        }else{
            return $this->sex[$data['sex']];
        }
    }

    public function get_by_user_id($user_id)
    {
        if (0 >= $user_id) {
            return array();
        }

        $where['account_id'] = $user_id;
        $result = $this->where($where)->find();

        return $result;
    }

    public function getNicknaemByUserid($userid){
        return $this->where('id',$userid)->value('nickname');
    }

    public function nickname($userid, $nickname)
    {
        if ($userid)
            $where['account_id'] = $userid;
        $result = $this->where($where)->setField("nickname", $nickname);
        return $result;
    }

    public function sex($userid, $sex)
    {
        if ($userid)
            $where['account_id'] = $userid;
        $result = $this->where($where)->setField("sex", $sex);
        return $result;
    }

    public function birth($userid, $birth)
    {
        if ($userid)
            $where['account_id'] = $userid;
        $result = $this->where($where)->setField("birth", $birth);
        return $result;
    }
}