<?php
namespace App\Services;

abstract class Service
{
    /**
     * ===================================
     *      laravel服务层方法封装
     */

    /**
     * 功能：获取limit
     * User: Zhiwupei
     * @return \Illuminate\Config\Repository|mixed
     */
    public function limit()
    {
        $defaultLimit = 20;
        $maxLimit     = 100;
        $limit        = request()->get('limit');

        return is_numeric($limit) ? min($limit, $maxLimit) : $defaultLimit;
    }

    /**
     * 功能：获取page
     * User: Zhiwupei
     * @return \Illuminate\Config\Repository|mixed
     */
    public function page()
    {
        $limit = request()->get('page', 1);
        return $limit;
    }

    /**
     * 判断一个数组的值是否存在于另外一个数组当中，如果存在分别取出对应数组的下标
     */
    public function arr_key_val($arr,$arr2)
    {
        foreach($arr as $key => $value){
            foreach($arr2 as $k => $v){
                if($value == $v) {
                    $params[] = $k;
                }
            }
        }
        return $params;
    }
}