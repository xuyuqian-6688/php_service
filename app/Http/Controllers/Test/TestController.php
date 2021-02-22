<?php

namespace App\Http\Controllers\Test;
use App\Http\Controllers\Controller; 
use App\Http\Requests\Test\TestRequest;
use App\Services\Test\TestService;

class TestController extends Controller{
    public function index(TestRequest $request){
        return (new TestService())->test($request);
    }
}