<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 18/2/12
 * Time: 下午2:51
 */
namespace app\test1\controller;

use think\Controller;
use think\Db;
class Fdj extends Controller
{
    function index(){
        $file = RUNTIME_PATH.'/credits_record.html';
        if(file_exists($file)) unlink($file);
        error_log('<table>'."\r\n",3,$file);

        $current_time = time();
        $fdj = [];
        //$result = Db::query('select * from tb_employee where tb_user_id > 0');

        $result = Db::query('select employee_id from tb_employee_withdraw group by employee_id');

        foreach($result as $key => $val){
            $value = Db::query('select id,tb_user_id,`name` from tb_employee where id = '.$val['employee_id'] . ' limit 1');
            if(0 == sizeof($value)) continue;
//            $fdj[$value['tb_user_id']]['account_id'] = $value['tb_user_id'];
//            $fdj[$value['tb_user_id']]['employee_id'] = $value['id'];
//            $fdj[$value['tb_user_id']]['name'] = $value['name'];
            //var_dump($fdj);
            $value = $value[0];

            // 总流水余额
            $query_str = 'select sum(credits) as sum_credits FROM tb_credits_record where tb_employee_id = '.$value['id'];
            //echo $query_str;
            $credits_record = Db::query($query_str);
            $credits_record = $credits_record[0]['sum_credits'] + 0;

            // 冻结工资和奖励
            //$query_str = 'select sum(credits) as sum_credits from tb_credits_increasement where isconfirm < 2  and UNIX_TIMESTAMP(unfreeze_time) <= ' . $current_time . ' and employee_id = '.$value['id'];
            $query_str = 'select sum(credits) as sum_credits from tb_credits_increasement where employee_id = %s and (isconfirm < %s or UNIX_TIMESTAMP(unfreeze_time) > %s)';
            $query_str = sprintf($query_str,$value['id'],2,$current_time);
            //echo $query_str;
            $kyzj_record = Db::query($query_str);
            $kyzj_record = $kyzj_record[0]['sum_credits'] + 0;
            //var_dump($kyzj_record);


            if(0 < $credits_record-$kyzj_record){
                $msg = '<tr><td>%s</td><td>%s</td></tr>';
                $msg = sprintf($msg,$value['name'],$credits_record-$kyzj_record);
                error_log($msg."\r\n",3,$file);
            }

        }
        error_log('<table>'."\r\n",3,$file);
    }




}