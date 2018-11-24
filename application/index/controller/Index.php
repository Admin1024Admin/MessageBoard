<?php
namespace app\index\controller;
use think\captcha\Captcha;
use think\Controller;
class Index extends Controller
{
    //跳转到登录页面
    public function index()
    {
        return $this->fetch("index/index");
    }
    //登录页面的验证码
    public function getCode(){
        $captcha = new Captcha();
        //中午 验证码字体使用扩展包内`think-captcha/assets/zhttfs`字体文件
        //$captcha->useZh = true;
        return $captcha->entry();
    }
    //跳转到登录页面
    public function index2($name)
    {
        return $name;
    }
}
