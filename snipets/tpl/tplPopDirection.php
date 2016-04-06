<?php
$this->GetPopDirectionList();

?>
<div class="w-clearfix popdirection">
    <?php
    foreach ($this->popDirection as $cruis)
    {

        ?>
        <div class="pupdirectionitem" style='<?php
        echo 'background-image: url("'. tmbImg($cruis->TV['pop_img'],'&w=700&zc=1').'");';
        ?>'>
            <div class="w-clearfix poptop">
                <div class="poptopheadtext"><?php echo $cruis->TV['pop_direction']; ?>
                    <div class="popdescr"><?php echo $cruis->TV['pop_title_description']; ?></div>
                </div>

                <div class="popdate"><?php echo $cruis->TV['kr_date_start']; ?><br>–<br><?php echo $cruis->TV['kr_date_end']; ?></div>
            </div>
            <div class="poppriceblock">
                <div class="popprice">от <?php echo $this->GetCruisMinPrice($cruis->id) ?> р.</div>
                <div class="poppricedescr">за 1-го человека</div>
            </div>
            <a href="/bronirovanie.html?cruis_id=<?php echo $cruis->id;?>"><div class="popbutton">Подробнее</div></a>
        </div>
        <?php
    }
    ?>
<!--
    <div class="pupdirectionitem right">
        <div class="w-clearfix poptop">
            <div class="poptopheadtext">Круиз в Астрахань</div>
            <div class="popdescr">Раннее бронирование</div>
            <div class="popdate">15.12.15<br>–<br>20.12.15</div>
        </div>
        <div class="poppriceblock">
            <div class="popprice">от 18 000 р.</div>
            <div class="poppricedescr">за 1-го человека</div>
        </div>
        <div class="popbutton">Подробнее</div>
    </div>
    -->
</div>