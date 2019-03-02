<?php
namespace app\Admin\controller;

use PHPMailer\SendEmail;

class Index extends Base
{
    public function index()
    {
        if (!session('we_admin', '', 'we')){
            $this->error('请登录','User/Login');
        }
        return $this->fetch();
    }
    public function welcome(){
        //SendEmail::SendEmail('494243846@qq.com','We-Times.club密码找回','请访问:http://localhost/We-Times/public/Admin/User/login.html');
        //return "发送成功";
        return $this->fetch();
    }

    public function Invitation(){
        $admin_name = session('we_admin', '', 'we')['admin_name'];
        $data = model('InvitationCode')->getCodeByAdminName($admin_name);
        $this->assign('list', $data);
        return $this->fetch();
    }

    public function Carousel(){
        $carousels = model('Carousel')->SelectInfo('');
        $this->assign('list', $carousels);
        return $this->fetch();
    }

    public function Carousel_upload(){
        if (request()->file()){
            $file = request()->file('file');
            $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'upload' . DS . 'images' . DS . 'carousel');
            $save_name = $info->getFilename();
            $upload_time = time();
            $upload_admin = session('we_admin', '', 'we')['admin_name'];
            $data = [
                'save_name' => $save_name,
                'upload_time' => $upload_time,
                'upload_admin' => $upload_admin,
                'status' => 0
            ];
            model('Carousel')->add($data);

        }
        else{

            return $this->fetch();
        }

    }

    public function City()
    {
        if (request()->isPost()){
            $data =input('post.');
            if ($data['part'] == 0){
                $all_data = [
                    'city_name' => $data['city_name'],
                    'e_name' => $data['e_name'],
                    'parent_id' => 0,
                    'status' => 0,
                    'create_time' => time(),
                    'create_admin' => session('we_admin', '', 'we')['admin_name']
                ];
                model('City')->add($all_data);
                $this->success('添加成功');
            }
            else if($data['part'] == 1){
                $all_data = [
                    'city_name' => $data['city_name1'],
                    'e_name' => $data['e_name1'],
                    'parent_id' => $data['parent_id'],
                    'status' => 0,
                    'create_time' => time(),
                    'create_admin' => session('we_admin', '', 'we')['admin_name']
                ];
                model('City')->add($all_data);
                $this->success('添加成功');
            }

        }
        else{
            $First = model('City')->SearchFirst();
            $this->assign('list', $First);
            return $this->fetch();
        }

    }

    public function User(){
       return $this->fetch();
    }

    public function Logout(){
        session(null, 'we');
        $this->redirect(url('User/Login'));
    }

}
