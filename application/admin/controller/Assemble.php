<?php
namespace app\admin\controller;

class Assemble extends BaseAdmin
{
    public function goods()
    {
        $title=\input("title");
        $map=[];
        if($title){
            $map['name']=["like","%".$title."%"];
        }else{
            
            $title='';
        }
        

       
        $this->assign("title",$title);
        $list=db('assemble_goods')->where($map)->order(['sort'=> 'asc','id'=>'desc'])->paginate(20,false,['query'=>request()->param()]);
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

        $image=request()->file("image");

        if($image){
            $data['image']=uploads("image");
        }
        $re=db("assemble_goods")->insert($data);

        if($re){
            $this->success("保存成功",url('goods'));
        }else{
            $this->error("保存失败");
        }

    }
    public function changeu(){
        $id=input('id');
        $re=db('assemble_goods')->where("id",$id)->find();
        if($re){
            if($re['up'] == 0){
                $res=db('assemble_goods')->where("id",$id)->setField("up",1);
            }
            if($re['up'] == 1){
                $res=db('assemble_goods')->where("id",$id)->setField("up",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function change(){
        $id=input('id');
        $re=db('assemble_goods')->where("id",$id)->find();
        if($re){
            if($re['status'] == 0){
                $res=db('assemble_goods')->where("id",$id)->setField("status",1);
            }
            if($re['status'] == 1){
                $res=db('assemble_goods')->where("id",$id)->setField("status",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function sort(){
        $data=input('post.');
    
        foreach ($data as $id => $sort){
            db('assemble_goods')->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('goods');
    }
    public function modifys()
    {
        $id=input('id');
        $re=db('assemble_goods')->where("id",$id)->find();
        $this->assign("re",$re);

      
        
        return $this->fetch();
    }
    public function usave(){
        $id=input('id');
        $data=input('post.');
        $re=db("assemble_goods")->where("id",$id)->find();
        if($re){
            if(request()->file("image")){
                $data['image']=uploads('image');
              
            }else{
                $data['image']=$re['image'];
            }
        
           
            $res=db("assemble_goods")->where("id",$id)->update($data);
            if($res){
                $this->success("修改成功",url('goods'));
            }else{
                $this->error("修改失败");
            }
        }else{
            $this->error("参数错误");
        }
        
    }
    public function delete(){
        $id=input('id');
        $re=db("assemble_goods")->where("id",$id)->find();

        if($re){
            
            db("assemble_goods")->where("id",$id)->delete();

            $this->redirect('goods');

        }else{
            $this->redirect('goods');
        }
        
    }
    public function lister()
    {
        $title=\input("title");

        $status=input("status");
        $map=[];
     //   var_dump($status);
        if($title || $status ){
            if($title){
                $map['name']=["like","%".$title."%"];
            }else{
                $title='';
            }
            if($status){
                $map['a.status']=['eq',$status];
            }else{
                $status=0;
            }
            
        }else{
            
            $title='';
            $status=0;
            
        }
    
        $this->assign("title",$title);
        $this->assign("status",$status);
        $list=db('assemble')->alias("a")->field("a.*,b.phone")->where($map)->join("user b","a.uid=b.uid",'left')->order(['a.id'=>'desc'])->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return $this->fetch();
    }
    public function dd()
    {
        
        $start=input('start');
        $end=input('end');
        $code=\input('code');
      
        $status=input("status");
        $map=[];
        if($start || $code  || $status || $status === '0'){
            if($start){
                
                $map['a.time']=['between time',[$start.'00:00:01',$end.'23:59:59']];
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
         
            if($status || $status === '0'){
                $map['a.status']=["eq",$status];
            }else{
                $status=10;
            }
        }else{
            
            $start="";
            $end="";
        
          
            $code="";
            $status=10;
         
        }
        $uid=session("uid");

        
        $this->assign("start",$start);
        $this->assign("end",$end);
      
        $this->assign("code",$code);
        $this->assign("status",$status);
        
        $list=db("assemble_dd")->alias('a')->field("a.*,b.phone,b.nickname")->where($map)->join("user b","a.uid = b.uid","LEFT")->order("a.id desc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        
        return $this->fetch();
    }
    public function out(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
      

        $status=input("status");
        $map=[];
        if($start || $code || $status || $status === '0'){
            if($start){
                
                $map['a.time']=['between time',[$start.'00:00:01',$end.'23:59:59']];
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
         
            if($status || $status === '0'){
                if($status == 10){
                    $map=[];
                }else{
                    $map['a.status']=["eq",$status];
                }
                
            }else{
                $status=10;
            }
        }
       
        $list=db("assemble_dd")->alias('a')->field("a.*,b.nickname,b.phone")->where($map)->join("user b","a.uid = b.uid","LEFT")->order("id desc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G");
        $arrHeader =  array("商品名称","商品价格","订单号码","实际付款","收货人姓名","收货人电话","订单状态");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            
            if($v['status'] == 0){
                $v['type']="未付款";
            }
            if($v['status'] == 1){
                $v['type']="拼团中";
            }
            if($v['status'] == 2){
                $v['type']="待发货";
            }
            if($v['status'] == 3){
                $v['type']="待收货";
            }
            if($v['status'] == 4){
                $v['type']="已完成";
            }
            if($v['status'] == 5){
                $v['type']="拼团失败";
            }
            
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['name']);
            $objActSheet->setCellValue('B'.$k, $v['goods_price']);    
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['code']);
            $objActSheet->setCellValue('D'.$k, $v['price']);
            $objActSheet->setCellValue('E'.$k, $v['nickname']);
            $objActSheet->setCellValue('F'.$k, $v['phone']);
            $objActSheet->setCellValue('G'.$k, $v['type']);
           
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
        $objActSheet->getColumnDimension('G')->setWidth(30);
  

        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        if($status == 0){
            $name="未付款订单";
        }elseif($status == 1){
            $name="拼团中订单";
        }elseif($status == 2){
            $name="待发货订单";
        }elseif($status == 3){
            $name="待收货订单";
        }elseif($status == 4){
            $name="已完成订单";
        }elseif($status == 5){
            $name="拼团失败订单";
        }else{
            $name="全部订单";
        }

        $outfile = "$times"."$name".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
        
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
           
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
            
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function changes()
    {
        $id=\input('id');
        $re=db("assemble_dd")->where("id",$id)->find();
        if($re){
            $id=$re['id'];
            if($re['status'] == 0){
                $data['fu_time']=time();
                $data['status']=1;
                $changestatus=db("assemble_dd")->where("id=$id")->update($data);
                if($changestatus){
                    $bid=$re['a_id'];

                    $assemble=db("assemble")->where("id",$bid)->find();

                    if($assemble){
                        if($assemble['status'] == 0){
                            db("assemble")->where("id",$bid)->setField("status",1);
                        }
                        $num=$assemble['number']-$assemble['num'];
                        
                        if($re['lid'] != 0){
                            db("assemble_log")->where("id",$re['lid'])->setField("status",1);
                        }
                        if($num <= 1){
                            db("assemble")->where("id",$bid)->setInc("num",1);
                            db("assemble")->where("id",$bid)->setField("status",2);
                            $res=db("assemble_dd")->where(["a_id"=>$bid,"status"=>1])->select();
                            if($res){
                                db("assemble_dd")->where(["a_id"=>$bid,"status"=>1])->setField("status",2);
                            }
                            // if($re['lid'] != 0){
                            //     db("assemble")->where("id",$re['lid'])->setField("status",2);
                            // }
                        }else{
                            db("assemble")->where("id",$bid)->setInc("num",1);

                        }
                    }

                    echo '0';
                }else{
                    echo '1';
                }
            }else{
                echo '1';
            }
        }else{
            echo '1';
        }
    }
    public function delete_dd()
    {
        $id=\input('id');
        $re=db("assemble_dd")->where("id=$id")->find();
        if($re){
            $del=db("assemble_dd")->where("id=$id")->delete();
            
            echo '0';
            
        }else{
            echo '1';
        }
    }
    public function fa_dd()
    {
        $id=\input('id');
        $re=db("assemble_dd")->where("id",$id)->find();
        if($re){
           if($re['status'] == 2){
            db("assemble_dd")->where("id",$id)->setField("status",3);
            echo '0';
           } else{
               echo '2';
           }
           
        }else{
            echo '1';
        }
    }
    public function que_dd()
    {
        $id=\input('id');
        $re=db("assemble_dd")->where("id",$id)->find();
        if($re){
           if($re['status'] == 3){
            db("assemble_dd")->where("id",$id)->setField("status",4);
            echo '0';
           } else{
               echo '2';
           }
           
        }else{
            echo '1';
        }
    }
}