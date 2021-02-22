<?php


namespace App\Http\Requests\Test;

use App\Http\Requests\FormsRequest;

class TestRequest extends FormsRequest
{
    /**
     * @return array
     *      验证规则
     */
    public function rulesData()
    {
        return [
            'index'=>[
                'name'=>'required',
            ]
        ];
    }
}