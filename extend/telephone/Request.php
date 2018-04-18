<?php
/**
 * Created by PhpStorm.
 * User: liuwen
 * Date: 16/6/5
 * Time: 下午10:26
 */

namespace telephone;
class Request
{
    public function fsockopen(array $data, $url)
    {
        $post_data = http_build_query($data);

        $url_info = parse_url($url);

        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $post_data;

        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        if ($gets != '') {
            $start = strpos($gets, '<?xml');
            if ($start > 0) {
                $gets = substr($gets, $start);
            }
        }
        return $gets;
    }
}