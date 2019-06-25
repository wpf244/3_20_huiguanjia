<?php
namespace app\admin\controller;

class Ment extends BaseAdmin
{
    public function lister()
    {
        $list=db("need")->where("status",0)->paginate(20);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);

        db("need")->where("look",0)->setField("look",1);
        return $this->fetch();
    }
    public function delete(){
        $id=input('id');
        $re=db("need")->where("id=$id")->find();
        if($re){
           $del=db("need")->where("id=$id")->delete();
           
        }
        $this->redirect('lister');
    }
    public function change(){
        $id=input('id');
        $re=db("need")->where("id=$id")->find();
        if($re){
           db("need")->where("id=$id")->setField("status",1);
           
        }
        $this->redirect('lister');
    }
    public function index()
    {
        $list=db("need")->where("status",1)->paginate(20);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        return $this->fetch();
    }
    public function deletes(){
        $id=input('id');
        $re=db("need")->where("id=$id")->find();
        if($re){
           $del=db("need")->where("id=$id")->delete();
           
        }
        $this->redirect('index');
    }
    public function look()
    {
        $id=input("id");

        $list=db("need_order")->alias("a")->field("a.*,b.nickname,b.phone,c.username,c.name,c.addr,c.genre")->where("nid",$id)->join("user b","a.uid = b.uid")->join("user_apply c","a.uid=c.u_id")->order("a.id desc")->select();

        $this->assign("list",$list);
        
        return $this->fetch();
    }
}