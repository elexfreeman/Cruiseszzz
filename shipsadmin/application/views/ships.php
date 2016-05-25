

<div class="w-clearfix ships-container">
    <?php
    //выводим плитку кораблиов
    foreach ($ships as $ship)
    {
        ?>
        <a href="<?php echo site_url('ships/ship')."/".$ship->id; ?>">
        <div class="ship-item click">
            <div class="s-title"><?php echo $ship->title; ?></div>
            <div class="s-photo"><img src="/<?php echo $ship->TV['t_title_img'];  ?>"  class="s-img"></div>
        </div>
        </a>
        <?php
    }
    ?>


</div>
