<?php
namespace app\api\controller;

use think\Request;
use Think\Db;

class User extends BaseHome
{
    
    /**
    * 用户信息
    *
    * @return void
    */
    public function index()
    {
        $uid=Request::instance()->header('uid');

        $re=db("user")->field("nickname,image,phone,red_money,money")->where("uid",$uid)->find();

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$re
        ];
        echo \json_encode($arr);

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

    /**
    * 提交保存
    *
    * @return void
    */
    public function save()
    {
        $uid=Request::instance()->header('uid');

        $data['u_id']=$uid;
        $data['username']=input("username");
        $data['idcode']=input("idcode");
        $data['name']=input("name");
        $data['addr']=input("addr");
        $data['genre']=input("genre");
        $data['u_phone']=input("phone");
        $data['u_level']=3;
        $data['type']=1;
        $data['u_time']=time();
        $data['image']=input("image");

        $res=db("user_apply")->where(['u_id'=>$uid,'type'=>1])->find();

        if($res){
            if(empty($data['image'])){
                $data['image']=$res['image'];
            }
            $re=db("user_apply")->where("id",$res['id'])->update($data);
        }else{
            $re=db("user_apply")->insert($data);
        }

        

        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>"保存成功",
                'data'=>''
            ];
        }else{
            $arr=[
                'error_code'=>0,
                'msg'=>"保存失败",
                'data'=>''
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 申请加入前页面判断
    *
    * @return void
    */
    public function change_apply()
    {
        
        $url=parent::getUrl();

        $uid=Request::instance()->header('uid');

        $user=db("user")->where("uid",$uid)->find();

        if($user['level'] == 3){
            $arr=[
                'error_code'=>2,
                'msg'=>"已经是入住酒店啦",
                'data'=>[]
            ];
        }else{

            $re=db("user_apply")->where(['u_id'=>$uid,'type'=>1])->find();

            $re['image']=$url.$re['image'];

            if($re){
                $arr=[
                    'error_code'=>0,
                    'msg'=>"已经提交申请了",
                    'data'=>$re
                ];
            }else{
                $arr=[
                    'error_code'=>1,
                    'msg'=>"没有提交申请",
                    'data'=>[]
                ];
            }

        }

        
        echo \json_encode($arr);
    }
    /**
    * 申请进度查看
    *
    * @return void
    */
    public function apply_detail()
    {
        $uid=Request::instance()->header('uid');

        $re=db("user_apply")->field("u_status,rebut")->where(['u_id'=>$uid])->find();

        db("user_apply")->where(['u_id'=>$uid])->setField("rebut_look",1);

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$re
        ];
        echo \json_encode($arr);
    }
    /**
    * 我的顾问
    *
    * @return void
    */
    public function problem()
    {
        $res=db("lb")->field("id,name")->where(["fid"=>6,"status"=>1])->order(["sort asc","id desc"])->select();
        if($res){
            $arr=[
                'error_code'=>0,
                'msg'=>"获取成功",
                'data'=>$res
            ]; 
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>"暂无数据",
                'data'=>[]
            ]; 
        }
        echo \json_encode($arr);
    }
    /**
    * 问题详情
    *
    * @return void
    */
    public function problem_detail()
    {
        $id=input("id");
        $re=db("lb")->field("id,name,desc")->where("id",$id)->find();
        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>"获取成功",
                'data'=>$re
            ]; 
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>"暂无数据",
                'data'=>[]
            ]; 
        }
        echo \json_encode($arr);

    }
    /**
    * 推荐好友列表
    *
    * @return void
    */
    public function friends_lister()
    {
        $uid=Request::instance()->header("uid");
        $re=db("user")->field("money,already_money")->where(["uid"=>$uid,"is_delete"=>0])->find();

        $achievement=db("user")->where(["fid"=>$uid,"is_delete"=>0])->select();
        $re['achievement']=count($achievement);

        $data=array();
        $data[]=$uid;
        foreach($achievement as $v){
            $data[]=$v['uid'];
        }
        $re['team']=db("user")->where(["fid"=>["in",$data],"is_delete"=>0])->count();

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$re
        ]; 
    
       echo \json_encode($arr);
    }
    /**
    * 我的团队一级分销成员
    *
    * @return void
    */
    public function my_first_team()
    {
        $uid=Request::instance()->header("uid");

        $res=db("user")->field("uid as userid,nickname,image,phone")->where(["fid"=>$uid,"is_delete"=>0])->select();

        foreach($res as $k => $v){
            $res[$k]['cou']=db("user")->where(["fid"=>$v['userid'],"is_delete"=>0])->count();
        }

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$res
        ]; 
    
       echo \json_encode($arr);


    }
    /**
    * 我的团队一级分销详情
    *
    * @return void
    */
    public function my_first_team_detail()
    {
        $userid=input("userid");
        $res=db("user")->field("uid,nickname,image,time,money")->where(["fid"=>$userid,"is_delete"=>0])->select();

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$res
        ]; 
    
       echo \json_encode($arr);
    }
    /**
    * 我的团队二级分销
    *
    * @return void
    */
    public function my_team()
    {
        $uid=Request::instance()->header("uid");
    
        $achievement=db("user")->where(["fid"=>$uid,"is_delete"=>0])->select();
        
        $data=array();
        foreach($achievement as $v){
            $data[]=$v['uid'];
        }
        if(!empty($data)){
            $res=db("user")->field("uid,nickname,image,phone,money")->where(["fid"=>["in",$data],"is_delete"=>0])->select();
        }else{
            $res=[];
        }
        

        $arr=[
            'error_code'=>0,
            'msg'=>"获取成功",
            'data'=>$res
        ]; 
    
       echo \json_encode($arr);
    }

     /**
     * 代言名片
     *
     * @return void
     */
     public function card(){
        $uid = Request::instance()->header('uid');
        $user = db('user')->where('uid', $uid)->find();
        if($user['card'] != ''){
            $url = parent::getUrl().'/'.$user['card'];
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$url
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'重新获取',
                'data'=>''
            ];
        }
       
        echo \json_encode($arr);
    }

    /**
     * 获取小程序二维码
     *
     * @return void
     */
    public function getqrcode(){
        //接收参数
        $uid = Request::instance()->header('uid');
        $scene = Request::instance()->param('scene', 0);
        $page = Request::instance()->param('page', '');

        

        //微信token
        $payment=db("payment")->where("id",1)->find();
        $appid = $payment['appid'];
        $secret = $payment['appsecret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
        $results=json_decode(file_get_contents($url)); 
        //请求二维码的二进制资源
        $post_data='{"scene":"'.$scene.'", "page":"'. $page .'"}';
        $res_url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$results->access_token;
        $result=$this->httpRequest($res_url,$post_data,'POST');
        
        //转码为base64格式并本地保存
        $base64_image ="data:image/jpeg;base64,".base64_encode($result);

        
        $path = 'uploads/'.uniqid().'.jpg';
        $res = $this->file_put($base64_image, $path);
      //  var_dump($res);exit;
        //var_dump($res);exit;
        //业务处理
        if($res){
            db('user')->where('uid', $uid)->update(['card'=>$path]);
            $url_res=parent::getUrl();
            $arr=[
                'error_code'=>0,
                'data'=>$url_res.'/'.$path,
                'msg'=>'生成成功'
            ];
        }else{
            $arr=[
                'error_code'=>2,
                'data'=>'',
                'msg'=>'生成失败'
            ];
        }
        echo \json_encode($arr);
    }

    /**
     * 图片保存
     *
     * @param [type] $base64_image_content base64格式图片资源
     * @param [type] $new_file 保存的路径，文件夹必须存在
     * @return void
     */
     public function file_put($base64_image_content,$new_file)
     {
         header('Content-type:text/html;charset=utf-8');
         if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
             if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                 return true;
             }else{
                 return false;
             }
         }
     }

    /**
     * curl函数网站请求封装函数
     *
     * @param [type] $url 请求地址
     * @param string $data 数据
     * @param string $method 请求方法
     * @return void
     */
     function httpRequest($url, $data='', $method='GET'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }
     
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    /**
    * 提现
    *
    * @return void
    */
    public function cash()
    {
        $uid=Request::instance()->header("uid");

        $re=db("user")->field("money")->where("uid",$uid)->find();

        $lb=db("lb")->field("desc")->where("fid",8)->find();
        $re['desc']=$lb['desc'];
        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$re
            
        ];
        echo \json_encode($arr);
    }
    /**
    * 提现
    *
    * @return void
    */
    public function red_cash()
    {
        $uid=Request::instance()->header("uid");

        $re=db("user")->field("red_money")->where("uid",$uid)->find();

        // $lb=db("lb")->field("desc")->where("fid",8)->find();
        // $re['desc']=$lb['desc'];
        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>$re
            
        ];
        echo \json_encode($arr);
    }
     /**
    * 红包余额保存提现
    *
    * @return void
    */
    public function red_cash_save()
    {
        $uid=Request::instance()->header("uid");

        $money=input("money");
        $content=input("content");

        $re=db("user")->field("red_money")->where("uid",$uid)->find();
        $moneys=$re['red_money'];

        if($moneys >= $money){
            $basic=db("basic")->where("id",1)->find();
            $quota=$basic['quota'];
            $charge=$basic['rate'];
            if($money >= $quota){
               
                   
                $data['uid']=$uid;
                $data['moneys']=$money;
                $data['charge']=$money*$charge/100;
                $data['money']=$money-$data['charge'];
                $data['content']=$content;
                $data['time']=time();
                $data['balance']=$moneys-$money;
                $data['types']=1;

                $datas['uid']=$uid;
                $datas['money']=$money;
                $datas['type']=0;
                $datas['content']="提现减少红包余额";
                $datas['time']=time();

                Db::startTrans();
                try{
                   
                   db("user")->where("uid",$uid)->setDec("red_money",$money);
                   db("cash")->insert($data);
                   db("red_log")->insert($datas);
                    // 提交事务
                    Db::commit();   
                   
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    $arr=[
                        'error_code'=>3,
                        'msg'=>"系统繁忙请稍后再试",
                        'data'=>[]
                        
                    ];
                }

              $arr=[
                    'error_code'=>0,
                    'msg'=>"操作成功",
                    'data'=>[]
                    
                ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>"最少提现".$quota."元",
                    'data'=>[]
                    
                ];
            }
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'红包余额不足',
                'data'=>[]
                
            ];
        }
        echo \json_encode($arr);
    }

    /**
    * 红包获取记录
    *
    * @return void
    */
    public function red_log()
    {
        $uid=Request::instance()->header("uid");

        $res=db("red_log")->where(["uid"=>$uid,"type"=>['>',0]])->order("id desc")->select();

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
    * 红包提现记录
    *
    * @return void
    */
    public function red_cash_log()
    {
        $uid=Request::instance()->header("uid");
        $res=db("cash")->where(["uid"=>$uid,"types"=>1])->order("id desc")->select();
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
    * 保存提现
    *
    * @return void
    */
    public function cash_save()
    {
        $uid=Request::instance()->header("uid");

        $money=input("money");
        $content=input("content");

        $re=db("user")->field("money")->where("uid",$uid)->find();
        $moneys=$re['money'];

        if($moneys >= $money){
            $basic=db("basic")->where("id",1)->find();
            $quota=$basic['quota'];
            $charge=$basic['rate'];
            if($money >= $quota){
               
                   
                $data['uid']=$uid;
                $data['moneys']=$money;
                $data['charge']=$money*$charge/100;
                $data['money']=$money-$data['charge'];
                $data['content']=$content;
                $data['time']=time();
                $data['balance']=$moneys-$money;

                $datas['uid']=$uid;
                $datas['money']=$money;
                $datas['type']=0;
                $datas['oper']="提现减少佣金";
                $datas['time']=time();

                Db::startTrans();
                try{
                   
                   db("user")->where("uid",$uid)->setDec("money",$money);
                   db("cash")->insert($data);
                   db("money_log")->insert($datas);
                    // 提交事务
                    Db::commit();   
                   
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    $arr=[
                        'error_code'=>3,
                        'msg'=>"系统繁忙请稍后再试",
                        'data'=>[]
                        
                    ];
                }

              $arr=[
                    'error_code'=>0,
                    'msg'=>"操作成功",
                    'data'=>[]
                    
                ];
            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>"最少提现".$quota."元",
                    'data'=>[]
                    
                ];
            }
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'佣金余额不足',
                'data'=>[]
                
            ];
        }
        echo \json_encode($arr);
    }
    /**
    * 提现记录
    *
    * @return void
    */
    public function cash_log()
    {
        $uid=Request::instance()->header("uid");
        $res=db("cash")->where(["uid"=>$uid,"types"=>0])->order("id desc")->select();
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
    * 我的订单
    *
    * @return void
    */
    public function my_dd()
    {
        $uid=Request::instance()->header("uid");
        $url=parent::getUrl();

        $status=input("status");

        $map=[];

        if($status || $status === "0"){
            $map['status']=['eq',$status];
        }

        $res=db("order")->field("id,hotel,room,dates,image,status")->where($map)->where(["uid"=>$uid])->select();

        if($res){
            foreach($res as $k => $v){
                $res[$k]['image']=$url.$v['image'];
            }
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
    * 订单详情
    *
    * @return void
    */
    public function dd_detail()
    {
        $id=input("id");
        $url=parent::getUrl();

        $re=db("order")->where(["id"=>$id,"is_delete"=>0])->find();
        $re['image']=$url.$re['image'];
        if($re){
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
        echo \json_encode($arr);
    }
    /**
    * 保存评价
    *
    * @return void
    */
    public function save_assess()
    {
        
       
        $id=input("did");

        $order=db("order")->where("id",$id)->find();

        if($order['status'] == 2){
            $res=db("order")->where("id",$id)->setField("status",3);

            $data['hid']=$order['hid'];
            $data['number']=input("number");
            $data['content']=input("content");
            $data['addtime']=time();
            $data['status']=0;
            $data['uid']=Request::instance()->header("uid");
            $re=db("assess")->insert($data);
            if($re){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'保存成功',
                    'data'=>[]
                    
                ];
            }else{
                $arr=[
                    'error_code'=>1,
                    'msg'=>'保存失败',
                    'data'=>[]
                    
                ];
            }
        }else{
            $arr=[
                'error_code'=>2,
                'msg'=>'订单状态异常',
                'data'=>[]
                
            ];
        }

        
        echo \json_encode($arr);
    }
    /**
    * 订单确认完成
    *
    * @return void
    */
    public function change()
    {
        $did=input("did");

        $re=db("order")->where(["id"=>$did])->find();

        if($re['status'] == 1){
            $res=db("order")->where(["id"=>$did])->setField("status",2);
            if($res){
                $arr=[
                    'error_code'=>0,
                    'msg'=>'操作成功',
                    'data'=>[]
                    
                ];
            }else{
                $arr=[
                    'error_code'=>1,
                    'msg'=>'操作失败',
                    'data'=>[]
                    
                ];
            }
        }else{
            $arr=[
                'error_code'=>2,
                'msg'=>'订单状态异常',
                'data'=>[]
                
            ];
        }
        echo \json_encode($arr);
    }



}