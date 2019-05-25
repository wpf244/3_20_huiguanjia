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
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>1])->join("user b","a.uid=b.uid","left")->order("id desc")->paginate(20);

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
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>2])->join("user b","a.uid=b.uid","left")->order("id desc")->paginate(20);

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
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>3])->join("user b","a.uid=b.uid","left")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
    public function full()
    {
        
        $re=db("red")->where("id",4)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function save_full()
    {
        $data=input("post.");
        $re=db("red")->where("id",4)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function full_lister()
    {

        $list=db("full")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
    public function savef()
    {
        $id=input("id");
        if($id){
            $re=db("full")->where(['id'=>$id])->find();
            if($re){
                $data=input("post.");
                
                $res=db("full")->where(['id'=>$id])->update($data);
                if($res){
                    $this->success("修改成功",url('full_lister'));
                }else{
                    $this->error("修改失败",url('full_lister'));
                }
            }else{
                $this->error("参数错误",url('full_lister'));
            }
        }else{
            $data=input("post.");
            $rea=db("full")->insert($data);
            if($rea){
                $this->success("添加成功",url('full_lister'));
            }else{
                $this->error("添加失败",url('full_lister'));
            }
        }
        
    }
    public function modify_full()
    {
        $id=input("id");
        $re=db("full")->where("id",$id)->find();
        echo json_encode($re);
    }
    public function delete_f(){
        $id=input('id');
        $re=db("full")->where("id=$id")->find();
        if($re){
           $del=db("full")->where("id=$id")->delete();
           if($del){
               echo '1';
           }else{
               echo '2';
           }
        }else{
            echo '0';
        }
       
    }
    public function full_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        
        $this->assign("start",$start);
        $this->assign("end",$end);
 
        $this->assign("phone",$phone);

        $this->assign("code",$code);

        $this->assign("hid",$hid);
        
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"full"=>1])->where($map)->order(["status asc","id desc"])->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);

        $hotel=db("hotel")->where(["up"=>1,"is_delete"=>0])->select();
        $this->assign("hotel",$hotel);
        
        return $this->fetch();
    }
    public function outf(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"full"=>1])->where($map)->order("id desc")->paginate(20,false,['query'=>request()->param()]);
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L");
        $arrHeader =  array("订单号","订单金额","满减前金额","优惠金额","酒店名称","房间名称","房间类型","会议时间","会议人数","会议需求","联系电话","下单时间");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['price']);
            $objActSheet->setCellValue('C'.$k, $v['old_price']);  
            $objActSheet->setCellValue('D'.$k, $v['full_price']);      
            // 表格内容
            $objActSheet->setCellValue('E'.$k, $v['hotel']);
            $objActSheet->setCellValue('F'.$k, $v['room']);
            $objActSheet->setCellValue('G'.$k, $v['room_type']);
            $objActSheet->setCellValue('H'.$k, $v['dates']);
            $objActSheet->setCellValue('I'.$k, $v['number']);
            $objActSheet->setCellValue('J'.$k, $v['ment']);
            $objActSheet->setCellValue('K'.$k, $v['phone']);
            $objActSheet->setCellValue('L'.$k, \date("Y-m-d H:i:s",$v['time']));
         

    
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
        $objActSheet->getColumnDimension('H')->setWidth(30);
        $objActSheet->getColumnDimension('I')->setWidth(30);
        $objActSheet->getColumnDimension('J')->setWidth(30);
        $objActSheet->getColumnDimension('K')->setWidth(30);
        $objActSheet->getColumnDimension('L')->setWidth(30);

        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."满减订单".".xls";
    
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
        $re=db("order")->where("id=$id")->find();
        if($re){
            $data['status']=2;
            
            db("order")->where("id=$id")->update($data);
          
            
            $this->redirect("full_dd");
        }else{
            $this->redirect("full_dd");
        }
    }
    public function dd()
    {
        
        $re=db("red")->where("id",5)->find();
        $this->assign("re",$re);

        return $this->fetch();
    }
    public function save_d()
    {
        $data=input("post.");
        $re=db("red")->where("id",5)->update($data);
        if($re){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }
    public function dd_log()
    {
        $list=db("red_log")->alias("a")->field("a.*,b.nickname")->where(["type"=>4])->join("user b","a.uid=b.uid","left")->order("id desc")->paginate(20);

        $this->assign("list",$list);

        $page=$list->render();

        $this->assign("page",$page);

        return $this->fetch();
    }
    public function spike()
    {
        
        $re=db("red")->where("id",6)->find();
        $this->assign("re",$re);

        return $this->fetch();

        return $this->fetch();
    }
    public function spike_log()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        
        $this->assign("start",$start);
        $this->assign("end",$end);
 
        $this->assign("phone",$phone);

        $this->assign("code",$code);

        $this->assign("hid",$hid);
        
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"skill"=>1])->where($map)->order(["status asc","id desc"])->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);

        $hotel=db("hotel")->where(["up"=>1,"is_delete"=>0])->select();
        $this->assign("hotel",$hotel);
        
        return $this->fetch();
    }
    public function outs(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"skill"=>1])->where($map)->order("id desc")->paginate(20,false,['query'=>request()->param()]);
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L");
        $arrHeader =  array("订单号","订单金额","原价格","酒店名称","房间名称","房间类型","会议时间","会议人数","会议需求","联系电话","下单时间");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['price']);
            $objActSheet->setCellValue('C'.$k, $v['old_price']);  
            
            // 表格内容
            $objActSheet->setCellValue('D'.$k, $v['hotel']);
            $objActSheet->setCellValue('E'.$k, $v['room']);
            $objActSheet->setCellValue('F'.$k, $v['room_type']);
            $objActSheet->setCellValue('G'.$k, $v['dates']);
            $objActSheet->setCellValue('H'.$k, $v['number']);
            $objActSheet->setCellValue('I'.$k, $v['ment']);
            $objActSheet->setCellValue('J'.$k, $v['phone']);
            $objActSheet->setCellValue('K'.$k, \date("Y-m-d H:i:s",$v['time']));
         

    
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
        $objActSheet->getColumnDimension('H')->setWidth(30);
        $objActSheet->getColumnDimension('I')->setWidth(30);
        $objActSheet->getColumnDimension('J')->setWidth(30);
        $objActSheet->getColumnDimension('K')->setWidth(30);

        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."秒杀订单".".xls";
    
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
    public function rebate_log()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        
        $this->assign("start",$start);
        $this->assign("end",$end);
 
        $this->assign("phone",$phone);

        $this->assign("code",$code);

        $this->assign("hid",$hid);
        
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"rebate"=>1])->where($map)->order(["status asc","id desc"])->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);

        $hotel=db("hotel")->where(["up"=>1,"is_delete"=>0])->select();
        $this->assign("hotel",$hotel);
        
        return $this->fetch();
    }
    public function outr(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $phone=\input('phone');
        $hid=input("hid");
        $map=[];
        if($start){
            $arrdateone = strtotime($start);
            $arrdatetwo = strtotime($end . ' 23:55:55');
            $map['time'] = array(
                array(
                    'egt',
                    $arrdateone
                ),
                array(
                    'elt',
                    $arrdatetwo
                ),
                'AND'
            );
        }
        if($code){
            $map['code']=array('like','%'.$code.'%');
        }
    
        if($phone){
            $map['phone']=array('like','%'.$phone.'%');
            
        }
        if($hid != 0){
            $map['hid']=['eq',$hid];
        }
         
        $list=db("order")->where(['status'=>['gt',0],"is_delete"=>0,"rebate"=>1])->where($map)->order("id desc")->paginate(20,false,['query'=>request()->param()]);
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H,I,J,K,L");
        $arrHeader =  array("订单号","订单金额","原价格","酒店名称","房间名称","房间类型","会议时间","会议人数","会议需求","联系电话","下单时间");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['price']);
            $objActSheet->setCellValue('C'.$k, $v['old_price']);  
            
            // 表格内容
            $objActSheet->setCellValue('D'.$k, $v['hotel']);
            $objActSheet->setCellValue('E'.$k, $v['room']);
            $objActSheet->setCellValue('F'.$k, $v['room_type']);
            $objActSheet->setCellValue('G'.$k, $v['dates']);
            $objActSheet->setCellValue('H'.$k, $v['number']);
            $objActSheet->setCellValue('I'.$k, $v['ment']);
            $objActSheet->setCellValue('J'.$k, $v['phone']);
            $objActSheet->setCellValue('K'.$k, \date("Y-m-d H:i:s",$v['time']));
         

    
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
        $objActSheet->getColumnDimension('H')->setWidth(30);
        $objActSheet->getColumnDimension('I')->setWidth(30);
        $objActSheet->getColumnDimension('J')->setWidth(30);
        $objActSheet->getColumnDimension('K')->setWidth(30);

        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."限时折扣订单".".xls";
    
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
    public function save_s()
    {
        $start_time=input("start_time");

        $end_time=input("end_time");

        if($end_time > $start_time){
            
            $data['open']=input("open");
            $data['start_time']=strtotime($start_time);
            $data['end_time']=strtotime($end_time);

            $re=db("red")->where("id",6)->update($data);
            if($re){
                $this->success("修改成功");
            }else{
                $this->error("修改失败");
            }

        }else{
            $this->error("开始时间不得大于结束时间");
        }
    }
    public function rebate()
    {
        
        $re=db("red")->where("id",7)->find();
        $this->assign("re",$re);

        return $this->fetch();

        return $this->fetch();
    }
    public function save_r()
    {
        $start_time=input("start_time");

        $end_time=input("end_time");

        if($end_time > $start_time){
            
            $data['open']=input("open");
            $data['start_time']=strtotime($start_time);
            $data['end_time']=strtotime($end_time);

            $re=db("red")->where("id",7)->update($data);
            if($re){
                $this->success("修改成功");
            }else{
                $this->error("修改失败");
            }

        }else{
            $this->error("开始时间不得大于结束时间");
        }
    }
    public function change_s()
    {
        $id=\input('id');
        $re=db("order")->where("id=$id")->find();
        if($re){
            $data['status']=2;
            
            db("order")->where("id=$id")->update($data);
          
            
            $this->redirect("spike_log");
        }else{
            $this->redirect("spike_log");
        }
    }
    public function change_r()
    {
        $id=\input('id');
        $re=db("order")->where("id=$id")->find();
        if($re){
            $data['status']=2;
            
            db("order")->where("id=$id")->update($data);
          
            
            $this->redirect("rebate_log");
        }else{
            $this->redirect("rebate_log");
        }
    }
}