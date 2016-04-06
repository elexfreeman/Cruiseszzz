<?php
$pop_cruis_list=$this->GetPopCrus();
?>
<div class="w-clearfix pop-cruis-list">
    <?php
    foreach ($pop_cruis_list as $cruis)
    {
        ?>
        <pre style="display: none"><?php print_r($cruis);?></pre>
        <div class="w-clearfix pop-cruis" style='<?php
        echo 'background-image: url("'. $cruis->TV['pop_img'].'");';
        ?>'>
            <div class="w-clearfix cr-title">
                <div class="cr-delete" onclick="ZAdmin.Delete(?php echo $cruis->id; ?>);">X</div>
                <div class="cr-top-title-1"><?php echo $cruis->TV['pop_direction']; ?></div>
                <div class="cr-top-title-2"><?php echo $cruis->TV['pop_title_description']; ?></div>
                <div class="cr-date"><?php echo $cruis->TV['kr_date_start']; ?><br>
                    –<br><?php echo $cruis->TV['kr_date_end']; ?></div>
            </div>
            <div class="cr-price-box">
                <div class="cr-price">от <?php echo $this->GetCruisMinPrice($cruis->id) ?> р.</div>
                <div class="cr-price-description">за 1-го человека</div>
            </div>
            <div class="cr-edit-button" onclick="ZAdmin.PopCruisEdit(<?php echo $cruis->id; ?>);">Изменить</div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Modal -->
<div class="modal fade" id="PopCruisEdtModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content crus-modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Редактирование круиза: <span class="ce_id">№№№№№№№</span></h4>
            </div>
            <div class="modal-edit-cruis">
                <div class="w-form">
                    <form id="email-form-2"  action="ajax.html"
                          enctype="multipart/form-data" method="POST"
                          name="email-form-2" data-name="Email Form 2" class="w-clearfix">
                        <input type="hidden" name="action" value="ZAdminCruisUpdate">
                        <input type="hidden" name="cruis_id" id="cruis_id" value="ZAdminShipUpdate">
                        <label for="pop_direction">Заголовок (*Круиз в Астрахань)</label>
                        <input id="pop_direction" type="text" placeholder="" name="pop_direction" class="w-input">
                        <label for="pop_title_description">Подпись (*Раннее бронирование):</label>
                        <input id="pop_title_description" type="text" placeholder="" name="pop_title_description" class="w-input">
                        <label for="pop_img">Фоновая картинка:</label>
                        <input id="pop_img" type="file" name="pop_img" class="w-input">

                        <label for="kr_slider_img_1">Картинка слайда 1:</label>
                        <input id="kr_slider_img_1" type="file" name="kr_slider_img_1" class="w-input">

                        <label for="kr_slider_img_2">Картинка слайда 2:</label>
                        <input id="kr_slider_img_2" type="file" name="kr_slider_img_2" class="w-input">

                        <label for="kr_slider_img_3">Картинка слайда 3:</label>
                        <input id="kr_slider_img_3" type="file" name="kr_slider_img_3" class="w-input">

                        <label for="kr_slider_img_4">Картинка слайда 4:</label>
                        <input id="kr_slider_img_4" type="file" name="kr_slider_img_4" class="w-input">

                        <label for="kr_slider_img_5">Картинка слайда 5:</label>
                        <input id="kr_slider_img_5" type="file" name="kr_slider_img_5" class="w-input">

                        <label for="kr_slider_img_6">Картинка слайда 6:</label>
                        <input id="kr_slider_img_6" type="file" name="kr_slider_img_6" class="w-input">


                        <label for="field">Описание:</label>
                        <div class="cr-description-box">
                            <textarea style="height: 100%" id="pop_description" placeholder="" name="pop_description" class="w-input"></textarea>
                        </div>
                        <input type="submit" value="Сохранить" class="w-button cr-edit-modal-button">
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    $(function() {

    });
</script>