<?php
namespace app\admin\controller;

class Market extends BaseAdmin
{
    public function red()
    {
        $re=db("red")->where("id",1)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function save()
    {
        $data=input("post.");
        $re=db("red")->where("id",1)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function red_log()
    {
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>1])->join("user b","a.uid=b.uid")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
    public function shake()
    {
        $re=db("red")->where("id",2)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function saves()
    {
        $data=input("post.");
        $re=db("red")->where("id",2)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function shake_log()
    {
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>2])->join("user b","a.uid=b.uid")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
    public function lister()
    {
        $re=db("red")->where("id",3)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function savel()
    {
        $data=input("post.");
        $re=db("red")->where("id",3)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function turntable()
    {
        $list=db("prize")->order(["sort asc","id desc"])->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);
        
        return $this->fetch();
    }
    public function add()
    {
        return $this->fetch();
    }
    public function savep()
    {
        $data=input("post.");
        $file=request()->file("image");
        if(!empty($file)){
            $data['image']=uploads("image");
        }
        $re=db("prize")->insert($data);
        if($re){
            $this->success("添加成功",url('turntable'));
        }else{
            $this->error("添加失败");
        }
    }
    public function modifys()
    {
        $id=input("id");
        $re=db("prize")->where("id",$id)->find();
        $this->assign("re",$re);
        
    
        return $this->fetch();
    }
    public function usavep()
    {
        $id=input("id");
        $re=db("prize")->where(['id'=>$id])->find();
        if($re){
            $data=input("post.");
            $file=request()->file("image");
            if(!empty($file)){
                $data['image']=uploads("image");
            }else{
                $data['image']=$re['image'];
            }
            $res=db("prize")->where(['id'=>$id])->update($data);
            if($res){
                $this->success("修改成功",url('turntable'));
            }else{
                $this->error("修改失败",url('turntable'));
            }
        }else{
            $this->error("参数错误",url('turntable'));
        }
    }
    public function sort(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('prize')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('turntable');
    }
    public function delete(){
        $id=input('id');
        $re=db("prize")->where("id=$id")->find();
        if($re){
           $del=db("prize")->where("id=$id")->delete();
           if($del){
               echo '1';
           }else{
               echo '2';
           }
        }else{
            echo '0';
        }
       
    }
    public function delete_all(){
        $id=input('id');
        $arr=explode(",", $id);
        foreach ($arr as $v){
            $re=db('prize')->where("id=$v")->find();
            if($re){
                $del=db('prize')->where("id=$v")->delete();
        }
        
       }
       $this->redirect('turntable');
    }
    public function turntable_log()
    {
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>3])->join("user b","a.uid=b.uid")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
}