<?php
namespace app\admin\controller;

use think\Request;
class Agio extends BaseAdmin
{
    public function lister()
    {
        $re=db("cobber")->where("id=1")->find();
        $this->assign("re",$re);
        return $this->fetch();
    }
    public function save()
    {
        $data=input("post.");
        $res=db("cobber")->where("id=1")->update($data);
        if($res){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function index()
    {
        $re=db("cobber")->where("id=2")->find();
        $this->assign("re",$re);
        return $this->fetch();
    }
    public function usave()
    {
        $data=input("post.");
        $res=db("cobber")->where("id=2")->update($data);
        if($res){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function fund()
    {
        $re=db("fund")->where("id=1")->find();
        $this->assign("re",$re);
        return $this->fetch();
    }
    public function level()
    {
        $list=db("level")->select();
        $this->assign("list",$list);
        return $this->fetch();
    }

    public function save_place(){
        if($this->request->isAjax()){
            $id=$_POST['id'];
            if($id){
                $data=input("post.");
                $res=db("level")->where("id=$id")->update($data);
                if($res){
                    $this->success("修改成功！",url('level'));
                }else{
                    $this->error("修改失败！",url('level'));
                }
            }else{
                $data=input("post.");
                $re=db("level")->insert($data);
                if($re){
                    $this->success("添加成功！",url('level'));
                }else{
                    $this->error("添加失败！",url('level'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('level'));
        }
    }
    public function modify(){
        $id=input('id');
        $re=db('level')->where("id=$id")->find();
        echo json_encode($re);
    }

    /**
     * 推荐奖励金
     *
     * @return void
     */
    public function bonus(){
        $bonus = db("cobber")->where("id",3)->find();
        if(!$bonus){
            db("cobber")->insert(['id'=>3, 'rabate'=>5]);
            $bonus = db("cobber")->where("id",3)->find();
        }
        $this->assign("re",$bonus);
        return $this->fetch();
    }

    /**
     * 修改奖励金百分比
     *
     * @return void
     */
    public function bonus_modify(){
        $rabate = Request::instance()->param('rabate', 5);
        $res = db("cobber")->where("id", 3)->update(['rabate'=>$rabate]);
        if($res){
            $this->success("修改成功！");
        }else{
            $this->error("修改失败！");
        }
    }

}