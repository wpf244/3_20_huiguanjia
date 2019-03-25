<?php
namespace app\api\controller;

class Index extends BaseApi
{
    public function index()
    {
        $url=parent::getUrl();
        //banner图
        $lb=db("lb")->field("image")->where(["fid"=>1,"status"=>1])->order(["sort asc","id desc"])->select();
        foreach($lb as $k =>$v){
            $lb[$k]['image']=$url.$v['image'];
        }

        //需求广告
        $demand=db("lb")->field("desc")->where("fid",2)->find();
        $demand['desc']=strip_tags($demand['desc']);

        //服务保障
        $ensure=db("lb")->field("name,image")->where(["fid"=>3,"status"=>1])->order(["sort asc","id desc"])->select();
        foreach($ensure as $ke =>$ve){
            $ensure[$ke]['image']=$url.$ve['image'];
        }

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'lb'=>$lb,
                'demand'=>$demand,
                'ensure'=>$ensure,
            ]
        ];
    
        echo \json_encode($arr);
    }
    public function demand()
    {
        //会议类型
        $type=db("demand")->field("id,name")->where("type",1)->order(["sort asc","id desc"])->select();

        //会议时长
        $times=db("demand")->field("id,name")->where("type",2)->order(["sort asc","id desc"])->select();

        //参会人数
        $number=db("demand")->field("id,name")->where("type",3)->order(["sort asc","id desc"])->select();

        //会议预算
        $money=db("demand")->field("id,name")->where("type",4)->order(["sort asc","id desc"])->select();

        //会议需求
        $ment=db("demand")->field("id,name")->where("type",5)->order(["sort asc","id desc"])->select();

        //会议城市
        $city=db("demand")->field("id,name")->where("type",6)->order(["sort asc","id desc"])->select();

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'type'=>$type,
                'times'=>$times,
                'number'=>$number,
                'money'=>$money,
                'ment'=>$ment,
                'city'=>$city,
            ]
        ];
    
        echo \json_encode($arr);
    }
    /**
    * 保存需求
    *
    * @return void
    */
    public function save()
    {
        $data=input("post.");
        $data['time']=time();
        $re=db("need")->insert($data);
        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>'提交成功',
                'data'=>''
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'提交失败',
                'data'=>''
            ];
        }
        echo \json_encode($arr);
    }






}