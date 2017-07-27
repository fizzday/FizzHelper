<?php

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
        }
        echo show_msg($text, true);
        echo '<META HTTP-EQUIV="refresh" CONTENT="' . $time . '; URL=' . $url . '">';
        exit;
    }
}

if (!function_exists('show_msg')) {
    /**
     * 展示信息到页面
     * @param null $msg 展示的信息
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
     * 获取随机字符串 (默认随机字母或数字, 如果 $letter 和 $number 都为 true, 则是字母开头)
     * @param int $len 长度
     * @param array $conf ['number', 'letter', 'upper'] 或者 单个的如 'number'
     * @return string       期望长度的返回值
     */
    function getCode($len = 10, $conf = ['number', 'letter'])
    {
        // 源字符串, 去除了数字 1,4,0 ; 去除了字母 i,l,o  易混淆的字符
        $origin_str['number'] = "2356789";
        $origin_str['letter'] = "abcdefghjkmnpqrstuvwxyz";
        $origin_str['upper'] = "ABCDEFGHJKMNPQRSTUVWXYZ";

        // 判断 $conf 类型
        if (!empty($conf) && !is_array($conf)) $conf = array($conf);

        // 拿到指定类型的所有字符串
        $str_all = array_reduce($conf, function ($res, $item) use ($origin_str) {
            return $res . $origin_str[$item];
        });

        // 打乱并截取对应长度的字符串
        $str_res = str_shuffle($str_all);
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
     * @param string $mode 打开方式
     */
    function file_set($file, $data, $mode = 'a')
    {
        $dir = (dirname($file));

        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $fp = fopen($file, $mode);
        fwrite($fp, $data);
        fclose($fp);

        chmod($file, 0777);

        return true;
    }
}

if (!function_exists('curl_post')) {
    /**
     * curl发送post请求
     * @param string $url
     * @param array /string $post_data
     */
    function curl_post($url, $post_data=[])
    {
        //初始化一个 cURL 对象
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // 设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);          //单位 秒，也可以使用
        // 添加post数据到请求中
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // 执行post请求，获得回复
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
if (!function_exists('curl_get')) {
    /**
     * curl发送post请求
     * @param string $url
     * @param array /string $post_data
     */
    function curl_get($url, $data = [])
    {
        if ($data) {
            if (strpos($url, '?')) $url = $url . '&' . http_build_query($data);
            else $url = $url . '?' . http_build_query($data);
        }

        //初始化一个 cURL 对象
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);          //单位 秒，也可以使用

        // 执行post请求，获得回复
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('curlUpload')) {
    /**
     * curl 上传文件
     * @param string $url 上传地址
     * @param array $data 上传的其他数据
     * @param string $file 上传的图片
     * @param string $fileKey 图片的 key 值, 相当于form下input的name值
     */
    function curlUpload_bak($url = '', $file = '', $data = array(), $fileKey = '')
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

        $response = curl_exec($request);
        curl_close($request);

        return $response;
    }

    /**
     * @param $url  地址
     * @param array $arr    数据键值对
     * @param string $files 文件(单文件直接放字符串, 多文件放数据)
     * @param string $fileKey   希望接收时的文件key名字,默认files
     * @return string 成功或失败
     */
    function curlUpload($url, $arr = [], $files = '', $fileKey='files', $token='JxRaZezavm3HXM3d9pWnYiqqQC1SJbsU')
    {
        $curl = curl_init($url);

        $fileData = [];
        if ($files) {
            if (class_exists('\CURLFile')) {// 这里用特性检测判断php版本
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
                if (is_array($files)) {
                    foreach ($files as $k=>$file) {
                        $fileData[$fileKey."[".$k."]"] = new \CURLFile($file, '', 'up_real_name');
                    }
                } else {
                    $fileData[$fileKey] = new \CURLFile($files);
                }
            } else {
                if (defined('CURLOPT_SAFE_UPLOAD')) {
                    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
                }
                if (is_array($files)) {
                    foreach ($files as $k=>$file) {
                        $fileData[$fileKey."[".$k."]"] = '@' . realpath($file);
                    }
                } else {
                    $fileData[$fileKey] = '@' . realpath($files);
                }
            }
        }

        $data = array_merge((array)$arr, $fileData);

        $header = array('token:'.$token);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置头信息的地方

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
//        $error = curl_error($curl);
        curl_close($curl);

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
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_browser = Array(
            "mqqbrowser", //手机QQ浏览器
            "opera mobi", //手机opera
            "juc", "iuc",//uc浏览器
            "fennec", "ios", "applewebKit/420", "applewebkit/525", "applewebkit/532", "ipad", "iphone", "ipaq", "ipod",
            "iemobile", "windows ce",//windows phone
            "240×320", "480×640", "acer", "android", "anywhereyougo.com", "asus", "audio", "blackberry", "blazer", "coolpad", "dopod", "etouch", "hitachi", "htc", "huawei", "jbrowser", "lenovo", "lg", "lg-", "lge-", "lge", "mobi", "moto", "nokia", "phone", "samsung", "sony", "symbian", "tablet", "tianyu", "wap", "xda", "xde", "zte"
        );
        $is_mobile = false;
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
     * @param $starttime (开始时间, 可以是时间戳,也可以是日期格式类似, endtime)
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
        $days = floor($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $hours = floor($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $mins = floor($remain / 60);
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
    function toInfiniteTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
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
     * @param string $name 显示的key名字
     * @param string $child 子级分类的key
     * @param int $deep 当前层级深度
     * @return string
     */
    function showInfiniteTree($data, $name = 'name', $child = '_child', $deep = 0)
    {
        static $html = '';
        if (is_array($data)) {
            foreach ($data as $v) {
                $html .= str_repeat('|--', $deep) . $v[$name] . PHP_EOL;
                if (!empty($v[$child])) showInfiniteTree($v[$child], $name, $child, $deep + 1);
            }
        }

        return $html;
    }
}

function start_with($fullWord, $startStr = '')
{
    return (!$fullWord || !$startStr) ? false : ((substr($fullWord, 0, mb_strlen($startStr)) == $startStr) ? true : false);
}


function config($key, $default = '')
{
    // 缓存当前配置文件到内存中
    static $conf = [];

    $args = strpos($key, '.') ? explode('.', $key) : [$key];

    $result = null;
    if ($args) {
        // 执行缓存
        if (!isset($conf[$args[0]])) $conf[$args[0]] = require CONF_PATH . $args[0] . '.php';

        $count = count($args);
        if ($count) {
            $result = $conf[$args[0]];

            for ($i = 1; $i < $count; $i++) {
                $result = $result[$args[$i]];
            }
        } else $result = $conf[$args[0]];
    }

    return $result ?: $default;
}

function cache($fileName, $data)
{
    //目录检测
    $dir = 'default.cache_path';
    \Fizzday\FizzDir\Dir::create($dir);
    //缓存文件
    $file = $dir . '/' . md5($fileName) . '.php';
    //读取数据
    if (is_null($data) && is_file($file)) {
        $data = file_get_contents($file);

        return unserialize($data) ?: null;
    }
    //写入数据
    $data = serialize($data);

    return file_put_contents($file, $data);
}

function dump($data)
{
    echo "<pre>";
    print_r($data);
    echo "<pre>";
}

function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    die;
}

/**
 * 接口json返回
 * @param $data
 * @param int $status 0成功, 大于0失败 (如常规失败定义为400, 认证失败定义为401)
 * @return string
 */
function successReturn($data = '', $status = 0, $ext = '')
{
    $re = array();

    $re['status'] = $status ? : 0;

    if (!$data) $data = $status ? "fail" : "success";
    $re['data'] = $data;

    if ($ext) $re['ext'] = $ext;

    return $re;
}

/**
 * 接口json返回
 * @param $data
 * @param int $status 0成功, 1失败, 100验证失败
 * @return string
 */
function failReturn($data = '', $status = 1)
{
    return successReturn($data, $status);
}

function jsonReturn($data = '', $status = 0, $ext = '')
{
    return json_encode(successReturn($data, $status, $ext));
}

function export_csv($filename, $data, $head='')
{
    $string=$head;
    foreach ($data as $key => $value)
    {
        foreach ($value as $k => $val)
        {
            $value[$k]=iconv('utf-8','gb2312',$value[$k]);
        }

        $string .= implode(",",$value).PHP_EOL; //用英文逗号分开
    }

    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $string;
}



