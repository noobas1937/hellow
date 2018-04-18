<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 上午 9:59
 */

namespace gyo2o\model;


use gyo2o\BaseModel;
use fast\Random;
use gyo2o\dao\attachmentDao;
use think\Config;
class Attachment extends BaseModel
{
    public function uploadImg($file){
        //判断是否已经存在附件
        $sha1 = $file->hash();

        $upload = Config::get('upload');

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int) $upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix ? $suffix : 'file';
        $replaceArr = [
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        $domain = Config::get('qiniu.domain');
        //上传到七牛云
        $qiniu = new \gmars\qiniu\Qiniu();
        if($key = $qiniu->upload($uploadDir.$fileName)){
            $params = array(
                'url' =>$key,
                'create_date' => date('Y-m-d H:i:s')
            );
            $attachment = new attachmentDao();
            $attachment->save($params);
            $aid['id'] = $attachment->id;
            return [ 'url' => 'http://'.$domain.'/'.$key, 'id'  => $aid['id']];

        }else{
            return false;
        }
    }

}