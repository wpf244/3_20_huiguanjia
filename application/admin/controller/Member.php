<?php
namespace app\admin\controller;

use think\Request;
class Member extends BaseAdmin
{
    public function lister()
    {
        $list=db("user")->order("uid desc")->paginate(10);
        
        $res = [];
        foreach($list as $v){
            $v['pid_user'] = db("user")->where("uid", $v['fid'])->value('nickname');
            $res[] = $v;
        }
        $this->assign("list",$res);
        $page=$list->render();
        $this->assign("page",$page);   
        return $this->fetch();
    }

/**
     * 等级调整
     *
     * @return void
     */
    public function level_change(){
        $type = Request::instance()->param('type', '');
        $uid = Request::instance()->param('uid', 0);
        $user = db('user')->where('uid', $uid)->find();
        if($type == 'up'){
            if($user['level'] == 2){
                return array('status'=>-1, 'data'=>array(), 'msg'=>'已经是最高等级了');
            }
            $res = db('user')->where('uid', $uid)->setInc('level');
        }elseif($type == 'down'){
            if($user['level'] == 0){
                return array('status'=>-1, 'data'=>array(), 'msg'=>'已经是最低等级了');
            }
            $res = db('user')->where('uid', $uid)->setDec('level');
        }
        if($res){
            $level = db('user')->where('uid', $uid)->value('level');
            if($level == 1){
                $level_name = '一级合伙人';
            }elseif($level == 2){
                $level_name = '二级合伙人';
            }else{
                $level_name = '普通会员';
            }
            return array('status'=>1, 'data'=>array('level_name'=>$level_name), 'msg'=>'操作成功');
        }else{
            return array('status'=>1, 'data'=>array(), 'msg'=>'操作失败');
        }
    }

    /**
     * 修改奖励金
     *
     * @return void
     */
    public function change_bonus(){
        $id = Request::instance()->param('id', 0);
        $bonus = Request::instance()->param('bonus', -1);
        if($id == 0 || $bonus == -1){
            echo '0';
            return;
        }
        $res = db('user')->where('uid', $id)->setField('bonus', $bonus);
        if($res){
            echo "1";
        }else{
            echo "0";
        }
    }

    /**
     * 修改股数
     *
     * @return void
     */
    public function change_money(){
        $id = Request::instance()->param('id', 0);
        $money = Request::instance()->param('money', -1);
        if($id == 0 || $money == -1){
            echo '0';
            return;
        }
        $res = db('user')->where('uid', $id)->setField('money', $money);
        if($res){
            echo "1";
        }else{
            echo "0";
        }
    }

    /**
     * 股权日志
     *
     * @return void
     */
    public function money_log(){
        $id = Request::instance()->param('id', 0);
        $user = db('user')->where('uid', $id)->find();
        $list = db("money_log")->where('u_id', $id)->paginate(10);
        $this->assign('list', $list);
        $this->assign('user', $user);
        return $this->fetch();
    }

    /**
     * 奖励金日志
     *
     * @return void
     */
    public function bonus_log(){
        $id = Request::instance()->param('id', 0);
        $user = db('user')->where('uid', $id)->find();
        $list = db("bonus_log")->where('u_id', $id)->paginate(10);
        $this->assign('list', $list);
        $this->assign('user', $user);
        return $this->fetch();
    }

    /**
     * 奖励金提现
     *
     * @return void
     */
    public function balance(){
        $wx_account = Request::instance()->param('wx_account', '');
        $wx_nickname = Request::instance()->param('wx_nickname', '');
        $status = Request::instance()->param('status', '-1');
        $start = Request::instance()->param('start', '');
        $end = Request::instance()->param('end', '');
        $map = [];
        if($status != -1){
            $map['status'] = $status;
        }
        if($wx_account != ''){
            $map['wx_account'] = array('like', '%'.$wx_account.'%');
        }
        if($wx_nickname != ''){
            $map['wx_nickname'] = array('like', '%'.$wx_nickname.'%');
        }
        if($start != '' && $end != ''){
            $map['c.time'] = array(array('egt',strtotime($start)),array('elt',strtotime($end.' 23:55:55')),'AND');
        }elseif($start == '' && $end != ''){
            $map['c.time'] = array('elt',strtotime($end.' 23:55:55'));
        }elseif($start != '' && $end == ''){
            $map['c.time'] = array('egt',strtotime($start));
        }
        $list = db("bonus_withdrow")->alias('c')
        ->field('u.nickname, u.image, c.id, c.uid, c.money, c.wx_nickname, c.wx_account, c.status, c.time')
        ->join('user u','u.uid=c.uid')->where($map)->order('c.time desc')->paginate(10,false,['query'=>request()->param()]);

        $this->assign('wx_account', $wx_account);
        $this->assign('wx_nickname', $wx_nickname);
        $this->assign('status', $status);
        $this->assign('start', $start);
        $this->assign('end', $end);
        $this->assign('list', $list);
        return $this->fetch('balance');
    }

    /**
     * 奖励金状态
     *
     * @return void
     */
    public function balance_status(){
        $id = Request::instance()->param('id');
        $ftype = Request::instance()->param('ftype');
        db("bonus_withdrow")->where('id', $id)->setField('status', $ftype);
        $user = db("bonus_withdrow")->where('id', $id)->find();
        if($ftype == 3){
            //驳回，返回余额
            db("user")->where('uid', $user['uid'])->setInc('bonus', $user['money']);
        }
    }













    public function change()
    {
        $id=input('id');
        $re=db("user")->where("uid=$id")->find();
        if($re){
           if($re['u_status'] == 0){
            $data['u_status']=1;
            $data['u_jtime']=\time();

            $res=\db("user")->where("uid=$id")->update($data);

            $datas['u_id']=0;
            $datas['p_id']=$id;
            $datas['time']=time();
            db("user_log")->insert($datas);
            echo '1';
           }else{
            echo '2'; 
           } 
            
            
        }else{
            echo '0';
        }
    }
    public function changes()
    {
        $id=input('id');
        $re=db("user")->where("uid=$id")->find();
        if($re){
           if($re['u_status'] == 1){
            $data['u_status']=0;
            $data['u_jtime']="";

            $res=\db("user")->where("uid=$id")->update($data);

            echo '1';
           }else{
            echo '2'; 
           } 
            
            
        }else{
            echo '0';
        }
    }
    public function delete()
    {
        $id=input('id');
        $re=db("user")->where("uid=$id")->find();
        if($re){
            
            $del=db("user")->where("uid=$id")->delete();
            if($del){
                
                echo '0';
            }else{
                echo '1';
            }
        }else{
            echo '2';
        }
    }
    public function modifys()
    {
        $data=db("user")->field("u_name")->select();
        $arr=array();
        foreach($data as $v){
            $arr[]=$v['u_name'];
        }
        $this->assign("data",json_encode($arr,JSON_UNESCAPED_UNICODE));
        
        $id=input('id');
        $re=db("user")->where("uid=$id")->find();
        if($re){
            $this->assign("re",$re);
            return $this->fetch();
        }else{
            $this->redirect('lister');
        }

    }
    public function add()
    {
        $data=db("user")->field("u_name")->select();
        $arr=array();
        foreach($data as $v){
            $arr[]=$v['u_name'];
        }
        $this->assign("data",json_encode($arr,JSON_UNESCAPED_UNICODE));
        return $this->fetch();
    }
    public function save()
    {
        $pid=input('pid');
        $data=input('post.');
        if(empty($pid)){
            $data['pid']=0;
        }else{
            $re=db("user")->where("u_name='$pid'")->find();
            if($re){
                
                $data['pid']=$re['uid'];
              
            }else{
                $this->error("推荐人不存在",url('lister'));exit;
            }
        }
        if(\input('u_status')){
            $data['u_status']=1;
            $data['u_jtime']=time();
        }
        $data['u_pwd']=md5(input('u_pwd'));
        $data['u_pwds']=md5(\input('u_pwds'));
        $data['u_ztime']=time();
        $code=\time();
        $data['u_code']=mb_substr($code,-6,6);
        
        $rea=db("user")->insert($data);
        if($rea){
            $this->success("添加成功",url('lister'));
        }else{
            $this->error("系统繁忙，请稍后再试",url('lister'));
        }
        
    }
    public function usave()
    {
        $uid=input('uid');
        $re=db("user")->where("uid=$uid")->find();
        if($re){
            $pid=input('pid');
            if(empty($pid)){
                $data['pid']=0;
            }else{
                $re=db("user")->where("u_name='$pid'")->find();
                if($re){
                    $data['pid']=$re['uid'];  
                }else{
                    $this->error("推荐人不存在",url('lister'));exit;
                }
            }
            if(!empty('u_pwd')){
                $data['u_pwd']=md5(input('u_pwd'));
            }
            if(!empty('u_pwds')){
                $data['u_pwds']=md5(input('u_pwds'));
            }
            $data['u_name']=input('u_name');
            $data['level']=input('level');
            $data['u_phone']=input('u_phone');
            $data['u_wx']=input('u_wx');
            $data['u_alipay']=input('u_alipay');
            if(\input('u_status')){
                $data['u_status']=1;
            }else{
                $data['u_status']=$re['u_status'];
            }
            $res=db("user")->where("uid=$uid")->update($data);
            if($res){
                $this->success("修改成功",url('lister'));
            }else{
                $this->error("修改失败",url('lister'));
            }

        }else{
            $this->error("系统繁忙，请稍后再试",url('lister'));
        }
    }

















 
}