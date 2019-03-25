<?php
namespace app\admin\controller;

class Demand extends BaseAdmin
{
    public function type()
    {
        $list = Db('demand')->where("type",1)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_type(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/type'));
                }else{
                    $this->error("修改失败！",url('Demand/type'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=1;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/type'));
                }else{
                    $this->error("添加失败！",url('Demand/type'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/type'));
        }
    }
    public function sort_type(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('type');
    }
    public function delete_type(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('type');
    }
    public function modify_type(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }

    public function times()
    {
        $list = Db('demand')->where("type",2)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_times(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/times'));
                }else{
                    $this->error("修改失败！",url('Demand/times'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=2;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/times'));
                }else{
                    $this->error("添加失败！",url('Demand/times'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/times'));
        }
    }
    public function sort_times(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('times');
    }
    public function delete_times(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('times');
    }
    public function modify_times(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }

    public function number()
    {
        $list = Db('demand')->where("type",3)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_number(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/number'));
                }else{
                    $this->error("修改失败！",url('Demand/number'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=3;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/number'));
                }else{
                    $this->error("添加失败！",url('Demand/number'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/number'));
        }
    }
    public function sort_number(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('number');
    }
    public function delete_number(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('number');
    }
    public function modify_number(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }


    public function money()
    {
        $list = Db('demand')->where("type",4)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_money(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/money'));
                }else{
                    $this->error("修改失败！",url('Demand/money'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=4;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/money'));
                }else{
                    $this->error("添加失败！",url('Demand/money'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/money'));
        }
    }
    public function sort_money(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('money');
    }
    public function delete_money(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('money');
    }
    public function modify_money(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }

    public function lister()
    {
        $list = Db('demand')->where("type",5)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_lister(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/lister'));
                }else{
                    $this->error("修改失败！",url('Demand/lister'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=5;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/lister'));
                }else{
                    $this->error("添加失败！",url('Demand/lister'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/lister'));
        }
    }
    public function sort_lister(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('lister');
    }
    public function delete_lister(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('lister');
    }
    public function modify_lister(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }

    public function city()
    {
        $list = Db('demand')->where("type",6)->order(['sort asc','id desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_city(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['name']=input("name");
                $res=db("demand")->where("id",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Demand/city'));
                }else{
                    $this->error("修改失败！",url('Demand/city'));
                }
            }else{
                $data['name']=input("name");
                $data['type']=6;
                $re=db("demand")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Demand/city'));
                }else{
                    $this->error("添加失败！",url('Demand/city'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Demand/city'));
        }
    }
    public function sort_city(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('demand')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('city');
    }
    public function delete_city(){
        $id=input('id');
        $re=db("demand")->where("id=$id")->find();
        if($re){
           $del=db("demand")->where("id=$id")->delete();
           
        }
        $this->redirect('city');
    }
    public function modify_city(){
        $id=input('id');
        $re=db('demand')->where("id=$id")->find();
        echo json_encode($re);
    }

}