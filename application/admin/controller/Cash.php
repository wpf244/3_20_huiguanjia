<?php
namespace app\admin\controller;

use Think\Db;

class Cash extends BaseAdmin
{
    public function index()
    {
        $list=db("cash")->alias("a")->field("a.*,b.nickname,b.phone")->where("a.status",0)->join("user b","a.uid=b.uid")->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function change()
    {
        $id=input("id");
        $re=db("cash")->where("id",$id)->find();
        if($re){
            
             $data['status']=1;
             $data['times']=time();
             // 启动事务
                Db::startTrans();
                try{
                    db("user")->where("uid",$re['uid'])->setInc("already_money",$re['money']);
                    db("cash")->where("id",$id)->update($data);
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    echo '0';
                }
               echo '1';
        }else{
           echo '0';
        }
    }

    public function bo()
    {
        $id=input("id");
        $re=db("cash")->where("id",$id)->find();
        if($re){
            
             $data['status']=2;
             $data['times']=time();

             $datas['uid']=$re['uid'];
             $datas['money']=$re['moneys'];
             $datas['type']=1;
             $datas['oper']="佣金提现驳回增加";
             $datas['time']=time();
             // 启动事务
                Db::startTrans();
                try{
                    db("user")->where("uid",$re['uid'])->setInc("money",$re['moneys']);
                    db("cash")->where("id",$id)->update($data);
                    db("money_log")->insert($datas);
                    // 提交事务
                    Db::commit();    
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    echo '0';
                }
               echo '1';
        }else{
           echo '0';
        }
    }
    public function lister()
    {
        $list=db("cash")->alias("a")->field("a.*,b.nickname,b.phone")->where("a.status",1)->join("user b","a.uid=b.uid")->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
    public function reject()
    {
        $list=db("cash")->alias("a")->field("a.*,b.nickname,b.phone")->where("a.status",2)->join("user b","a.uid=b.uid")->paginate(20);
        $this->assign("list",$list);

        $page=$list->render();
        $this->assign("page",$page);

        return $this->fetch();
    }
}