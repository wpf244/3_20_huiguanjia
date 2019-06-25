<?php
namespace app\api\controller;

class Index extends BaseApi
{
    public function index()
    {
        $url=parent::getUrl();
        //banner图
        $lb=db("lb")->field("image")->where(["fid"=>1,"status"=>1])->order(["sort asc","id desc"])->select();
        foreach($lb as $k =>$v){
            $lb[$k]['image']=$url.$v['image'];
        }

        //需求广告
        $demand=db("lb")->field("desc")->where("fid",2)->find();
        $demand['desc']=strip_tags($demand['desc']);

        //服务保障
        $ensure=db("lb")->field("name,image")->where(["fid"=>3,"status"=>1])->order(["sort asc","id desc"])->select();
        foreach($ensure as $ke =>$ve){
            $ensure[$ke]['image']=$url.$ve['image'];
        }

        //举办过的会议
        $meeting=db("meeting")->field("id,name,company,hotel,image")->where(["is_delete"=>0])->order(['sort asc','id desc'])->select();
        foreach($meeting as $km => $vm){
            $meeting[$km]['image']=$url.$vm['image'];
        }

        //热门会议酒店
        $hot=db("hotel")->field("id,name,addr,image")->where(["is_delete"=>0,'up'=>1,'status'=>1])->order(['sort asc','id desc'])->select();
        foreach($hot as $kh => $vh){
            $hot[$kh]['image']=$url.$vh['image'];
        }

        //精选会议酒店
        $choice=db("hotel")->field("id,name,addr,image,type,area")->where(["is_delete"=>0,'up'=>1,'statu'=>1])->order(['sort asc','id desc'])->select();
        foreach($choice as $kc => $vc){
            $choice[$kc]['image']=$url.$vc['image'];
        }

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'lb'=>$lb,
                'demand'=>$demand,
                'ensure'=>$ensure,
                'meeting'=>$meeting,
                'hot'=>$hot,
                'choice'=>$choice

            ]
        ];
    
        echo \json_encode($arr);
    }
    public function demand()
    {
        //会议类型
        $type=db("demand")->field("id,name")->where("type",1)->order(["sort asc","id desc"])->select();

        //会议时长
        $times=db("demand")->field("id,name")->where("type",2)->order(["sort asc","id desc"])->select();

        //参会人数
        $number=db("demand")->field("id,name")->where("type",3)->order(["sort asc","id desc"])->select();

        //会议预算
        $money=db("demand")->field("id,name")->where("type",4)->order(["sort asc","id desc"])->select();

        //会议需求
        $ment=db("demand")->field("id,name")->where("type",5)->order(["sort asc","id desc"])->select();

        //会议城市
        $city=db("demand")->field("id,name")->where("type",6)->order(["sort asc","id desc"])->select();

        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'type'=>$type,
                'times'=>$times,
                'number'=>$number,
                'money'=>$money,
                'ment'=>$ment,
                'city'=>$city,
            ]
        ];
    
        echo \json_encode($arr);
    }
    /**
    * 保存需求
    *
    * @return void
    */
    public function save()
    {
        $data=input("post.");
        $data['time']=time();
        unset($data['formid']);
        $re=db("need")->insert($data);
        if($re){

            $formid=input("formid");

            $page="/pages/invitation/invitation";

            $user=db("user")->where(["status"=>1,"level"=>3])->select();

            $datas['phone']=input("phone");
            $datas['time']=date("Y/m/d");

            if($user){
                foreach($user as $v){
                    $this->send($v['openid'],$formid,$page,$datas);
                }
            }

            $arr=[
                'error_code'=>0,
                'msg'=>'提交成功',
                'data'=>''
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'提交失败',
                'data'=>''
            ];
        }
        echo \json_encode($arr);
    }
    public function send($openid,$formid,$page,$datas)
    {
        $result=$this->get_token();

        $token = $result['access_token'];

        $template_id=$this->get_template_id($token);



    //    $datas='{
    //     "keyword1" => array(
    //         "value" => '.$datas["phone"].'
    //     ),
    //     "keyword2" => array(
    //         "value" => '.$datas["time"].'
    //     }';
     
        $datas = array(
                'touser' => $openid, 
                'template_id' => $template_id, 
                // 'page' => 'pages/ordermsg/ordermsg?id = ' . $wx_id,//要跳转的页面点击推送消息，可以携带参数，跳转到小程序后显示详细的信息。
                'page' => $page,//点击推送消息要跳转的页面
                'form_id' => $formid,
                'data' => array(
                    'keyword1' => array(
                        'value' => $datas['phone'], // 商品名称
                        'color' => '#173177'
                    ),
                    'keyword2' => array(
                        'value' => $datas['time'], //订单金额
                        'color' => '#173177'
                    )
                
                )
        );
    $post_datas = json_encode($datas);


        // $post_datas='{"touser":"'.$openid.'", "template_id":"'. $template_id .'", "page":"'. $page .'", "form_id":"'. $formid .'", "data":"'. $data .'"}';

        $res_url="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$token;

        $results=$this->httpRequest($res_url,$post_datas,'POST');

    //    var_dump($results);exit;
        
    }
    /**
    * 获取模板消息id
    *
    * @return void
    */
    public function get_template_id($token)
    {
        $post_data='{"offset":"0", "count":"1"}';
  
        $res_url="https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=".$token;
        $results=$this->httpRequest($res_url,$post_data,'POST');

        $template_id=json_decode($results)->list[0]->template_id;

        return $template_id;
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
    * 获取token
    *
    * @return void
    */
    public function get_token()
    {
        $payment=db("payment")->where("id",1)->find();
        $appid = $payment['appid'];
        $secret = $payment['appsecret'];

        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";

        $results=json_decode(file_get_contents($url),true);

        return $results;
    }







}