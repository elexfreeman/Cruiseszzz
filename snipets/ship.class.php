<?php
/**
 * Created by PhpStorm.
 * User: elex
 * Date: 27.09.15
 * Time: func15:23
 */

require_once 'functions.php';
require_once 'reviews.php';

/*Класс для работы с кораблями*/
class Ship
{
    /*Отправка на почту*/
    public $mailheaders;

    public $adminEmail;
    public $adminSMS;

    public $shipKey='ad441cf7449bc9af3977e6b0c2a6806e3655247c';

    public $uploaddir;
    public $ship_images_dir = '/images/teplohod/';

    public $ShipsParent = 2;
    public $ShipsTemplate = 2;
    public  $popDirection;


    public $CruisTemplate = 3;

    public $ShipPhotoTemplate = 5;
    public $CruisPriceTemplate = 5;
    public $CityTemplate = 9;
    public $CityParent = 4528;
    public $PriceTemplate = 6;
    public $ExTemplate = 10;
    public $CautaTemplate = 4;

    public $ZayavkaTemplate = 11;
    public $ZayavkaParent = 20303;

    public $AgreementPage = 102440;


    function __construct()
    {
        $this->mailheaders  = "Content-type: text/html; charset=utf-8 \r\n";
        $this->mailheaders .= "From: berg <www-data@berg-kruiz.ru>\r\n";

        $this->uploaddir = $_SERVER['DOCUMENT_ROOT'].'/images/teplohod/';
       // $this->mailheaders = "-fMIME-Version: 1.0"."\r\n".  " From: berg-kruiz.ru <shop@berg-kruiz.ru>"."\r\n";
        $mainPage = GetPageInfo(1);

        $this->adminEmail=explode(';', $mainPage->TV['adminEmailList']);
        $this->adminSMS=explode(';', $mainPage->TV['sms_number']);

        //= array('follen-des@mail.ru','zakaz@berg-tour.ru','manager@berg-tour.ru');

    }

    function LoadShipsList()
    {
        global $modx;
        global $table_prefix;
        global $shipKey;
        $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Ships/';
        $ships=json_decode(file_get_contents($URL), true);

        $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/ShipsImages/';
        $ships_img=json_decode(file_get_contents($URL), true);

        foreach($ships as $inner_id => $ship)
        {
            $product = new stdClass();

            $product->pagetitle=$ship;
            $product->parent=$this->ShipsParent;
            $product->template=$this->ShipsTemplate;

            $product->TV['t_title']=$ship;
            $product->TV['t_inner_id']=$inner_id;
            $product->TV['t_title_img'];

            $product->alias = encodestring($product->TV['t_inner_id'].'_'.$product->TV['t_title']);
            $product->url="ships/" .$product->alias . ".html";

            if(isset($ships_img[$inner_id]))  $product->TV['t_title_img']=$ships_img[$inner_id];
            IncertPage($product);
        }
    }


    /*
     * Описание объекта Ship
     * $Ship->pagetitle - Название корабля
     * $Ship->parent=2 - Родитель
     * $Ship->template=2 - Шаблон

     * $Ship->TV['t_title']
     * $Ship->TV['t_inner_id']
     * $Ship->TV['t_title_img']
     *
     *$Ship->alias = encodestring($Ship->TV['t_inner_id'].'_'.$Ship->TV['t_title']);
     *$Ship->url="ships/" .$Ship->alias . ".html"
     * */





    /*получить InnerID корабля*/
    function GetShipInnerID($content_id)
    {
        $ship=GetPageInfo($content_id);
        return $ship->TV['t_inner_id'];
    }


    /*Информация в ввиде объекта о корабля по его внутреннему номеру*/
    function GetShipInfo($ship_id)
    {
        return GetPageInfo($ship_id);
    }



    /*Массив объектов теплоходов*/
    function GetShipsList()
    {
        global $modx;
        global $table_prefix;


        $sql="select
                    ships.id ship_id,
                    ships.pagetitle ship_title,
                    tv.name tv_name,
                    cv.value tv_value


                    from ".$table_prefix."site_content ships

                    left join ".$table_prefix."site_tmplvar_contentvalues cv
                    on ships.id=cv.contentid

                    left join ".$table_prefix."site_tmplvars tv
                    on tv.id=cv.tmplvarid


                    where (ships.parent=".$this->ShipsParent.")and(tv.name='t_in_filtr')

";
        //echo $sql;
        $Ships = array();
        foreach ($modx->query($sql) as $row)
        {
            $Ships[]=GetPageInfo($row['ship_id']);
        }
        return $Ships;
        //print_r($Ships);
    }


    /*Список городов выбраных кораблей*/
    function GetShipsCityList()
    {
        global $modx;
        global $table_prefix;
        $sql="	select
	kr.id kr_id,
	kr.pagetitle kr_title,
	tv.name tv_name,
	REPLACE(cv.value, '*','') tv_value


	from " . $table_prefix . "site_content kr

	left join " . $table_prefix . "site_tmplvar_contentvalues cv
	on kr.id=cv.contentid

	left join " . $table_prefix . "site_tmplvars tv
	on tv.id=cv.tmplvarid

	where (kr.parent in
				(

			-- ********************************
				select ship_id id from
				(
				select
				ships.id ship_id,
				ships.pagetitle ship_title,
				tv.name tv_name,
				cv.value tv_value


				from " . $table_prefix . "site_content ships

				left join " . $table_prefix . "site_tmplvar_contentvalues cv
				on ships.id=cv.contentid

				left join " . $table_prefix . "site_tmplvars tv
				on tv.id=cv.tmplvarid


				where (ships.parent=".$this->ShipsParent.")and(tv.name='t_in_filtr')

				) ships_tbl
			-- ********************************
			)
)

and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_route')
order by cv.value
";

        $citiesy=array();

        foreach ($modx->query($sql) as $row_c_tv)
        {
            $tmp=explode(" – ",$row_c_tv['tv_value']);

            foreach($tmp as $city)
            {
                //теперь разбираем на скобочки " ("
                $city=explode(' (',$city);
                $city=$city[0];
                $citiesy[$city]=1;
            }

            //теперь выстраиваем сортировку
            $city2=array();
            foreach($citiesy as $city=> $t)
            {
                $city2[]=$city;
            }
        }
        sort($city2);
        return $city2;
    }


    /*Список начальных городов выбраных кораблей*/
    function GetShipsCityStartList()
    {
        global $modx;
        global $table_prefix;
        $sql="	select
	kr.id kr_id,
	kr.pagetitle kr_title,
	tv.name tv_name,
	REPLACE(cv.value, '*','') tv_value


	from " . $table_prefix . "site_content kr

	left join " . $table_prefix . "site_tmplvar_contentvalues cv
	on kr.id=cv.contentid

	left join " . $table_prefix . "site_tmplvars tv
	on tv.id=cv.tmplvarid

	where (kr.parent in
				(

			-- ********************************
				select ship_id id from
				(
				select
				ships.id ship_id,
				ships.pagetitle ship_title,
				tv.name tv_name,
				cv.value tv_value


				from " . $table_prefix . "site_content ships

				left join " . $table_prefix . "site_tmplvar_contentvalues cv
				on ships.id=cv.contentid

				left join " . $table_prefix . "site_tmplvars tv
				on tv.id=cv.tmplvarid


				where (ships.parent=".$this->ShipsParent.")and(tv.name='t_in_filtr')

				) ships_tbl
			-- ********************************
			)
)

and(kr.template=".$this->CruisTemplate." )and(tv.name='kr_route')
order by cv.value
";

        $citiesy=array();

        foreach ($modx->query($sql) as $row_c_tv)
        {
            $tmp=explode(" – ",$row_c_tv['tv_value']);

            $city=$tmp[0];
            $city=explode(' (',$city);
            $city=$city[0];
            $citiesy[$city]=1;

            //теперь выстраиваем сортировку
            $city2=array();
            foreach($citiesy as $city=> $t)
            {
                $city2[]=$city;
            }
        }
        sort($city2);
        return $city2;
    }



    //ЗАгрузка статусов кают
    function LoadCauts()
    {
        global $modx;
        global $table_prefix;
        global $shipKey;
        echo "<pre>";
        $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Tours/4/';
        echo $URL;

       /* $cruis_list=json_decode(file_get_contents($URL), true);*/

/*
        $sql="select
               ships.id ship_id,
                ships.alias ship_aliac,
                ships.pagetitle ship_title,
                cruis.id cruis_id,
                cruis.alias cruis_aliac,
                cruis.pagetitle cruis_title

                from modx_site_content ships


                join modx_site_content cruis
                on cruis.parent=ships.id

                 where
                 (ships.template=".$this->ShipsTemplate.")and(cruis.template=".$this->CruisTemplate.")";
*/
        $Ships=$this->GetShipsList();
       // print_r($Ships);

        /*Перебераем этот список*/
        foreach($Ships as $key=>$ship)
        {
            echo "********// ship ".$ship->title."\r\n";
            $cruis_list=$this->GetShipCruisList($ship->id);
            foreach($cruis_list as $cr_id=>$cruis)
            {
                echo "********* CRUIS".$cruis->title."\r\n";
                $URL='http://api.infoflot.com/JSON/'.
                    $this->shipKey.'/CabinsStatus/'.$ship->TV['t_inner_id'].'/'.$cruis->TV['kr_inner_id']."/";
                echo $URL."\r\n";
                $cauta_list=json_decode(file_get_contents($URL), true);
               // print_r($cauta_list);
                foreach($cauta_list as $id=>$cauta)
                {
                    echo "********* cauta".$cauta['name']."\r\n";
                   /* ob_flush();
                    flush();*/
                     //ie working must
                    $obj = new stdClass();

                    $obj->pagetitle=$ship->alias."-".$cruis->alias."-".$id."_".$cauta['name'];
                    $obj->parent=$cruis->id;
                    $obj->template=$this->CautaTemplate;
                    $obj->TV['k_name']=$cauta['name'];
                    $obj->TV['k_type']=$cauta['type'];
                    $obj->TV['k_deck']=$cauta['deck'];
                    $obj->TV['k_separate']=$cauta['separate'];
                    $obj->TV['k_status']=$cauta['status'];
                    $obj->TV['k_gender']=$cauta['gender'];
                    $obj->TV['k_inner_id']=$id;

                    $obj->TV['k_places']='';
                    $places=$cauta['places'];
                    foreach($places as $place_id=>$place)
                    {
                        $obj->TV['k_places'].="ID:".$place_id."-NAME:".$place['name']."-TYPE:".$place['type']."-POSITION:".$place['position']."-STATUS:".$place['status']."||";
                    }

                    $obj->alias = encodestring($obj->pagetitle);
                    $obj->url="ships/".$ship->alias."/".$cruis->alias."/" . $obj->alias.".html";

                  //  echo "=================== Каюта \r\n";
                   // print_r($obj);

                    echo "cauta_id=".IncertPage($obj)."\r\n";
                    // $cruis_inner_id=$obj->TV['kr_inner_id'];

                }
            }


         /*  */

        }




/*
        foreach ($modx->query($sql) as $row)
        {
            $ship=GetPageInfo($row['ship_id']);
            print_r($ship);

            $cruis=GetPageInfo($row['cruis_id']);
            print_r($cruis);

            $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/CabinsStatus/'.$ship->TV['k_inner_id'].'/'.$cruis->TV['kr_inner_id']."/";
            echo $URL;

            $cauta_list=json_decode(file_get_contents($URL), true);
            foreach($cauta_list as $id=>$cauta)
            {
                ob_flush();
                flush(); //ie working must
                $obj = new stdClass();

                $obj->pagetitle=$id."_".$cauta['name'];
                $obj->parent=$row['cruis_id'];
                $obj->template=$cauta->CruisTemplate;
                $obj->TV['k_name']=$cauta['name'];
                $obj->TV['k_type']=$cauta['type'];
                $obj->TV['k_deck']=$cauta['deck'];
                $obj->TV['k_separate']=$cauta['separate'];
                $obj->TV['k_status']=$cauta['status'];
                $obj->TV['k_gender']=$cauta['gender'];
                $obj->TV['k_inner_id']=$id;

                $obj->TV['k_places']='';
                $places=$cauta['places'];
                foreach($places as $place_id=>$place)
                {
                    $obj->TV['k_places']=$place_id."-".$place['name']."-".$place['type']."-".$place['position']."-".$place['status']."||";

                }

                $obj->alias = encodestring($obj->pagetitle);
                $obj->url="ships/".$row['ship_aliac']."/".$row['cruis_aliac']."/" . $obj->alias.".html";

                //print_r($obj);
                echo "Круиз \r\n";
                IncertPage($obj);
               // $cruis_inner_id=$obj->TV['kr_inner_id'];

            }
        }
        */
    }


    //Загрузка туров для теплохода
    function LoadShipsTours()
    {
        global $shipKey;
        echo "<pre>";
        $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Tours/4/';
        echo $URL;
        $cities=array();
        $cruis_list=json_decode(file_get_contents($URL), true);
       // print_r($cruis_list);
        /*Получаем список теплоходов*/
        $Ships=$this->GetShipsList();

        /*Перебераем этот список*/
        foreach($Ships as $key=>$Ship)
        {

            echo "Корбель \r\n";
            print_r($Ship);

            /*Загружаем список круизов для теплохода*/
            $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Tours/'.$Ship->TV['t_inner_id'].'/';
            echo $URL."<br>";
            $cruis_list=json_decode(file_get_contents($URL), true);
            /*Перебираем этот список*/
            echo "-------------------------------------
            ";
            echo "-------------------------------------
            ";
            echo "Список круизов
            ";
            foreach($cruis_list as $id=>$cruis)
            {
                //echo $cruis['name']."\r\n";
                ob_flush();
                flush(); //ie working must
                $obj = new stdClass();

                $obj->pagetitle=$id."_".$cruis['name'];
                $obj->parent=$Ship->id;
                $obj->template=$this->CruisTemplate;
                $obj->TV['kr_name']=$cruis['name'];
                $obj->TV['kr_inner_id']=$id;
                $obj->TV['kr_date_start']=$cruis['date_start'];
                $obj->TV['kr_date_end']=$cruis['date_end'];
                $obj->TV['kr_nights']=$cruis['nights'];
                $obj->TV['kr_days']=$cruis['days'];
                $obj->TV['kr_weekend']=0;
                if($cruis['weekend']) $obj->TV['kr_weekend']=1;
                //$obj->TV['kr_weekend']=$cruis['weekend'];
                $obj->TV['kr_cities']=$cruis['cities'];
                $obj->TV['kr_route']=$cruis['route'];
                $obj->TV['kr_route_name']=$cruis['route_name'];
                $obj->TV['kr_region']=$cruis['region'];
                $obj->TV['kr_river']=$cruis['river'];
                $obj->TV['kr_surchage_meal_rub']=$cruis['surchage_meal_rub'];
                $obj->TV['kr_surcharge_excursions_rub']=$cruis['surcharge_excursions_rub'];
                $obj->echo=true;
                $obj->alias = encodestring($obj->pagetitle);
                $obj->url="ships/".$Ship->alias."/".$obj->alias . ".html";
                $cruis_alias=$obj->alias;
                //print_r($obj);
                echo "Круиз \r\n";
                $cruis_id=IncertPage($obj);
                $cruis_inner_id=$obj->TV['kr_inner_id'];
                /*Вставляем цены*/

                //Обновляем города
                $tmp=explode(' – ',$obj->TV['kr_cities']);
                foreach($tmp as $city)
                {
                    $cities[$city]=1;
                }



                /*Нужен выделенный сервер чтобы проставить таймауты*/
                echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                ";
                echo "Цены
                ";
                foreach($cruis['prices'] as $price_id=>$price)
                {
                    $obj2 = new stdClass();

                    $obj2->pagetitle=$cruis['name']."_".$price_id."_".$price['name'];
                    $obj2->parent=$cruis_id;
                    $obj2->template=$this->PriceTemplate;
                    $obj2->TV['cr_price_name']=$price['name'];
                    $obj2->TV['cr_price_price_eur']=$price['price_eur'];
                    $obj2->TV['cr_price_price']=$price['price'];
                    $obj2->TV['cr_price_price_usd']=$price['price_usd'];
                    $obj2->TV['cr_price_places_total']=$price['places_total'];
                    $obj2->TV['cr_price_places_free']=$price['places_free'];


                    $obj2->alias = encodestring($obj2->pagetitle);
                    $obj2->url="ships/".$Ship->alias."/".$cruis_alias."/".$obj2->alias . ".html";
                   //  print_r($obj2);
                    IncertPage($obj2);
                }


                /*Загружаем экскурсии*/
              /*  echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                ";
                echo "экскурсии
                ";
                $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Excursions/'.$Ship->TV['t_inner_id'].'/'.$cruis_inner_id."/";
                echo $URL."<br>";
                $ex_list=json_decode(file_get_contents($URL), true);
                foreach($ex_list as $ex_inner_id=>$ex)
                {

                    ob_flush();
                    flush(); //ie working must
                    $obj2 = new stdClass();

                    $obj2->pagetitle=$ex_inner_id."_".$ex['city'];
                    $obj2->parent=$cruis_id;
                    $obj2->template=$this->ExTemplate;
                    $obj2->TV['ex_city']=$ex['city'];
                    $obj2->TV['ex_date_start']=$ex['date_start'];
                    $obj2->TV['ex_time_start']=$ex['time_start'];
                    $obj2->TV['ex_date_end']=$ex['date_end'];
                    $obj2->TV['ex_time_end']=$ex['time_end'];
                    $obj2->TV['ex_description']=$ex['description'];


                    $obj2->alias = encodestring($ex_inner_id."_".$obj2->pagetitle);
                    $obj2->url="ships/".$Ship->alias."/".$cruis_alias."/".$obj2->alias . ".html";
                    //  print_r($obj2);
                    IncertPage($obj2);
                }*/

            }
        }

        /*Вставляем странцы городов*/
        foreach($cities as $city=>$val)
        {
            $obj2 = new stdClass();

            $obj2->pagetitle=$city;
            $obj2->parent=$this->CityParent;
            $obj2->template=$this->CityTemplate;
            IncertPage($obj2);
        }
        echo "</pre>";
    }


    function LoadShipsPhoto()
    {

        $Ships=$this->GetShipsList();
        print_r($Ships);

        /*Перебераем этот список*/
        foreach($Ships as $key=>$Ship)
        {
            $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/ShipsPhoto/'.$Ship->TV['t_inner_id'].'/';
            echo $URL."<br>";
            $ShipsPhotos=json_decode(file_get_contents($URL), true);
            foreach($ShipsPhotos as $id=>$Images)
            {
                $obj = new stdClass();

                $obj->pagetitle='Img_'.$key."_".$id;
                $obj->parent=$Ship->id;
                $obj->template=$this->ShipPhotoTemplate;
                $obj->TV['ph_t_full']=$Images['full'];
                $obj->TV['ph_t_thumbnail']=$Images['thumbnail'];

                $obj->alias = encodestring($obj->pagetitle);
                $obj->url="ships/".$Ship->alias."/".$obj->alias . ".html";
                print_r($obj);
                IncertPage($obj);
            }
        }
    }

    function tplShipsList()
    {
        include "tpl/tplShipsList.php";
    }

    function GetShipImg($ship_id)
    {
        global $modx;
        global $table_prefix;

        $sql="select * from ".$table_prefix."site_content where (parent=".$ship_id.")and(template=".$this->ShipPhotoTemplate.")";
        $obj = array();
        foreach ($modx->query($sql) as $row)
        {
            $obj[]=GetPageInfo($row['id']);
        }
        return $obj;
    }


    /*Получает список круизов для теплохода*/
    function GetShipCruisList($ship_id)
    {
        global $modx;
        global $table_prefix;

        $sql="select * from ".$table_prefix."site_content where (parent=".$ship_id.")and(template=".$this->CruisTemplate.")";
       // echo $sql;
        $obj = array();
        foreach ($modx->query($sql) as $row)
        {
            $tem=GetPageInfo($row['id']);
           // if((isset($tem->TV['kr_route_name']))and($tem->TV['kr_route_name']!=''))
                $obj[]=$tem;
        }
        return $obj;
    }

    /*Возвращает массив прайса курза*/
    function GetCruisPriceList($cruis_id)
    {
        global $modx;
        global $table_prefix;

        $sql="select * from ".$table_prefix."site_content where (parent=".$cruis_id.")and(template=".$this->PriceTemplate.")";
        // echo $sql;
        $obj = array();
        foreach ($modx->query($sql) as $row)
        {
            $tem=GetPageInfo($row['id']);
            $obj[]=$tem;
        }
        return $obj;
    }

    function GetCruisMinPrice($cruis_id)
    {
       $priceList = $this->GetCruisPriceList($cruis_id);
        $minPrice = 10000000000;
        foreach($priceList as $price)
        {
            if($price->TV['cr_price_price']<$minPrice) $minPrice=$price->TV['cr_price_price'];
        }
        return $minPrice;
    }

    function GetShipByCruis($cruis_id)
    {
        $cruis = GetPageInfo($cruis_id);
        return GetPageInfo($cruis->parent);
    }


    /*Вывод списка круизов для теплохода*/
    function tplShipCruisList($ship_id)
    {
        include "tpl/tplShipCruisList.php";
    }

    function tplSearchForm()
    {

        include "tpl/tplSearchForm.php";
    }


    function Search()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplSearchResult.php";
    }



    /*Шаблон формы бронирования*/
    function tplBron()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplBron.php";
    }



    /*список сают круизов*/
    function GetCautaList($cruis_id)
    {
        global $modx;
        global $table_prefix;

        $sql="select * from ".$table_prefix."site_content where (parent=".$cruis_id.")and(template=".$this->CautaTemplate.")";
        foreach ($modx->query($sql) as $row)
        {
            $tem=GetPageInfo($row['id']);
            // if((isset($tem->TV['kr_route_name']))and($tem->TV['kr_route_name']!=''))
            $obj[]=$tem;
        }
        return $obj;
    }

    /*Иформация о каюте в виде объекта*/
    function GetCautaInfo($cauta_id)
    {
        $obj = new stdClass();
        $cauta=GetPageInfo($cauta_id);
        $obj->nomer=$cauta->TV['k_name'];
        $obj->type=$cauta->TV['k_type'];
        $obj->deck=$cauta->TV['k_deck'];
        $obj->places=$cauta->TV['k_places'];
        $obj->deck=$cauta->TV['k_deck'];
        $obj->inner_id=$cauta->TV['k_inner_id'];
        $obj->id=$cauta->id;
        $cruis_id=$cauta->parent;
        $cruis_price_list=$this->GetCruisPriceList($cruis_id);
        foreach ($cruis_price_list as $price_id=>$price)
        {
            if($obj->type==$price->TV['cr_price_name'])
            {
                $obj->price=$price->TV['cr_price_price'];
                $obj->free_place=$price->TV['cr_price_places_free'];
            }
        }
        return $obj;
    }


    /*==========  ЗАЯВКИ ===============*/

    /*Отправка заявки оператору*/
    /*Обратная связб форма отправка на почту*/
    /*todo письмо оформления круизы*/
    function z_send($z)
    {
        $message = 'Оформление заявки на круиз №'.$z->id."<br>"."<br>";;

        $message .= 'Имя: '.$z->TV['z_user_name']."<br>";
        $message .= 'Фамилия: '.$z->TV['z_user_lastname']."<br>";
        $message .= 'E-mail: '.$z->TV['z_user_email']."<br>";
        // $message .= 'Дата рождения: '.$z->TV['z_user_birthday']."<br>";
        $message .= 'Телефон: '.$z->TV['z_user_phone']."<br>";
        $message .= 'Фамилия: '.$z->TV['z_user_lastname']."<br>";
        $message .= 'Коментарий пользователя: '.$z->TV['z_info']."<br>";

        $message .= 'Теплоход: '.$z->TV['z_ship_name']."<br>";
        $message .= 'Номер круиза: '.$z->TV['z_cruis_id']."<br>";
        $message .= 'Цена: '.$z->TV['z_price']."<br>";
        $message .= 'Сумма: '.$z->TV['z_summa']."<br>";
        $message .= 'Палуба: '.$z->TV['z_deck']."<br>";
        $message .= 'Тип: '.$z->TV['z_type']."<br>";
        $message .= 'Свободные места: '.$z->TV['z_free_place']."<br>";
        $message .= 'Номер брони в инфофлоте: '.$z->TV['z_infoflot_responce']."<br>";



        $subject="berg-kruiz.ru заказ круиза ".$z->TV['z_user_name']." ".$z->TV['z_user_lastname'];
        EscapeString($_GET['m_phone'])." (".EscapeString($_GET['m_name']).")";

        $res['mail'] = $this->SendAdminEmail($subject, $message);
        $message = '
        Уважаемый (ая) '.$z->TV['z_user_lastname'].'  '.$z->TV['z_user_name'].', <br><br>
        спасибо за Вашу покупку круиза на теплоход '.$z->TV['z_ship_name'].'. <br>
        Ваша каюта № '.$z->TV['z_cauta_nomer'].'.<br>
        Мы свяжемся с Вами для уточнения деталей по Вашему круизу. <br>
        Ваш менеджер Елена Чеснокова <b>тел. (846) 205 22 70</b>
        <br>
        <br>
        Соглашение можно скачать по этой <a href="http://berg-kruiz.ru/dogovor_Berg_rechnye_kruizy.doc">ссылке</a>
        ';
        $subject="berg-kruiz.ru спасибо за оформление круиза";
        elex_send_email($z->TV['z_user_email'], $subject, $message);

        $res['error'] = 0;

        require_once 'mainsms.class.php' ;
        $api = new MainSMS ( 'berg-kruiz' , '12b83f711fda5', false, false );
        //$api->sendSMS ( '89608196846' , $_GET['ringName']." ".$_GET['ringPhone'] , 'kolodec');
        $api->sendSMS ( '89879552270' , 'Заявка на круиз '.$z->id,'berg-kruiz');
        $api->sendSMS ( '89170303007' , 'Заявка на круиз '.$z->id,'berg-kruiz');
        $response = $api->getResponse();
        $res['response']=$response;
        $res['balance']=$api->getBalance ();
        return $res;
    }

    function SendSMS($msg)
    {
        $mainPage = GetPageInfo(1);
        $this->adminSMS;

        require_once 'mainsms.class.php' ;
        $api = new MainSMS ( 'berg-kruiz' , '12b83f711fda5', false, false );
        //$api->sendSMS ( '89608196846' , $_GET['ringName']." ".$_GET['ringPhone'] , 'kolodec');
        $api->sendSMS ( '89371782812' , $msg,'berg-kruiz');
        $response = $api->getResponse();
        $res['response']=$response;
        $res['balance']=$api->getBalance ();
        return $res;
    }


    /*Добавить заказ от клиента в базц*/
    function z_add()
    {
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
        $obj->TV['z_deck']=$_GET['deck'];
        $obj->TV['z_type']=$_GET['type'];
        $obj->TV['z_free_place']=$_GET['free_place'];


        $obj->TV['z_user_lastname']=$_GET['LastName'];
        $obj->TV['z_user_name']=$_GET['FirstName'];
        $obj->TV['z_user_birthday']=$_GET['birthday'];
        $obj->TV['z_user_email']=$_GET['Email'];
        $obj->TV['z_user_phone']=$_GET['Phone'];
        $obj->TV['z_info']=$_GET['info'];
        $obj->TV['z_date']=$today;
        $obj->TV['z_status']='Новая';
        $obj->echo=false;

        $obj->pagetitle=substr($obj->TV['z_date'], 0, -9)."_".$obj->TV['z_user_name']."_".$obj->TV['z_ship_id']."_". $obj->TV['z_cruis_id']."_". $obj->TV['z_cauta_nomer']."z_".rand(5, 60);
        //$obj->pagetitle=$ship;

        $obj->alias = encodestring($obj->pagetitle);
        $obj->url="zayavki/" .$obj->alias . ".html";

        $obj->id=IncertPage($obj);
        /*ОТправляем на почт*/
        $obj->TV['mail']=$this->z_send($obj);
        echo json_encode($obj);
    }

    /*Возвращает массив объектов с заявками*/
    function GetZ()
    {

        global $modx;
        global $table_prefix;

        $sql="select * from ".$table_prefix."site_content
        where (parent=".$this->ZayavkaParent.")and(template=".$this->ZayavkaTemplate.")
        order by menuindex desc
        ";
        foreach ($modx->query($sql) as $row)
        {
            $tem=GetPageInfo($row['id']);
            // if((isset($tem->TV['kr_route_name']))and($tem->TV['kr_route_name']!=''))
            $obj[]=$tem;
        }
        return $obj;
    }

    function tplZList()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplZList.php";
    }
    function ZChange()
    {

        $z = GetPageInfo($_POST['z_id']);
        echo "<pre>";
        print_r($z);
        echo "</pre>";
        IncertPageTV($z->id,'z_status',$_POST['z_status'] );
        header('Location: /z-admin.html');

    }

    /*Форма изменения статуса заявки с отправкой почты в зависимости от статуса*/
    function tplZForm()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplZForm.php";
    }

    //выводит список теплоходов
    function tplShips()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplShips.php";
    }


    //Выдает инфу по теплоходу
    function tplShipInfo($ship_id)
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplShipInfo.php";
    }
    /*===================================*/

    function GetZStatusClass($status)
    {

    }

    function tplShipsAdmin()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplShipsAdmin.php";
    }

    function tplZAdminShipEditModal($ship_id)
    {
        include "tpl/tplZAdminShipEditModal.php";
    }

    /*сохраняет в базу из админки популярных круизов*/
    function ZAdminShipUpdate($ship_id)
    {
        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
        /*Проверяем пришло ли обновление картинки*/


        if($_FILES['shipImg']['name']!='')
        {
            $rnd=PassGen();
            $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['shipImg']['name']);
            echo $uploadfile;

            if (move_uploaded_file($_FILES['shipImg']['tmp_name'],$uploadfile)) {
                echo "Файл корректен и был успешно загружен.\n";
                IncertPageTV($ship_id,'t_title_img', '/images/teplohod/'.$rnd.'_'.basename($_FILES['shipImg']['name']) );
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }


        /*Схема теплохода*/
        if($_FILES['t_sh_img']['name']!='')
        {
            $rnd=PassGen();
            $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['t_sh_img']['name']);
            echo $uploadfile;

            if (move_uploaded_file($_FILES['t_sh_img']['tmp_name'],$uploadfile)) {
                echo "Файл корректен и был успешно загружен.\n";
                IncertPageTV($ship_id,'t_sh_img', '/images/teplohod/'.$rnd.'_'.basename($_FILES['t_sh_img']['name']) );
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }

        /*Дополнительные 4 изображения*/
        echo 'Дополнительные 4 изображения';
        for($i=1;$i<5;$i++)
        {
            if($_FILES['t_gl_img_0'.$i]['name']!='')
            {
                $rnd=PassGen();
                $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['t_gl_img_0'.$i]['name']);
                echo $uploadfile;

                if (move_uploaded_file($_FILES['t_gl_img_0'.$i]['tmp_name'],$uploadfile)) {
                    echo "Файл корректен и был успешно загружен.\n";
                    IncertPageTV($ship_id,'t_gl_img_0'.$i, '/images/teplohod/'.$rnd.'_'.basename($_FILES['t_gl_img_0'.$i]['name']) );
                } else {
                    echo "Возможная атака с помощью файловой загрузки!\n";
                }
            }
        }

        /*Хериим все данные об популярных круизах теплохода*/
        $cruis_list=$this->GetShipCruisList($ship_id);
        foreach($cruis_list as $cruis)
        {
            IncertPageTV($cruis->id,'kr_pop','Нет');
        }

        /*перебираем все круизы теплохода*/
        foreach ($_POST as $tt=>$val )
        {
            $tt=explode('_',$tt);
            print_r($tt);
            if($tt[0]=='cruis')
            {
                $cruis_id=$tt[1];
                IncertPageTV($cruis_id,'kr_pop','Да');
            }
        }

        /*Вставляем текст параходик*/
        IncertPageTV($ship_id,'t_description',$_POST['ship-description']);
        IncertPageTV($ship_id,'t_usl',$_POST['t_usl']);
        IncertPageTV($ship_id,'t_teh_xar',$_POST['t_teh_xar']);
        header('Location: /p-admin.html');
        exit;
        echo "</pre>";
    }

    /*Выдает спсок популярных круизов*/
    function GetPopCrus()
    {
        global $modx;
        global $table_prefix;
        $sql='select * from '.$table_prefix.'site_tmplvar_contentvalues CV where
(CV.tmplvarid=(select TV.id from '.$table_prefix.'site_tmplvars TV
where TV.name ="kr_pop"))and(CV.value like "%Да%");';

        $products=Array();
        foreach ($modx->query($sql) as $row)
        {
            $products[] = GetPageInfo($row['contentid']);
        }
        return $products;
    }

    function tplPopCruis()
    {
        global $modx;
        global $table_prefix;
        include "tpl/tplPopCruis.php";
    }

    /*Инфа по круизу для модла редактирования*/
    function GetPopCrusModalInfo($cruis_id)
    {
        $cruis=GetPageInfo($cruis_id);
        $res = Array();
        $res['id']=$cruis->id;
        $res['url']=$cruis->url;
        foreach ($cruis->TV as $TV_name=>$TV_value)
        {
            $res[$TV_name]=$TV_value;
        }
        echo json_encode($res);
    }

    function ZAdminCruisUpdate()
    {
        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);

        $cruis_id=EscapeString($_POST['cruis_id']);
        /*Проверяем пришло ли обновление картинки*/

        if($_FILES['pop_img']['name']!='')
        {
            $rnd=PassGen();
            $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['pop_img']['name']);
            echo $uploadfile;

            if (move_uploaded_file($_FILES['pop_img']['tmp_name'],$uploadfile)) {
                echo "Файл корректен и был успешно загружен.\n";
                IncertPageTV($cruis_id,'pop_img', $this->ship_images_dir.$rnd.'_'.basename($_FILES['pop_img']['name']) );
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }

        for($i=1;$i<7;$i++)
        {
            if($_FILES['kr_slider_img_'.$i]['name']!='')
            {
                $rnd=PassGen();
                $uploadfile = $this->uploaddir . $rnd.'_'.basename($_FILES['kr_slider_img_'.$i]['name']);
                echo $uploadfile;

                if (move_uploaded_file($_FILES['kr_slider_img_'.$i]['tmp_name'],$uploadfile)) {
                    echo "Файл корректен и был успешно загружен.\n";
                    IncertPageTV($cruis_id,'kr_slider_img_'.$i, $this->ship_images_dir.$rnd.'_'.basename($_FILES['kr_slider_img_'.$i]['name']) );
                } else {
                    echo "Возможная атака с помощью файловой загрузки!\n";
                }
            }

        }

        IncertPageTV($cruis_id,'pop_direction',$_POST['pop_direction']);
        IncertPageTV($cruis_id,'pop_title_description',$_POST['pop_title_description']);
        IncertPageTV($cruis_id,'pop_description',$_POST['pop_description']);
        header('Location: /c-admin.html');
    }



    function tplContacts()
    {
        include 'tpl/tplContacts.php';
    }

    /*Выдает список популярных направлений*/
    function GetPopDirectionList()
    {
        $this->popDirection = Array();
        $ships=$this->GetShipsList();
        foreach ($ships as $ship )
        {
            $cruis_list=$this->GetShipCruisList($ship->id);
            foreach ($cruis_list as $cruis )
            {
                if($cruis->TV['kr_pop']=='Да') $this->popDirection[]=$cruis;
            }
        }
    }

    function tplPopDirection()
    {
        include 'tpl/tplPopDirection.php';
    }

    /*Оплата*/
    function Apex()
    {
        include 'tpl/tplApex.php';
    }


    function ApexGetPayStatus()
    {
        /*created — заявка создана, но операции по ней
заявка оплачена, деньги списаны со счета плательщика.
         declined — заявка на оплату отклонена банком.
Например, на карте плательщика не хватает средств. 
        canceled — заявка отменена плательщиком. Например,
была нажата кнопка отмены на странице банка. 
        refunded — деньги возвращены плательщику. reversed —
оплата отменена / отозвана.
         on-payment — идет оплата. Это обычно случается, когда соединение клиента
разорвалось при прохождении оплаты в банке. error — ошибка.
         unknow — пользователь не дошел до
оплаты.*/


        $ApexStatus = Array();
        $ApexStatus['created'] = 'Успешная оплата';
        $ApexStatus['declined'] = 'Отклонено банком';
        $ApexStatus['canceled'] = 'Отменено';
        $ApexStatus['refunded'] = 'Деньги возвращены';
        $ApexStatus['on-payment'] = 'Идет оплата';
        $ApexStatus['unknow'] = 'Вы не дошли до оплаты';
        echo $ApexStatus[$_GET['result']];
    }


    /*Выдает текст соглашения*/
    function Agreement()
    {
        $agree = GetPageInfo($this->AgreementPage);
        return $agree->content;
    }


    /*Форма входа в админку*/
    function  tplAdminLogin()
    {
        include 'tpl/tplAdminLogin.php';
    }

    function isAdminLoginPage()
    {
        if((isset($_SESSION['admin_login']))and($_SESSION['admin_login']==true))
        {

        }
        else
        {
            header('Location: /admin-login/');
            exit;
        }
    }


    /*Главная функция для снипита*/
    function Run($scriptProperties)
    {
        if(isset($scriptProperties['action']))
        {
            if($scriptProperties['action']=='LoadShipsList') $this->LoadShipsList();
            if($scriptProperties['action']=='LoadShipsTours') $this->LoadShipsTours();
            if($scriptProperties['action']=='LoadShipsPhoto') $this->LoadShipsPhoto();
            if($scriptProperties['action']=='LoadCauts') $this->LoadCauts();


            if($scriptProperties['action']=='tplShipsList') $this->tplShipsList();
            if($scriptProperties['action']=='tplBron') $this->tplBron();

            if($scriptProperties['action']=='tplSearchForm') $this->tplSearchForm();
            if($scriptProperties['action']=='GetShipsCityList') echo $this->GetShipsCityList();
            if($scriptProperties['action']=='ajax') $this->Ajax();
            if($scriptProperties['action']=='ZChange') $this->ZChange();
            if($scriptProperties['action']=='tplZForm') $this->tplZForm();

            if($scriptProperties['action']=='tplZList') $this->tplZList();
            if($scriptProperties['action']=='tplShips') $this->tplShips();
            if($scriptProperties['action']=='tplShipInfo') $this->tplShipInfo($scriptProperties['ship_id']);
            if($scriptProperties['action']=='tplShipsAdmin') $this->tplShipsAdmin();
            if($scriptProperties['action']=='tplPopCruis') $this->tplPopCruis();

            if($scriptProperties['action']=='tplContacts') $this->tplContacts();
            if($scriptProperties['action']=='GetHeadTV') $this->GetHeadTV($scriptProperties['tv']);
            if($scriptProperties['action']=='tplPopDirection') $this->tplPopDirection();

            if($scriptProperties['action']=='Apex') $this->Apex();
            if($scriptProperties['action']=='ApexGetPayStatus') $this->ApexGetPayStatus();


            if($scriptProperties['action']=='tplAdminLogin') $this-> tplAdminLogin();
            if($scriptProperties['action']=='isAdminLoginPage') $this-> isAdminLoginPage();
        }
    }

    /*Обратная связб форма отправка на почту*/
    function CallBackSendMainPage()
    {
        $m_name = EscapeString($_GET['m_name']);
        $m_phone = EscapeString($_GET['m_phone']);

        $message = '
        <h3>Сообщение с обратной связи</h3>
        <h4>Имя: '.EscapeString($_GET['m_name']).'</h4>
        <h4>Номер: '.EscapeString($_GET['m_phone']).'</h4> ';

        $from ='shop@berg-kruiz.ru'; // от кого
        $mailheaders = "Content-type:text/html;charset=utf8;From:".$from;

        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $mailheaders= "-fMIME-Version: 1.0\r\n";
        $mailheaders .= "Content-type: text/html; charset=utf8\r\n";

        /* дополнительные шапки */
        $mailheaders .= "From: berg-kruiz.ru <".$from.">\r\n";
        $subject="berg-kruiz.ru заказ звонка на номер ".
            EscapeString($_GET['m_phone'])." (".EscapeString($_GET['m_name']).")";

        $res['mail'] = $this->SendAdminEmail($subject, $message, $mailheaders);

        $res['error'] = 0;
        echo json_encode($res);
    }


    /*Обратная связб форма отправка на почту*/
    function CallBackSendWaterRent()
    {
        $r_phone = EscapeString($_GET['r_phone']);

        $message = '
        <h3>Сообщение с обратной связи аренда водного транспорта</h3>
        <h4>Имя: '.$r_phone.'</h4>';

        $from ='shop@berg-kruiz.ru'; // от кого
        $mailheaders = "Content-type:text/html;charset=utf8;From:".$from;

        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $mailheaders= "-fMIME-Version: 1.0\r\n";
        $mailheaders .= "Content-type: text/html; charset=utf8\r\n";

        /* дополнительные шапки */
        $mailheaders .= "From: berg-kruiz.ru <".$from.">\r\n";


        $subject="berg-kruiz.ru аренда водного транспорта".
            $r_phone;

        $res['mail'] = $this->SendAdminEmail($subject, $message, $mailheaders);

        $res['error'] = 0;
        echo json_encode($res);
    }

    /*Обратная связб форма отправка на почту*/
    function CallBackSendContakts()
    {
        $cf_name = EscapeString($_GET['cf_name']);
        $cf_phone = EscapeString($_GET['cf_phone']);

        $message = '
        <h3>Сообщение с обратной связи аренда водного транспорта</h3>
        <h4>Имя: '.$cf_phone.'</h4>  '.
        '<h4>Телефон: '.$cf_name.'</h4>  ';

        $from ='shop@berg-kruiz.ru'; // от кого
        $mailheaders = "Content-type:text/html;charset=utf8;From:".$from;

        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $mailheaders= "-fMIME-Version: 1.0\r\n";
        $mailheaders .= "Content-type: text/html; charset=utf8\r\n";

        /* дополнительные шапки */
        $mailheaders .= "From: berg-kruiz.ru <".$from.">\r\n";

        $subject="berg-kruiz.ru Заказать звонок менеджера".
            $cf_phone.' '.$cf_name;

        $res['mail'] = $this->SendAdminEmail($subject, $message, $mailheaders);

        $res['error'] = 0;
        echo json_encode($res);
    }
    function GetHeadTV($tv)
    {
        $mainPage = GetPageInfo(1);
        echo $mainPage->TV[$tv];
    }

    /*Отправляет почту всем админам указанных в adminEmail */
    function SendAdminEmail($subject, $message)
    {
        $res = Array();
        foreach($this->adminEmail as $email)
        {
            $res[] = elex_send_email($email, $subject, $message);

        }
        return $res;
    }

    function SendUserEmail($user,$subject, $message)
    {
        elex_send_email($user, $subject, $message);
    }


    /*получение списка мест в каюте*/
    function LoadCautaPalces()
    {
        /*http://api.infoflot.com/JSON/a04a83e5ccb19b661c4c0873d3234287982fb5d3/Requests/280843?cabins[491331]=323*/
        $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Requests/'.$_GET['z_cruis_inner_id'].'?';

    }


    /*Отправка заявки в инфофлот*/
    function InfoflotSend()
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
        $url='http://api.infoflot.com/JSON/'.$this->shipKey.'/Requests/'.$_GET['z_cruis_inner_id'].'?';
        $url.='cabins['.$_GET['z_cauta_inner_id'].']=';
        $url.='&places['.$_GET['z_place'].'][name]=Турист';
        $url.='&places['.$_GET['z_place'].'][type]=0';
        $url.='&places['.$_GET['z_place'].'][birthday]='.$_GET['z_user_birthday'];
        $url.='&submit=1';
        $url.='&customer='.$_GET['z_user_name'].'%20'.$_GET['z_user_lastname'];
        $url.='&email='.$_GET['z_user_email'];
        $url.='&phone='.$_GET['z_user_phone'];
        $res['url']=$url;
        $res['get']=$_GET;
        $res['infoflot']=file_get_contents($url);
        echo json_encode($res);
    }

    /*Событие входа в админку*/
    function zLoginForm()
    {
        $mainPage=GetPageInfo(1);
        $res['status']=0;
        if(($_GET['z_admin_login']==$mainPage->TV['admin_login'])and($_GET['z_admin_password']==$mainPage->TV['admin_password']))
        {
            $_SESSION['admin_login']=true;
            $res['status']='done';
        }
        else
        {
            unset($_SESSION['admin_login']);
        }
        echo json_encode($res);

    }

    function AdminLogout()
    {
        unset($_SESSION['admin_login']);
        $res['status']='done';
        echo json_encode($res);
    }


    /*ID каюты по ее номеру*/
    function GetCautaIdByNumber($cruiz_id,$cauta_number)
    {
        //$cruiz=GetPageInfo($cruiz_id);
        $cauta_id=0;
        $cauta_list=GetChildList($cruiz_id,$this->CautaTemplate);
        foreach ($cauta_list as $cauta)
        {
            if($cauta->TV['k_name']==$cauta_number) {
                $cauta_id=$cauta->id;
                break;
            }
        }
        return $cauta_id;
    }

    /*Ajax - вывод*/
    function Ajax()
    {
        if(isset($_GET['action']))
        {
            if ($_GET['action'] == 'GetShipsCityList')
            {
                echo $this->GetShipsCityList();
            }
            elseif ($_GET['action'] == 'Search')
            {
                $this->Search();
            }
            elseif ($_GET['action'] == 'z_add')
            {
                $this->z_add();
            }
            elseif ($_GET['action'] == 'tplZForm')
            {
                $this->tplZForm();
            }
            elseif ($_GET['action'] == 'tplZAdminShipEditModal')
            {
                $this->tplZAdminShipEditModal(EscapeString($_GET['ship_id']));
            }
            elseif ($_GET['action'] == 'GetPopCrusModalInfo')
            {
                $this->GetPopCrusModalInfo(EscapeString($_GET['cruis_id']));
            }
            elseif ($_GET['action'] == 'CallBackSendMainPage')
            {
                $this->CallBackSendMainPage();
            }
            elseif ($_GET['action'] == 'CallBackSendWaterRent')
            {
                $this->CallBackSendWaterRent();
            }
            elseif ($_GET['action'] == 'CallBackSendContakts')
            {
                $this->CallBackSendContakts();
            }
            elseif ($_GET['action'] == 'SendReview')
            {
                $r = new reviews();
                $r->SendReview();
            }
            elseif ($_GET['action'] == 'LoadCautaPalces')
            {
               $this->LoadCautaPalces();
            }
            elseif ($_GET['action'] == 'InfoflotSend')
            {
               $this->InfoflotSend();
            }
            elseif ($_GET['action'] == 'zLoginForm')
            {
               $this->zLoginForm();
            }
            elseif ($_GET['action'] == 'AdminLogout')
            {
               $this->AdminLogout();
            }
        }
        else
        {
            print_r($_POST);
            if(isset($_POST['action']))
            {
                if($_POST['action']=='ZAdminShipUpdate')
                {
                    $this->ZAdminShipUpdate(EscapeString($_POST['ship_id']));
                }
                elseif($_POST['action']=='ZAdminCruisUpdate')
                {
                    $this->ZAdminCruisUpdate();
                }
                elseif($_POST['action']=='ZChange') $this->ZChange();
            }
        }

    }



}