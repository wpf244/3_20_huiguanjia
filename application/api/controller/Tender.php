<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Tender extends BaseHome
{
    public function lister()
    {
        $url=parent::getUrl();
        
        $banner=db("lb")->field("image")->where(["fid"=>9,"status"=>1])->order(["sort asc","id desc"])->select();

        foreach($banner as $k => $v){
            $banner[$k]['image']=$url.$v['image'];
        }

        $list=db("need")->where(["status"=>0])->order("id desc")->select();



        $arr=[
            'error_code'=>0,
            'msg'=>'获取成功',
            'data'=>[
                'banner'=>$banner,
                'list'=>$list
            ]
        ];
    
        echo \json_encode($arr);
    }
    public function detail()
    {
        $id=input("id");

        $re=db("need")->where(["id"=>$id,"status"=>0])->find();

        $re['lister']=db("need_order")->alias("a")->field("b.company")->where(["nid"=>$id])->join("user b","a.uid=b.uid")->select();

        if($re){
            $arr=[
                'error_code'=>0,
                'msg'=>'获取成功',
                'data'=>$re
            ];
        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'此订单不存在',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);

    }
    /**
    * 接单
    *
    * @return void
    */
    public function save()
    {
        $uid=Request::instance()->header("uid");

        $user=db("user")->where("uid",$uid)->find();

        if($user['level'] == 3){

            $id=input("id");

            $re=db("need")->where(["id"=>$id,"status"=>0])->find();

            if($re){

                $data['uid']=$uid;
                $data['nid']=$id;
                $data['time']=time();

                // 启动事务
                Db::startTrans();
                try{
                    $need_order=db("need_order")->where(["uid"=>$uid,"nid"=>$id])->find();

                    if(empty($need_order)){
                        db("need_order")->insert($data);
                        db("need")->where("id",$id)->setInc("num",1);

                        

                    $arr=[
                        'error_code'=>0,
                        'msg'=>'接单成功',
                        'data'=>[]
                    ];
                    }else{
                        $arr=[
                            'error_code'=>4,
                            'msg'=>'已经接过此单了',
                            'data'=>[]
                        ];
                    }
                   
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {

                    $arr=[
                        'error_code'=>3,
                        'msg'=>'接单失败',
                        'data'=>[]
                    ];
                    // 回滚事务
                    Db::rollback();
                }

             

            }else{
                $arr=[
                    'error_code'=>2,
                    'msg'=>'此订单不存在',
                    'data'=>[]
                ];
            }

        }else{
            $arr=[
                'error_code'=>1,
                'msg'=>'只有入住酒店才可以接单哦',
                'data'=>[]
            ];
        }
        echo \json_encode($arr);
    }
}