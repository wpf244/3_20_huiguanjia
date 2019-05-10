<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Market extends BaseHome
{
    /**
    * 红包规则
    *
    * @return void
    */
    public function red_rule()
    {
        $re=db("red")->field("content")->where("id",1)->find();

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$re
        ];
    
        echo \json_encode($arr);
    }
    /**
    * 领红包
    *
    * @return void
    */
    public function red()
    {
        $uid=Request::instance()->header("uid");
        
        $re=db("red")->where("id",1)->find();
        
        $number=$re['number'];

        //查找用户今天领红包个数
        
        $re_number=db("red_log")->where(["uid"=>$uid,"type"=>1])->whereTime("time","d")->count();

        if($re_number >= $number){
            $arr=[
                'error_code'=>1,
                'msg'=>'红包领完了,明天再来吧',
                'data'=>[]
            ];
        }else{           
            if($re['open'] == 1){
                $money=(mt_rand(1,$re['money']*100))/100;
                $money=sprintf("%.2f",$money);

              //  var_dump($money);exit;
              $data['uid']=$uid;
              $data['money']=$money;
              $data['time']=time();
              $data['type']=1;
              $data['content']="领取红包";
                Db::startTrans();
                try{
                   db("user")->where("uid",$uid)->setInc("red_money",$money);
                  
                   db("red_log")->insert($data);
                    // 提交事务
                    Db::commit();   
                   
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    
                  
                }
                $arr=[
                    'error_code'=>0,
                    'msg'=>'获取成功',
                    'data'=>[
                        'money'=>$money,
                    ]
                ];
                
            }else{
             
                $arr=[
                    'error_code'=>1,
                    'msg'=>'红包领完了,明天再来吧',
                    'data'=>[]
                ];
            }

        }
        echo \json_encode($arr);
    }
    /**
    * 摇一摇
    *
    * @return void
    */
    public function shake()
    {
        $uid=Request::instance()->header("uid");
        
        $re=db("red")->where("id",2)->find();
        
        $number=$re['number'];

        //查找用户今天领红包个数
        
        $re_number=db("red_log")->where(["uid"=>$uid,"type"=>2])->whereTime("time","d")->count();

        if($re_number >= $number){
            $arr=[
                'error_code'=>1,
                'msg'=>'红包领完了,明天再来吧',
                'data'=>[]
            ];
        }else{           
            if($re['open'] == 1){
                $money=(mt_rand(1,$re['money']*100))/100;
                $money=sprintf("%.2f",$money);

              //  var_dump($money);exit;
              $data['uid']=$uid;
              $data['money']=$money;
              $data['time']=time();
              $data['type']=2;
              $data['content']="摇一摇";
                Db::startTrans();
                try{
                   db("user")->where("uid",$uid)->setInc("red_money",$money);
                  
                   db("red_log")->insert($data);
                    // 提交事务
                    Db::commit();   
                   
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    
                  
                }
                $arr=[
                    'error_code'=>0,
                    'msg'=>'获取成功',
                    'data'=>[
                        'money'=>$money,
                    ]
                ];
                
            }else{
             
                $arr=[
                    'error_code'=>1,
                    'msg'=>'红包领完了,明天再来吧',
                    'data'=>[]
                ];
            }

        }
        echo \json_encode($arr);
    }
    /**
    * 大转盘
    *
    * @return void
    */
    public function prize()
    {
        $url=parent::getUrl();

        $res=db("prize")->order(["sort asc","id desc"])->select();

        foreach($res as $k => $v){
            $res[$k]['image']=$url.$v['image'];
        }

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$res
        ];

        echo \json_encode($arr);
    }
    /**
    * 抽奖
    *
    * @return void
    */
    public function prize_save()
    {
        $res=db("prize")->field("id,name,money,proba")->select();

        foreach ($res as $key => $val) {   
            $arr[$val['id']] = ($val['proba']*100);//概率数组   
        }  

        $rid =$this->get_rand($arr); //根据概率获取奖项id
        
        $prize=db("prize")->where(["id"=>$rid])->find();

        $money=$prize['money'];

        $uid=Request::instance()->header("uid");
        
        $re=db("red")->where("id",3)->find();
        
        $number=$re['number'];

        //查找用户今天领红包个数
        
        $re_number=db("red_log")->where(["uid"=>$uid,"type"=>3])->whereTime("time","d")->count();

        if($re_number >= $number){
            $arr=[
                'error_code'=>1,
                'msg'=>'请明天再来',
                'data'=>[]
            ];
        }else{           
            if($re['open'] == 1){
               
              $data['uid']=$uid;
              $data['money']=$money;
              $data['time']=time();
              $data['type']=3;
              $data['content']="大转盘";
                Db::startTrans();
                try{
                   db("user")->where("uid",$uid)->setInc("red_money",$money);
                  
                   db("red_log")->insert($data);
                    // 提交事务
                    Db::commit();   
                   
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();

                    
                  
                }
                $arr=[
                    'error_code'=>0,
                    'msg'=>'获取成功',
                    'data'=>[
                        'money'=>$money,
                    ]
                ];
                
            }else{
             
                $arr=[
                    'error_code'=>2,
                    'msg'=>'谢谢参与',
                    'data'=>[]
                ];
            }

        }
        echo \json_encode($arr);



    }

    //计算中奖概率
    public function get_rand($proArr) {   
        $result = '';   
        //概率数组的总概率精度   
        $proSum = array_sum($proArr);   
        // var_dump($proSum);
        //概率数组循环   
        foreach ($proArr as $key => $proCur) {   
          $randNum = mt_rand(1, $proSum);  //返回随机整数 
   
          if ($randNum <= $proCur) {   
            $result = $key;   
            break;   
          } else {   
            $proSum -= $proCur;   
          }   
        }   
        unset ($proArr);   
        return $result;   
  
     }















}