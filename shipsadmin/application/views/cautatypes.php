<h2><?php echo $ship->title;  ?></h2>
<h3>Типы кают <a href="<?php echo site_url('ships/cautatypesedit')."/".$ship->id; ?>" class="ct-add-button">Добавить</a></h3>
<div class="adm-cauta-types-list">
    <?php
    foreach ($ship->CautaTypes as $cauta_type)
    {
        ?>
    <a class="CautaTypeItem" href="<?php echo site_url('ships/cautatypesedit')."/".$ship->id."/".$cauta_type->id; ?>">
        <?php echo $cauta_type->TV['k_type_name']; ?>
        <?php if(isset($cauta_type->TV['k_caption'])) echo $cauta_type->TV['k_caption']; ?>

    </a>
        <?php

    }

    ?>

</div>