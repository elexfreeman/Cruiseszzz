<?php
$MainPage = GetPageInfo(1);

?>

    <div class="contaktshead">Контакты</div>


    <div class="contactscolleft">

        <div class="address-buttons">
            <div class="address-b active" city="samara">Самара</div>
            <div class="address-b" city="toglyaty">Тольятти</div>
        </div>

        <div class="address-tab samara active">
            <div class="contaddresslabel">Наш адрес:</div>
            <div class="w-clearfix contcolleftlr address">
                <img src="/images/cont-point.jpg" class="contimg point">
                <div class="contlrtext"><?php echo $MainPage->TV['address'];?></div>
            </div>

            <a href='<?php echo $MainPage->TV['pano_link'];?>' target="_blank">
                <div class="w-clearfix contcolleftlr pano">
                    <img src="/images/cont-photo.jpg" class="contimg pano">
                    <div class="contlrtext">смотреть панораму</div>
                </div>
            </a>

            <div class="w-clearfix contcolleftlr phone">
                <img src="/images/cont-phone.jpg" class="contimg phone">
                <div class="contlrtext"><?php echo $MainPage->TV['phone1'];?><br><?php echo $MainPage->TV['phone2'];?></div>
            </div>
            <div class="w-clearfix contcolleftlr email">
                <img src="/images/cont-email.jpg" class="contimg email">
                <div class="contlrtext"><?php echo $MainPage->TV['email'];?></div>
            </div>
            <div class="contactsgrtext">График работы:</div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft pnpt"><?php echo $MainPage->TV['work_days'];?></div>
                <div class="grworkright"><?php echo $MainPage->TV['wowrk_time'];?></div>
            </div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft subota">СБ</div>
                <div class="grworkright"><?php echo $MainPage->TV['work_s'];?></div>
            </div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft vsk">ВС</div>
                <div class="grworkright"><?php echo $MainPage->TV['work_vs'];?></div>
            </div>
        </div>

        <div class="address-tab toglyaty">
            <div class="contaddresslabel">Наш адрес:</div>
            <div class="w-clearfix contcolleftlr address">
                <img src="/images/cont-point.jpg" class="contimg point">
                <div class="contlrtext"><?php echo $MainPage->TV['t_address'];?></div>
            </div>

            <a href='<?php echo $MainPage->TV['t_pano_link'];?>' target="_blank">
                <div class="w-clearfix contcolleftlr pano">
                    <img src="/images/cont-photo.jpg" class="contimg pano">
                    <div class="contlrtext">смотреть панораму</div>
                </div>
            </a>

            <div class="w-clearfix contcolleftlr phone">
                <img src="/images/cont-phone.jpg" class="contimg phone">
                <div class="contlrtext"><?php echo $MainPage->TV['t_phone'];?></div>
            </div>
            <div class="w-clearfix contcolleftlr email">
                <img src="/images/cont-email.jpg" class="contimg email">
                <div class="contlrtext"><?php echo $MainPage->TV['t_email'];?></div>
            </div>
            <div class="contactsgrtext">График работы:</div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft pnpt"><?php echo $MainPage->TV['t_work_days'];?></div>
                <div class="grworkright"><?php echo $MainPage->TV['t_wowrk_time'];?></div>
            </div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft subota">СБ</div>
                <div class="grworkright"><?php echo $MainPage->TV['t_work_s'];?></div>
            </div>
            <div class="w-clearfix graficworitem">
                <div class="graficworkleft vsk">ВС</div>
                <div class="grworkright"><?php echo $MainPage->TV['t_work_vs'];?></div>
            </div>
        </div>

        <div class="conturaddrestext">Юридический<br>адрес:</div>
        <div class="conturinfo"><?php echo $MainPage->TV['ur_address'];?></div>
    </div>

<div class="contactsright">


    <div class="contrh1">ИНН</div>
    <div class="contrtext"><?php echo $MainPage->TV['inn'];?></div>
    <div class="contrh1">ОГРН</div>
    <div class="contrtext"><?php echo $MainPage->TV['ogrn'];?></div>
    <div class="contrh1">ОКПО</div>
    <div class="contrtext"><?php echo $MainPage->TV['okpo'];?></div>
    <div class="contrh1">р/с</div>
    <div class="contrtext"><?php echo $MainPage->TV['rs'];?></div>
    <div class="contrh1">Корр. счет</div>
    <div class="contrtext"><?php echo $MainPage->TV['korsc'];?></div>


</div>
