<?php
//Чтобы забронировать или выкупить каюту - нажмите на нее
?>
<script>
    $(function() {
        Scroll("cruis-container");
    });
</script>

<div class="cruiscontainer" >
    <div class="cruissliderbox"
        <?php
        if($data['cruis']->TV['kr_slider_img_1']=='') echo 'style="display:none"';
        ?>
        >

        <div data-animation="slide" data-duration="500" data-infinite="1"
             class="w-slider w-hidden-small w-hidden-tiny cruisslider">
            <div class="w-slider-mask">

                <div class="w-slide crsliderslide" style="transform: translateX(0px); opacity: 1;">

                    <?php
                        for($i=1;$i<4;$i++)
                        {
                            ?>
                            <a href="#" class="w-lightbox w-inline-block">
                                <img src="<?php echo tmbImg( $data['cruis']->TV['kr_slider_img_'.$i],'&w=700&zc=1'); ?>" class="cruissliderimg">
                                <script type="application/json" class="w-json">{
                                        "items": [
                                            {
                                                "url": "<?php  echo $data['cruis']->TV['kr_slider_img_'.$i]; ?>",
                                                "fileName": "11.jpg",
                                                "origFileName": "1.jpg",
                                                "width": 472,
                                                "height": 473,
                                                "size": 108711,
                                                "caption": "",
                                                "type": "image"
                                            }
                                        ]
                                    }</script>
                            </a>

                            <?php
                        }
                    ?>
                 </div>
                <div class="w-slide crsliderslide" style="transform: translateX(0px); opacity: 1;">
                    <?php
                    for($i=4;$i<7;$i++)
                    {
                        ?>
                        <a href="#" class="w-lightbox w-inline-block">
                            <img src="<?php echo tmbImg( $data['cruis']->TV['kr_slider_img_'.$i],'&w=700&zc=1'); ?>" class="cruissliderimg">
                            <script type="application/json" class="w-json">{
                                    "items": [
                                        {
                                            "url": "<?php  echo $data['cruis']->TV['kr_slider_img_'.$i]; ?>",
                                            "fileName": "11.jpg",
                                            "origFileName": "1.jpg",
                                            "width": 472,
                                            "height": 473,
                                            "size": 108711,
                                            "caption": "",
                                            "type": "image"
                                        }
                                    ]
                                }</script>
                        </a>


                        <?php
                    }
                    ?>
                </div>
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
                    for($i=1;$i<7;$i++)
                    {
                        ?>
                <div class="w-slide cruisslderslidemobile">
                        <img src="<?php echo tmbImg( $data['cruis']->TV['kr_slider_img_'.$i],'&w=700&zc=1'); ?>" class="crsmobsliderimg">
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

    <div class="bronDescription" >
        <div class="bronShipTitle"><?php  echo $data['ship']->title; ?></div>
        <div id="cruis-container"></div>

        <div class="cruis-description">
            <p><b>Направление: </b><?php echo $data['cruis']->TV['kr_route'] ?></p>
            <p><b>Города посещения: </b><?php echo $data['cruis']->TV['kr_cities'] ?></p>
            <p><b>Дата начала круиза: </b><?php echo $data['cruis']->TV['kr_date_start'] ?></p>
            <p><b>Дата окончания круиза: </b><?php echo $data['cruis']->TV['kr_date_end'] ?></p>
            <p><b>Внутренний номер круиза: </b><?php echo $data['cruis']->TV['kr_inner_id'] ?></p>

        </div>


        <?php




        /*выбираем теплоход по ID и рисуем палубы*/
        /* id=19436 Александр Невский*/
        if((int)$data['ship']->id==19436)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

                <div class="ship-paluba">
                    <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                     <?php include 'ships/tplAlexNevsky.php'; ?>
                </div>
            <?php
        }
        elseif((int)$data['ship']->id==19441)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

            <div class="ship-paluba">
                <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                <?php  include 'ships/tplAlexTolst.php'; ?>
            </div>
            <?php
        }
        elseif((int)$data['ship']->id==19477)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

            <div class="ship-paluba">
                <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                <?php  include 'ships/tplSemenB.php'; ?>
            </div>
            <?php
        }
        /*Достоевский*/
        elseif((int)$data['ship']->id==19481)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

            <div class="ship-paluba">
                <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                <?php  include 'ships/tplDostoevski.php'; ?>
            </div>
            <?php
        }
        elseif((int)$data['ship']->id==19430)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

            <div class="ship-paluba">
                <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                <?php  include 'ships/tplGerzen.php'; ?>
            </div>
            <?php
        }
      /*  elseif((int)$data['ship']->id==19483)
        {
            ?>
            <div class="ship-loader">
                <h3 class="cruisformh3">Загрузка информации о наличии мест в каютах</h3>
                <div id="progressbar"></div>
            </div>

            <div class="ship-paluba">
                <h3 class="cruisformh3">Чтобы забронировать или выкупить каюту - нажмите на нее</h3>
                <?php  include 'ships/tplHirurg.php'; ?>
            </div>
            <?php
        }*/
        else
        {
            ?>
            <div class="bron_ship_sh">
                <a href="#" class="w-lightbox w-inline-block">
                    <img class="bron_t_sh" src="<?php  echo $data['ship']->TV['t_sh_img']; ?>">
                    <script type="application/json" class="w-json">{
                            "items": [
                                {
                                    "url": "<?php  echo $data['ship']->TV['t_sh_img']; ?>",
                                    "fileName": "11.jpg",
                                    "origFileName": "1.jpg",
                                    "width": 472,
                                    "height": 473,
                                    "size": 108711,
                                    "caption": "<?php  echo $data['ship']->title; ?>",
                                    "type": "image"
                                }
                            ]
                        }</script>
                </a>
            </div>

        <div class="bron_pop_description">
            <?php echo $data['cruis']->TV['pop_description']; ?>
        </div>
    </div>
    <h3 class="cruisformh3">Бронирование и оплата</h3>

    <div class="cruisformtextcenter" >
        На нашем сайте Вы можете воспользоваться предварительной системой бронирования круизов.
        <span class="centerformcrspan">Бронирование круизов производится бесплатно.</span><br>
        Срок оплаты забронированного тура составляет в среднем 3-5 дней после подтверждения заявки и зависит от
        выбранного Вами круиза.<br>
        <span class="centerformcrspan">Так же вы можете совершить покупку круиза прямо сейчас!</span>

    </div>

    <h2 class="cruish2" id="cauta">Выберите каюту</h2>
    <div class="alert-input alert-cauta"></div>
    <div class="cruistablebox">
        <div class="w-clearfix cruistablehead">
            <div class="crtableheaditem">Каюта</div>
            <div class="crtableheaditem th2">Категория</div>
            <div class="crtableheaditem th3">Палуба</div>
            <div class="crtableheaditem th4">Цена основного <br>места</div>
            <div class="crtableheaditem th5">Количество мет</div>
        </div>

        <?php
        foreach($data['cauta_list'] as $cauta)
        {
            $cauta=$this->GetCautaInfo($cauta->id);
            ?>
            <div class="w-clearfix cruistableitem">
                <div class="cruistablecol">
                    <div class="cruischk click cauta-select2"
                         inner_id="<?php echo $cauta->inner_id; ?>"
                         nomer="<?php echo $cauta->nomer; ?>"
                         cauta_id="<?php echo $cauta->id; ?>"
                         price="<?php echo $cauta->price; ?>"
                         deck="<?php echo $cauta->deck; ?>"
                         type="<?php echo $cauta->type; ?>"
                         free_place="<?php echo $cauta->free_place; ?>"

                        ></div>
                    <div class="crchktext"><?php echo $cauta->nomer; ?></div>
                </div>
                <div class="cruistablecol col2"><?php echo $cauta->type; ?></div>
                <div class="cruistablecol col3"><?php echo $cauta->deck; ?></div>
                <div class="cruistablecol col4"><?php echo $cauta->price; ?></div>
                <div class="cruistablecol col5"><?php echo $cauta->free_place; ?></div>
            </div>
            <?php
        }
        ?>

    </div>



    <div class="bron-places" id="places"></div>
    <div class="cruisbronform" id="BronForm">


        <div class="cruisformtextcenter alert"></div>
        <div class="w-form cruisinputform">
            <form id="z-form" name="z-form">
                <input type="hidden" id="cauta_inner_id" name="cauta_inner_id" value="">
                <input type="hidden" id="price" name="price" value="0">
                <input type="hidden" id="deck" name="deck" value="">
                <input type="hidden" id="type" name="type" value="">
                <input type="hidden" id="free_place" name="free_place" value="">
                <input type="hidden" id="cauta_nomer" name="cauta_nomer" value="">
                <input type="hidden" id="cauta_id" name="cauta_id" value="">
                <input type="hidden" id="cruis_inner_id" name="cruis_inner_id" value="<?php echo $data['cruis']->TV['kr_inner_id']; ?>">
                <input type="hidden" id="cruis_id" name="cruis_id" value="<?php echo $data['cruis']->id; ?>">
                <input type="hidden" id="curuis_price" name="cruis_id" value="<?php echo $data['cruis']->id; ?>">
                <input type="hidden" id="ship_id" name="ship_id" value="<?php echo $data['cruis']->parent; ?>">
                <input type="hidden" id="ship_name" name="ship_name" value="<?php echo $data['ship']->title; ?>">
                <input type="hidden" id="z-form-action" name="action" value="z_add">

                <div id="tplBronForm2" class="cruisformtextcenter">

                </div>
                <div class="cruisformleft">


                    <label for="Phone" class="cruisformlabel">Контактный телефон:</label>
                    <div class="alert-input alert-phone"></div>
                    <input id="Phone" type="text" placeholder="+7 000 000 00 00" name="Phone"
                           required="required" data-name="u_phone"
                           class="w-input cruisforminput">



                    <label for="Email" class="cruisformlabel">Ваша электронная почта:</label>
                    <input id="Email" type="text" placeholder="example@mail.com"
                           name="Email" required="required" data-name="Email"
                           class="w-input cruisforminput">
                    <!--  <img src="/ajax-bron.html?action=captcha" alt="Captcha Image" /><br>-->


                    <label for="capcha" class="cruisformlabel">Введите защитный код:</label>
                    <div class="alert-input alert-ca"></div>
                    <input id="capcha" type="text" value=""
                           name="capcha" required="required" data-name="capcha"
                           class="w-input cruisforminput">

                    <?php

                    // Echo out a sample image
                    echo '<img src="'.$data['capcha'].'">';
                    ?>

                    <label class="cruisformlabel">Выбранная каюта:
                        <span class="cruisformcautanomber select-cauta"></span>
                    </label>
                </div>
                <div class="cruisformright">
                    <label for="info" class="cruisformlabel">Дополнительная
                        информация:</label>
                    <textarea style="    height: 200px;" id="info" name="info" data-name="info" class="w-input cruisformtextarrea"></textarea>
                </div>
            </form>
        </div>



        <div class="PayButtons agreement">
            <div class="w-checkbox">
                <div class="alert-input alert-agreement"></div>
                <input id="agreement" type="checkbox" name="agreement" value="1"  class="w-checkbox-input">
                <label for="agreement" class="w-form-label">Я даю свое согласие на обработку
                    персональных данных и соглашаюсь с </label>
                <span class="agreementspan" onclick="$('#ModalAgrement').modal('show');">условиями и политикой конфиденциальности</span>
            </div>
        </div>

        <div class="PayButtons">
            <div class="cruisformcommitbutton pay"  onclick="Bron2.OrderBy();">Купить сейчас</div>
            <div class="cruisformcommitbutton"  onclick="Bron2.Order();">Забронировать</div>
        </div>
    </div>
            <?php
        }
        ?>




</div>


<div id="robokassa" style="display: none"></div>

<!-- Modal -->
<div class="modal fade" id="ModalBron" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title " id="modal-bron-title">Успешная бронь</h4>
            </div>
            <div class="modal-body " id="modal-born-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="cruisformcommitbutton" style="padding-top: 0;" data-dismiss="modal">Закрыть</button>

            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ModalTimer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title " id="modal-bron-title">Подождите</h4>
            </div>
            <div class="modal-body " id="modal-born-body">
               <img src="/images/loader.GIF" style="display: block;margin: 0 auto;">
            </div>

        </div>
    </div>
</div>

<?php include 'tplBronModal.php';?>

<!-- Modal -->
<div class="modal fade" id="ModalAgrement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Договор</h4>
            </div>
            <div class="modal-body" style="padding: 30px;">

                <?php echo $data['Agreement']; ?>
            </div>
            <div class="modal-footer">
                <button class="cruisformcommitbutton pay"
                        style=" padding-top: 0;"
                        data-dismiss="modal"  onclick="Bron2.OrderBy();">Закрыть</button>

            </div>
        </div>
    </div>
</div>
