<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/12 0012
 * Time: 下午 2:31
 */

namespace app\admin\controller\enterprise;


use app\common\controller\Backend;

class Importprice extends Backend
{

    public function add(){
        if ($this->request->isPost())
        {
//            Config::set('default_return_type', 'json');
            $file = $this->request->file('file');
            if(empty($file)){
                $this->error('缺少参数');
            }
            $path = $file->getRealPath();
            $riderModel = new \gyo2o\model\Salary();
            $result = $riderModel->importPrize($path);

            if($result){
                $this->success('导入成功', null,['code'=>100000]);
            }else{
                $this->error('导入失败'.$riderModel->getError(), null,['code'=>100001,'msg'=>$riderModel->getError()]);
            }


        }
        return $this->view->fetch();
    }
}