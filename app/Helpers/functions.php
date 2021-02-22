<?php

/**
 * =================================
 *   常用函数封装
 * =================================
 */

/**
 * @param null $data
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Symfony\Component\HttpFoundation\Response
 *      成功返回
 */
function successReturn($data = null)
{
    $data = emptyToObj($data);
    $code = 200;
    $msg  = trans('code.' . $code);
    $data = ['code' => (int)$code, 'msg' => $msg, 'data' => $data];
    return response($data, 200);
}


/**
 * 数据为空时返回空对象
 * @param $data
 */
function emptyToObj($data = '')
{
    if (empty($data)) {
        return null;
    }
    return $data;
}

/**
 * @param $code
 * @param string $msg
 * @param null $data
 * @param array $header
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Symfony\Component\HttpFoundation\Response
 *      失败返回
 */
function errorReturn($code, $msg = '', $data = null, $header = [])
{
    if (empty($msg)) {
        $msg = trans('code.' . $code);
    }
    $data   = ['code' => (int)$code, 'msg' => $msg, 'data' => $data];
    $status = in_array($code, [401]) ? $code : 200;
    return response($data, $status, $header);
}


/**
 *  实例化模型
 */
if (!function_exists('model')) {
    /**
     * new 模型
     * @param $path
     */
    function model(string $path): object
    {
        return new $path;
    }
}

/**
 *  获取当前控制器与方法
 */
function getCurrentAction()
{
    $route = \Route::currentRouteAction();
    list($class, $action) = explode('@', $route);

    return ['controller' => $class, 'action' => $action];
}

/**
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 * 无限分类 数组转 树形结构
 */
function list_to_tree($list, $pk = 'id', $pid = 'parent_id', $child = 'child', $root = 0)
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
                $tree[$data[$pk]] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent                     =& $refer[$parentId];
                    $parent[$child][$data[$pk]] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 获取文件后缀
 * @param $file
 * @return mixed
 */
function getExtension($file)
{
    if (strpos($file, '?') !== false) {
        $file = explode('?', $file)[0];
    }
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    return $ext;
}

/**
 * 获取当前页面url
 * @return string
 */
function getCanonicalUrl($type = 0)
{
    //$canonical = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $canonical = request()->fullUrl();
    if (strpos($canonical, '-p') !== false) {
        $canonical = explode('-p', $canonical)[0];
    }
    if ($type == 1) {
        $canonical = str_replace("//www.", "//m.", $canonical);
    }
    if ($type == 2) {
        $canonical = str_replace("//m.", "//www.", $canonical);
    }
    return $canonical;
}

/**
 * 二维数组根据某字段重组
 * @param $arr
 * @param $name
 * @return array
 */
function groupListArray($arr, $name)
{
    $array = [];
    foreach ($arr as $k => $v) {
        $array[$v[$name]] = $v;
    }
    return $array;
}

/**
 * @param $list
 * @param int $pid
 * @param int $level
 * @return array
 *      递归函数实现无限极分类
 */
function get_cate_list($list,$pid=0,$level=0) {
    $tree = array();
    foreach($list as $row) {
        if($row['pid']==$pid) {
            $row['level'] = $level;
            $tree[] = $row;
            get_cate_list($list, $row['id'], $level + 1);
        }
    }
    return $tree;
}

/**
 * @param $list
 * @return array|mixed
 *      父子级树状结构排序
 */
function get_tree_list($list){
    //将每条数据中的id值作为其下标
    $temp = [];
    foreach($list as $v){
        $v['son'] = [];
        $temp[$v['id']] = $v;
    }
    //获取分类树
    foreach($temp as $k=>$v){
        $temp[$v['pid']]['son'][] = &$temp[$v['id']];
    }
    return isset($temp[0]['son']) ? $temp[0]['son'] : [];
}

/**
 * @param $list
 * @param $key
 * @param int $sort
 * @return bool
 *      二维数组排序
 */
function get_array_sort($list,$key,$sort=SORT_DESC) {
    $array = array_column($list,$key);
    $res = array_multisort($array,$sort,$list);
    return $res;
}

function sortes(){
    /**
     * 二维数组根据某个字段进行排序#
     */
    array_multisort(array_column($data, 'sort'), SORT_DESC, $data);
    array_multisort(array_column($data, 'sort1'), SORT_DESC, array_column($data, 'sort2'), SORT_DESC, $data);

    /**
     * 二维数据根据某个 key 当键名#
     */
    array_column($arr,$column_name,$key_name);
}








