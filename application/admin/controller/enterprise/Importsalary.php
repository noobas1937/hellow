<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 下午 3:35
 */

namespace app\admin\controller\enterprise;


use app\common\controller\Backend;

class Importsalary extends Backend
{

    public function add(){
        if ($this->request->isPost())
        {
//            Config::set('default_return_type', 'json');
            $file = $this->request->file('file');
            $year = $this->request->post('year');
            $month = $this->request->post(('month'));
            if(empty($file)||empty($year)||empty($month)){
                $this->error('缺少参数');
            }
            $path = $file->getRealPath();
            $riderModel = new \gyo2o\model\Salary();
            $result = $riderModel->importExcel($path,$year,$month);

            if($result){
                $this->success('导入成功', null,['code'=>100000]);
            }else{
                $this->error('导入失败'.$riderModel->getError(), null,['code'=>100001,'msg'=>$riderModel->getError()]);
            }


        }
        return $this->view->fetch();
    }
}