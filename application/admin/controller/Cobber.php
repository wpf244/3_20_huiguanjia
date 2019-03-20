<?php
namespace app\admin\controller;

class Cobber extends BaseAdmin
{
    public function index()
    {
        $list=db("go_up")->where("status=0")->paginate(10);
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function delete()
    {
        $id=input('id');
        $re=db("go_up")->where("go_id=$id")->find();
        if($re){
           $del=db("go_up")->where("go_id=$id")->delete();
           echo '0';
        }else{
            echo '1';
        }
    }
    public function lister()
    {
        $list=db("go_up")->where("status=1")->paginate(10);
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function deletes()
    {
        $id=input('id');
        $re=db("go_up")->where("go_id=$id")->find();
        if($re){
           $del=db("go_up")->where("go_id=$id")->delete();
           echo '0';
        }else{
            echo '1';
        }
    }
}