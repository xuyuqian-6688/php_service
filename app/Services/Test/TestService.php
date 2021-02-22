<?php


namespace App\Services\Test;
use Illuminate\Http\Request;

class TestService
{
    public function test(Request $request){
        print_r($request->all());
        echo "1111";
        return getCanonicalUrl();
    }
}