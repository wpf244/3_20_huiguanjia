<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Bargain extends BaseHome
{
    public function index()
    {
        $url=parent::getUrl();
        
        $res=db("bargain_goods")->field("id,name,image,tag")->where(["up"=>1])->order(["sort asc","id desc"])->select();

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
    * 我进行中的砍价
    *
    * @return void
    */
    public function my_bargain()
    {
        $uid=Request::instance()->header("uid");

        $url=parent::getUrl();

        $re=db("bargain")->where(["uid"=>$uid,"status"=>["in",[0,1]],"pay_status"=>0,"look"=>0,"g_status"=>0])->find();


        if($re){

            $gid=$re['gid'];

            $goods=db("bargain_goods")->where("id",$gid)->find();
    
            $re['image']=$url.$goods['image'];
    
    
            $rel=db("bargain")->where(["uid"=>$uid,"status"=>1,"look"=>0])->find();
    
            if($rel){
                db("bargain")->where(["id"=>$rel['id']])->setField("look",1);
            }
    
            $times=$re['times']*60*60;
    
            $end_time=$re['time']+$times;
    
            if(\time() >= $end_time){
    
                $re['$date']=0;
    
            }else{
    
                $re['date']=$end_time;
            }

            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$re
            ];

        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'暂无数据',
                'data'=>[]
            ];
        }
        
       
      
    
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

        $re=db("bargain_goods")->where(["id"=>$id,"up"=>1])->find();

        $re['image']=$url.$re['image'];

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$re
        ];
    
        return json($arr);

        
    }
    /**
    * 保存砍价
    *
    * @return void
    */
    public function save_bargain()
    {
        $id=input("id");

        $re=db("bargain_goods")->where("id",$id)->find();

        $uid=Request::instance()->header("uid");

        if($re){

            $reb=db("bargain")->where(["uid"=>$uid,"gid"=>$id,"status"=>0])->find();

            if(empty($reb)){

                $data['uid']=$uid;
                $data['gid']=$id;
                $data['name']=$re['name'];
                $data['price']=$re['price'];
                $data['floor_price']=$re['floor_price'];
                $data['time']=time();
                $data['times']=$re['time'];
                $data['surplus_price']=$re['price'];
                $data['can_price']=$re['price']-$re['floor_price'];
                

                $rea=db("bargain")->insert($data);

                if($rea){
                    $arr=[
                        'error_code'=>0,
                        'msg'=>'发起成功',
                        'data'=>[]
                    ];
                }else{
                    $arr=[
                        'error_code'=>3,
                        'msg'=>'发起失败',
                        'data'=>[]
                    ];
                }

            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'已有砍价商品正在进行中',
                    'data'=>[]
                ];
            }

        }else{

            $arr=[
                'error_code'=>1,
                'msg'=>'商品信息不存在',
                'data'=>[]
            ];

        }
        return json($arr);
    }
    /**
    * 砍价
    *
    * @return void
    */
    public function take()
    {
        $uid=Request::instance()->header("uid");

        $id=input("id");

        $log=db("bargain_log")->where(["bid"=>$id,"uid"=>$uid])->find();

        $user=db("user")->where("uid",$uid)->find();


        if(empty($log)){

            $bargain=db("bargain")->where(["id"=>$id,"status"=>0])->find();

            if($bargain){

                $gid=$bargain['gid'];

                $goods=db("bargain_goods")->where(["id"=>$gid,"up"=>1])->find();

                if($goods){

                    //查询已砍价人数 得出砍价区间

                    $number=$bargain['number'];

                    $goods_nums=$goods['nums'];

                    if($number >= $goods_nums){

                        $first_price=$goods['end_price'];

                        $end_price=$goods['end_prices'];

                    }else{

                        $first_price=$goods['first_price'];

                        $end_price=$goods['first_prices'];

                    }

                    //算出本次砍价金额

                    $money=mt_rand($first_price*100,$end_price*100)/100;

                    $money = sprintf("%.2f",$money);

                    //查询还能砍的价格
                    $can_price=$bargain['can_price'];

                    if($money > $can_price){
                        $money=$money-$can_price;
                        $data['status']=1;
                    }
                    $data['already_price']=$bargain['already_price']+$money;
                    $data['surplus_price']=$bargain['surplus_price']-$money;
                    $data['can_price']=$bargain['can_price']-$money;
                    $data['number']=$bargain['number']+1;
                    if($goods['number'] != 0){
                        $goods_number=$goods['number']-$bargain['number'];
                        if($goods_number <= 1){
                            $data['status']=1;
                        }
                    }

                    //日志数据
                    $log['uid']=$uid;
                    $log['bid']=$id;
                    $log['price']=$money;
                    $log['time']=time();
                  

                    // 启动事务
                    Db::startTrans();
                    try{
                        db("bargain")->where("id",$id)->update($data);
                        db("bargain_log")->insert($log);

                        $arr=[
                            'error_code'=>0,
                            'msg'=>'砍价成功',
                            'data'=>$money
                        ];
                        // 提交事务
                        Db::commit();    
                    } catch (\Exception $e) {

                        $arr=[
                            'error_code'=>1,
                            'msg'=>'砍价失败',
                            'data'=>[]
                        ];
                        // 回滚事务
                        Db::rollback();
                    }





                }else{
                    $arr=[
                        'error_code'=>2,
                        'msg'=>'商品信息已失效',
                        'data'=>[]
                    ];
                }

            }else{
                $arr=[
                    'error_code'=>3,
                    'msg'=>'此砍价活动已失效',
                    'data'=>[]
                ];
            }

        }else{
            $arr=[
                'error_code'=>4,
                'msg'=>'你已经砍过了',
                'data'=>[]
            ];

        }
        return json($arr);
    }
    /**
    * 砍价详情
    *
    * @return void
    */
    public function help()
    {
        $id=input("id");

        $url=parent::getUrl();

        $re=db("bargain")->where(["id"=>$id])->find();

        if($re){
 
            $gid=$re['gid'];

            $goods=db("bargain_goods")->where("id",$gid)->find();

            $re['image']=$url.$goods['image'];

            $times=$re['times']*60*60;

            $end_time=$re['time']+$times;

            if(\time() >= $end_time){

                $re['date']=0;

            }else{

                $re['date']=$end_time;
            }

            $res=db("bargain_log")->where("bid",$id)->select();

          
            $scale=\intval($re['already_price']/($re['price']-$re['floor_price'])*100);

            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>[]
            ];


        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'获取失败',
                'data'=>[]
            ];
        }
        return json($arr);

     
    }


















}