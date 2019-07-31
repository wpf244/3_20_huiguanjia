<?php
namespace app\api\controller;

use think\Controller;
use think\Request;

class BaseHome extends Controller
{
    public function _initialize()
    {
        $token=Request::instance()->header('token');
         if($token != '50a00a9b8d3402ed4f1b3ed4b890294b'){
             $arrs=[
                 'error_code'=>500,
                 'msg'=>"token验证失败",
                 'data'=>''
             ];
             echo \json_encode($arrs);exit;
         }
         $uid=Request::instance()->header('uid');
         if(empty($uid)){
            $arrs=[
                'error_code'=>501,
                'msg'=>"请先登录",
                'data'=>''
            ];
            echo \json_encode($arrs);exit;
         }else{
             $user=db("user")->where("uid",$uid)->find();
             if(empty($user)){
                $arrs=[
                    'error_code'=>502,
                    'msg'=>"登录信息已失效",
                    'data'=>''
                ];
                echo \json_encode($arrs);exit;
             }else{
                 if($user['status'] == 0 || $user['is_delete'] == -1){
                    $arrs=[
                        'error_code'=>502,
                        'msg'=>"登录信息已失效",
                        'data'=>''
                    ];
                    echo \json_encode($arrs);exit; 
                 }
             }
         }

          //更新拼团过期
        $time=time();

        $res=db("assemble")->where("status",1)->select();


        foreach($res as $k => $v){
            $bar_time=$v['time']+$v['date']*60*60;

            if($time >= $bar_time){
                //修改拼团为过期

                db("assemble")->where("id",$v['id'])->setField("status",3);

                //退款
                $res=db("assemble_dd")->where(["a_id"=>$v['id'],"status"=>1])->select();

                if($res){

                    foreach($res as $v){
  
                        $out_trade_no=$v['code'];
                        $total_fee=$v['price']*100;
                        $refund_fee=$v['price']*100;

                        $data=db("payment")->where("id",1)->find();

                        $input = new \WxPayRefund();
                        $input->SetOut_trade_no($out_trade_no);
                        $input->SetTotal_fee($total_fee);
                        $input->SetRefund_fee($refund_fee);
                        $input->SetOut_refund_no("sdkphp".date("YmdHis"));
                        $input->SetOp_user_id($data['mchid']);

                        $order = \WxPayApi::refund($input,$data);

                        db("assemble_dd")->where("id",$v['id'])->update(["status"=>5]);

                    }


                }


            }
        }

        //更新砍价到期的

        $time=time();

        $res=db("bargain")->where("status",0)->select();

        foreach($res as $k => $v){
            $bar_time=$v['time']+$v['times']*60*60;

            if($time >= $bar_time){
                db("bargain")->where("id",$v['id'])->setField("status",2);
            }
        }

        //更新商品活动到期的
        $goods=db("bargain_goods")->where("up",1)->select();

        foreach($goods as $vg){

           

            $goods_time=strtotime($vg['end_time']);

            if($time > $goods_time){

                db("bargain_goods")->where("id",$vg['id'])->setField("up",0);

                db("bargain")->where(["gid"=>$vg['id'],"status"=>0])->setField("g_status",1);

            }
        }


    }
    public function getUrl(){
        $url=Request::instance()->domain();
        
        return $url;
    }
}