<?php
namespace app\api\controller;

use think\Request;

class Buy extends BaseHome
{
    public function order()
    {
        //参会人数
        $number=db("demand")->field("id,name")->where("type",3)->order(["sort asc","id desc"])->select();

        //会议需求
        $ment=db("demand")->field("id,name")->where("type",5)->order(["sort asc","id desc"])->select();

        //下单广告
        $lb=db("lb")->field("desc")->where("fid",7)->find();
        $lb['desc']=strip_tags($lb['desc']);

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'number'=>$number, 
                'ment'=>$ment,
                'lb'=>$lb
            ]
        ];
    
        echo \json_encode($arr);
    }
    /**
    * 生产订单
    *
    * @return void
    */
    public function save()
    {
       $uid=Request::instance()->header("uid");
       $data=input("post.");
       $data['uid']=$uid;
       $hid=input("hid");
       $rid=input("rid");
       $hotel=db("hotel")->where(["id"=>$hid,"up"=>1,"is_delete"=>0])->find();
       $room=db("hotel_room")->where(["id"=>$rid,"room_up"=>1,"room_is_delete"=>0])->find();
       if($hotel && $room && $room['hid'] == $hid){
            $data['hid']=$hid;
            $data['rid']=$rid;
            $data['hotel']=$hotel['name'];
            $data['image']=$hotel['image'];
            $data['room']=$room['room_name'];
            $data['price']=$room['room_price'];
            $data['code']="CK-".uniqid();
            $data['time']=time();
            if($room['room_type'] == 1){
                $data['room_type']="会议室";
            }else{
                $data['room_type']="客房";
            }
            $re=db("order")->where(["uid"=>$uid,"hid"=>$hid,"rid"=>$rid,"status"=>0])->find();
            if($re){
                db("order")->where("id",$re['id'])->delete();
            }
            $rea=db("order")->insert($data);

            $did = db('order')->getLastInsID();

            if($did){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'订单生成成功',
                    'data'=>[
                        'did'=>$did,   
                    ]
                    ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'订单生成失败',
                    'data'=>[]
                    ];
            }
       }else{
        
        $arr=[
            'error_code'=>1,
            'msg'=>'参数错误',
            'data'=>[]
        ];
     
       }
       echo \json_encode($arr);
    }
}