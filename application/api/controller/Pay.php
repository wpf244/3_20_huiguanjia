<?php
namespace app\api\controller;

use think\Controller;
use think\Loader;
use think\Request;


Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
Loader::import('WxPay.WxPay', EXTEND_PATH, '.JsApiPay.php');
class Pay extends Controller
{
    public function getopenid()
    {
        $code=\input('code');
        $appid="wxcde9f11cf93da5ba";
        $secret="18bayingshangmaohuizhongchuangda";
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
        $results=json_decode(file_get_contents($url),true);
        \var_dump($results);exit;
        $openid=$results['openid'];
        return $openid;

        
    }
    public function pays()
    {
        $data=db("payment")->where("id",1)->find();
       
        $did=\input('did');
        $uid=Request::instance()->header('uid');
        $user=db("user")->where("uid=$uid")->find();
       
       $openid=$user['openid'];
     //   $openid="oZwh45FZmYEuMXJoB04m9-bBAn4s";
     //   \var_dump($openid);exit;
        $red=db("order")->where("id=$did")->find();
        $order=$red['code'];
        $money=($red['price']*100);
        $name=$red['room'];
        
        
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("$name");
        $input->SetOut_trade_no("$order");
        $input->SetTotal_fee("$money");
        $input->SetNotify_url("https://huiguanjia.dd371.com/Api/Pay/notify/");
        $input->SetTrade_type("JSAPI");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //     由小程序端传给服务端
        $input->SetOpenid($openid);
        //     向微信统一下单，并返回order，它是一个array数组
        $order = \WxPayApi::unifiedOrder($input,$data);
        //     json化返回给小程序端
        $tools=new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order,$data);
        
//         $arr=[
//             'error_code'=>0,
//             'data'=>$jsApiParameters
//         ];
        echo $jsApiParameters;
    }
    public function notify()
    {
        
        //获取返回的xml
        $testxml  = file_get_contents("php://input");
        //将xml转化为json格式
        $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        
        //转成数组
        $result = json_decode($jsonxml, true);
        
        if($result){
            //如果成功返回了
            if($result['return_code'] == 'SUCCESS'){
                //进行改变订单状态等操作。。。。
                $order_code= $result['out_trade_no'];
                $re=db("order")->where("code='$order_code'")->find();
                $id=$re['id'];
                if($re['status'] == 0){
                    $data['fu_time']=time();
                    $data['status']=1;
                    $changestatus=db("order")->where("id=$id")->update($data);
                    if($changestatus){
                        
                        //是否开启分销
                        $basic=db("basic")->where("id",1)->find();
                        if($basic['status'] == 1){
                            //分销返利
                            $moneys=$re['price'];
                            $uid=$re['uid'];
                           
                            $this->share($uid,$moneys);
                        }
                    }
                }
                
            }
        }
        
    }

    // public function ceshi()
    // {
    //     $this->share("39","0.01");
    // }

    public function share($uid,$moneys)
    {
         $user=db("user")->where(["uid"=>$uid,"status"=>1,"is_delete"=>0])->find();
         if($user){
             $fid=$user['fid'];
             if($fid != 0){
                  //返利
                  $fuser=db("user")->where(["uid"=>$fid,"status"=>1,"is_delete"=>0])->find();
                  if($fuser){
                      $level=$fuser['level'];
                      //应返佣金
                      $money = $this->should_share($level,$moneys);

                      //用户增加佣金
                      db("user")->where("uid",$fid)->setInc("money",$money);

                      //增加佣金日志
                      $data['uid']=$fid;
                      $data['type']=1;
                      $data['money']=$money;
                      $data['oper']="一级分销增加佣金";
                      $data['time']=time();
                      $this->money_log($data);

                      //二级返利
                      $fids=$fuser['fid'];
                      if($fids != 0){
                          //返利
                        $fusers=db("user")->where(["uid"=>$fids,"status"=>1,"is_delete"=>0])->find();
                        if($fusers){
                            $levels=$fusers['level'];
                            //应返佣金
                            $moneys = $this->should_shares($levels,$moneys);

                            //用户增加佣金
                            db("user")->where("uid",$fids)->setInc("money",$moneys);

                            //增加佣金日志
                            $datas['uid']=$fids;
                            $datas['type']=1;
                            $datas['money']=$moneys;
                            $datas['oper']="二级分销增加佣金";
                            $datas['time']=time();
                            $this->money_log($datas);
                       }

                     }

                  }
             }
         }
    }

    /**
    * 一级应返佣金
    *
    * @return void
    */
    public function should_share($level,$moneys)
    {
         $share=db("share")->where("id",1)->find();
         if($level == 1){
             $money = $moneys*$share['level_1']/100;
         }
         if($level == 2){
            $money = $moneys*$share['level_2']/100;
        }
        if($level == 3){
            $money = $moneys*$share['level_3']/100;
        }
        return $money;
    }
      /**
    * 二级应返佣金
    *
    * @return void
    */
    public function should_shares($level,$moneys)
    {
         $share=db("share")->where("id",1)->find();
         if($level == 1){
             $money = $moneys*$share['level_12']/100;
         }
         if($level == 2){
            $money = $moneys*$share['level_22']/100;
        }
        if($level == 3){
            $money = $moneys*$share['level_32']/100;
        }
        return $money;
    }
    /**
    * 佣金日志
    *
    * @return void
    */
    public function money_log($data)
    {
        db("money_log")->insert($data);
    }

    public function pays_order()
    {
        $id=\input('did');
        $uid=Request::instance()->header('uid');
        $user=db("user")->where("uid=$uid")->find();
       
        $openid=$user['openid'];
    
        $red=db("express_dd")->where("id=$id")->find();
        $order=$red['code'];
        $money=($red['money']*100);
    
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("快递费用");
        $input->SetOut_trade_no("$order");
        $input->SetTotal_fee("$money");
        $input->SetNotify_url("https://weitenong.dd371.com/Api/Pay/notify_info/");
        $input->SetTrade_type("JSAPI");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //     由小程序端传给服务端
        $input->SetOpenid($openid);
        //     向微信统一下单，并返回order，它是一个array数组
        $order = \WxPayApi::unifiedOrder($input);
        //     json化返回给小程序端
        
        $tools=new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order);
    
//         $arr=[
//             'error_code'=>0,
//             'data'=>$jsApiParameters
//         ];
        echo $jsApiParameters;
    }
    public function notify_info()
    {
        //获取返回的xml
        $testxml  = file_get_contents("php://input");
        //将xml转化为json格式
        $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        //转成数组
        $result = json_decode($jsonxml, true);
    
        if($result){
            //如果成功返回了
            if($result['return_code'] == 'SUCCESS'){
                //进行改变订单状态等操作。。。。
                $order_code= $result['out_trade_no'];
                $re=db("express_dd")->where("code='$order_code'")->find();
                $id=$re['id'];
                if($re['status'] == 0){
                    $changestatus=db("express_dd")->where("id=$id")->setField("status",1);
                   
                    //增加用户积分
                    $uid=$re['u_id'];
                    $integ=db("free")->where("id",3)->find()['num'];
                    db("user")->where("uid=$uid")->setInc("integ",$integ);
                 
                }
              
               
            }
        }
    }
    //使用佣金支付
    public function pays_y()
    {
         $did=input('did');
         $red=db("car_dd")->where("did=$did")->find();
         if($red){
            if($red['status'] == 0){
                $uid=Request::instance()->header('uid');
                $user=db("user")->where("uid=$uid")->find();
                if($user){
                     $money=$user['money'];
                     $price=$red['zprice'];
                     if($money >= $price){
                        $datas['status']=1;
                        $datas['pay_type']=1;
                        $resd=db("car_dd")->where("did=$did")->update($datas);
                        if($resd){
                            $pay = $red['pay'];
                            $res = explode(",",$pay);
                            foreach($res as $v){
                                $dd = db("car_dd")->where("code='$v'")->find();
                                $uid = $dd['uid'];
                                $gid = $dd['gid'];
                                $did = $dd['did'];
                                $num = $dd['num'];
                                $re_d = db("car_dd")->where("did=$did")->update($datas);
                                
                                //增加销量
                                $sales=db("goods")->where("gid=$gid")->setInc("g_sales",$num);
        
                                //减少库存
                                db("goods")->where("gid=$gid")->setDec("g_kc",$num);
        
                            }
                            $res=db("user")->where("uid=$uid")->setDec("money",$price);
                            $arrs['money']=$price;
                            $arrs['u_id']=$uid;
                            $arrs['time']=time();
                            $arrs['status']=0;
                            db("money_log")->insert($arrs);
                            $arr=[
                                'error_code'=>0,
                                'data'=>'支付成功'
                            ]; 
                        }else{
                            $arr=[
                                'error_code'=>5,
                                'data'=>'支付失败'
                            ]; 
                        }

                     }else{
                        $arr=[
                            'error_code'=>4,
                            'data'=>'股金不足'
                        ]; 
                     }
                }else{
                    $arr=[
                        'error_code'=>3,
                        'data'=>'用户不存在'
                    ]; 
                }
            }else{
                $arr=[
                    'error_code'=>2,
                    'data'=>'订单状态异常'
                ];
            }
         }else{
             $arr=[
                 'error_code'=>1,
                 'data'=>'此订单不存在'
             ];
         }
         echo json_encode($arr);
    }
    
    
    
    
    
    
    
    
}