<h2><?php echo $ship->title; ?></h2>
<?php
if ($cautatypeid == '0') {
    ?>
    <h3>Добавление нового типа каюты</h3>
    <?php
} else {
    ?>
    <h3>Редактирование типа каюты <?php echo $cautatype->TV['k_type_name']; ?></h3>
    <?php
}
?>

<?php echo form_open("ships/cautatypesedit/" . $ship->id . "/" . $cautatype->id); ?>

<?php
if ($cautatypeid == '0') {
    ?>
    <input type="hidden" name="action" value="save">
    <?php
} else {
    ?>
    <input type="hidden" name="action" value="update">
    <?php
}
?>

<label>Название</label>
<input class="w-input sf-img-input" type="text" name="pagetitle"
       value="<?php if ($cautatypeid != 0) echo $cautatype->TV['k_type_name']; ?>">

<label>Описание каюты</label>
<div class="k_description-div">
    <textarea rows="10" class="w-input " type="text" name="k_description"
              id="k_description"><?php if (($cautatypeid != 0)and(isset($cautatype->TV['k_description']))) echo $cautatype->TV['k_description']; ?></textarea>
</div>
<br>
<div class="nabor-uslug-div">
    <label>Набор услуг</label>
    <?php
    $i = 0;
    if((isset($cautatype->TV['k_params']))and($cautatype->TV['k_params']!=''))
    {
        $cautatype->TV['k_params']=explode('||',$cautatype->TV['k_params']);
    }
    else $cautatype->TV['k_params']=array();

    foreach ($naborUslug as $n) {
        ?>
        <div class="w-checkbox nabor-uslug">
            <input id="naborUslug_<?php echo $i; ?>" type="checkbox"
                   <?php
                   if(in_array($n,$cautatype->TV['k_params'])) echo ' checked ';

                   ?>

                   name="naborUslug_<?php echo $i; ?>"
                   class="w-checkbox-input" value="<?php echo $n; ?>">
            <label for="naborUslug_<?php echo $i; ?>" class="w-form-label"><?php echo $n; ?></label>
        </div>

        <?php
        $i++;

    }
    ?>
</div>
<div class="k_img_div">
    <h5>Изображения каюты</h5>
    <?php
    for ($i = 1; $i <= 4; $i++)
    {
        if ((isset($cautatype->TV['k_img' . $i])) and ($cautatype->TV['k_img' . $i] != '')) {
            ?>
            <img class="k_img" src="<?php echo $cautatype->TV['k_img' . $i] ?>">
            <?php
        }
    }

    ?>

    <?php
    for ($i = 1; $i <= 4; $i++)
    {

        ?>
        <input class="w-input sf-img-input" type="file" name="k_img<?php echo $i;?>" value="">
        <?php
    }
    ?>


</div>

<input type="submit" value="Сохранить" class="w-button sf-button">
</form>


<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    $(function () {
        setTimeout(function () {
            tinymce.init({selector: '#k_description'});
        }, 1000);
    });
</script>