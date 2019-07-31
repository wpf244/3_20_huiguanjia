<?php
namespace app\api\controller;

use think\Request;

class Assemble extends BaseHome
{
    public function index()
    {
        $url=parent::getUrl();
        
        $res=db("assemble_goods")->field("id,name,image,tag")->where(["up"=>1])->order(["sort asc","id desc"])->select();

        foreach($res as &$v){
            $v['image']=$url.$v['image'];
        }
        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$res
        ];
    
        return json($arr);
   
    }
    /**
    * 商品详情
    *
    * @return void
    */
    public function detail()
    {
        
        $url=parent::getUrl();
        
        $id=input("id");

        $re=db("assemble_goods")->where("id",$id)->find();

        $re['image']=$url.$re['image'];


        $res=db("assemble")->alias("a")->field("id,number,num,a.time,a.date,b.nickname,b.image")->where(["gid"=>$id,"a.status"=>1])->join("user b","a.uid=b.uid")->select();
       
        foreach($res as $k => $v){
            $res[$k]['nums']=$v['number']-$v['num'];
            $date=$v['time']+$v['date']*60*60;
            $res[$k]['dates']=$date;
        }
      

        $cou=count($res);

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'goods'=>$re,
                'list'=>$res,
                'cou'=>$cou,
            ]
        ];
    
        return json($arr);
    }
    /**
    * 发起拼团
    *
    * @return void
    */
    public function save()
    {
        $uid=Request::instance()->header("uid");

        $id=input("id");

        $num=1;

        $re=db("assemble")->where(["uid"=>$uid,"gid"=>$id,"status"=>0])->find();

        if($re){
            db("assemble")->where("id",$re['id'])->delete();
        }

        $res=db("assemble")->where(["uid"=>$uid,"gid"=>$id,"status"=>1])->find();

        if(empty($res)){

            $goods=db("assemble_goods")->where("id",$id)->find();
            
            $data['uid']=$uid;
            $data['gid']=$id;
            $data['name']=$goods['name'];
            $data['tag']=$goods['tag'];
            $data['image']=$goods['image'];
            $data['price']=$goods['price'];
            $data['floor_price']=$goods['floor_price'];
            $data['number']=$goods['group_number'];
            $data['date']=$goods['group_time'];
            $data['buy_num']=$num;
            $data['time']=time();
        

            db("assemble")->insert($data);

            $id=db("assemble")->getLastInsID();

            if($id){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'拼团成功',
                    'data'=>[
                        'id'=>$id
                    ]
                ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'系统繁忙,请稍后再试',
                    'data'=>''
                ];
            }

        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'你已经有此类商品的拼团了',
                'data'=>''
            ];
        }
        return json($arr);
    }
    /**
    * 生成订单
    *
    * @return void
    */
    public function sdd()
    {
        $did=input('id');

        $re=db("assemble")->where("id",$did)->find();

        $gid=$re['gid'];
     
        $uid=Request::instance()->header("uid");
        $ob=db("assemble_dd");
        $old_dd=db("assemble_dd")->where(["gid"=>$gid,"uid"=>$uid,"status"=>0,"a_id"=>$did])->find();
        if($old_dd){
            $ob->where("id",$old_dd['id'])->delete();
         
        }
        $good=db("assemble_goods")->where("id",$gid)->find();
     
        $arr=array();
        $arr['gid']=$gid;
        $arr['uid']=$uid;
        $arr['a_id']=$did;
        $arr['goods_price']=$good['price'];
        $arr['num']=$re['buy_num'];
        $arr['price']=$good['floor_price']*$re['buy_num'];
        $arr['name']=$good['name'];
       
        $arr['image']=$good['image'];
        $arr['code']="CK-".uniqid();
       
        $arr['time']=time();
       
        
        $re=$ob->insert($arr);
        
        $dids = db('assemble_dd')->getLastInsID();
        if($dids){
            $arr=[
                'error_code'=>0,
                'msg'=>'订单生成成功',
                'data'=>['did'=>$dids]
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'订单生成失败',
                'data'=>''
            ];
        }
        return json($arr);
    }
    /**
    * 参与他人的拼团
    *
    * @return void
    */
    public function save_other()
    {
        $uid=Request::instance()->header("uid");

        $id=input("id");

        $re=db("assemble")->where("id",$id)->find();

        if($re['status'] == 1){
            if($re['uid'] == $uid){

                $arr=[
                    'error_code'=>2,
                    'msg'=>'你已经参与此拼团',
                    'data'=>''
                ];

            }else{
                
                $log=db("assemble_log")->where(["uid"=>$uid])->find();

                if($log){

                    if($log['status'] == 0){
                        $arr=[
                            'error_code'=>0,
                            'msg'=>'拼团成功',
                            'data'=>['id'=>$log['id']]
                        ];
                    }else{
                        $arr=[
                            'error_code'=>2,
                            'msg'=>'你已经参与此拼团',
                            'data'=>''
                        ];
                    }

                }else{

                    $data['uid']=$uid;
                    $data['gid']=$re['gid'];
                    $data['aid']=$id;
                    $data['time']=time();

                    db("assemble_log")->insert($data);

                    $id=db("assemble_log")->getLastInsID();

                    $arr=[
                        'error_code'=>0,
                        'msg'=>'拼团成功',
                        'data'=>['id'=>$id]
                    ];



                }

            }
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'此拼团已结束',
                'data'=>''
            ];
        }
        return json($arr);
    }
     /**
    * 参加他人拼团生成订单
    *
    * @return void
    */
    public function sdd_other()
    {
        $did=input('id');

       
        $re=db("assemble_log")->where("id",$did)->find();

        $gid=$re['gid'];
     
        $uid=Request::instance()->header("uid");
        $ob=db("assemble_dd");
        $old_dd=db("assemble_dd")->where(["gid"=>$gid,"uid"=>$uid,"status"=>0,"a_id"=>$did,"lid"=>$re['id']])->find();
        if($old_dd){
            $ob->where("id",$old_dd['id'])->delete();
         
        }
        $good=db("assemble_goods")->where("id",$gid)->find();
     
        $arr=array();
        $arr['gid']=$gid;
        $arr['uid']=$uid;
        $arr['a_id']=$re['aid'];
        $arr['goods_price']=$good['price'];
        $arr['num']=1;
        $arr['price']=$good['floor_price'];
        $arr['name']=$good['name'];
        $arr['lid']=$re['id'];
        $arr['image']=$good['image'];
        $arr['code']="CK-".uniqid(); 
        $arr['time']=time();
      
        
        $re=$ob->insert($arr);
        
        $dids = db('assemble_dd')->getLastInsID();
        $arr=[
            'error_code'=>0,
            'msg'=>'订单生成成功',
            'data'=>['did'=>$dids]
        ];
        return json($arr);
    }
    /**
    * 我发起的拼团
    *
    * @return void
    */
    public function start()
    {
        
        $uid=Request::instance()->header("uid");

        $rec=db("assemble")->where(["uid"=>$uid,"status"=>1])->select();

        

        $res=db("assemble")->where(["uid"=>$uid,"status"=>2])->select();

        

        $reg=db("assemble")->where(["uid"=>$uid,"status"=>3])->select();

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'rec'=>$rec,
                'res'=>$res,
                'reg'=>$reg
                ]
        ];
        return json($arr);


    }
    /**
    * 我参与的拼团
    *
    * @return void
    */
    public function partake()
    {

        $uid=Request::instance()->header("uid");

        $rec=db("assemble_log")->alias("a")->where("a.uid",$uid)->where("b.status",1)->join("assemble b","a.aid=b.id")->select();

        $res=db("assemble_log")->alias("a")->where("a.uid",$uid)->where("b.status",2)->join("assemble b","a.aid=b.id")->select();

        $reg=db("assemble_log")->alias("a")->where("a.uid",$uid)->where("b.status",3)->join("assemble b","a.aid=b.id")->select();

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'rec'=>$rec,
                'res'=>$res,
                'reg'=>$reg
                ]
        ];
        return json($arr);
    }
















}
