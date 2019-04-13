<?php
namespace app\admin\controller;

class Meeting extends BaseAdmin
{
    public function lister()
    {
        $key=input("key");
        $map=[];
        if($key){
            $map['name']=array("like","%$key%");
        }
        $list=db("meeting")->where("is_delete",0)->order(["sort asc","id desc"])->where($map)->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function add()
    {
     
        return $this->fetch();
    }
    public function save()
    {
        $data=input("post.");
        $file=request()->file("image");
        if(!empty($file)){
            $data['image']=uploads("image");
        }
        $re=db("meeting")->insert($data);
        if($re){
            $this->success("添加成功",url('lister'));
        }else{
            $this->error("添加失败");
        }
    }
    public function modifys()
    {
        $id=input("id");
        $re=db("meeting")->where("id",$id)->find();
        $this->assign("re",$re);
        
        return $this->fetch();
    }
    public function usave()
    {
        $id=input("id");
        $re=db("meeting")->where(['id'=>$id,'is_delete'=>0])->find();
        if($re){
            $data=input("post.");
            $file=request()->file("image");
            if(!empty($file)){
                $data['image']=uploads("image");
            }else{
                $data['image']=$re['image'];
            }
            $res=db("meeting")->where(['id'=>$id,'is_delete'=>0])->update($data);
            if($res){
                $this->success("修改成功",url('lister'));
            }else{
                $this->error("修改失败",url('lister'));
            }
        }else{
            $this->error("参数错误",url('lister'));
        }
    }
    public function changeu(){
        $id=input('id');
        $re=db('meeting')->where("id=$id")->find();
        if($re){
            if($re['up'] == 0){
                $res=db('meeting')->where("id=$id")->setField("up",1);
            }
            if($re['up'] == 1){
                $res=db('meeting')->where("id=$id")->setField("up",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }

    public function delete(){
        $id=input('id');
        $re=db("meeting")->where("id=$id")->find();
        if($re){
           $del=db("meeting")->where("id=$id")->setField("is_delete",-1);
           if($del){
               echo '1';
           }else{
               echo '2';
           }
        }else{
            echo '0';
        }
       
    }
    public function sort(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('meeting')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('lister');
    }
    public function delete_all(){
        $id=input('id');
        $arr=explode(",", $id);
        foreach ($arr as $v){
            $re=db('meeting')->where("id=$v")->find();
            if($re){
                $del=db('meeting')->where("id=$v")->setField("is_delete",-1);
        }
        
       }
       $this->redirect('lister');
    }
}