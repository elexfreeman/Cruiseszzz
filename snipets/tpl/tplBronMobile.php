<?php
/**
 * Created by PhpStorm.
 * User: folle
 * Date: 24.03.2016
 * Time: 13:34
 * Модальное окно выбора места в каюте и заказа
 */

?>


<!-- Modal -->
<div id="BronModalV2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="bron-modal">
                <div class="w-clearfix bronmodaltop">
                    <div class="bronmodalclosebutton"  data-dismiss="modal" aria-label="Close"></div>
                    <div class="bronmodaltopinfo">
                        <div class="bronmodallist">
                            <div class="bronmodallistcaption">Каюта №<span id="bron-modal-cauta-number">0</span></div>
                        </div>
                        <div class="bronmodalprice"><span id="bron-modal-cauta-summa">0</span> р.</div>
                    </div>
                </div>
                <div class="bronmodalplaces havePlace">
                    <div class="bronmodalplacescaption">Выберите места
                        <br>в каюте:</div>
                    <div class="bronmodalplacebox"></div>
                </div>
                <div class="bronmodalplaces noPlace">
                    <div class="bronmodalplacescaption">Свободных мест нет</div>
                </div>
                <div class="bronbuttons">
                    <div class="bronbuttonbron" onclick="Bron2.BronTabClick();">Забронировать</div>
                    <div class="bronor">ИЛИ</div>
                    <div class="bronbuttonpaynow" onclick="Bron2.PayTabClick(); ">Купить сейчас</div>
                </div>
                <div class="w-form bronmodalforwr">

                    <form class="bronForm2" id="wf-form-BronModalForm-pay" name="wf-form-BronModalForm-pay" data-name="BronModalForm">
                        <input type="hidden" name="place_price" class="place_price" value="0">
                        <input type="hidden" name="need_call" id="need_call" value="1">
                        <input type="hidden" name="from" value="pay">
                        <input type="hidden" name="action" id="action-pay" value="bronPay">
                        <input type="hidden" name="cauta_number" class="cauta_number" value="">
                        <input type="hidden" name="cruiz_id" class="cruis_id" value="<?php echo $data['cruis']->id; ?>">
                        <input type="hidden" id="ship_id" name="ship_id" value="<?php echo $data['cruis']->parent; ?>">
                        <input type="hidden" id="ship_name" name="ship_name" value="<?php echo $data['ship']->title; ?>">

                        <h3 class="bronmodalh3">Покупатель</h3>
                        <div class="bronformfieldsrow passjir" id="p_0">
                            <img id="u_img_0" onclick="Bron2.ChangeSex('0');" src="/images/bron-man.png" class="bron-icon-wc u_img_0">
                            <input class="u_sex_0" type="hidden"  name="u_sex_0" value="1">
                            <input id="u_surname_0" type="text" placeholder="Фамилия" name="u_surname_0" required="required" class="w-input bronmodalinput">
                            <input id="u_name_0" type="text"  placeholder="Имя" name="u_name_0" required="required" class="w-input bronmodalinput">
                            <input id="u_patronymic_0" type="text" placeholder="Отчество" name="u_patronymic_0" class="w-input bronmodalinput ">
                            <input  id="u_birthday_0"  placeholder="Дата рождения"
                                   name="u_birthday_0" required="required" class="w-input bronmodalinput hasDatepickerBron u_birthday_0">

                            <div class="w-radio bron-radio" onclick="">
                                <input onchange="Bron2.ImPassaj();" style='margin-top: 19px;' id="is_p_0" type="checkbox" name="is_p" value="Radio"  data-name="Radio" class="w-radio-input bron-radio-p">
                                <label style="text-align: center;    width: 88px;" for="is_p_0" class="w-form-label">Является<br>пассажиром</label>
                            </div>
                        </div>

                        <h3 class="bronmodalh3">Пассажиры</h3>
                        <div class="passaj_content-pay"></div>

                        <div class="bron-phone-content">
                            <h3 class="bronmodalh3">Телефон</h3>
                            <input id="u_phone-2" type="text" placeholder="+71234567890" name="u_phone" class="w-input bronmodalinput phone">
                        </div>

                        <div class="bronmodalcallme call-pay check click">У меня остались вопросы перезвоните мне</div>

                        <div class="bron-phone-content">
                            <h3 class="bronmodalh3">Введите кодовую фразу</h3>
                            <?php  echo '<img src="'.$data['capcha'].'">'; ?>
                            <input id="u_ca" type="text"  name="u_ca" required="required"  class="w-input bronmodalinput">
                        </div>

                        <div class="PayButtons agreement">
                            <div class="w-checkbox">
                                <div class="alert-input alert-agreement"></div>
                                <input id="agreement" type="checkbox" name="agreement" value="1"  class="bron-radio-p w-checkbox-input">
                                <label for="agreement" class="w-form-label"> Я даю свое согласие на обработку
                                    персональных данных и соглашаюсь с </label>
                                <span class="agreementspan" onclick="$('#ModalAgrement').modal('show');">условиями и политикой конфиденциальности</span>
                            </div>
                        </div>
                        <input type="button" value="Заказать круиз" data-wait="Please wait..." class="w-button bronbuttonsubmit pay" onclick="Bron2.PayClick();">
                    </form>




                    <!-- ----БРОНИРОВНИЕ ------------>
                    <form class="bronForm2" id="wf-form-BronModalForm-bron" name="wf-form-BronModalForm-bron" data-name="BronModalForm">
                        <input type="hidden" name="place_price" class="place_price" value="0">
                        <input type="hidden" name="need_call" class="need_call" value="1">
                        <input type="hidden" name="action" value="bronPay">
                        <input type="hidden" name="from" value="bron">
                        <input type="hidden" name="cauta_number" class="cauta_number" value="">
                        <input type="hidden" name="cruiz_id" class="cruis_id" value="<?php echo $data['cruis']->id; ?>">
                        <input type="hidden" name="ship_id" value="<?php echo $data['cruis']->parent; ?>">
                        <input type="hidden" name="ship_name" value="<?php echo $data['ship']->title; ?>">

                        <h3 class="bronmodalh3">Пассажиры</h3>
                        <div class="passaj_content-bron"></div>

                        <div class="bron-phone-content">
                            <h3 class="bronmodalh3">Телефон</h3>
                            <input id="u_phone-2" type="text" placeholder="мой телефон для связи" name="u_phone"  class="w-input bronmodalinput phone">
                        </div>

                        <div class="bronmodalcallme call-bron check click">У меня остались вопросы перезвоните мне</div>

                        <div class="bron-phone-content">
                            <h3 class="bronmodalh3">Введите кодовую фразу</h3>
                            <?php  echo '<img src="'.$data['capcha'].'">'; ?>
                            <input id="u_ca" type="text"  name="u_ca" required="required"  class="w-input bronmodalinput">
                        </div>


                        <div class="PayButtons agreement">
                            <div class="w-checkbox">
                                <div class="alert-input alert-agreement"></div>
                                <input id="agreement" type="checkbox" name="agreement" value="1"  class="bron-radio-p w-checkbox-input">
                                <label for="agreement" class="w-form-label aggr_text"> Я даю свое согласие на обработку
                                    персональных данных и соглашаюсь с </label>
                                <span class="agreementspan" onclick="Bron2.Agrement();">условиями и политикой конфиденциальности</span>
                            </div>
                        </div>
                        <input type="button" value="Заказать круиз" onclick="Bron2.BronClick();" class="w-button bronbuttonsubmit bron">
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
