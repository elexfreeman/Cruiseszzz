<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Модель логинов*/
/*
 Описание глобалов
^Test.VACUsers("admin","group")="administrator"
^Test.VACUsers("admin","password")="!1qazxsw2"
^Test.VACUsers("admin","fullname")="Администратор"


 * */

class Ships_model extends CI_Model
{

    public $auth;

    public $settings;
    public $table_prefix;

    public $ShipsParent = 2;
    public $ShipsTemplate = 2;
    public $CruisTemplate = 3;

    /*шаблон списка типов кают*/
    public $CautaTypesTpl = 31;
    /*Шаблон типов кают*/
    public $CautaTypeTpl = 32;

    public $naborUslug;/*Набр услуг в каюте*/

    public function __construct()
    {
        $this->load->library('functions');

        $this->settings=$this->functions->GetPageInfo(1);

        $this->dbMySQL = $this->load->database('default', TRUE);
        $this->load->helper('url');
        $this->table_prefix="modx_";
        $this->naborUslug=array('обычная каюта','зеркало','радио',
            'умывальник (с горячей и холодной водой)','обзорное окно',
            'розетка 220 V','шкаф для одежды','санузел (душ, туалет, умывальник)',
            'душевая кабина','стол','окно-иллюминатор','кондиционер',
            'холодильник','телефон','фен','набор посуды','выход на палубу',
            'журнальный столик','DVD-проигрыватель','весы','телевизионная плазменная панель',
            'трюмо','прикроватная тумбочка','комод');

    }

    public function GetShipsList()
    {
        $sql="select
                    ships.id ship_id,
                    ships.pagetitle ship_title,
                    tv.name tv_name,
                    cv.value tv_value


                    from ".$this->table_prefix."site_content ships

                    left join ".$this->table_prefix."site_tmplvar_contentvalues cv
                    on ships.id=cv.contentid

                    left join ".$this->table_prefix."site_tmplvars tv
                    on tv.id=cv.tmplvarid


                    where (ships.parent=".$this->ShipsParent.")and(tv.name='t_in_filtr')
                    and(ships.deleted=0)

";
        //echo $sql;
        $ships = array();
        $query = $this->dbMySQL->query($sql);
        foreach ($query->result_array() as $row)
        {
            $ships[]=$this->functions->GetPageInfo($row['ship_id']);
        }
        return $ships;
    }


    /*Получает список круизов для теплохода*/
    function GetShipCruisList($ship_id,$delete=false)
    {


        $sql="select * from ".$this->table_prefix."site_content where
        (parent=".$ship_id.")and(template=".$this->CruisTemplate.")
        and(deleted=0)
        ";
        // echo $sql;
        $obj = array();
        $query = $this->dbMySQL->query($sql);
        foreach ($query->result_array() as $row)
        {
            $tem=$this->functions->GetPageInfo($row['id'],$delete);
            // if((isset($tem->TV['kr_route_name']))and($tem->TV['kr_route_name']!=''))
            $obj[]=$tem;
        }
        return $obj;
    }

    /*Инфо по теплоходу*/
    function GetShipInfo($ship_id)
    {
        $ship = $this->functions->GetPageInfo($ship_id);
        $cautaTypesRoot = $this->functions->GetChildListNoSort($ship_id,$this->CautaTypesTpl,false);
        $ship->cautaTypesRoot = $cautaTypesRoot;
        /*Типы кают*/
        $ship->CautaTypes=array();
        if(count($cautaTypesRoot)>0)
        {
            $ship->CautaTypes = $this->functions->GetChildListNoSort($cautaTypesRoot[0]->id,$this->CautaTypeTpl,false);
        }
        else
        {
            /*Вставляем заглушку типов кают*/
            $page = new stdClass();
            $page->pagetitle='Типы кают'.$ship_id;
            $page->parent=$ship->id;
            $page->template=$this->CautaTypesTpl;
            $page->url='';
            $page->TV=array();
            $page->alias = '';
            $this->functions->IncertPage($page);
        }
        /*Ищем описания коют*/
        $ship->CruisList = $this->GetShipCruisList($ship->id);
        return $ship;
    }

    /*Возвращает тип каюты*/
    public function GetCautaType($cautatypeid)
    {

        return $this->functions->GetPageInfo($cautatypeid);
    }


    function IncertCautaType($ship_id,$cautatypeid,$arg)
    {
        $ship=$this->GetShipInfo($ship_id);
        $page = new stdClass();
        if($cautatypeid!=0) $page->id=$cautatypeid;
        $page->pagetitle=$arg['pagetitle'];
        $page->parent=$ship->cautaTypesRoot[0]->id;
        $page->template=$this->CautaTypeTpl;
        $page->url='';
        $page->TV['k_type_name']=$arg['pagetitle'];
        $page->TV['k_description']=$arg['k_description'];
        //for($i=1;$i<=4;$i++) $page->TV['k_img'.$i]=$arg['k_img'.$i];

        $naborUslog='';
        foreach ($arg as $key=>$value) {
            $key=explode("_",$key);
            if($key[0]=='naborUslug')  $naborUslog.=$value."||";
        }

        $page->TV['k_params']=$naborUslog;
        $page->alias = '';
        $this->functions->IncertPage($page);


        /*Теперь картинки*/
        for($i=1;$i<=4;$i++)
        {
            if(((isset($_FILES['k_img'.$i]['name'])))and($_FILES['k_img'.$i]['name']!=''))
            {
                $rnd=$this->functions->PassGen();
                $uploadfile = $this->config->item('images_dir') . $rnd.'_'.basename($_FILES['k_img'.$i]['name']);

                if (move_uploaded_file($_FILES['k_img'.$i]['tmp_name'],$uploadfile)) {

                    $page->TV['k_img'.$i]=$rnd.'_'.basename($_FILES['k_img'.$i]['name']);

                } else {
                  //error
                }
            }
        }

        $this->functions->IncertPage($page);
    }

    /*Устанавливат популярный круиз*/
    function CruisSetPop($cruis_id,$val)
    {
        $this->functions->IncertPageTV($cruis_id,'kr_pop',$val);
        $res['status']='done';
        return json_encode($res);
    }

    function ShipDataUpdate($ship_id)
    {
        /*Проверяем пришло ли обновление картинки*/
        if((isset($_FILES['shipImg']['name']))and($_FILES['shipImg']['name']!=''))
        {
            $rnd=$this->PassGen();
            $uploadfile = $this->config->item('images_dir') . $rnd.'_'.basename($_FILES['shipImg']['name']);

            if (move_uploaded_file($_FILES['shipImg']['tmp_name'],$uploadfile))
            {
                $this->functions->IncertPageTV($ship_id,'t_title_img', '/images/teplohod/'.$rnd.'_'.basename($_FILES['shipImg']['name']) );
            }
            else
            {
               // echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }

        /*Схема теплохода*/
        if((isset($_FILES['t_sh_img']['name']))and($_FILES['t_sh_img']['name']!=''))
        {
            $rnd=$this->PassGen();
            $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['t_sh_img']['name']);

            if (move_uploaded_file($_FILES['t_sh_img']['tmp_name'],$uploadfile))
            {
                //echo "Файл корректен и был успешно загружен.\n";
                $this->functions->IncertPageTV($ship_id,'t_sh_img', '/images/teplohod/'.$rnd.'_'.basename($_FILES['t_sh_img']['name']) );
            }
            else
            {
               // echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }

        /*Дополнительные 4 изображения*/
        //echo 'Дополнительные 4 изображения';
        for($i=1;$i<5;$i++)
        {
            if($_FILES['t_gl_img_0'.$i]['name']!='')
            {
                $rnd=$this->PassGen();
                $uploadfile = $this->config->item('images_dir') . $rnd.'_'.basename($_FILES['t_gl_img_0'.$i]['name']);

                if (move_uploaded_file($_FILES['t_gl_img_0'.$i]['tmp_name'],$uploadfile))
                {
                  //  echo "Файл корректен и был успешно загружен.\n";
                    $this->functions->IncertPageTV($ship_id,'t_gl_img_0'.$i, '/images/teplohod/'.$rnd.'_'.basename($_FILES['t_gl_img_0'.$i]['name']) );
                }
                else
                {
                   // echo "Возможная атака с помощью файловой загрузки!\n";
                }
            }
        }

        /*Хериим все данные об популярных круизах теплохода*/
        $cruis_list=$this->GetShipCruisList($ship_id);
        foreach($cruis_list as $cruis)
        {
            $this->functions->IncertPageTV($cruis->id,'kr_pop','Нет');
        }

        /*перебираем все круизы теплохода*/
        foreach ($_POST as $tt=>$val )
        {
            $tt=explode('_',$tt);

            if($tt[0]=='cruis')
            {
                $cruis_id=$tt[1];
                $this->CruisSetPop($cruis_id,'Да');
            }
        }

        /*Вставляем текст параходик*/
        if(isset($_POST['ship-description'])) $this->functions->IncertPageTV($ship_id,'t_description',$_POST['ship-description']);
        if(isset($_POST['t_usl'])) $this->functions->IncertPageTV($ship_id,'t_usl',$_POST['t_usl']);
        if(isset($_POST['t_teh_xar'])) $this->functions->IncertPageTV($ship_id,'t_teh_xar',$_POST['t_teh_xar']);

    }


}