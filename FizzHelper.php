<?php
/**
 * 常用函数结合
 * 
 * @v($data) 格式化打印, 不终止
 * @d($data) 格式化打印, 并终止
 * @returnTrue($data = []) 类的方法返回数据(成功返回)
 * @returnTrue($data = []) 类的方法返回数据(失败返回)
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


