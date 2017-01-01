<?php
/**
 * 常用函数结合
 *
 * @apireturn($data = '', $status = "0000", $arr = '') api 返回json
 * @d($data) 格式化打印, 并终止
 * @error($text = "", $url = '', $time = 2) 跳转加提示 -- 错误跳转
 * @file_set($file, $data, $mode = 'a') 指针写入文件
 * @getCode($len = 10, $conf = ['number', 'letter']) 获取随机字符串 (默认随机字母或数字, 如果 $letter 和 $num 都为 true, 则是字母开头)
 * @getTextareaRealStr($textareaStr = "") 获取文本框的 文本 兼容字符串
 * @mbSubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) 字符串截取，支持中文和其他编码
 * @returnTrue($data = []) 类的方法返回数据(成功返回)
 * @returnTrue($data = []) 类的方法返回数据(失败返回)
 * @shortenSinaUrl($long_url) 新浪长地址转短地址接口
 * @show_msg($msg = null, $return = false) 展示信息到页面
 * @start_with($word, $str) 是否以某个字符串开头
 * @success($text = "", $url = '', $time = 1) 跳转加提示 -- 成功跳转
 * @v($data) 格式化打印, 不终止
 */

if (!function_exists('vv')) {
    /**
     * 格式化打印, 不终止
     * @param string $data
     */
    function vv($data = '')
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('vd')) {
    /**
     * 格式化打印, 并终止
     * @param string $data
     */
    function vd($data = '')
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

if (!function_exists('apireturn')) {
    /**
     * 接口json返回
     * @param $data
     * @param int $status 0成功, 1失败, 100验证失败
     * @return string
     */
    function apireturn($data = '', $status = 0)
    {
        $re           = array();
        $re['status'] = $status;
        $re['data']   = '';
        $re['msg']    = '';

        if ($status == 0) {
            $re['data']   = $data;
            $re['status'] = 0;
            $re['msg']    = 'success';
        } else {
            $re['msg'] = $data;
            if (empty($data)) {
                $re['msg'] = 'fail';
                if ($status == 100) $re['msg'] = 'verification failed';
            }
        }
//        if (empty($re['data'])) unset($re['data']);

        echo json_encode($re, JSON_UNESCAPED_UNICODE);
        die;
    }
}

if (!function_exists('internalreturn')) {
    /**
     * php内部方法返回
     * @param $data
     * @param int $status 0成功, 1失败
     * @return string
     */
    function internalreturn($data = '', $status = 0)
    {
        $re           = array();
        $re['status'] = $status;
        $re['data']   = '';
        $re['msg']    = '';

        if ($status == 0) {
            $re['data']   = $data;
            $re['status'] = 0;
            $re['msg']    = 'success';
        } else {
            $re['msg'] = $data;
            if (empty($data)) {
                $re['msg'] = 'fail';
            }
        }

//        if (empty($re['data'])) unset($re['data']);

        return $re;
        die;
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
            $url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/';
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
//            $url = '/';
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
     * @param array $conf ['number', 'letter', 'upper'] 或者 单个的如 'num'
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
        $str_res       = str_shuffle($str_all);
        $str_res_count = strlen($str_res);

        // 根据长度取对应的数据
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $index = mt_rand(0, $str_res_count - 1);
            $str .= $str_res[$index];
        }

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

if (!function_exists('mbSubstr')) {
    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str     需要转换的字符串
     * @param string $start   开始位置
     * @param string $length  截取长度
     * @param string $charset 编码格式
     * @param string $suffix  截断显示字符
     * @return string
     */
    function mbSubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }

        return $suffix ? $slice . '...' : $slice;
    }
}

if (!function_exists('shortenSinaUrl')) {
    /**
     * 新浪长地址转短地址接口
     * @param $long_url
     * @return mixed
     */
    function shortenSinaUrl($long_url)
    {
        $apiKey   = '1681459862';//这里是你申请的应用的API KEY，随便写个应用名就会自动分配给你
        $long_url = urlencode($long_url);
        $apiUrl   = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . $apiKey . '&url_long=' . $long_url;
        $curlObj  = curl_init();
        curl_setopt($curlObj, CURLOPT_URL, $apiUrl);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        $response = curl_exec($curlObj);
        curl_close($curlObj);
        $json = json_decode($response);

        return $json[0]->url_short;
    }
}

if (!function_exists('curlPost')) {
    /**
     * curl发送post请求
     * @param string $url
     * @param array /string $post_data
     */
    function curl_post($url, $post_data)
    {
        //初始化一个 cURL 对象
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // 设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, 1);
        // 添加post数据到请求中
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // 执行post请求，获得回复
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('curlUpload')) {
    /**
     * curl 上传文件
     * @param string $url   上传地址
     * @param array $data   上传的其他数据
     * @param string $file  上传的图片
     * @param string $fileKey   图片的 key 值, 相当于form下input的name值
     */
    function curlUpload($url = '', $data = array(), $file = '', $fileKey = '')
    {
        if (empty($fileKey)) $fileKey = 'files';

        if (!empty($file)) {
            if (is_array($file)) {
                foreach ($file as $key => $value) {
                    $data[$fileKey . '[' . $key . ']'] = new \CURLFile(realpath($value));
                }
            } else {
                $data[$fileKey . '[0]'] = new \CURLFile(realpath($file));
            }
        }

        $request = curl_init($url);

        curl_setopt($request, CURLOPT_POST, true);

        curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        echo curl_exec($request);
        curl_close($request);
    }
}

if (!function_exists('sendPost')) {
    /**
     * 发送post请求
     * @param string $url      请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    function send_post($url, $post_data)
    {

        $postdata = http_build_query($post_data);
        $options  = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context  = stream_context_create($options);
        $result   = file_get_contents($url, false, $context);

        return $result;
    }
}

if (!function_exists('sendGet')) {
    /**
     * 发送 get 请求
     * @param string $url     请求地址
     * @param array $get_data post键值对数据
     * @return string
     */
    function send_get($url, $get_data = '')
    {

        $postdata = http_build_query($get_data);

        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 5 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);

        return $result;
    }
}

if (!function_exists('isMobile')) {
    /**
     * 判断是否是手机端
     * @return bool
     */
    function isMobile()
    {
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $mobile_browser = Array(
            "mqqbrowser", //手机QQ浏览器
            "opera mobi", //手机opera
            "juc", "iuc",//uc浏览器
            "fennec", "ios", "applewebKit/420", "applewebkit/525", "applewebkit/532", "ipad", "iphone", "ipaq", "ipod",
            "iemobile", "windows ce",//windows phone
            "240×320", "480×640", "acer", "android", "anywhereyougo.com", "asus", "audio", "blackberry", "blazer", "coolpad", "dopod", "etouch", "hitachi", "htc", "huawei", "jbrowser", "lenovo", "lg", "lg-", "lge-", "lge", "mobi", "moto", "nokia", "phone", "samsung", "sony", "symbian", "tablet", "tianyu", "wap", "xda", "xde", "zte"
        );
        $is_mobile      = false;
        foreach ($mobile_browser as $device) {
            if (stristr($user_agent, $device)) {
                $is_mobile = true;
                break;
            }
        }

        return $is_mobile;
    }
}

if (!function_exists('timeDiff')) {
    /**
     * 计算时间差(日,时分秒)
     * @param $starttime   (开始时间, 可以是时间戳,也可以是日期格式类似, endtime)
     * @param $endtime
     * @param string $type (day, hour, minute, second)
     * @return array|bool|mixed
     */
    function timeDiff($starttime, $endtime, $type = '')
    {
        $s = strpos($starttime, '-');
        $e = strpos($endtime, '-');

        if ($s) $starttime = strtotime($starttime);
        if ($e) $endtime = strtotime($endtime);

        //计算天数
        $timediff = abs($endtime - $starttime);
        $days     = floor($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $hours  = floor($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $mins   = floor($remain / 60);
        //计算秒数
        $secs = $remain % 60;

        $res = array("day" => $days, "hour" => $hours, "minute" => $mins, "second" => $secs);
        if (!empty($type)) {
            if (!in_array($type, array_keys($res))) return false;

            return $res[$type];
        }

        return $res;
    }
}

if (!function_exists('toInfiniteTree')) {
    /**
     * 把返回的数据集转换成无限级分类Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function toInfiniteTree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('showInfiniteTree')) {
    /**
     * 展示无限级分类为树状结构(根据情况整改显示的html)
     * @param $data             数据列表
     * @param string $name      显示的key名字
     * @param string $child     子级分类的key
     * @param int $deep         当前层级深度
     * @return string
     */
    function showInfiniteTree($data, $name='name', $child='_child', $deep=0)
    {
        static $html = '';
        if (is_array($data)) {
            foreach ($data as $v) {
                $html .= str_repeat('|--', $deep).$v[$name].PHP_EOL;
                if (!empty($v[$child]))  showInfiniteTree($v[$child], $name, $child, $deep+1);
            }
        }

        return $html;
    }
}

if (!function_exists('returnFalse')) {

}


