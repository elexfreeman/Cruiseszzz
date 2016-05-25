<h2><?php echo $ship->title;  ?></h2>
<form id="ship_edit_form" action="ajax.html" name="ship_edit_form"  enctype="multipart/form-data" method="POST" class="w-clearfix">
    <input type="hidden" name="action" value="ZAdminShipUpdate">
    <input type="hidden" name="ship_id" value="<?php echo $ship->id; ?>">
    <!-- <h3 class="ship-modal-title"><?php echo $ship->title; ?></h3> -->
    <div class="w-clearfix s-d-top-div">
        <label for="name" class="sf-img-inpu-label">Изменить главное изображение:</label>
        <input id="name" type="file"  name="shipImg" data-name="Name" class="w-input sf-img-input">
        <img src="/<?php echo $ship->TV['t_title_img'];  ?>" class="s-t-img">
        <div class="s-d-text-input" style="width: 700px">
            <textarea id="ship-description"
                      placeholder="Описание теплохода"
                      name="ship-description"
                      data-name="ship-description" row="20"
                      class="w-input s-d-text-input"><?php echo $ship->TV['t_description'];  ?></textarea></div>



        <div class="adm-cauta-types-modal">
            <h3>Типы кают</h3>
            <a class="CautaTypeItem active" href="<?php echo site_url('ships/cautatypesedit')."/".$ship->id; ?>">
                ДОБАВИТЬ
            </a>

            <?php

            foreach ($ship->CautaTypes as $cauta_type)
            {
                ?>
                <a class="CautaTypeItem" href="<?php echo site_url('ships/cautatypesedit')."/".$ship->id."/".$cauta_type->id; ?>">
                    <?php echo $cauta_type->TV['k_type_name']; ?>


                </a>
                <?php
            }



            ?>

        </div>






        <div class="w-clearfix ff-sm-img-box">
            <img  src="<?php echo $ship->TV['t_gl_img_01'];  ?>"  class="ff-sm-img">
            <img  src="<?php echo $ship->TV['t_gl_img_02'];  ?>"  class="ff-sm-img">
            <img  src="<?php echo $ship->TV['t_gl_img_03'];  ?>"  class="ff-sm-img">
            <img  src="<?php echo $ship->TV['t_gl_img_04'];  ?>"  class="ff-sm-img">
        </div>

    </div>
    <label for="name" class="sf-img-inpu-label">Дополнительные изображения:</label>
    <input id="name" type="file" name="t_gl_img_01" class="w-input sf-img-input">
    <input id="name" type="file" name="t_gl_img_02" class="w-input sf-img-input">
    <input id="name" type="file" name="t_gl_img_03" class="w-input sf-img-input">
    <input id="name" type="file" name="t_gl_img_04" class="w-input sf-img-input">

    <label for="name" class="sf-img-inpu-label">Картинка схемы теплохода:</label>
    <input id="name" type="file" name="t_sh_img" class="w-input sf-img-input">

    <label for="t_usl" class="sf-img-inpu-label">К услугам туристов на борту (!!!заполнять через ; ):</label>
   <textarea id="t_usl" style="height: 200px;"
             placeholder="К услугам туристов на борту (!!!заполнять через ; )"
             name="t_usl"
             data-name="ship-description" row="20"
             class="w-input"><?php echo $ship->TV['t_usl'];  ?></textarea>

    <label for="name" class="sf-img-inpu-label">Технические характеристики (!!!заполнять через ; ):</label>
   <textarea id="t_teh_xar" style="height: 200px;"
             placeholder="Технические характеристики"
             name="t_teh_xar"
             data-name="ship-description" row="20"
             class="w-input"><?php echo $ship->TV['t_teh_xar'];  ?></textarea>


    <h3 class="sf-h3">Круизы</h3>
    <div class="s-form-cruis-list" style="height: auto;overflow: auto">
        <?php
        foreach($ship->CruisList as $cruis)
        {
            ?>
            <div class="w-clearfix sf-cruis">
                <div class="sf-c-direction"><?php echo $cruis->TV['kr_route'];  ?></div>
                <div class="sf-c-date"><?php echo $cruis->TV['kr_date_start'];  ?> - <?php echo $cruis->TV['kr_date_end'];  ?></div>
                <div class="sf-c-daynight"><?php echo $cruis->TV['kr_days'];  ?> / <?php echo $cruis->TV['kr_nights'];  ?></div>
                <div class="w-checkbox sf-pop-ch">
                    <input id="cruis_<?php echo $cruis->id;?>"
                        <?php if((isset($cruis->TV['kr_pop'])) and($cruis->TV['kr_pop']=='Да')) echo ' checked style="color:red" '; ?>

                           type="checkbox" name='cruis_<?php echo $cruis->id;?>' class="w-checkbox-input chk_pop">
                    <label for="cruis_<?php echo $cruis->id;?>" class="w-form-label" <?php if((isset($cruis->TV['kr_pop'])) and($cruis->TV['kr_pop']=='Да')) echo ' style="color:red" '; ?>>Популярный</label></div>
            </div>
            <?php
        }
        ?>
    </div>
    <input type="submit" value="Изменить"  onclick="ZAdmin.ShipUpdate(<?php echo $ship->id; ?>)" class="w-button sf-button">
</form>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    $(function() {
        setTimeout(function() { tinymce.init({ selector:'#ship-description' }); }, 1000);
    });
</script>