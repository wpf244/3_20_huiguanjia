<?php
namespace app\api\controller;


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
            $re=\db("code")->where("phone='$phone'")->find();
            if($re){
                $del=db("code")->where("phone='$phone'")->delete();
            }
            $rea=db("code")->insert($data);
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
}