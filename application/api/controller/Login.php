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
        $payment=db("payment")->where("id",1)->find();
        $appid = $payment['appid'];
        $secret = $payment['appsecret'];
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
        $results=json_decode(file_get_contents($url),true);
     //  var_dump($results);exit;
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
       // $data=input("post.");
        $data['status']=1;
        $data['level']=1;
        $data['phone']=input("phone");
        $data['pwd']=input("pwd");
        $data['company']=input("company");
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
                 
                    if($level == 2){
                        
                        $res=db("user")->where("uid",$uid)->update($data);
                        $datas['u_id']=$uid;
                        $datas['u_phone']=$phone;
                        $datas['u_level']=$level;
                    //    $datas['company']=input("company"); //公司名称
                        $datas['name']=input("name"); //酒店名称
                        $datas['image']=input("image");
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
                                
                        }elseif($level == 3){
                                $res=db("user")->where("uid",$uid)->update($data);
                                $datass['u_id']=$uid;
                                $datass['username']=input("username");
                                $datass['idcode']=input("idcode");
                                $datass['addr']=input("addr");
                                $datass['genre']=input("genre");
                                $datass['u_phone']=$phone;
                                $datass['u_level']=3;
                                $datass['type']=1;
                                $datass['u_time']=time();
                               $datass['company']=input("company"); //公司名称
                                $datass['name']=input("name"); //酒店名称
                                $datass['image']=input("image");
                                $datass['u_time']=time();
                                db("user_apply")->insert($datass);

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
                                $data['company']=input("company");
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
           if($re['is_delete'] == 0){
            $arr=[
                'error_code'=>0,
                'msg'=>'登录成功',
                'data'=>['uid'=>$re['uid']]
            ]; 
           } else{
            $arr=[
                'error_code'=>1,
                'msg'=>'账号或密码错误',
                'data'=>[]
            ]; 
           }
            
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'账号或密码错误',
                'data'=>[]
            ]; 
        }
        echo json_encode($arr);
    }
    /**
    * 上传图片
    *
    * @return void
    */
    public function add_img(){
        if(!is_string(input('image'))){
            $image=uploads('image');
        }
        if($image){
            $arr=$image;
        }else{
            $arr="发布失败";
        }
        echo $arr;
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
   
}