<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.04.2016
 * Time: 16:43
 */


class ILoader extends Ship
{
    public $modx;

    function __construct()
    {
        global $modx;
        $this->modx = &$modx;

    }
    /*выдает круиз по его внутреннему номеру*/
    /**/
    function GetCruisByInnerID($ship_id, $cruis_inner_id)
    {
        $res = 0;
        /*Получаем список круизов теплохода*/
        $cruis_list = $this->GetShipCruisList($ship_id);
        /*Ищем наш круиз*/
        foreach ($cruis_list as $cruis) {
            if ($cruis->TV['kr_inner_id'] == $cruis_inner_id) {
                $res = $cruis->id;
                break;
            }
        }
        return $res;
    }

    /*Вставляет куриз в базу не проверяя есть ли такой*/
    public function IncertCruis($ship, $cruis)
    {
        $obj = new stdClass();

        $obj->pagetitle = $cruis['id'] . "_" . $cruis['name'];
        $obj->parent = $ship->id;
        $obj->template = $this->CruisTemplate;
        $obj->TV['kr_name'] = $cruis['name'];
        $obj->TV['kr_inner_id'] = $cruis['id'];
        $obj->TV['kr_date_start'] = $cruis['date_start'];
        $obj->TV['kr_date_end'] = $cruis['date_end'];
        $obj->TV['kr_nights'] = $cruis['nights'];
        $obj->TV['kr_days'] = $cruis['days'];
        $obj->TV['kr_weekend'] = 0;
        if ($cruis['weekend']) $obj->TV['kr_weekend'] = 1;
        //$obj->TV['kr_weekend']=$cruis['weekend'];
        $obj->TV['kr_cities'] = $cruis['cities'];
        $obj->TV['kr_route'] = $cruis['route'];
        $obj->TV['kr_route_name'] = $cruis['route_name'];
        $obj->TV['kr_region'] = $cruis['region'];
        $obj->TV['kr_river'] = $cruis['river'];
        $obj->TV['kr_surchage_meal_rub'] = $cruis['surchage_meal_rub'];
        $obj->TV['kr_surcharge_excursions_rub'] = $cruis['surcharge_excursions_rub'];
        $obj->echo = true;
        $obj->alias = encodestring($obj->pagetitle);
        $obj->url = "ships/" . $ship->alias . "/" . $obj->alias . ".html";
        $cruis_alias = $obj->alias;
        //print_r($obj);

        $cruis_id = IncertPage($obj);
        $cruis_inner_id = $obj->TV['kr_inner_id'];
        /*Вставляем цены*/

        //Обновляем города
        /*   $tmp=explode(' – ',$obj->TV['kr_cities']);
           foreach($tmp as $city)
           {
               $cities[$city]=1;
           }*/


        /*Нужен выделенный сервер чтобы проставить таймауты*/
        echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                ";
        echo "Цены
                ";
        foreach ($cruis['prices'] as $price_id => $price) {
            $obj2 = new stdClass();

            $obj2->pagetitle = $cruis['name'] . "_" . $price_id . "_" . $price['name'];
            $obj2->parent = $cruis_id;
            $obj2->template = $this->PriceTemplate;
            $obj2->TV['cr_price_name'] = $price['name'];
            $obj2->TV['cr_price_price_eur'] = $price['price_eur'];
            $obj2->TV['cr_price_price'] = $price['price'];
            $obj2->TV['cr_price_price_usd'] = $price['price_usd'];
            $obj2->TV['cr_price_places_total'] = $price['places_total'];
            $obj2->TV['cr_price_places_free'] = $price['places_free'];

            $obj2->alias = encodestring($obj2->pagetitle);
            $obj2->url = "ships/" . $ship->alias . "/" . $cruis_alias . "/" . $obj2->alias . ".html";
            //  print_r($obj2);
            IncertPage($obj2);
        }
    }

    /*Информация о каюте с инфофлота*/
    function GetCautaInfoInfoflot($ship_inner_id,$cruis_inner_id,$cauta_nomer)
    {
        $URL='http://api.infoflot.com/JSON/'.
            $this->shipKey.'/CabinsStatus/'.$ship_inner_id.'/'.$cruis_inner_id."/";

        $cauta_list=json_decode(file_get_contents($URL), true);
        $obj = new stdClass();
        $obj->responce=$cauta_list;
        $obj->id=0;
        $obj->url=$URL;
        foreach($cauta_list as $id=>$cauta)
        {
            if($cauta_nomer==$cauta['name'])
            {
                $obj->nomer=$cauta['name'];
                $obj->type=$cauta['type'];
                $obj->deck=$cauta['deck'];
                $obj->separate=$cauta['separate'];
                $obj->status=$cauta['status'];
                $obj->gender=$cauta['gender'];
                $obj->id=$id;
                $obj->places=$cauta['places'];
                /* foreach($places as $place_id=>$place)
                 {
                     $obj->places.="ID:".$place_id."-NAME:".$place['name']."-TYPE:".$place['type']."-POSITION:".$place['position']."-STATUS:".$place['status']."||";
                 }*/
            }
        }
        return $obj;
    }


    public function UpdateCruis($cruis_id, $ship, $cruis)
    {

        /*Обновляются только TV*/
        $obj = new stdClass();

        $obj->pagetitle = $cruis['id'] . "_" . $cruis['name'];
        $obj->parent = $ship->id;
        $obj->template = $this->CruisTemplate;
        $obj->TV['kr_name'] = $cruis['name'];
        $obj->TV['kr_inner_id'] = $cruis['id'];
        $obj->TV['kr_date_start'] = $cruis['date_start'];
        $obj->TV['kr_date_end'] = $cruis['date_end'];
        $obj->TV['kr_nights'] = $cruis['nights'];
        $obj->TV['kr_days'] = $cruis['days'];
        $obj->TV['kr_weekend'] = 0;
        if ($cruis['weekend']) $obj->TV['kr_weekend'] = 1;
        //$obj->TV['kr_weekend']=$cruis['weekend'];
        $obj->TV['kr_cities'] = $cruis['cities'];
        $obj->TV['kr_route'] = $cruis['route'];
        $obj->TV['kr_route_name'] = $cruis['route_name'];
        $obj->TV['kr_region'] = $cruis['region'];
        $obj->TV['kr_river'] = $cruis['river'];
        $obj->TV['kr_surchage_meal_rub'] = $cruis['surchage_meal_rub'];
        $obj->TV['kr_surcharge_excursions_rub'] = $cruis['surcharge_excursions_rub'];
        $obj->echo = true;

        $cruis_alias = $obj->alias;


        /*Блять!*/
        //$cruis_id=IncertPage($obj);


        foreach ($obj->TV as $tv_name => $tv_value) {
            IncertPageTV($cruis_id, $tv_name, $tv_value);
            echo "UPDATE ", $tv_name . "=" . $tv_value . " \r\n";
        }

        /*ТЕперь обновляем цены*/
        /*Загружаем все цены круиза*/


        $prices_modx = GetChildList($cruis_id,$this->CruisPriceTemplate);

        echo "PRICES================" ." \r\n";
        foreach ($cruis['prices'] as $price_id => $price) {
        {

            foreach ($prices_modx as $price_modx)
            {

                //*Если название цены совпадает то обновляем цену*/

                if($price_modx->TV['cr_price_name']==$price['name'])
                {

                    IncertPageTV($price_modx->id, 'cr_price_price_eur', $price['price_eur']);
                    IncertPageTV($price_modx->id, 'cr_price_price', $price['price']);
                    IncertPageTV($price_modx->id, 'cr_price_price_usd',  $price['price_usd']);
                    IncertPageTV($price_modx->id, 'cr_price_places_total',  $price['places_total']);
                    IncertPageTV($price_modx->id, 'cr_price_places_free',  $price['places_free']);
                    echo 'cr_price_name^ ='.$price_modx->TV['cr_price_name']." = ".$price['price']." \r\n";
                }

            }

        }
            /*k_type=cr_price_name*/
            /*Ищем каюту для цены*/

        }
    }



    /*Получает список круизов с инфофлота*/
    public function InfoflotGetCruisList($ship_inner_id)
    {
        global $shipKey;
        $URL = 'http://api.infoflot.com/JSON/' . $this->shipKey . '/Tours/' . $ship_inner_id . '/';
        $cruis_list = json_decode(file_get_contents($URL), true);
        $objects=[];
        foreach ($cruis_list as $id => $cruis)
        {
            /*Запихиваем все в $objects*/
            $obj = new stdClass();
            $obj->inner_id = $id;
            $obj->ship_inner_id = $ship_inner_id;
            $obj->TV['kr_name'] = $cruis['name'];
            $obj->TV['kr_inner_id'] = $id;
            $obj->TV['kr_date_start'] = $cruis['date_start'];
            $obj->TV['kr_date_end'] = $cruis['date_end'];
            $obj->TV['kr_nights'] = $cruis['nights'];
            $obj->TV['kr_days'] = $cruis['days'];
            $obj->TV['kr_weekend'] = 0;
            if ($cruis['weekend']) $obj->TV['kr_weekend'] = 1;
            $obj->TV['kr_cities'] = $cruis['cities'];
            $obj->TV['kr_route'] = $cruis['route'];
            $obj->TV['kr_route_name'] = $cruis['route_name'];
            $obj->TV['kr_region'] = $cruis['region'];
            $obj->TV['kr_river'] = $cruis['river'];
            $obj->TV['kr_surchage_meal_rub'] = $cruis['surchage_meal_rub'];
            $obj->TV['kr_surcharge_excursions_rub'] = $cruis['surcharge_excursions_rub'];
            /*ID объектра равен внутреннему id круиза*/
            $objects[$id]=$obj;
        }
        return $objects;
    }

    /*удаляет из нашей базы круизы которых нету в иинфофлоте*/
    public function RebaceCruis()
    {
        global $shipKey;

        $ships = $this->GetShipsList();
        /*Перебераем этот список корабельков*/
        foreach ($ships as $key => $ship)
        {
            /*Получаем список круизов теплохода*/
            $CruisList=$this->GetShipCruisList($ship->id);
            /*Список круизов Инфофлота*/
            $InfoflotCruisList = $this->InfoflotGetCruisList($ship->TV['t_inner_id']);
            /*Перебераем их и сравниваем с базой инфофлота*/
            foreach($CruisList as $cruis)
            {
                if(isset($InfoflotCruisList[$cruis->TV['kr_inner_id']]))
                {
                   // echo $ship->title." cruis inner_id = ".$cruis->TV['kr_inner_id'].' status = EXIST'."\n";
                }
                else
                {
                    echo $ship->title." cruis inner_id = ".$cruis->TV['kr_inner_id'].' status = DELETED'."\n";
                    PageDelete($cruis->id);
                }
            }
        }
    }

    /*выдает каюту по внутреннему номеру*/
    function GetCautaByItterID($crus_id,$cauta_inner_id)
    {
        $res= new stdClass();
        $res->id=0;
        $modx_cauta_list=$this->GetCautaList($crus_id);
        foreach($modx_cauta_list as $cauta)
        {
            if($cauta->TV['k_inner_id']==$cauta_inner_id) $res=$cauta;
        }
        return $res;
    }

    function UpdateCauts()
    {
        global $modx;
        global $table_prefix;
        global $shipKey;
        /*ТЕпере обновляем каюты*/

        $ships=$this->GetShipsList();
        /*Перебераем этот список*/
        foreach($ships as $key=>$ship)
        {
            echo "********// ship ".$ship->title."\r\n";
            $modx_cruis_list=$this->GetShipCruisList($ship->id);
            foreach($modx_cruis_list as $modx_cruis)
            {


                $URL='http://api.infoflot.com/JSON/'.
                    $this->shipKey.'/CabinsStatus/'.$ship->TV['t_inner_id'].'/'.$modx_cruis->TV['kr_inner_id']."/";

                $cauta_list=json_decode(file_get_contents($URL), true);

                foreach($cauta_list as $id=>$cauta)
                {
                    echo "********* cauta".$cauta['name']."\r\n";
                    $obj = new stdClass();
                    $obj->pagetitle=$ship->alias."-".$modx_cruis->alias."-".$id."_".$cauta['name'];
                    $obj->parent=$modx_cruis->id;
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
                    $obj->url="ships/".$ship->alias."/".$modx_cruis->alias."/" . $obj->alias.".html";

                    //  echo "=================== Каюта \r\n";
                    // print_r($obj);

                    $mod_cauta=$this->GetCautaByItterID($modx_cruis->id,$id);
                    if($mod_cauta->id==0)
                    {
                        /*Нет такой каюты*/
                        IncertPage($obj);
                        echo $ship->title." INSERT CAUTA ".$id;
                    }
                    else
                    {
                        IncertPageTV($mod_cauta->id, 'k_name', $obj->TV['k_name']);
                        IncertPageTV($mod_cauta->id, 'k_type', $obj->TV['k_type']);
                        IncertPageTV($mod_cauta->id, 'k_deck', $obj->TV['k_deck']);
                        IncertPageTV($mod_cauta->id, 'k_separate', $obj->TV['k_separate']);
                        IncertPageTV($mod_cauta->id, 'k_status', $obj->TV['k_status']);
                        IncertPageTV($mod_cauta->id, 'k_gender', $obj->TV['k_gender']);
                        IncertPageTV($mod_cauta->id, 'k_places', $obj->TV['k_places']);
                        IncertPageTV($mod_cauta->id, 'k_inner_id', $obj->TV['k_inner_id']);
                        echo $ship->title." UPDATE CAUTA ".$id;
                    }
                    // $cruis_inner_id=$obj->TV['kr_inner_id'];
                }
            }
        }
    }

    //Загрузка туров для теплохода
    function LoadShipsTours()
    {
        global $shipKey;

        $Ships = $this->GetShipsList();

        /*Перебераем этот список*/
        foreach ($Ships as $key => $Ship) {
            echo "Корбель \r\n";
            /*Загружаем список круизов для теплохода*/
            $URL = 'http://api.infoflot.com/JSON/' . $this->shipKey . '/Tours/' . $Ship->TV['t_inner_id'] . '/';
            echo $URL . "<br>";
            $cruis_list = json_decode(file_get_contents($URL), true);
            /*Перебираем этот список*/

            foreach ($cruis_list as $id => $cruis) {
                //echo $cruis['name']."\r\n";
                ob_flush();
                flush(); //ie working must

                $cruis['id'] = $id;
                $cruis['ship_id'] = $Ship->id;

                /* 1 сверка с нашей базой*/
                /*Проверяем есть ли такой круиз в нашей базе*/
                $cruis_info = $this->GetCruisByInnerID($Ship->id, $id);
                if ($cruis_info == 0) {
                    $this->IncertCruis($Ship, $cruis);
                    echo "SHIP = " . $Ship->title . " Cruis inner_id=" . $cruis['id'] . " status = INCERT \r\n";
                } else {
                    $this->UpdateCruis($cruis_info, $Ship, $cruis);
                    echo "SHIP = " . $Ship->title . " Cruis inner_id=" . $cruis['id'] . "  status = UPDATE \r\n";
                }



            }
        }
        $this->RebaceCruis();
    }
}