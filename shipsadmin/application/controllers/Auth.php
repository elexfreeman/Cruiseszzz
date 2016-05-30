<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {


    public $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('auth_model');
        $this->load->helper('form');
        $this->load->helper('url');
    }




    public function index()
    {
        if($this->auth_model->IsLogin())
        {
            header('Location: '.base_url());
            exit;
        }
        else
        {
            $this->data['error']='1';
            $this->load->view('head');
            $this->load->view('auth/loginform',$this->data);
            $this->load->view('footer');
       }
    }


    /*Событие входа пользователя*/
    public function login()
    {


       if( $this->auth_model->IsAdmin($_POST['username'],$_POST['password']))
        {
            $auth = new stdClass();
            $auth->login=$_POST['username'];
            $auth->login=$_POST['password'];
            $this->session->set_userdata('auth', $auth);
            header('Location: '.base_url());
            exit;
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }


    }

    public function logout()
    {
        unset($_SESSION['auth']);
        header('Location: '.base_url('auth'));
        exit;
    }
}
