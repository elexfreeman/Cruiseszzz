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



}