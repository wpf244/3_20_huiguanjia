<?php
namespace app\api\controller;

class Hotel extends BaseApi
{
    
    /**
    * 城市列表
    *
    * @return void
    */
    public function city()
    {
        $res=db("hotel_city")->where(['pid'=>0])->order(["c_sort asc","cid desc"])->select();
        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$res
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 酒店列表
    *
    * @return void
    */
    public function lister()
    {
        $url=parent::getUrl();
        $cid=input("cid");
        $res=db("hotel")->field("id,name,addr,type,area,number,hall,image,guest")->where(["cid"=>$cid,"is_delete"=>0,'up'=>1])->order(["sort asc","id desc"])->select();
        foreach($res as $k => $v){
            $res[$k]['image']=$url.$v['image'];
        }
        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$res
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 搜索条件
    *
    * @return void
    */
    public function serach_term()
    {
        $cid=input("cid");
        //区域列表
        $city=db("hotel_city")->where(["pid"=>$cid])->order(["c_sort asc","cid desc"])->select();

        //场地类型
        $type=db("hotel_other")->where("otype",1)->select();
        
        //会场面积
        $area=db("hotel_other")->where("otype",2)->select();
        
        //容纳人数
        $num=db("hotel_other")->where("otype",3)->select();
        
        //参考价格
        $money=db("hotel_other")->where("otype",4)->select();
      
        
        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'city'=>$city,
                'type'=>$type,
                'area'=>$area,
                'num'=>$num,
                'money'=>$money,
            ]
        ];
       
        echo \json_encode($arr);
    }
    /**
    * 搜索列表
    *
    * @return void
    */
    public function search_lister()
    {
   
        $url=parent::getUrl();

        $cid=input("cid");
        if($cid){
            $map['cid']=array('eq',$cid);
        }
        
        $map['is_delete']=array('eq',0);
        $map['up']=array('eq',1);

        $qid=input("qid");
        if($qid){
            $map['qid']=array("eq",$qid);
        }
        $tid=input("tid");
        if($tid){
            $map['tid']=array("eq",$tid);
        }
        $aid=input("aid");
        if($aid){
            $map['aid']=array("eq",$aid);
        }
        $nid=input("nid");
        if($nid){
            $map['nid']=array("eq",$nid);
        }
        $mid=input("mid");
        if($mid){
            $map['mid']=array("eq",$mid);
        }

        $keywords=input("keywords");
        $map['name|addr']=array("like","%$keywords%");

        $sort=input("sort");
        if($sort == 1){
            //低价优先
          $sorts=["price asc","id desc"];
        }elseif($sort == 2){
            $sorts=["price desc","id desc"];
        }else{
            $sorts=["sort asc","id desc"];
        }


        $res=db("hotel")->field("id,name,addr,type,area,number,hall,image,guest")->where($map)->order($sorts)->select();
        
        foreach($res as $k => $v){
            $res[$k]['image']=$url.$v['image'];
        }
       
        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$res
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);

    }
    /**
    * 酒店详情
    *
    * @return void
    */
    public function detail()
    {
        $id=input("id");
        $url=parent::getUrl();

        $re=db("hotel")->where(["id"=>$id,"is_delete"=>0])->find();
        

        if($re){
            $re['image']=$url.$re['image'];
             //banner图
         
            $banner=db("hotel_img")->where(["hid"=>$id,"status"=>1])->select();

            foreach($banner as $k => $v){
                $banner[$k]['image']=$url.$v['image'];
            }

            //会议室
            $con_room=db("hotel_room")->where(["hid"=>$id,'room_up'=>1,'room_is_delete'=>0,'room_type'=>1])->order
            (["room_sort asc","id desc"])->select();
            $cou=count($con_room);
            foreach($con_room as $kc => $vc){
                $con_room[$kc]['room_image']=$url.$vc['room_image'];
            }

            //客房
            $con_rooms=db("hotel_room")->where(["hid"=>$id,'room_up'=>1,'room_is_delete'=>0,'room_type'=>2])->order
            (["room_sort asc","id desc"])->select();
            $cous=count($con_rooms);
            foreach($con_rooms as $kcs => $vcs){
                $con_rooms[$kcs]['room_image']=$url.$vcs['room_image'];
            }

            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>[
                    'banner'=>$banner,
                    'hotel'=>$re,
                    'con_room'=>[
                        'cou'=>$cou,
                        'list'=>$con_room
                    ],
                    'guset_room'=>[
                        'cou'=>$cous,
                        'list'=>$con_rooms
                    ]

                ]
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 房间详情
    *
    * @return void
    */
    public function room_detail()
    {
        $url=parent::getUrl();

        $id=input("id");

        $re=db("hotel_room")->where(['id'=>$id,'room_up'=>1,'room_is_delete'=>0])->find();

        if($re){
            $re['room_image']=$url.$re['room_image'];
            //banner图
           $banner=db("room_img")->where(["rid"=>$id,"status"=>1])->select();

           foreach($banner as $k => $v){
               $banner[$k]['image']=$url.$v['image'];
           }

         

           $arr=[
               'error_code'=>0,
               'msg'=>'获取成功',
               'data'=>[
                   'banner'=>$banner,
                   'room'=>$re,
               ]
           ];
       }else{
           $arr=[
               'error_code'=>1,
               'msg'=>'暂无数据',
               'data'=>[]
           ];
       }
       echo \json_encode($arr);
    }
    /**
    * 全部评价
    *
    * @return void
    */
    public function assess()
    {
        $id=input("id");
        $res=db("assess")->alias("a")->field("a.*,b.image")->where(["hid"=>$id,"a.status"=>1])->join("user b","a.uid = b.uid")->select();

        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$res
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];

        }
        echo \json_encode($arr);
    }
    /**
    * 附近场地
    *
    * @return void
    */
    public function nearby()
    {
        $url=parent::getUrl();

        $addr=input("addr");

        
        $map['is_delete']=array('eq',0);
        $map['up']=array('eq',1);

        $map['addr']=array('like',"%".$addr."%");

        $qid=input("qid");
        if($qid){
            $map['qid']=array("eq",$qid);
        }
        $tid=input("tid");
        if($tid){
            $map['tid']=array("eq",$tid);
        }
        $aid=input("aid");
        if($aid){
            $map['aid']=array("eq",$aid);
        }
        $nid=input("nid");
        if($nid){
            $map['nid']=array("eq",$nid);
        }
        $mid=input("mid");
        if($mid){
            $map['mid']=array("eq",$mid);
        }

        // $keywords=input("keywords");
        // $map['name']=array("like","%".$keywords."%");

        $sort=input("sort");
        if($sort == 1){
            //低价优先
          $sorts=["price asc","id desc"];
        }elseif($sort == 2){
            $sorts=["price desc","id desc"];
        }else{
            $sorts=["sort asc","id desc"];
        }

        
        $longs=input("longs");
        $lats=input("lats");

        $res=db("hotel")->field("id,name,addr,type,area,number,hall,image,guest,lats,longs")->where($map)->order($sorts)->select();
        
        foreach($res as $k => $v){
            $res[$k]['image']=$url.$v['image'];
            $res[$k]['gap']=$this->getDistance($lats,$longs,$v['lats'],$v['longs']);
        }
       
        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$res
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * @param $lat1
    * @param $lng1
    * @param $lat2
    * @param $lng2
    * @return int
    */
    function getDistance($lat1, $lng1, $lat2, $lng2){

        //将角度转为狐度

        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度

        $radLat2=deg2rad($lat2);

        $radLng1=deg2rad($lng1);

        $radLng2=deg2rad($lng2);

        $a=$radLat1-$radLat2;

        $b=$radLng1-$radLng2;

        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;

        return $s;

    }



















}