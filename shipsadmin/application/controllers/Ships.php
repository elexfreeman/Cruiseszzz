<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*контроллер главной страницы*/
class Ships extends CI_Controller {

    /*тут хранятся настройки, закгружаются в конструкторе*/
    public $settings;
    public $data;

    public function __construct()
    {
        parent::__construct();

        parent::__construct();
        /*Загружаем  библиотеку сессий*/
        $this->load->library('session');

        /*Закгружаем хелперы*/
        $this->load->helper('form');
        $this->load->helper('url');
        /*Загружаем модели*/
        $this->load->model('auth_model');
        $this->load->model('ships_model');
    }

	public function index()
	{
        /*переменные для языков описанны тут: \application\language\*/

        if( $this->auth_model->IsLogin())
        {
            header('Location: '.base_url('ship'));
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }
	}

    /*выводит корабли*/
    public function ship($ship_id=0)
    {
        $this->data['logout_link']=site_url('auth/logout');
        $this->data['navbar_link']='ships';

        if( $this->auth_model->IsLogin())
        {
            if($ship_id==0)
            {
                $this->data['auth']=$this->session->userdata('auth');
                $this->data['ships']=$this->ships_model->GetShipsList();
                $this->load->view('head',$this->data);
                /*шаблон страницы*/
                $this->load->view('navbar',$this->data);
                $this->load->view('ships',$this->data);

                $this->load->view('footer',$this->data);
            }
            else
            {
                $this->data['auth']=$this->session->userdata('auth');
                $this->data['ship']=$this->ships_model->GetShipInfo($ship_id);
                $this->load->view('head',$this->data);
                /*шаблон страницы*/
                $this->load->view('navbar',$this->data);
                $this->load->view('ship',$this->data);

                $this->load->view('footer',$this->data);
            }
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }
    }

    public function cautatypes($ship_id)
    {
        $this->data['logout_link']=site_url('auth/logout');
        $this->data['navbar_link']='ships';

        if( $this->auth_model->IsLogin())
        {
            if($ship_id==0)
            {
                header('Location: '.base_url('ships'));
                exit;
            }
            else
            {
                $this->data['auth']=$this->session->userdata('auth');
                $this->data['ship']=$this->ships_model->GetShipInfo($ship_id);
                $this->load->view('head',$this->data);
                /*шаблон страницы*/
                $this->load->view('navbar',$this->data);
                $this->load->view('cautatypes',$this->data);

                $this->load->view('footer',$this->data);
            }
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }
    }

    public function cautatypesedit($ship_id,$cautatypeid=0)
    {
        $this->data['logout_link']=site_url('auth/logout');
        $this->data['navbar_link']='ships';

        if( $this->auth_model->IsLogin())
        {
            if($cautatypeid==0)
            {
                if(isset($_POST['action']))
                {


                }
                else
                {
                    /*Добавляем тип каюты*/
                    /*Обновляем*/
                    $this->data['auth']=$this->session->userdata('auth');
                    $this->data['ship']=$this->ships_model->GetShipInfo($ship_id);
                    $this->data['cautatypeid']=$cautatypeid;

                    $this->load->view('head',$this->data);
                    /*шаблон страницы*/
                    $this->load->view('navbar',$this->data);
                    $this->load->view('cautatypesedit',$this->data);

                    $this->load->view('footer',$this->data);
                }

            }
            else
            {
                if(isset($_POST['action']))
                {
                    print_r($_POST);

                }
                else {
                    /*Обновляем*/
                    $this->data['auth'] = $this->session->userdata('auth');
                    $this->data['ship'] = $this->ships_model->GetShipInfo($ship_id);
                    $this->data['cautatypeid'] = $cautatypeid;
                    $this->data['cautatype'] = $this->ships_model->GetCautaType($cautatypeid);
                    $this->data['naborUslug'] = $this->ships_model->naborUslug;
                    $this->load->view('head', $this->data);
                    /*шаблон страницы*/
                    $this->load->view('navbar', $this->data);
                    $this->load->view('cautatypesedit', $this->data);

                    $this->load->view('footer', $this->data);
                }
            }
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }
    }
}
