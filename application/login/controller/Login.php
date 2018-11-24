<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/9/10
 * Time: 14:55
 */
    namespace app\login\controller;
    use app\liuyan\model\Message;
    use think\Controller;
    use think\Request;
    use think\captcha\Captcha;
    use think\View;
    use app\login\model\Account;
    use app\login\model\Comments;
    use app\liuyan\controller\LiuYan;
    class Login extends Controller{
        protected $request;
//        public function __construct(Request $request = null)
//        {
//            parent::__construct();
//            $this->request = Request::instance();
//        }
        public function login(Request $request){
            $view = View::instance();
            $captcha = new Captcha();
            $username = $request->post("username");
            $password = $request->post("password");
            $code = $request->post("code");
            if($captcha->check($code)){
                $where = function($query) use($username,$password){
                    $query->where([
                        "username"=>$username,
                        "password"=>md5($password)
                    ]);
                };

                $result = Account::get($where);
                if(isset($result["username"])){
                    session("username",$result["username"]);
                    session("netname",$result["netname"]);
                    $this->redirect("login/login/login_success");
                    //return $this->fetch("index");
                }else{
                    $view->assign([
                        "userError"=>"用户名不存在",
                        "passwordError"=>"密码错误"
                    ]);
                    return $view->fetch("index@index/index");
//                    $this->redirect("index/index/index");
                }
            }else{
                //$this->redirect("index/index/index");
                $view->assign("codeError","验证码错误");
                return $view->fetch("index@index/index");
            }
        }
        //登录成功跳转首页
        public function login_success(){
            if(!session('username')){
               return $this->fetch("index@index/index");
            }else{
                return $this->fetch("index");
            }
        }

        //跳转到注册页面
        public function gozhuce()
        {
            return $this->fetch("zhuce");
        }
        //注册
        public function zhuce(Request $request){
            $netname = $request->post("netname");
            $username = $request->post("username");
            $password = $request->post("password");
            $email = $request->post("email");
            $result = Account::create([
                "username"=>$username,
                "netname"=>$netname,
                "password"=>md5($password),
                "email"=>$email
            ]);
            if($result!=null){
                return $this->fetch("index@index/index");
            }else{
                return $this->fetch("zhuce");
            }
        }
        //个人资料信息
        public function my_message(){
            if(!session('username')){
                return $this->fetch("index@index/index");
            }else{
                $username = session("username");
                $result = Account::get(["username"=>$username]);
                if($result!=null){
                    $this->assign("user",$result);
                    return $this->fetch("mymessage");
                }
            }
        }

        //修改用户信息
        public function update_user(Request $request){
            $id = $request->post("id");
            $netname = $request->post("netname");
            $username = $request->post("username");
            $password = $request->post("password");
            $email = $request->post("email");
            $account = new Account();
            $account["id"] = $id;
            $account["netname"] = $netname;
            $account["username"] = $username;
            //判断是否更改了密码
            $user = Account::get($id);
            if($user['password']==$password){
                $account["password"] = $password;
            }else{
                $account["password"] = md5($password);
            }
            $account["email"] = $email;
            $result = Account::update([
                    "id"=>$account["id"],
                    "netname"=> $account["netname"],
                    "username"=> $account["username"],
                    "password"=> $account["password"],
                    "email"=> $account["email"]
                ]);
            $this->assign("updateSuccess",true);
            if($result!=null){
                $this->assign("user",$result);
                return $this->fetch("mymessage");
            }else{
                $this->assign("user",$account);
                return $this->fetch("mymessage");
            }
        }
        //留言
        public function liuyan(Request $request){

            $message = $request->post("message");
            $ly = new LiuYan();
            $affect = $ly->addMessage($message);
            $this->assign("affect",$affect);
            $this->redirect("login/login/info");
            //echo "<script>location='/MessageBoard/public/index.php/login/login/info'</script>";
        }

        //留言板首页
        public function info(){
            if(!session('username')){
                return $this->fetch("index@index/index");
                //echo "<script>location='/MessageBoard/public/index.php/index/login/info'</script>";
            }
            //获取分页数据
            $ly = LiuYan::getPage();
            //$page = $ly->render();
            $this->assign("msgs",$ly);
            return $this->fetch("login/message");
        }

        //我的留言
        public function my_liuyan(){
            if(!session('username')){
                return $this->fetch("index@index/index");
                //echo "<script>location='/MessageBoard/public/index.php/index/login/info'</script>";
            }else{
                //获取session中的用户名
                $username = session("username");
                //根据用户名查询用户信息
                $user = Account::get(["username"=>$username]);
                //获取用户网名
                $netname = $user["netname"];
                //根据用户网名查询该用户所有的留言
                $result = Message::getPage($netname);
                $this->assign("msgs",$result);
                return $this->fetch("liuyans");
            }
        }
        //删除留言
        public function del($id){
            if(!session('username')){
                return $this->fetch("index@index/index");
                //echo "<script>location='/MessageBoard/public/index.php/index/login/info'</script>";
            }
            if($id!=null){
                $result = Message::destroy(["id"=>$id]);
                $this->assign("delsuccess",true);
                //获取session中的用户名
                $username = session("username");
                //根据用户名查询用户信息
                $user = Account::get(["username"=>$username]);
                //获取用户网名
                $netname = $user["netname"];
                //根据用户网名查询该用户所有的留言
                $result = Message::getPage($netname);
                $this->assign("msgs",$result);
                return $this->fetch("liuyans");
            }
        }

        //查看留言详情
        public function message($id){
            $msg = Message::get($id);
            $result = Comments::getPage($id);
            $result2 = array();
            $i = 0;
            foreach ($result as $value){
                $user = Account::get($value['uid']);
                $r = array("netname"=>$user["netname"],"comment"=>$value["msg"],"create_time"=>$value["create_time"],"id"=>$value["id"]);
                $result2[$i] = $r;
                $i++;
            }
            $this->assign("msg",$msg);
            $this->assign("msgs",$result);
            $this->assign("comments",$result2);
           return $this->fetch("comments");
        }

        //评论
        public function pinglun(Request $request){
            if(!session('username')){
                return $this->fetch("index@index/index");
                //echo "<script>location='/MessageBoard/public/index.php/index/login/info'</script>";
            }else{
                $username = session("username");
                $user = Account::get(["username"=>$username]);
                $mid = $request->post("mid");
                echo $mid;
                $msg = $request->post("msg");
                Comments::create([
                    "uid"=>$user["id"],
                    "mid"=>$mid,
                    "msg"=>$msg
                ]);
                $this->redirect("login/login/message?id=".$mid);
            }
        }

        //搜索
        public function search(Request $request){
            $keyword = $request->get("keyword");
            $ly = Message::getWherePage($keyword);
            $this->assign("msgs",$ly);
            return $this->fetch("login/message");
        }
    }
?>