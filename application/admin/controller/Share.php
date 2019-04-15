<?php
namespace app\admin\controller;

class Share extends BaseAdmin
{
    public function basic()
    {
        $re=db("basic")->where("id",1)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function save()
    {
        $data=input("post.");
        $re=db("basic")->where("id",1)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function share()
    {
        $re=db("share")->where("id",1)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function saves()
    {
        $data=input("post.");
        $re=db("share")->where("id",1)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
}