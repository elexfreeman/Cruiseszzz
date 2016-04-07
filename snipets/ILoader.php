<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.04.2016
 * Time: 16:43
 */
class ILoader extends Ship
{
    /*выдает круиз по его внутреннему номеру*/
    /**/
    function GetCruisByInnerID($ship_id,$cruis_inner_id)
    {
        $res=0;
        /*Получаем список круизов теплохода*/
        $cruis_list = $this->$this->GetShipCruisList($ship_id);
        /*Ищем наш круиз*/
        foreach ($cruis_list as $cruis)
        {
            if($cruis->TV['kr_inner_id']==$cruis_inner_id)
            {
                $res =  $cruis->id;
                break;
            }
        }
        return $res;
    }

    /*Вставляет куриз в базу не проверяя есть ли такой*/
    public function IncertCruis($ship,$cruis)
    {
        $obj = new stdClass();

        $obj->pagetitle=$cruis['id']."_".$cruis['name'];
        $obj->parent=$ship->id;
        $obj->template=$this->CruisTemplate;
        $obj->TV['kr_name']=$cruis['name'];
        $obj->TV['kr_inner_id']=$cruis['id'];
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
        $obj->url="ships/".$ship->alias."/".$obj->alias . ".html";
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
            $obj2->url="ships/".$ship->alias."/".$cruis_alias."/".$obj2->alias . ".html";
            //  print_r($obj2);
            IncertPage($obj2);
        }
    }

    public function UpdateCruis($ship,$cruis)
    {

    }

    //Загрузка туров для теплохода
    function LoadShipsTours()
    {
        global $shipKey;
        echo "<pre>";

        $Ships=$this->GetShipsList();

        /*Перебераем этот список*/
        foreach($Ships as $key=>$Ship)
        {
            echo "Корбель \r\n";
            /*Загружаем список круизов для теплохода*/
            $URL='http://api.infoflot.com/JSON/'.$this->shipKey.'/Tours/'.$Ship->TV['t_inner_id'].'/';
            echo $URL."<br>";
            $cruis_list=json_decode(file_get_contents($URL), true);
            /*Перебираем этот список*/

            foreach($cruis_list as $id=>$cruis)
            {
                //echo $cruis['name']."\r\n";
                ob_flush();
                flush(); //ie working must

                $cruis['id']=$id;
                $cruis['ship_id']=$Ship->id;

                /* 1 сверка с нашей базой*/
                /*Проверяем есть ли такой круиз в нашей базе*/
                $cruis_info=$this->GetCruisByInnerID($Ship->id,$id);
                if($cruis_info==0)
                {
                    //$this->IncertCruis($Ship,$cruis);
                    echo "Cruis inner_id=".$cruis['id'].' status = INCERT';
                }
                else
                {
                   // $this->UpdateCruis($Ship,$cruis);
                    echo "Cruis inner_id=".$cruis['id'].' status = UPDATE';
                }
                /*Нужно еще удалить тех что нет в базе инфлота*/
                /* 2 сверка с базой нифофлота*/





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
     /*   foreach($cities as $city=>$val)
        {
            $obj2 = new stdClass();

            $obj2->pagetitle=$city;
            $obj2->parent=$this->CityParent;
            $obj2->template=$this->CityTemplate;
            IncertPage($obj2);
        }*/
        echo "</pre>";
    }
}