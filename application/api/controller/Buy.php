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

            //判断是否开启满减 
            
            $open=db("red")->where("id",4)->find()['open'];

            if($open == 1){
                
                $full=db("full")->where(["full"=>["<=",$room['room_price']]])->order("full desc")->find();

                if($full){
                    
                    $data['old_price']=$room['room_price'];
                    $data['price']=$room['room_price']-$full['money'];
                    $data['full_price']=$full['money'];
                    $data['full']=1;
                    if($data['price'] <= 0){
                        $data['price']=0.01;
                    }

                }else{
                    $data['price']=$room['room_price'];
                }

            }else{
                $data['price']=$room['room_price'];
            }

            $data['hid']=$hid;
            $data['rid']=$rid;
            $data['hotel']=$hotel['name'];
            $data['image']=$hotel['image'];
            $data['room']=$room['room_name'];
            
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

         //   var_dump($data);exit;
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
    /**
    * 秒杀
    *
    * @return void
    */
    public function skill()
    {
        
        $url=parent::getUrl();
        
        $re=db("red")->where("id",6)->find();

        if($re['open'] == 1){
            $res=db("hotel_room")->alias("a")->field("a.*,b.name")->where(['room_up'=>1,'room_is_delete'=>0,'room_skill'=>1])->join("hotel b","a.hid=b.id")->order(["room_sort asc","a.id desc"])->select(); 

            foreach($res as $k => $v){
                $res[$k]['room_image']=$url.$v['room_image'];

            }

            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>[
                    'list'=>$res,
                    'time'=>$re
                ]
            ];

        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'秒杀活动已结束',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 秒杀生产订单
    *
    * @return void
    */
    public function save_skill()
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
            $data['old_price']=$room['room_price'];
            $data['price']=$room['room_skill_price'];
            $data['skill']=1;
                  
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

         //   var_dump($data);exit;
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
    /**
    * 秒杀结束请求
    *
    * @return void
    */
    public function skill_end()
    {
        $re=db("red")->where("id",6)->setField("open",0);

        db("hotel_room")->where("room_skill",1)->setField("room_skill",0);
    }
    /**
    * 折扣
    *
    * @return void
    */
    public function rebate()
    {
        
        $url=parent::getUrl();
        
        $re=db("red")->where("id",7)->find();

        if($re['open'] == 1){
            $res=db("hotel_room")->alias("a")->field("a.*,b.name")->where(['room_up'=>1,'room_is_delete'=>0,'room_rebate'=>1])->join("hotel b","a.hid=b.id")->order(["room_sort asc","a.id desc"])->select(); 

            foreach($res as $k => $v){
                $res[$k]['room_image']=$url.$v['room_image'];

            }

            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>[
                    'list'=>$res,
                    'time'=>$re
                ]
            ];

        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'限时折扣已结束',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 限时折扣生产订单
    *
    * @return void
    */
    public function save_rebate()
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
            $data['old_price']=$room['room_price'];
            $data['price']=$room['room_rebate_price'];
            $data['rebate']=1;
                  
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

         //   var_dump($data);exit;
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
    /**
    * 限时折扣结束请求
    *
    * @return void
    */
    public function rebate_end()
    {
        $re=db("red")->where("id",7)->setField("open",0);

        db("hotel_room")->where("room_rebate",1)->setField("room_rebate",0);
    }






}