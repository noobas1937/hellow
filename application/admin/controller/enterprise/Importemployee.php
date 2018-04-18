<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 下午 4:15
 */

namespace app\admin\controller\enterprise;


use app\common\controller\Backend;

class Importemployee extends Backend
{
    public function add(){
        if ($this->request->isPost())
        {
//            Config::set('default_return_type', 'json');
            $param = $this->request->post();
            $file = $this->request->file('file');
            if(empty($param) || empty($file)){
                $this->error('缺少参数');
            }
            $colun = [];
            foreach ($param as $key=>$value){
                if(empty($value)){
                    continue;
                }
                if(!preg_match('/^[a-z]$/i',$value)){
                    $this->error('所在列必须是字母');
                }
                $value = strtoupper($value);
                $colun[$value] = $key;
            }
            if(!array_search('idcard',$colun)){
                $this->error('身份证所在列不能为空');
            }
            $path = $file->getRealPath();
            $riderModel = new \gyo2o\model\TbEmployee();
            $result = $riderModel->importExcel($path,$colun);

            if($result){
                $this->success('导入成功', null,['code'=>100000]);
            }else{
                $this->error('导入失败'.$riderModel->getError(), null,['code'=>100001,'msg'=>$riderModel->getError()]);
            }


        }
        return $this->view->fetch();
    }

}