<?php

namespace app\admin\behavior;

use think\Config;

class AdminLog
{

    public function run(&$params)
    {
        if (request()->isPost())
        {
            $controllername = strtolower(request()->controller());
            $actionname = strtolower(request()->action());
            $path = str_replace('.', '/', $controllername) . '/' . $actionname;
            $rulinfo = model('AuthRule')->where(['name'=>$path])->find();
            if($rulinfo&&$rulinfo['is_log']){
                \app\admin\model\AdminLog::record();
            }

        }
    }

}
