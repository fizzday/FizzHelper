# FizzHelper
非常实用的php函数小工具(the useful helpers for widely used)
## 使用方法
- 直接使用composer命令
```
composer require fizzday/fizzhelper
```
- 写入composer.json
```
{
    "require": {
        "fizzday/fizzhelper": "dev-master"
    }
}
```

## 函数列表举例
- getCode() 获取随机字符串, 源码如下:
```
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
```
很好用的获取随机字符串的函数, 可以指定长度, 类型(包括大小写字母和数字), 如:  
```
<?php
echo getCode(10);  // y8k3ecs8g

echo getCode(4, "letter"); // keyk

echo getCode(6, ['upper', 'letter']); // kUPsFz
```
不难看出, 可以任意生成需要的字符串
 
 ---
 
- v(), d() 格式化打印, 源码如下:  
```
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
```
一目了然, `v()`格式化打印, 继续向下执行; `d()`格式化打印并终止, 这个两个函数的意义就是把我们从 `echo "<pre>"` 和 `print_r()`中解脱出来

类似的还有很多, 平常一点点来收集整理
