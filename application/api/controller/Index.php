<?php
namespace app\api\controller;

class Index extends BaseApi
{
    public function index()
    {
        $url=parent::getUrl();
        $lb=db("lb")->field("image")->where(["fid"=>1,"status"=>1])->order(["sort asc","id desc"])->select();
        foreach($lb as $k =>$v){
            $lb[$k]['image']=$url.$v['image'];
        }
        $arr=[
            'error_code'=>0,
            'msg'=>'订单生成失败',
            'data'=>[
                'lb'=>$lb,
            ]
        ];
    
        echo \json_encode($arr);
    }
}