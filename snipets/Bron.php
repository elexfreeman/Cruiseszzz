<?php
$captcha_core_path = MODX_CORE_PATH.'components/captcha/';
require_once $captcha_core_path.'model/captcha/veriword.class.php';
/**
 * Created by PhpStorm.
 * User: folle
 * Date: 02.03.2016
 * Time: 11:16
 * Бронирование круиза класс
 */
require_once 'functions.php';
require_once 'ship.class.php';
class Bron extends Ship
{

    public $data;
    /*Конвертирует номер кают из 967700000000000001 в 1*/
    function ConvertPlaceNumber($N)
    {
        /*1 - обрезаем до 6 символов сначала*/
        $N = substr($N, -5);

        return (int)$N;
    }
    /*Шаблон формы бронирования 2 - с выбором мест*/
    function tplBron2()
    {
        global $modx;
        /*С выбором мест*/
        $data['cruis']=GetPageInfo(EscapeString($_GET['cruis_id']));
        $data['ship']=GetPageInfo($data['cruis']->parent);
        $data['cauta_list']=$this->GetCautaList($data['cruis']->id);
        $data['Agreement']=$this->Agreement();
        $data['capcha']=$this->GetCaptchaImg();

        include "tpl/tplBron2.php";
    }

    /*Форма бронирования*/
    function tplBronForm2()
    {
        ClearGet();
        $data['get']=$_GET;
        include 'tpl/tplBronForm2.php';
    }

    /*ГГенерирует каптчу*/
    /*Внимание! Код класса модифицирован*/
    function captcha()
    {
        global $modx;
       /* if (!defined('MODX_API_MODE')) {
            define('MODX_API_MODE', true);
        }
        @include(dirname(dirname(__FILE__)) . '/config.core.php');
        if (!defined('MODX_CORE_PATH'))
            define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
        @include_once (MODX_CORE_PATH . "model/modx/modx.class.php");
        $modx = new modX();
        $modx->initialize('web');*/
        session_start();
        $captcha_core_path = MODX_CORE_PATH.'components/captcha/';
        require_once $captcha_core_path.'model/captcha/veriword.class.php';

        $vword = new VeriWord($modx);
        $vword->output_image();
        $vword->destroy_image();
        //session_write_close();
        exit();
    }

    function GetCaptchaImg()
    {
        global $modx;
        $vword = new VeriWord($modx);
        ob_start(); // Let's start output buffering.
        imagejpeg($vword->draw_image());;//This will normally output the image, but because of ob_start(), it won't.
        $contents = ob_get_contents(); //Instead, output above is saved to $contents
        ob_end_clean(); //End the output buffer.

        return "data:image/jpeg;base64," . base64_encode($contents);

    }

    /*Очищает на всякий случай сессию дяля капчи*/
    function ClearCa()
    {
        unset($_SESSION['veriword']);
        echo 'Thanx! Clear all capcha session values.';
    }


    /*Проверка на соответствие каптчи*/
    function isCa()
    {
        $res['status']=0;
        $res['veryword']=$_SESSION['veriword'];
        $res['veryword2']=$_SESSION['veriword2'];
        if($_GET['captcha']== $_SESSION['veriword']) $res['status']=1;
        echo json_encode($res);
    }


    /*Просмотр заявок*/
    function ShowOrder($order_id)
    {
        $order = GetPageInfo($order_id);
        $cruis = GetPageInfo($order->TV['z_cruis_id']);
        print_r($order);
        echo " ------------------------------------------------------------------------";
        echo " ------------------------------------------------------------------------";
        $z_passj = json_decode($order->TV['z_passj']);
        $i=0;
        print_r($z_passj);
        echo count($z_passj);
        while($i <count($z_passj))
        {
            echo $z_passj[$i]->name;
            echo $z_passj[$i+1]->birthday;
            $i=$i+2;
        }
    }

    /*выводи места в поповер каюты*/
    function tplPlacesPopover()
    {
        ClearGet();
        $this->data['cauta']=$_GET['kauta'];

        $cauta = GetPageInfo($_GET['cauta_id']);
        if($cauta!=0)
        {
            $this->data['places'] = $cauta->TV_Full['k_places'];
            $this->data['places'] = explode('||', $this->data['places']->value);

            include 'tpl/tplPlacesPopover.php';
        }
        else echo '0';
    }

      /*Информация о каюте для окна бронирования*/
    function GetCautaInfoBron()
    {

        /*deck:"Нижняя"
free_place:"6"
id:"45246"
inner_id:"5277"
nomer:"101"
places:"ID:527700000000000001-NAME:1-TYPE:0-POSITION:0-STATUS:0||
        ID:527700000010000002-NAME:2-TYPE:0-POSITION:0-STATUS:0||
        "
price:"5800"
type:"А2/2н"
        */
        //Очищаем $_GET
        ClearGet();
        /*Очищаем данные вывода*/
        $this->data = array();


        $this->data = $this->GetCautaInfo($this->GetCautaIdByNumber($_GET['cruis_id'],$_GET['cauta_number']));
        //$this->data->cautaid=$this->GetCautaIdByNumber($_GET['cruiz_id'],$_GET['cauta_number']);
        if($this->data->id!=0)
        {
           // $this->data['places'] = $this->data['cauta']->TV_Full['k_places'];
            $places = explode('||', $this->data->places);
            $this->data->places=array();
            $this->data->free_place=0;
            /*формируем масив мест*/
            foreach ($places as $place ) {
                /* ID:527700000010000002-NAME:2-TYPE:0-POSITION:0-STATUS:0*/
                $place=explode('-',$place);
                /* ID:527700000010000002 NAME:2 TYPE:0 POSITION:0 STATUS:0*/
                $place[0]=explode(':',$place[0]);
                $place[1]=explode(':',$place[1]);
                $place[2]=explode(':',$place[2]);
                $place_tmp=null;
                $place_tmp['number']=$place[1][1];
                $place_tmp['status']=$place[2][1];
                $place_tmp['inner_number']=$place[0][1];
                if($place_tmp['status']=='0') $this->data->free_place++;
                if($place_tmp['number']!='') $this->data->places[]=$place_tmp;

                $this->data->popover_title='Каюта №'.$_GET['cauta_number'];
                $this->data->popover_number=$_GET['cauta_number'];
                $this->data->popover_content='Свободных мест: '.$this->data->free_place;
                $this->data->popover_content.='<br>Цена за место: '.$this->data->price;
            }
        }
        else
        {
            $this->data->popover_title='Каюта №'.$_GET['cauta_number'];
            $this->data->popover_number=$_GET['cauta_number'];
            $this->data->popover_content='Свободных мест нет';

        }
       echo json_encode($this->data);
    }


    /*Бронирование и поплата со схемы*/
    function bronPay()
    {

        ClearGet();
        /*массив результат*/
        $res=array();
        $res['error']=1;
        /*проверяем капчу*/
        $res['errors_list']=array();

        $sex = array('1'=>'Муж','2'=>'Жен');


        if($_GET['u_ca']!= $_SESSION['veriword']) $res['errors_list']['u_ca']=$_SESSION['veriword'];
        if($_GET['form']=='pay')
        {
            if(mb_strlen($_GET['u_surname_0'])<3) $res['errors_list']['u_surname_0']=1;
            if(mb_strlen($_GET['u_name_0'])<3) $res['errors_list']['u_name_0']=1;
            if(mb_strlen($_GET['u_patronymic_0'])<3) $res['errors_list']['u_patronymic_0']=1;
            if(mb_strlen($_GET['u_birthday_0'])!=10) $res['errors_list']['u_birthday_0']=1;
        }

        if(mb_strlen($_GET['u_phone'])!=16) $res['errors_list']['u_phone']=1;
        if($_GET['agreement']!=1) {
            $res['errors_list']['agreement']=1;
            $res['agreement']=1;
        }

        /*Если нет ошибок*/
        if(count($res['errors_list'])==0)
        {
            $cruis=GetPageInfo($_GET['cruiz_id']);
            $cauta=GetPageInfo($this->GetCautaIdByNumber($_GET['cruiz_id'],$_GET['cauta_number']));
            $res['cauta']=$cauta;
            $res['error']=0;
            $url='http://api.infoflot.com/JSON/'.$this->shipKey.'/Requests/'.$cruis->TV['kr_inner_id'].'?';
            $url.='cabins['.$cauta->TV['k_inner_id'].']=';


            $obj = new stdClass();

            $today = date("Y-m-d H:i:s");

            $obj->parent=$this->ZayavkaParent;
            $obj->template=$this->ZayavkaTemplate;




            $obj->TV['z_cauta_nomer']=$_GET['cauta_nomer'];
            $obj->TV['z_cauta_inner_id']=$cauta->TV['cauta_inner_id'];
            $obj->TV['z_cauta_id']=$cauta->id;
            $obj->TV['z_cruis_id']=$_GET['cruis_id'];
            $obj->TV['z_cruis_inner_id']=$cruis->TV['cruis_inner_id'];
            $obj->TV['z_ship_id']=$_GET['ship_id'];
            $obj->TV['z_ship_name']=$_GET['ship_name'];
            $obj->TV['z_price']=$_GET['place_price'];
            $obj->TV['z_summa']=0;




            $passj = array();
            $obj->TV['z_user_name']='';

            foreach ($_GET as $key=>$val)
            {
                $key=explode('_',$key);
                if($key[0]=='placeid')
                {
                    $id=$key[1];
                    $url.='&places['.$_GET['placeid_'.$id].'][name]='.$_GET['u_surname_'.$id]."_".$_GET['u_name_'.$id]."_".$_GET['u_patronymic_'.$id];
                    $url.='&places['.$_GET['placeid_'.$id].'][type]=0';
                    $url.='&places['.$_GET['placeid_'.$id].'][birthday]='.$_GET['u_birthday_'.$id];
                    if ($obj->TV['z_user_name']=='') $obj->TV['z_user_name']=$_GET['u_surname_'.$id]."_".$_GET['u_name_'.$id]."_".$_GET['u_patronymic_'.$id];
                    $passj[]['name']=$_GET['u_surname_'.$id]."_".$_GET['u_name_'.$id]."_".$_GET['u_patronymic_'.$id];
                    $passj[]['u_birthday_']=$_GET['u_birthday_'.$id];
                    $obj->TV['z_summa']+=$obj->TV['z_price'];
                }
            }
            $obj->TV['z_passj']=json_encode($passj);

            $url.='&submit=1';

            /*Если это бронирование то назначаем плательщика 0-го пассажира*/
            if($_GET['form']=='pay')
            {
                $obj->TV['z_user_name']=$_GET['u_surname_0']."_".$_GET['u_name_0']."_".$_GET['u_patronymic_0'];
            }

            /*плательщик*/

            //$obj->TV['z_user_email']=$_GET['Email'];
            $obj->TV['z_user_phone']=$_GET['u_phone'];
            //$obj->TV['z_info']=$_GET['info'];
            $obj->TV['z_date']=$today;
            $obj->TV['z_status']='Новая';

            $url.='&customer='.$obj->TV['z_user_name'];
            $url.='&email='.'';
            $url.='&phone='. $obj->TV['z_user_phone'];
            $res['url']=$url;
            $res['get']=$_GET;
            $res['infoflot']=file_get_contents($url);

            //$res['infoflot']='test';
            //$res['infoflot']='testetst';

            if($res['infoflot']=='null') {
                $res['status_text'] = 'Ошибка в бронировании.';
                $res['text_body']='Ошибка в бронировании. Возможно ваши места уже забронированны. <br>
В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.';
            }
            else
            {
                $res['status_text'] = 'Успешное бронирование.';
                $res['text_body']='Успешное бронирование. Номер вашей брони: '.$res['infoflot'].' <br>
В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.';
            }
            $obj->TV['z_infoflot_responce']=$res['infoflot'];
            $obj->echo=false;

            $obj->pagetitle=substr($obj->TV['z_date'], 0, -9)."_"
                .$obj->TV['z_user_name']."_"
                .$obj->TV['z_ship_id']."_"
                .$obj->TV['z_cruis_id']."_"
                .$obj->TV['z_cauta_nomer']
                ."z_".rand(5, 60);


            $obj->alias = encodestring($obj->pagetitle);
            $obj->url="zayavki/" .$obj->alias . ".html";

            $obj->id=IncertPage($obj);
            $res['id']=$obj->id;
            /*ОТправляем на почт*/
            $obj->TV['mail']=$this->z_send($obj);

            $res['robo_form']=$this->Gen1('Номер брони: '.$res['infoflot'],$obj->TV['z_summa'],$res['id']);
        }
        echo json_encode($res);


    }


    /*Главная функция для снипита*/
    function Run($scriptProperties)
    {
        if (isset($scriptProperties['action'])) {
            if ($scriptProperties['action'] == 'tplBron2')
            {
                $this->tplBron2();
            }
            elseif($scriptProperties['action'] == 'tplPlaces')
            {
                include "tpl/tplPlaces.php";
            }
            elseif($scriptProperties['action'] == 'tplBronForm2')
            {
                include "tpl/tplBronForm2.php";
            }
            elseif($scriptProperties['action'] == 'InfoflotBron')
            {
               $this->InfoflotBron();
            }
            elseif($scriptProperties['action'] == 'captcha')
            {
               $this->captcha();
            }
            elseif($scriptProperties['action'] == 'isCa')
            {
               $this->isCa();
            }
            elseif($scriptProperties['action'] == 'ClearCa')
            {
               $this->ClearCa();
            }
            elseif($scriptProperties['action'] == 'GetCaptchaImg')
            {
               echo $this->GetCaptchaImg();
            }
            /*todo NEED to DELETE !!!*/
            elseif($scriptProperties['action'] == 'ShowOrder')
            {
               echo $this->ShowOrder($_GET['order_id']);
            }
            elseif($scriptProperties['action'] == 'tplPlacesPopover')
            {
               echo $this->tplPlacesPopover();
            }
            elseif($scriptProperties['action'] == 'GetCautaInfo')
            {
               $this->GetCautaInfoBron();
            }
            elseif($scriptProperties['action'] == 'bronPay')
            {
               $this->bronPay();
            }
            elseif($scriptProperties['action'] == 'SendSMS')
            {
               print_r($this->SendSMS('test'));
            }
        }
    }

    /*Генератор дял робокассы*/
    function Gen1($inv_desc,$out_summ,$inv_id=0)
    {
        // 2.
        // Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
        // Payment of the set sum with a choice of currency on site ROBOKASSA

        // регистрационная информация (логин, пароль #1)
        // registration info (login, password #1)
        $mrh_login = "berg-tour";
        $mrh_pass1 = "dYs8N3byKmD0JaeS9dv9";

        // номер заказа
        // number of order


        // описание заказа
        // order description
        //$inv_desc = "ROBOKASSA Advanced User Guide";

        // сумма заказа
        // sum of order
        //$out_summ = "8.96";

        //$inv_id=$inv_id;
        // тип товара
        // code of goods
        $shp_item = "1";

        // предлагаемая валюта платежа
        // default payment e-currency
        $in_curr = "";

        // язык
        // language
        $culture = "ru";

        // формирование подписи
        // generate signature
        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
        $res='';
        include "tpl/robokassa/tplRoboForm.php";
        return $res;
    }

    /*Отправка заявки в инфофлот*/
    function InfoflotBron()
    {


        //http://api.infoflot.com/JSON/a04a83e5ccb19b661c4c0873d3234287982fb5d3/Requests/8565?
        //cabins[491084]=
        //&places[491085][name]=Турист
        //&places[491085][type]=0
        //&places[491085][birthday]=01.01.1990
        //&submit=1
        //&customer=Покупатель
        //&email=mail@domain.tld
        //&phone=+7(926)123-45-67

        /*
         * Email: "elextraza@gmail.com"
Phone: "+7(333) 333-33-33"
action: "InfoflotBron"
cauta_id: "44932"
cauta_inner_id: "5658"
cauta_nomer: "208"
cruis_id: "22163"
cruis_inner_id: "282176"
deck: "Главная"
free_place: "4"
info: "232"
name1: "sdsdfsdf"
pasjBirthday1: "11.03.2016"
placeid_1: "565800000000000001"
price: "10500"
q: "ajax-bron.html"
ship_id: "19441"
ship_name: "Алексей Толстой"
type: "2В"*/
        ClearGet();
        $url='http://api.infoflot.com/JSON/'.$this->shipKey.'/Requests/'.$_GET['cruis_inner_id'].'?';
        $url.='cabins['.$_GET['cauta_inner_id'].']=';


        /*todo ставка заявки в админку */

        $obj = new stdClass();

        $today = date("Y-m-d H:i:s");

        $obj->parent=$this->ZayavkaParent;
        $obj->template=$this->ZayavkaTemplate;

        $obj->TV['z_cauta_nomer']=$_GET['cauta_nomer'];
        $obj->TV['z_cauta_inner_id']=$_GET['cauta_inner_id'];
        $obj->TV['z_cauta_id']=$_GET['cauta_id'];
        $obj->TV['z_cruis_id']=$_GET['cruis_id'];
        $obj->TV['z_cruis_inner_id']=$_GET['cruis_inner_id'];
        $obj->TV['z_ship_id']=$_GET['ship_id'];
        $obj->TV['z_ship_name']=$_GET['ship_name'];
        $obj->TV['z_price']=$_GET['price'];
        $obj->TV['z_summa']=0;
        $obj->TV['z_deck']=$_GET['deck'];
        $obj->TV['z_type']=$_GET['type'];
        $obj->TV['z_free_place']=$_GET['free_place'];


        $obj->TV['z_user_name']=$_GET['name1'];

        $obj->TV['z_user_email']=$_GET['Email'];
        $obj->TV['z_user_phone']=$_GET['Phone'];
        $obj->TV['z_info']=$_GET['info'];
        $obj->TV['z_date']=$today;
        $obj->TV['z_status']='Новая';


        $passj = array();

        foreach ($_GET as $key=>$val)
        {
            $key=explode('_',$key);
            if($key[0]=='placeid')
            {
                $id=$key[1];
                $url.='&places['.$_GET['placeid_'.$id].'][name]='.$_GET['name'.$id];
                $url.='&places['.$_GET['placeid_'.$id].'][type]=0';
                $url.='&places['.$_GET['placeid_'.$id].'][birthday]=01.01.2001';
                $passj[]['name']=$_GET['name'.$id];
                $passj[]['birthday']='01.01.2001';
                $obj->TV['z_summa']+=$obj->TV['z_price'];
            }
        }
        $obj->TV['z_passj']=json_encode($passj);

        $url.='&submit=1';
        $url.='&customer='.$_GET['name1'];
        $url.='&email='.$_GET['Email'];
        $url.='&phone='.$_GET['Phone'];
        $res['url']=$url;
        $res['get']=$_GET;
        $res['infoflot']=file_get_contents($url);
        //$res['infoflot']='test';
        //$res['infoflot']='testetst';

        if($res['infoflot']=='null') {
            $res['status_text'] = 'Ошибка в бронировании.';
            $res['text_body']='Ошибка в бронировании. Возможно ваши места уже забронированны. <br>
В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.';
        }
        else
        {
            $res['status_text'] = 'Успешное бронирование.';
            $res['text_body']='Успешное бронирование. Номер вашей брони: '.$res['infoflot'].' <br>
В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.';
        }
        $obj->TV['z_infoflot_responce']=$res['infoflot'];
        $obj->echo=false;

        $obj->pagetitle=substr($obj->TV['z_date'], 0, -9)."_"
            .$obj->TV['z_user_name']."_"
            .$obj->TV['z_ship_id']."_"
            .$obj->TV['z_cruis_id']."_"
            .$obj->TV['z_cauta_nomer']
            ."z_".rand(5, 60);
        //$obj->pagetitle=$ship;

        $obj->alias = encodestring($obj->pagetitle);
        $obj->url="zayavki/" .$obj->alias . ".html";

        $obj->id=IncertPage($obj);
        $res['id']=$obj->id;
        /*ОТправляем на почт*/
        $obj->TV['mail']=$this->z_send($obj);

        $res['robo_form']=$this->Gen1('Номер брони: '.$res['infoflot'],$obj->TV['z_summa'],$res['id']);

        echo json_encode($res);
    }


}