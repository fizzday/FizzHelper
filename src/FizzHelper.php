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
     * 接口json返回
     * @param $data
     * @param int $status 0成功, 1失败, 100验证失败
     * @return string
     */
    function apireturn($data='', $status=0)
    {
        $re = array();
        $re['status'] = $status;
        $re['data'] = '';
        $re['msg'] = '';

        if ($status == 0) {
            $re['data'] = $data;
            $re['status'] = 0;
            $re['msg'] = 'success';
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
    function internalreturn($data='', $status=0)
    {
        $re = array();
        $re['status'] = $status;
        $re['data'] = '';
        $re['msg'] = '';

        if ($status == 0) {
            $re['data'] = $data;
            $re['status'] = 0;
            $re['msg'] = 'success';
        } else {
            $re['msg'] = $data;
            if (empty($data)) {
                $re['msg'] = 'fail';
            }
        }
//        if (empty($re['data'])) unset($re['data']);

        return $re;
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

if (!function_exists('curl_post')) {
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

if (!function_exists('send_post')) {
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

if (!function_exists('send_get')) {
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

if (!function_exists('conf')) {
    function conf($key = '', $defaultValue = '')
    {
        $conf               = array();
        $conf['levelNum']   = 9;
        $conf['percentAll'] = array(
            array('level' => 1, 'percent' => 0.1),
            array('level' => 2, 'percent' => 0.1),
            array('level' => 3, 'percent' => 0.1),
            array('level' => 4, 'percent' => 0.1),
            array('level' => 5, 'percent' => 0.1),
            array('level' => 6, 'percent' => 0.1),
            array('level' => 7, 'percent' => 0.1),
            array('level' => 8, 'percent' => 0.1),
            array('level' => 9, 'percent' => 0.1)
        );

        if (empty($key)) {
            if (empty($defaultValue)) return $conf;

            return $defaultValue;
        }

        // 判断key是否是多级的
        if (strpos($key, '.') > 0) {
            $keyArr = explode('.', $key);

            static $resConf = array();
            foreach ($keyArr as $v) {
                $resConf = $conf[$v];
            }

            return $resConf;
        }

        return $conf[$key];
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

if (!function_exists('curlUpload')) {
    /**
     * curl 上传文件
     * @param string $url
     * @param array $data
     * @param string $file
     * @param string $fileKey
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

if (!function_exists('matchCash')) {
    /**
     * 互助匹配
     * 数据示例:
     * // $payList[] = array('uid'=>11, 'money'=>200);
     * // $payList[] = array('uid'=>12, 'money'=>200);
     * // $payList[] = array('uid'=>13, 'money'=>500);
     *
     * // $getList[] = array('uid'=>21, 'money'=>100);
     * // $getList[] = array('uid'=>22, 'money'=>200);
     * // $getList[] = array('uid'=>23, 'money'=>200);
     *
     * // $adminList[] = array('uid'=>31);
     * // $adminList[] = array('uid'=>32);
     * // $adminList[] = array('uid'=>33);
     *
     * @param $payList      提供帮助列表
     * @param $getList      获取帮助列表
     * @param $adminList    系统账号列表
     * @param array $orc    缓存容器
     * @return array
     */
    function matchCash($payList, $getList, $adminList, $orc = array('getIndex' => 0, 'getMoney' => 0, 'payIndex' => 0, 'payMoney' => 0))
    {
        static $matchList = array();
        // static $orc['getIndex'] = 0; // 当前收款人序号
        // static $orc['getMoney'] = 0; // 当前收款人待匹配的余额
        // static $payIndex = 0; // 当前打款人序号
        // static $orc['payMoney'] = 0; // 当前打款人待匹配的余额

        // 将操作的金额放入容器
        if (!$orc['payMoney']) $orc['payMoney'] = isset($payList[$orc['payIndex']])?$payList[$orc['payIndex']]['money']:0;
        if (!$orc['getMoney']) $orc['getMoney'] = isset($getList[$orc['getIndex']])?$getList[$orc['getIndex']]['money']:0;

        // 判断收款人是否匹配完毕
        if (empty($getList[$orc['getIndex']])) { // 匹配系统账户
            $countAdmin = count($adminList);
            $adminIndex = mt_rand(0, $countAdmin-1);
            $match      = array('payuid' => $payList[$orc['payIndex']]['uid'], 'getuid' => $adminList[$adminIndex]['uid'], 'money' => $orc['payMoney']);
            $matchList[]     = $match;
            $orc['payMoney'] = 0;
            $orc['payIndex']++;

            if (!empty($payList[$orc['payIndex']])) {
                matchCash($payList, $getList, $adminList, $orc);
            }

            return $matchList;
        }

        // 判断提供帮助的金额是否大于将要接收帮助的人的提现金额
        $payListLeave = array_slice($payList, ($orc['payIndex']));
        $paySumMoney  = sumFieldFromTwiceArray('money', $payListLeave) + $orc['payMoney'];

        if ($paySumMoney < $getList[$orc['getIndex']]['money']) {
            $getList = array();
            matchCash($payList, $getList, $adminList, $orc);

            return $matchList;
        }

        $minus = $orc['payMoney'] - $orc['getMoney'];
        if ($minus > 0) {   // 打款的有剩余
            $money_real      = $orc['getMoney'];    // 实际订单金额
            $match           = array('payuid' => $payList[$orc['payIndex']]['uid'], 'getuid' => $getList[$orc['getIndex']]['uid'], 'money' => $money_real);
            $orc['payMoney'] = $minus;         // 打款有剩余
            $orc['getMoney'] = 0;              // 收款重置为0
            $orc['getIndex']++;
        } elseif ($minus < 0) {
            $money_real      = $orc['payMoney'];    // 实际订单金额
            $match           = array('payuid' => $payList[$orc['payIndex']]['uid'], 'getuid' => $getList[$orc['getIndex']]['uid'], 'money' => $money_real);
            $orc['getMoney'] = abs($minus);         // 收款有剩余
            $orc['payMoney'] = 0;              // 打款重置为0
            $orc['payIndex']++;
        } else {
            $money_real      = $orc['payMoney'];
            $match           = array('payuid' => $payList[$orc['payIndex']]['uid'], 'getuid' => $getList[$orc['getIndex']]['uid'], 'money' => $money_real);
            $orc['getMoney'] = 0;
            $orc['payMoney'] = 0;
            $orc['getIndex']++;
            $orc['payIndex']++;
        }
        $matchList[] = $match;

        if (isset($payList[$orc['payIndex']]) || ($orc['payMoney'] > 0)) {
            matchCash($payList, $getList, $adminList, $orc);
        }

        return $matchList;
    }
}

if (!function_exists('sumFieldFromTwiceArray')) {
    /**
     * 获取二维数组中某个字段的和
     * @param $field    字段
     * @param $arr      要获取的二维数组
     * @return int
     */
    function sumFieldFromTwiceArray($field, $arr)
    {
        $str = 0;
        $arr = json_decode(json_encode($arr), true);
        foreach ($arr as $k => $v) {
            if (!empty($v[$field])) {
                $str += $v[$field];
            }
        }

        return $str;
    }
}

if (!function_exists('returnFalse')) {

}


