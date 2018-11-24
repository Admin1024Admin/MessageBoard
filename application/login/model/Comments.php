<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/10
 * Time: 20:26
 */
namespace app\login\model;
use think\Model;
class Comments extends Model{
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
    public static function getPage($mid){
        $msg = new Comments();
        $msgs = $msg->field(["id","msg","mid","uid","create_time"])
            ->where(["mid"=>$mid])
            ->order("create_time desc")
            ->paginate(5,true);
        return $msgs;
    }
}
?>