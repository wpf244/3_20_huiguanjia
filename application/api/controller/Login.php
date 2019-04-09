<?php
namespace app\api\controller;

use think\Request;



class Login extends BaseApi
{
    
    /**
    * 授权登录
    *
    * @return void
    */
    public function login()
    {
        $code=input('code');

        $fid = Request::instance()->param('fid', 0);
        if($fid != 0){
            $data['fid']=$fid;
        }

        $url="https://api.weixin.qq.com/sns/jscode2session?appid=wx0bf6a1d285ef4fc8&secret=32cded6845fe0735fea052ab0e415d1e&js_code=".$code."&grant_type=authorization_code";
        $results=json_decode(file_get_contents($url),true);
        $openid=$results['openid'];
        if(!$openid){
            $arr=[
                'error_code'=>1,
                'msg'=>'openID获取失败',
                'data'=>''
            ];
        }else{
            
            $data['openid']=$openid;
            $data['nickname']=\input('nickname');
            $data['image']=\input('image');
            $data['time']=\time();
            $ret=db('user')->where(array('openid'=>$openid))->find();
            if($ret['openid']){
                $res=db("user")->where(array('openid'=>$openid))->update($data);
                    $arr=[
                        'error_code'=>0,
                        'msg'=>'授权成功',
                        'data'=>[
                            'uid'=>$ret['uid'],
                        ]
                    ];
            }else{
                $rea=db('user')->insert($data);
                $uid=db('user')->getLastInsID();
                if($rea){
                    $arr=[
                        'error_code'=>0,
                        'msg'=>'授权成功',
                        'data'=>[
                            'uid'=>$uid,
                        ]
                    ];
    
                }else{
                    $arr=[
                        'error_code'=>2,
                        'msg'=>'授权失败',
                        'data'=>''
                    ];
                }
               
            }
        }
        echo \json_encode($arr);
    }
    /**
    * 获取验证码
    *
    * @return void
    */
    public function getcode(){
        $phone=input('phone');
        $re=db('user')->where("phone=$phone")->find();
        if($re){
            $arr=[
                'error_code'=>1,
                'msg'=>'此手机号已注册',
                'data'=>""
            ];
        }else{
            $code =mt_rand(100000,999999);       
            $data['phone']=$phone;
            $data['code']=$code;
            $data['time']=time();
            $re=\db("sms_code")->where("phone='$phone'")->find();
            if($re){
                $del=db("sms_code")->where("phone='$phone'")->delete();
            }
            $rea=db("sms_code")->insert($data);
            Post($phone,$code);
            if($rea){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'发送成功',
                    'data'=>''
                ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'发送失败',
                    'data'=>''
                ];
            }
           
        }
        echo json_encode($arr);
    }
    /**
    * 注册
    *
    * @return void
    */
    public function save()
    {
        $uid=Request::instance()->header("uid");
        $level=input("level");
        $data=input("post.");
        $data['status']=1;
        $data['level']=1;
        $phone=input("phone");
        $reu=db("user")->where("phone",$phone)->find();
        $code=input("code");
        $re=db("sms_code")->where(['phone'=>$phone,'code'=>$code])->find();
        if($re){
            $time=$re['time'];
            $times=time();
            $c_time=($times-$time);
            if($c_time < 300){
                db("sms_code")->where("id",$re['id'])->delete();
                if($reu){
                    $arr=[
                        'error_code'=>5,
                        'msg'=>'此手机号码已注册',
                        'data'=>''
                    ];
                }else{
                    $user=db("user")->where("uid",$uid)->find();
                    if($user){
                    unset($data['code']);
                    if($level == 2){
                        
                        $res=db("user")->where("uid",$uid)->update($data);
                        $datas['u_id']=$uid;
                        $datas['u_phone']=$phone;
                        $datas['u_level']=$level;
                        $datas['u_time']=time();
                        db("user_apply")->insert($datas);
                        if($res){
                                $arr=[
                                    'error_code'=>0,
                                    'msg'=>'注册成功',
                                    'data'=>''
                                ]; 
                                }else{
                                    $arr=[
                                        'error_code'=>4,
                                        'msg'=>'注册失败',
                                        'data'=>''
                                    ]; 
                                }
                                
                            }else{
                            
                                $res=db("user")->where("uid",$uid)->update($data);
                                if($res){
                                    $arr=[
                                        'error_code'=>0,
                                        'msg'=>'注册成功',
                                        'data'=>''
                                    ]; 
                                }else{
                                    $arr=[
                                        'error_code'=>4,
                                        'msg'=>'注册失败',
                                        'data'=>''
                                    ]; 
                                }
                            }
                            }else{
                                $arr=[
                                'error_code'=>3,
                                'msg'=>'授权失败',
                                'data'=>''
                                ]; 
                            }
                        }
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'验证码已失效',
                    'data'=>''
                ]; 
            }
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'验证码错误',
                'data'=>''
            ];
        }
        
        echo json_encode($arr);
    }
    /**
    *  忘记密码获取验证码
    *
    * @return void
    */
    public function forget_get_code()
    {
        $phone=input('phone');
        $re=db('user')->where("phone=$phone")->find();
        if($re){
            $code =mt_rand(100000,999999);       
            $data['phone']=$phone;
            $data['code']=$code;
            $data['time']=time();
            $re=\db("sms_code")->where("phone='$phone'")->find();
            if($re){
                $del=db("sms_code")->where("phone='$phone'")->delete();
            }
            $rea=db("sms_code")->insert($data);
            Post($phone,$code);
            if($rea){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'发送成功',
                    'data'=>''
                ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'发送失败',
                    'data'=>''
                ];
            }
           
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'此手机号未注册',
                'data'=>""
            ];
            
        }
        echo json_encode($arr);
    }
    /**
    * 修改密码
    *
    * @return void
    */
    public function usave()
    {
        $code=input("code");
        $phone=input("phone");
        $re=db("sms_code")->where(['phone'=>$phone,'code'=>$code])->find();
        if($re){
            $time=$re['time'];
            $times=time();
            $c_time=($times-$time);
            if($c_time < 300){
                db("sms_code")->where("id",$re['id'])->delete();
                $user=db("user")->where("phone",$phone)->find();
                if($user){
                    $data['pwd']=input("pwd");
                    db("user")->where("phone",$phone)->update($data);
                   
                    $arr=[
                        'error_code'=>0,
                        'msg'=>'修改成功',
                        'data'=>''
                    ]; 
                    
                }else{
                    $arr=[
                        'error_code'=>3,
                        'msg'=>'此手机号码未注册',
                        'data'=>''
                    ]; 
                }
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'验证码已失效',
                    'data'=>''
                ]; 
            }
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'验证码错误',
                'data'=>''
            ];
        }
        
        echo json_encode($arr);

    }
    /**
    * 登录
    *
    * @return void
    */
    public function index()
    {
        $phone=input("phone");
        $pwd=input("pwd");
        $re=db("user")->where(["phone"=>$phone,"pwd"=>$pwd])->find();
        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>'登录成功',
                'data'=>['uid'=>$re['uid']]
            ]; 
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'账号或密码错误',
                'data'=>[]
            ]; 
        }
        echo json_encode($arr);
    }
   
}