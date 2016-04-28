<?php $ship=$this->GetShipInfo($ship_id); ?>
<div class="poph1"><?php echo $ship->title; ?></div>
<div class="shippage">
    <div class="shippageinfo">
        <div class="cruissliderbox">
            <div data-animation="slide" data-duration="500" data-infinite="1"
                 class="w-slider w-hidden-small w-hidden-tiny cruisslider">
                <div class="w-slider-mask">
                    <div class="w-slide crsliderslide" style="transform: translateX(0px); opacity: 1;">
                        <?php
                        for($i=1;$i<5;$i++)
                        {
                            ?>
                            <img src="<?php echo $ship->TV['t_gl_img_0'.$i];  ?>" class="cruissliderimg">
                        <?php

                        }
                        ?>

                    </div>
                    <div class="w-slide" style="transform: translateX(0px); opacity: 1;"></div>
                </div>
                <div class="w-slider-arrow-left">
                    <div class="w-icon-slider-left crsliderarrowleft"></div>
                </div>
                <div class="w-slider-arrow-right">
                    <div class="w-icon-slider-right crslidearroright"></div>
                </div>
                <div class="w-slider-nav w-round">
                    <div class="w-slider-dot w-active" data-wf-ignore=""></div>
                    <div class="w-slider-dot" data-wf-ignore=""></div>
                </div>
            </div>
            <div data-animation="slide" data-duration="500" data-infinite="1"
                 class="w-slider w-hidden-main w-hidden-medium cruisslidermobile">
                <div class="w-slider-mask">
                    <?php
                    for($i=1;$i<5;$i++)
                    {
                        ?>
                        <div class="w-slide cruisslderslidemobile">
                            <img src="<?php echo $ship->TV['t_gl_img_0'.$i];  ?>" class="crsmobsliderimg">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="w-slider-arrow-left">
                    <div class="w-icon-slider-left crsliderarrowleft"></div>
                </div>
                <div class="w-slider-arrow-right">
                    <div class="w-icon-slider-right crslidearroright"></div>
                </div>
                <div class="w-slider-nav w-round"></div>
            </div>
        </div>

        <!--
        <div class="bron_ship_sh" style="margin-top: 20px">
            <a href="#" class="w-lightbox w-inline-block">
                <img class="bron_t_sh" src="<?php  echo $ship->TV['t_sh_img']; ?>">
                <script type="application/json" class="w-json">{
                        "items": [
                            {
                                "url": "<?php  echo $ship->TV['t_sh_img']; ?>",
                                "fileName": "11.jpg",
                                "origFileName": "1.jpg",
                                "width": 472,
                                "height": 473,
                                "size": 108711,
                                "caption": "<?php  echo $ship->title; ?>",
                                "type": "image"
                            }
                        ]
                    }</script>
            </a>
        </div>
        -->
        <div class="shippagedescriptiion">
            <?php echo $ship->TV['t_description'];  ?>


            <a href="#" class="w-lightbox cruisformcommitbutton t_sh_img">Схема теплохода<script type="application/json" class="w-json">{
                        "items": [
                            {
                                "url": "<?php  echo $ship->TV['t_sh_img']; ?>",
                                "fileName": "11.jpg",
                                "origFileName": "1.jpg",
                                "width": 472,
                                "height": 473,
                                "size": 108711,
                                "caption": "<?php  echo $ship->title; ?>",
                                "type": "image"
                            }
                        ]
                    }</script></a>
        </div>
        <div class="shippageparametrbox">
            <div class="sppramleft"><h3 class="spparamtitle">К услугам туристов на борту</h3>
                <div class="spparamleftdsk">
                    <?php
                    $ship->TV['t_teh_xar'];
                    $th = explode(';',$ship->TV['t_usl']);
                    $i=1;
                    foreach($th as $t)
                    {
                        if(strlen($t)>4)
                        {
                            ?>
                    <div class="spparamitem"><span class="spitemdeskrspan"><?php echo $i; ?> | </span><?php echo $t; ?></div>
                            <?php
                            $i++;
                        }
                    }
                    ?>

                </div>
            </div>
            <div class="spparamboxright"><h3 class="sptehhartitle">Технические<br>характиристики</h3>
                <ul class="sptexlist">
                    <?php
                    $ship->TV['t_teh_xar'];
                    $th = explode(';',$ship->TV['t_teh_xar']);
                    $i=1;
                    foreach($th as $t)
                    {
                        if(strlen($t)>4)
                        {
                            ?>
                            <li class="splistitem"><?php echo $t; ?></li>
                            <?php
                            $i++;
                        }
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="poph1 ship1">Круизы со свободными местами</div>
    <?php
    $sql_cruis="SELECT * FROM
".$table_prefix."site_content kr
WHERE (kr.parent = ".$ship_id.")and(kr.template=".$this->CruisTemplate.")
and(kr.deleted=0)
" ;


    $qq=$modx->query($sql_cruis);

    foreach ($qq as $row)
    {


        $cruis=GetPageInfo($row['id']);
        $ship=GetPageInfo($cruis->parent);
        $prices=$this->GetCruisPriceList($cruis->id);

        //Вычисляем паддинг вывода маршрута
        $route_padding='padding-top: 26px;';
        if(mb_strlen($cruis->TV['kr_route'],'UTF-8')>30)
        {
            $route_padding='padding-top: 13px;';
        }


        //Убераем * из маршрута
        $cruis->TV['kr_route']=str_replace('*','' ,$cruis->TV['kr_route']);

        //день/дней
        $days_text='дней';
        $cruis->TV['kr_days']= $cruis->TV['kr_days']+0;
        if( $cruis->TV['kr_days']==1) $days_text='день';
        if(in_array($cruis->TV['kr_days'],array(2,3,4)) ) $days_text='дня';

        //ночь/ночей
        $night_text='ночей';
        $cruis->TV['kr_nights']= $cruis->TV['kr_nights']+0;
        if( $cruis->TV['kr_nights']==1) $night_text='ночь';
        if(in_array($cruis->TV['kr_nights'],array(2,3,4)) ) $night_text='ночи';



        ?>



        <!-- NEW 2 --->
        <div class="w-clearfix resultitem">
            <div class="sitemimg"  style='background-image: linear-gradient(rgba(0, 92, 255, 0.52) 5%, rgba(255, 255, 255, 0)), url("<?php echo $ship->TV['t_title_img']; ?>");'>Круиз на теплоходе<br>"
                <?php echo $ship->TV['t_title']; ?>"</div>
            <div class="sicenter">
                <div class="sicentertop">
                    <div class="sicentertexttop"><?php echo $cruis->TV['kr_route']; ?></div>
                </div>
                <div class="siteminfoblocktext"><?php echo $cruis->TV['kr_date_start']; ?> - <?php echo $cruis->TV['kr_date_end']; ?><br><span
                        class="sitemdn"><?php echo $cruis->TV['kr_days']; ?> <?php echo $days_text; ?> / <?php echo $cruis->TV['kr_nights']; ?> <?php echo $night_text; ?></span><br>
                    <span class="sitemroute"><?php echo $cruis->TV['kr_cities']; ?></span>
                </div>
            </div>
            <div class="w-clearfix siright">
                <div class="w-clearfix sitemtophead">
                    <div class="sitemteopheadkauta">каюта</div>
                    <div class="sitemheadmest">мест</div>
                    <div class="sitemheadstoimost">стоимость</div>
                </div>
                <div class="sitemtable">
                    <?php
                    $hover=true;
                    foreach($prices as $price)
                    {
                        if((isset($price->TV['cr_price_places_free']))and($price->TV['cr_price_places_free']!=''))
                        {
                            ?>
                            <div class="w-clearfix sitableitem <?php
                            $hover = !$hover;
                            if($hover) echo ' hov';
                            ?>">
                                <div class="sitablekauta"><?php echo $price->TV['cr_price_name']; ?></div>
                                <div class="sitablemest"><?php echo $price->TV['cr_price_places_free']; ?></div>
                                <div class="sitablestoimost"><?php echo $price->TV['cr_price_price']; ?></div>
                            </div>

                            <?php
                        }

                    }
                    ?>

                </div>
                <div class="sirightbutton1">
                    <a class="siteminfoa" href="/bronirovanie.html?cruis_id=<?php echo $cruis->id; ?>"><div class="sitembutton">Забронировать</div></a>
                </div>
            </div>
        </div>



        <?php
    }


    ?>
</div>

