<?php
namespace app\admin\controller;

class Hotel extends BaseAdmin
{
    public function city()
    {
        $list=db('hotel_city')->where("pid=0")->order(["c_sort asc","cid asc"])->select();
        foreach($list as $k => $v){
            $list[$k]['lists']=db("hotel_city")->where("pid={$v['cid']}")->order(["c_sort asc","cid asc"])->select();
        }
        $this->assign("list",$list);

        return $this->fetch();
    }
    public function add_city()
    {
        $res=\db("hotel_city")->where("pid=0")->order("c_sort asc")->select();
        $this->assign("res",$res);
        
        return $this->fetch();
    }
    public function save_city()
    {
        $data=input('post.');
        $re=db("hotel_city")->insert($data);
        if($re){
            $this->success("添加成功",url('city'));
        }else{
            $this->error("添加失败",url('city'));
        }
    }
    public function modifys_city()
    {
        $res=\db("hotel_city")->where("pid=0")->select();
        $this->assign("res",$res);

        $id=input('id');
        $re=db("hotel_city")->where("cid=$id")->find();
        $this->assign("re",$re);
        
        return $this->fetch();
    }
    public function usave_city()
    {
        $cid=input('cid');
        $data=input('post.');
        $re=\db("hotel_city")->where("cid=$cid")->find();
        if($re){
           $res=\db("hotel_city")->where("cid=$cid")->update($data);
           if($res){
               $this->success("修改成功",url('city'));
           }else{
               $this->error("修改失败",url('city'));
           }
        }else{
            $this->error("参数错误");
        }
    }
    public function change_sort()
    {
        $cid=input('fieldid');
        $val=input('fieldvalue');
       // var_dump($val,$cid);exit;
        $re=db("hotel_city")->where("cid=$cid")->find();
        if($re){
            $res=db("hotel_city")->where("cid=$cid")->setField("c_sort",$val);
            if($res){
                echo '1';
            }else{
                echo '0';
            }
        }else{
            echo '0';
        }
    }
    public function delete_city()
    {
        $id=input('id');
        $re=\db("hotel_city")->where("cid=$id")->find();
        if($re){
            $del=db("hotel_city")->where("cid=$id")->delete();
            $res=db("hotel_city")->where("pid=$id")->select();
            if($res){
                $dels=db("hotel_city")->where("pid=$id")->delete();
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function type()
    {
        $list = Db('hotel_other')->where("otype",1)->order(['osort asc','oid desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function save_type(){
        if($this->request->isAjax()){
            $id=input("id");
            if($id){
                $data['oname']=input("name");
                $res=db("hotel_other")->where("oid",$id)->update($data);
                if($res){
                    $this->success("修改成功！",url('Hotel/type'));
                }else{
                    $this->error("修改失败！",url('Hotel/type'));
                }
            }else{
                $data['oname']=input("name");
                $data['otype']=input("type");
                $re=db("hotel_other")->insert($data);
                if($re){
                    $this->success("添加成功！",url('Hotel/type'));
                }else{
                    $this->error("添加失败！",url('Hotel/type'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Hotel/type'));
        }
    }
    public function sort_type(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('hotel_other')->where(array('oid' => $id ))->setField('osort' , $sort);
        }
        $this->success("操作成功");
    }
    public function delete_type(){
        $id=input('id');
        $re=db("hotel_other")->where("oid=$id")->find();
        if($re){
           $del=db("hotel_other")->where("oid=$id")->delete();
           if($del){
               echo '1';
           }else{
               echo '2';
           }
        }else{
            echo '0';
        }
       
    }
    public function modify_type(){
        $id=input('id');
        $re=db('hotel_other')->where("oid=$id")->find();
        echo json_encode($re);
    }
    public function area()
    {
        $list = Db('hotel_other')->where("otype",2)->order(['osort asc','oid desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function num()
    {
        $list = Db('hotel_other')->where("otype",3)->order(['osort asc','oid desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function money()
    {
        $list = Db('hotel_other')->where("otype",4)->order(['osort asc','oid desc'])->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function lister()
    {
        $key=input("key");
        $map=[];
        if($key){
            $map['name']=array("like","%$key%");
        }
        $list=db("hotel")->where("is_delete",0)->order(["sort asc","id desc"])->where($map)->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function add()
    {
        $city=db("hotel_city")->where("pid",0)->order(["c_sort asc","cid desc"])->select();
        $this->assign("city",$city);

        $type=db("hotel_other")->where("otype",1)->select();
        $this->assign("type",$type);

        $area=db("hotel_other")->where("otype",2)->select();
        $this->assign("area",$area);

        $num=db("hotel_other")->where("otype",3)->select();
        $this->assign("num",$num);

        $money=db("hotel_other")->where("otype",4)->select();
        $this->assign("money",$money);



        return $this->fetch();
    }
    public function getnext()
    {
        $cid=input("cid");
        $re=db("hotel_city")->where("pid",$cid)->order(["c_sort asc","cid desc"])->select();
       
        if($re){
           echo json_encode($re);
        }else{
            echo 0;
        }
    }
    public function save()
    {
        $data=input("post.");
        $file=request()->file("image");
        if(!empty($file)){
            $data['image']=uploads("image");
        }
        $re=db("hotel")->insert($data);
        if($re){
            $this->success("添加成功",url('lister'));
        }else{
            $this->error("添加失败");
        }
    }
    public function modifys()
    {
        $id=input("id");
        $re=db("hotel")->where("id",$id)->find();
        $this->assign("re",$re);
        
        $city=db("hotel_city")->where("pid",0)->order(["c_sort asc","cid desc"])->select();
        $this->assign("city",$city);

        $citys=db("hotel_city")->where("pid",$re['cid'])->order(["c_sort asc","cid desc"])->select();
        $this->assign("citys",$citys);

        $type=db("hotel_other")->where("otype",1)->select();
        $this->assign("type",$type);

        $area=db("hotel_other")->where("otype",2)->select();
        $this->assign("area",$area);

        $num=db("hotel_other")->where("otype",3)->select();
        $this->assign("num",$num);

        $money=db("hotel_other")->where("otype",4)->select();
        $this->assign("money",$money);

        
        return $this->fetch();
    }
    public function usave()
    {
        $id=input("id");
        $re=db("hotel")->where(['id'=>$id,'is_delete'=>0])->find();
        if($re){
            $data=input("post.");
            $file=request()->file("image");
            if(!empty($file)){
                $data['image']=uploads("image");
            }else{
                $data['image']=$re['image'];
            }
            $res=db("hotel")->where(['id'=>$id,'is_delete'=>0])->update($data);
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
        $re=db('hotel')->where("id=$id")->find();
        if($re){
            if($re['up'] == 0){
                $res=db('hotel')->where("id=$id")->setField("up",1);
            }
            if($re['up'] == 1){
                $res=db('hotel')->where("id=$id")->setField("up",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function changes(){
        $id=input('id');
        $re=db('hotel')->where("id=$id")->find();
        if($re){
            if($re['status'] == 0){
                $res=db('hotel')->where("id=$id")->setField("status",1);
            }
            if($re['status'] == 1){
                $res=db('hotel')->where("id=$id")->setField("status",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function change(){
        $id=input('id');
        $re=db('hotel')->where("id=$id")->find();
        if($re){
            if($re['statu'] == 0){
                $res=db('hotel')->where("id=$id")->setField("statu",1);
            }
            if($re['statu'] == 1){
                $res=db('hotel')->where("id=$id")->setField("statu",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function delete(){
        $id=input('id');
        $re=db("hotel")->where("id=$id")->find();
        if($re){
           $del=db("hotel")->where("id=$id")->setField("is_delete",-1);
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
            $re=db('hotel')->where("id=$v")->find();
            if($re){
                $del=db('hotel')->where("id=$v")->setField("is_delete",-1);
        }
        
       }
       $this->redirect('lister');
    }
    public function sort(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('hotel')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('lister');
    }
    public function img(){
        $id=input('id');
        $list=db('hotel_img')->alias('a')->field("a.*,b.name")->where("hid=$id")->join('hotel b','a.hid=b.id')->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        $this->assign("id",$id);
        
        return $this->fetch();
    }
    public function i_save(){
        $id=input('id');
        if($id){
            $data=input('post.');
            $re=db('hotel_img')->where("id=$id")->find();
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }else{
                $data['image']=$re['image'];
            }
            if(input('status')){
                $data['status']=1;
            }else{
                $data['status']=$re['status'];
            }
            $res=db("hotel_img")->where("id",$id)->update($data);
            if($res){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
        }else{
            $data=input('post.');
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }
            if(input('status')){
                $data['status']=1;
            }
    
            $re=db("hotel_img")->insert($data);
            if($re){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
        }
         
    }
    public function modify_i(){
        $id=input('id');
        $re=db('hotel_img')->where("id=$id")->find();
    
        echo json_encode($re);
    }
    public function change_i(){
        $id=input('id');
        $re=db('hotel_img')->where("id=$id")->find();
        if($re){
            if($re['status'] == 0){
                $res=db('hotel_img')->where("id=$id")->setField("status",1);
            }
            if($re['status'] == 1){
                $res=db('hotel_img')->where("id=$id")->setField("status",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function delete_i(){
        $id=input('id');
        $re=db('hotel_img')->where("id=$id")->find();
        if($re){
            $del=db('hotel_img')->where("id=$id")->delete();
            echo '1';
        }else{
            echo '0';
        }
    }
    public function room()
    {
        
        $key=input("key");
        $id=input("id");
        $map['hid']=array('eq',$id);
        if($key){
            $map['name']=array("like","%$key%");
        }
        $list=db("hotel_room")->alias("a")->field("a.*,b.name")->where("room_is_delete",0)->join("hotel b","a.hid=b.id")->order(["room_sort asc","id desc"])->where($map)->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        $this->assign("id",$id);

        return $this->fetch();
    }
    public function add_room()
    {
        $hid=input("hid");
        $this->assign("hid",$hid);
        return $this->fetch();
    }
    public function save_room()
    {
        $data=input("post.");
        $file=request()->file("room_image");
        if(!empty($file)){
            $data['room_image']=uploads("room_image");
        }
        $re=db("hotel_room")->insert($data);
        if($re){
            $this->success("添加成功");
        }else{
            $this->error("添加失败");
        }
    }
    public function modifys_room()
    {
        $id=input("id");
        $re=db("hotel_room")->where(["id"=>$id,"room_is_delete"=>0])->find();
        $this->assign("re",$re);
        return $this->fetch();
    }
    public function usave_room()
    {
        $id=input("id");
        $re=db("hotel_room")->where(["id"=>$id,"room_is_delete"=>0])->find();
        if($re){
            $data=input("post.");
            $file=request()->file("room_image");
            if(!empty($file)){
                $data['room_image']=uploads("room_image");
            }else{
                $data['room_image']=$re['room_image'];
            }
            $res=db("hotel_room")->where(["id"=>$id,"room_is_delete"=>0])->update($data);
            if($res){
                $this->success("修改成功");
            }else{
                $this->error("修改失败");
            }
        }else{
            $this->error("参数错误");
        }

        
    }
    public function sort_room(){
        $data=input('post.');
        foreach ($data as $id => $sort){
            db('hotel_room')->where(array('id' => $id ))->setField('room_sort' , $sort);
        }
        $this->success("排序成功");
    }
    public function delete_room(){
        $id=input('id');
        $re=db('hotel_room')->where("id=$id")->find();
        if($re){
            $del=db('hotel_room')->where("id=$id")->setField('room_is_delete' , -1);
            echo '1';
        }else{
            echo '0';
        }
    }
    public function change_r(){
        $id=input('id');
        $re=db('hotel_room')->where("id=$id")->find();
        if($re){
            if($re['room_up'] == 0){
                $res=db('hotel_room')->where("id=$id")->setField("room_up",1);
            }
            if($re['room_up'] == 1){
                $res=db('hotel_room')->where("id=$id")->setField("room_up",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function room_img(){
        $id=input('id');
        $list=db('room_img')->alias('a')->field("a.*,b.room_name")->where("rid=$id")->join('hotel_room b','a.rid=b.id')->paginate(10);
       // $list=db('room_img')->where("rid=$id")->paginate(10);
        $this->assign("list",$list);
     
       
        
        $page=$list->render();
        $this->assign("page",$page);
        
        $this->assign("id",$id);
        
        return $this->fetch();
    }
    public function room_i_save(){
        $id=input('id');
        if($id){
            $data=input('post.');
            $re=db('room_img')->where("id=$id")->find();
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }else{
                $data['image']=$re['image'];
            }
            if(input('status')){
                $data['status']=1;
            }else{
                $data['status']=$re['status'];
            }
            $res=db("room_img")->where("id",$id)->update($data);
            if($res){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
        }else{
            $data=input('post.');
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }
            if(input('status')){
                $data['status']=1;
            }
    
            $re=db("room_img")->insert($data);
            if($re){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
        }
         
    }
    public function room_modify_i(){
        $id=input('id');
        $re=db('room_img')->where("id=$id")->find();
    
        echo json_encode($re);
    }
    public function room_change_i(){
        $id=input('id');
        $re=db('room_img')->where("id=$id")->find();
        if($re){
            if($re['status'] == 0){
                $res=db('room_img')->where("id=$id")->setField("status",1);
            }
            if($re['status'] == 1){
                $res=db('room_img')->where("id=$id")->setField("status",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function room_delete_i(){
        $id=input('id');
        $re=db('room_img')->where("id=$id")->find();
        if($re){
            $del=db('room_img')->where("id=$id")->delete();
            echo '1';
        }else{
            echo '0';
        }
    }











}