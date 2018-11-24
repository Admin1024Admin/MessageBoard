<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/12
 * Time: 12:14
 */
    namespace app\liuyan\controller;
    use app\liuyan\model\Message;
    use think\Controller;
    use think\Request;
    use think\View;
    class LiuYan extends Controller{

        //分页查询
        public static function getPage(){
            $msg = new Message();
            $msgs = $msg->field(["id","wangming","message","create_time"])
                        ->order("create_time desc")
                        ->paginate(5,true);
            return $msgs;
        }



        //插入留言
        public function addMessage($msg){
            $netname = session("netname");
            $affect = Message::create([
                "wangming"=>$netname,
                "message"=>$msg
            ]);
            return $affect;
        }
    }
?>