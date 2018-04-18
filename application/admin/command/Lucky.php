<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 下午 3:07
 */

namespace app\admin\command;


use gyo2o\dao\LuckyDrawDao;
use gyo2o\model\LuckyDraw;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Lucky extends Command
{
    public function configure()
    {
        $this->setName('lucky')->setDescription('Cancel Lucky Apply');
    }

    public function execute(Input $input, Output $output)
    {
        $luckyDrawModel = new LuckyDraw();
        $luckyDrawDao = new LuckyDrawDao();
        $luckydrawIds = $luckyDrawDao->where(['type'=>2,'end_date'=>['<',date('Y-m-d H:i:s')]])->column('id');
        foreach ($luckydrawIds as $id){
            $result = $luckyDrawModel->endLucky($id);
            if($result){
                $output->writeln('成功');
            }else{
                $output->warning($luckyDrawModel->getError());
            }
        }

    }

}