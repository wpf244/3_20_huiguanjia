<?php
namespace app\api\controller;

use think\Request;

class User extends BaseHome
{
    
    /**
    * 用户信息
    *
    * @return void
    */
    public function index()
    {
        $uid=Request::instance()->header('uid');

        $re=db("user")->field("nickname,image,phone")->where("uid",$uid)->find();

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$re
        ];
        echo \json_encode($arr);

    }

    /**
    * 申请加入
    *
    * @return void
    */
    public function apply()
    {
        $genre=db("lb")->field("name")->where(['fid'=>4,'status'=>1])->order(["id asc","sort asc"])->select();

        $tips=db("lb")->field("desc")->where("fid",5)->find();
        
        $tips['desc']=strip_tags($tips['desc']);

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>[
                'genre'=>$genre,
                'tips'=>$tips,
            ]
        ];

        echo \json_encode($arr);
    }

    /**
    * 提交保存
    *
    * @return void
    */
    public function save()
    {
        $uid=Request::instance()->header('uid');

        $data['u_id']=$uid;
        $data['username']=input("username");
        $data['idcode']=input("idcode");
        $data['name']=input("name");
        $data['addr']=input("addr");
        $data['genre']=input("genre");
        $data['u_phone']=input("phone");
        $data['u_level']=3;
        $data['type']=1;
        $data['u_time']=time();

        $re=db("user_apply")->insert($data);

        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>"保存成功",
                'data'=>''
            ];
        }else{
            $arr=[
                'error_code'=>0,
                'msg'=>"保存失败",
                'data'=>''
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 申请加入前页面判断
    *
    * @return void
    */
    public function change_apply()
    {
        $uid=Request::instance()->header('uid');

        $re=db("user_apply")->where(['rebut_look'=>0,'u_id'=>$uid])->find();

        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>"已经提交申请了,去查看详情",
                'data'=>''
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>"没有提交申请,去提交",
                'data'=>''
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 申请进度查看
    *
    * @return void
    */
    public function apply_detail()
    {
        $uid=Request::instance()->header('uid');

        $re=db("user_apply")->field("u_status,rebut")->where(['u_id'=>$uid])->find();

        db("user_apply")->where(['u_id'=>$uid])->setField("rebut_look",1);

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$re
        ];
        echo \json_encode($arr);
    }

}