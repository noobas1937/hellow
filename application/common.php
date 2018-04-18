<?php

// 公共助手函数

if (!function_exists('__'))
{

    /**
     * 获取语言变量值
     * @param string    $name 语言变量名
     * @param array     $vars 动态变量值
     * @param string    $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name)
            return $name;
        if (!is_array($vars))
        {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return think\Lang::get($name, $vars, $lang);
    }

}

if (!function_exists('format_bytes'))
{

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime'))
{

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date'))
{

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl'))
{

    /**
     * 获取CDN的地址
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function cdnurl($url)
    {
        return preg_match("/^https?:\/\/(.*)/i", $url) ? $url : think\Config::get('site.cdnurl') . $url;
    }

}


if (!function_exists('is_really_writable'))
{

    /**
     * 判断文件或文件夹是否可写
     * @param	string $file 文件或目录
     * @return	bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/')
        {
            return is_writable($file);
        }
        if (is_dir($file))
        {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        }
        elseif (!is_file($file) OR ( $fp = @fopen($file, 'ab')) === FALSE)
        {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

}

if (!function_exists('rmdirs'))
{

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo)
        {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself)
        {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs'))
{

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest))
        {
            mkdir($dest, 0755);
        }
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        )
        {
            if ($item->isDir())
            {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir))
                {
                    mkdir($sontDir);
                }
            }
            else
            {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('mb_ucfirst'))
{

    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }

}
function adminid2name($id){
    return \think\Db::name('admin')->where('id',$id)->value('username');
}
function get_city_name($id){
    return \think\Db::name('city')->where('id',$id)->value('name');
}

function get_site_name($id){
    return \think\Db::name('site')->where('id',$id)->value('name');
}

function excelTime($date, $time = false) {
    if(function_exists('GregorianToJD')){
        if (is_numeric( $date )) {
            $jd = GregorianToJD( 1, 1, 1970 );
            $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
            $date = explode( '/', $gregorian );
            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    }else{
        $date=$date>25568?$date+1:25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs=(70 * 365 + 17+2) * 86400;
        $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
    }
    return $date;
}

function name2id($table,$name){
    return \think\Db::name($table)->where('name','LIKE',"%$name%")->value('id');
}

function id2name($table,$id){
    return \think\Db::name($table)->where('id',$id)->value('name');
}

function dishesimage($id){
    $aid = \think\Db::name('dishes')->where('id',$id)->value('dishes_image');
    $url = \think\Db::name('attachment')->where('id',$aid)->value('url');
    $qiniu = \think\Config::get('qiniu');
    return 'http://'.$qiniu['domain'].'/'.$url;
}

function dishesprice($id){
    return \think\Db::name('dishes')->where('id',$id)->value('dishes_price');
}

function getIdentifier($len = 32)
{
    list($msec, $sec) = explode(" ", microtime());
    $msec = str_replace('.', '', strval($msec));
    $timestamp = date('YmdHis', $sec);
    $identifier = $timestamp . $msec . (string)rand(99999999, 1000000000);
    $identifier = substr($identifier, -$len);
    $identifier = str_pad($identifier, $len, 0);
    return $identifier;
}