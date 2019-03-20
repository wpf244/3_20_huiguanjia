<?php
namespace app\api\controller;

use think\Controller;
use think\Request;


class BaseApi extends Controller
{
    public function getUrl(){
        $url=Request::instance()->domain();
        
        return $url;
    }
}