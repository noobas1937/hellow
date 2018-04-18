<?php
/**
 * Created by PhpStorm.
 * User: ggjrw
 * Date: 18/2/13
 * Time: 上午10:55
 */

namespace app\test1\controller;

use think\db;
class Lucky
{
    public function adddram(){
        $sqlstr = 'INSERT INTO `tt_qq`.`tb_lucky_draw` (`id`, `start_date`, `end_date`, `title`, `credits`, `frequency`, `type`, `with_people`, `left_with_people`, `ishot`) VALUES (NULL, "%s", "%s", "年会抽奖第%s场", 0, 0, 3, 0, 0, 0)';
        $sqlAry[] = sprintf($sqlstr,'2018-02-13 14:00','2018-02-13 14:30',1);
        $sqlAry[] = sprintf($sqlstr,'2018-02-13 15:00','2018-02-13 15:30',2);
        $sqlAry[] = sprintf($sqlstr,'2018-02-13 16:00','2018-02-13 16:30',3);
        $sqlAry[] = sprintf($sqlstr,'2018-02-13 17:00','2018-02-13 17:30',4);
        $sqlAry[] = sprintf($sqlstr,'2018-02-13 18:00','2018-02-13 18:30',5);
        foreach($sqlAry as $key => $value){
            echo $value.";<br/>";
        }
    }
}