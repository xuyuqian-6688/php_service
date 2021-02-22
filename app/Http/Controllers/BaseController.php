<?php

use App\Models\User;

class BaseController extends Controller
{
    /**
     * 获取token
     */
    public function get_token(User $user, $time = 12*60)
    {
         //setTTL:以分钟为单位
         $token = auth('user')->setTTL($time)->fromUser($user);
         //查找 agent_type
         if (isset($user->type) && $user->type == 2) {
             $agent_type = TbAgentUserInfo::where('user_bind_id', $user->id)->first();
         }
 
         return [
             'access_token' => $token,
             'expires_in' => auth('user')->factory()->getTTL() * 60,
         ];
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        $result = auth('user')->refresh();
        return true??false;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        auth('user')->logout();

        return true;
    }

    /**
     * 分页数据
     */
    public function limit()
    {
        $defaultLimit = 20;
        $maxLimit     = 100;
        $limit        = request()->get('limit');

        return is_numeric($limit) ? min($limit, $maxLimit) : $defaultLimit;
    }

    /**
     * 判断一个值是否存在于一个数组中，存在则取出下标
     */
    public function arr_key($arr,$value)
    {
        foreach($arr as $key => $value){
            if($value == $value){
                return $key;
            }
        }
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