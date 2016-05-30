<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Модель логинов*/
/*
 Описание глобалов
^Test.VACUsers("admin","group")="administrator"
^Test.VACUsers("admin","password")="!1qazxsw2"
^Test.VACUsers("admin","fullname")="Администратор"


 * */

class Auth_model extends CI_Model
{

    public $auth;

    public $settings;

    public function __construct()
    {
        $this->load->library('functions');

        $this->settings=$this->functions->GetPageInfo(1);

        $this->dbMySQL = $this->load->database('default', TRUE);
        $this->load->helper('url');

        if($this->session->has_userdata('auth'))
        {
            $auth = $this->session->has_userdata('auth');
        }
        else
        {
            $auth=false;
        }
    }


//генератор паролей
    public function PassGen($max=10)
    {
        // Символы, которые будут использоваться в пароле.
        $chars="qazxswedcvfrtgbnhyujmkip23456789QAZXSWEDCVFRTGBNHYUJMKLP";
        // Количество символов в пароле.

        // Определяем количество символов в $chars
        $size=StrLen($chars)-1;

        // Определяем пустую переменную, в которую и будем записывать символы.
        $password=null;

        // Создаём пароль.
        while($max--)
            $password.=$chars[rand(0,$size)];

        // Выводим созданный пароль.
        return $password;
    }

    /*Проверка на существование юзера*/
    function IsUserExist($login)
    {
        $login=$this->security->xss_clean($login);
        $sql="select count(*) cc from ".$this->UsersTable." where login='".$login."'";

        $query = $this->dbMySQL->query($sql);
        $row=$query->result_array();
        $row = $row[0];
        if((int)$row['cc']>0) return true; else return false;
    }

    /*Вставляет юзера с рандомным паролем*/
    public function AddUser($login)
    {
        if(!$this->IsUserExist($login))
        {
            $login=$this->security->xss_clean($login);
            $data = array('login' => $login, 'password' => $this->PassGen());
            return $this->dbMySQL->query($this->dbMySQL->insert_string($this->UsersTable, $data));
        }
        else return false;
    }



    public function GetAllUsers()
    {

    }

    public function UserInfo()
    {
        return $this->auth;
    }

    public function IsLogin()
    {
        if($this->session->has_userdata('auth'))
        {
            return true;
        }
        else return false;
    }



    public function IsAdmin($username,$password)
    {
        if( ($this->settings->TV['admin_login']==$username) and ($this->settings->TV['admin_password']==$password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /*Проверка на существование юзера*/
    public function  GetUserByNameAndPass($username,$password)
    {

        $sql="select * from ipre_users where (login='$username') and (password='$password')";

        $query = $this->dbMySQL->query($sql);
        $row=$query->result_array();

        $row = $row[0];
        $res = new stdClass();
        $res->login = $row['login'];
        $res->password = $row['password'];

        return $res;
    }

    /*выдает имя зареганого пользователя*/
    function GetloginUser()
    {
        if($this->IsLogin())
        {
            return $this->session->username;
        }
        else return false;
    }

    function GetLogoutUrl()
    {
        return base_url('auth/logout');
    }




}