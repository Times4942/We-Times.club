<?php
namespace app\Index\controller;
use think\Controller;
use PHPMailer\SendEmail;
class Base extends Controller
{
    public $account = '';
    public function _initialize() {
        // 获取登录用户数据
        $this->assign('user', $this->getLoginUser());
        // 获取城市数据
        $this->assign('city_name', $this->getCity());
    }

    public function getLoginUser() {
        if(!$this->account) {
            $this->account = session('we_user', '', 'we');
        }
        return $this->account;
    }

    public function getCity(){
        $default_info = model('City')->SearchDefault();
        if ( session('we_city', '', 'city') && !input('get.city_id')){
            $show_city = session('we_city', '', 'city')['city_name'];
        }
        else{
            $city_id = input('get.city_id' , $default_info['id'] , 'trim');
            $city_info = model('City')->getInfoById($city_id);
            session('we_city', $city_info, 'city');
            $show_city = $city_info['city_name'];
        }
        return $show_city;






//        if (!$city_id){
//            $default_info = model('City')->SearchDefault();
//            $default_name = $default_info['city_name'];
//            session('we_city', $default_info, 'city');
//            return $default_name;
//        }
//        else{
//            $city_info = model('City')->getInfoById($city_id);
//            $city_name = $city_info['city_name'];
//            session('we_city', $city_info, 'city');
//            return $city_name;
//        }
        

    }




}