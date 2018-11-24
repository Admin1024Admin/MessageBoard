<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/12
 * Time: 12:11
 */
    namespace app\liuyan\model;
    use think\Model;
    class Message extends Model{
        // 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
        protected $autoWriteTimestamp = true;
        // 创建时间字段
        protected $createTime = 'create_time';
        // 创建更新字段
        protected $updateTime = 'update_time';
        //配置写入时间类型
        protected $type = [
            "create_time"=>"datetime",
            "update_time"=>"datetime"
        ];

        //分页查询
        public static function getPage($wangming){
            $msg = new Message();
            $msgs = $msg->field(["id","wangming","message","create_time"])
                ->where(["wangming"=>$wangming])
                ->order("create_time desc")
                ->paginate(5,true);
            return $msgs;
        }

        //分页条件查询
        public static function getWherePage($keyword){
            $msg = new Message();
            $msgs = $msg->field(["id","wangming","message","create_time"])
                ->where(["message"=>["like","%".$keyword."%"]])
                ->order("create_time desc")
                ->paginate(5,true);
            return $msgs;
        }
    }
?>