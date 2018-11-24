<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/10
 * Time: 11:26
 */
    //创建路由
    //首页
    think\Route::rule("index","index/index/index");
    //验证码
    think\Route::rule("code","index/index/getCode");
    //登录提交请求
    think\Route::post("login","login/login/login");
    //留言
    think\Route::post("liuyan","login/login/liuyan");
    //注册
    think\Route::post("zhuce","login/login/zhuce");
    //跳转注册
    think\Route::get("gozhuce","login/login/gozhuce");
    //更新个人信息
    think\Route::post("updateuser","login/login/update_user");
    //评论
    think\Route::post("pinglun","login/login/pinglun");
    //搜索
    think\Route::get("search","login/login/search");

    //分页
// think\Route::get("page/:page","liuyan/liu_yan/getPage2")
?>