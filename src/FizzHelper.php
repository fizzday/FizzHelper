<?php
/**
 * 常用函数结合
 *
 * @apireturn($data = '', $status = "0000", $arr = '') api 返回json
 * @d($data) 格式化打印, 并终止
 * @error($text = "", $url = '', $time = 2) 跳转加提示 -- 错误跳转
 * @getCode($len = 10, $conf = ['number', 'letter']) 获取随机字符串 (默认随机字母或数字, 如果 $letter 和 $num 都为 true, 则是字母开头)
 * @getTextareaRealStr($textareaStr = "") 获取文本框的 文本 兼容字符串
 * @file_set($file, $data, $mode = 'a') 指针写入文件
 * @returnTrue($data = []) 类的方法返回数据(成功返回)
 * @returnTrue($data = []) 类的方法返回数据(失败返回)
 * @show_msg($msg = null, $return = false) 展示信息到页面
 * @start_with($word, $str) 是否以某个字符串开头
 * @success($text = "", $url = '', $time = 1) 跳转加提示 -- 成功跳转
 * @v($data) 格式化打印, 不终止
 */

if (!function_exists('v')) {
    /**
     * 格式化打印, 不终止
     * @param string $data
     */
    function v($data = '')
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('d')) {
    /**
     * 格式化打印, 并终止
     * @param string $data
     */
    function d($data = '')
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }
}

if (!function_exists('returnTrue')) {
    /**
     * 类的方法返回数据(成功返回)
     * @param array $data
     * @return array
     */
    function returnTrue($data = [])
    {
        $data['status'] = 1;
        $data['data']   = $data;
        $data['msg']    = 'success';

        return $data;
    }
}

if (!function_exists('returnFalse')) {
    /**
     * 类的方法返回数据(失败返回)
     * @param array $data
     * @return array
     */
    function returnFalse($data = [])
    {
        $data['status'] = 0;
        $data['data']   = $data;
        $data['msg']    = 'fail';

        return $data;
    }
}

if (!function_exists('error')) {
    /**
     * 跳转加提示 -- 错误跳转
     * @param string $text
     * @param string $url
     * @param number $time
     */
    function error($text = "", $url = '', $time = 2)
    {
        if (empty($text)) $text = '操作有误，请重新操作';
        if (empty($url)) {
            $url = $_SERVER["HTTP_REFERER"];
        }
        echo show_msg($text, true);
        echo '<META HTTP-EQUIV="refresh" CONTENT="' . $time . '; URL=' . $url . '">';
        exit;
    }
}

if (!function_exists('success')) {
    /**
     * 跳转加提示 -- 成功跳转
     * @param string $text
     * @param string $url
     * @param number $time
     */
    function success($text = "", $url = '', $time = 1)
    {
        if (empty($text)) $text = '操作成功';
        if (empty($url)) {
            $url = $_SERVER["HTTP_REFERER"];
        }
        echo show_msg($text, true);
        echo '<META HTTP-EQUIV="refresh" CONTENT="' . $time . '; URL=' . $url . '">';
        exit;
    }
}

if (!function_exists('show_msg')) {
    /**
     * 展示信息到页面
     * @param null $msg    展示的信息
     * @param bool $return 是否作为内容返回
     * @return string
     */
    function show_msg($msg = null, $return = false)
    {
        $text =
            <<<EOT
    <html><head><title>{$msg}</title><style>html,body{height:100%}body{margin:0;padding:0;width:100%;display:table;font-weight:100;font-family:Lato}.container{text-align:center;display:table-cell;vertical-align:middle}.content{text-align:center;display:inline-block}.title{font-size:4rem}</style></head><body><div class="container"><div class="content"><div class="title">{$msg}</div></div></div></body></html>
EOT;
        if ($return) return $text;

        echo $text;
        die;
    }
}

if (!function_exists('getCode')) {
    /**
     * 获取随机字符串 (默认随机字母或数字, 如果 $letter 和 $num 都为 true, 则是字母开头)
     * @param int $len    长度
     * @param array $conf ['number', 'letter', 'upper'] 或者 单个的 'num'
     * @return string       期望长度的返回值
     */
    function getCode($len = 10, $conf = ['number', 'letter'])
    {
        // 源字符串, 去除了数字 1,4,0 ; 去除了字母 i,l,o  易混淆的字符
        $origin_str['number'] = "2356789";
        $origin_str['letter'] = "abcdefghjkmnpqrstuvwxyz";
        $origin_str['upper']  = "ABCDEFGHJKMNPQRSTUVWXYZ";

        // 判断 $conf 类型
        if (!empty($conf) && !is_array($conf)) $conf = array($conf);

        // 拿到指定类型的所有字符串
        $str_all = array_reduce($conf, function ($res, $item) use ($origin_str) {
            return $res . $origin_str[$item];
        });

        // 打乱并截取对应长度的字符串
        $str = substr(str_shuffle($str_all), 0, $len);

        return $str;
    }
}

if (!function_exists('start_with')) {
    /**
     * 是否以某个字符串开头
     * @param  string $word 原生字符串, 如: withName
     * @param  string $str  标识字符串, 如: with
     * @return boolean      返回判断结果
     */
    function start_with($word, $str)
    {
        if (!empty($word) && !empty($str)) {
            $len = strlen(trim($str));
            if (substr($word, 0, $len) == $str) return true;
        }

        return false;
    }
}

if (!function_exists('getTextareaRealStr')) {
    /**
     * 获取文本框的 文本 兼容字符串
     * @param string $str
     * @return string
     */
    function getTextareaRealStr($textareaStr = "")
    {
        $str = "";
        if ($textareaStr) {
            $strArray = explode("\r\n", $textareaStr);
            foreach ($strArray as $item) {
                $str .= $item . PHP_EOL;
            }
        }

        return $str;
    }
}

if (!function_exists('file_set')) {
    /**
     * 指针写入文件
     * @param unknown $name 文件名
     * @param unknown $data 内容
     * @param string $mode  打开方式
     */
    function file_set($file, $data, $mode = 'a')
    {
        if (!$file || !$data) return false;

//    $dir = dirname($file);
//
//    if (!is_dir($dir)) mkdir($dir, 0777, true);

        $fp = fopen($file, $mode);
        fwrite($fp, $data);
        fclose($fp);

        chmod($file, 0777);

        return true;
    }
}

if (!function_exists('apireturn')) {
    /**
     * api 返回json
     * @param string $data   返回数据(或提示)
     * @param string $status 返回状态
     * @param string $arr    其他信息
     */
    function apireturn($data = '', $status = "0000", $arr = '')
    {
        $re = [];

        $re['code'] = $status;

        if ($status == '0000') {

            $re['info'] = $arr;

            $data_res = array();

            if (!empty($data[0])) {
                $re['data'] = $data;
            } else {

                $data_res[] = $data;
                $re['data'] = $data_res;

                if ($data == "[]" || empty($data)) {
                    $re['data'] = $data;
                }

            }

            $re['msg'] = 'success';

        } else if ($status == "9999") {
            $re['msg'] = $data;
        }

        echo json_encode($re);

        die;
    }
}

if (!function_exists('returnFalse')) {

}


