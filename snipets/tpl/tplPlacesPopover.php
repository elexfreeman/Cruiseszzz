<?php
/**
 * Created by PhpStorm.
 * User: cod_llo
 * Date: 14.03.16
 * Time: 17:46
 */
?>

    <form id="placesForm" target="">
        <input type="hidden" name="action"  value="tplBronForm2">

        <?php

        foreach ($this->data['places'] as $place) {

            $place = explode('-', $place);

            $place_id = explode(':', $place[0]);
            $place_name = explode(':', $place[1]);
            $place_type = explode(':', $place[2]);
            $place_position = explode(':', $place[3]);
            $place_status = explode(':', $place[4]);
            if ($place_name[1] != '')
            {
                ?>

                <div class="w-clearfix cruistableitem place">
                    <div class="cruistablecol col-place">
                        <div onclick="Bron2.PlaceSelect('<?php echo $place_id[1]; ?>')"
                             id="place_<?php echo $place_id[1]; ?>"
                             class="cruischkBox click cauta-select2" place_id="<?php echo $place_id[1]; ?>"></div>
                        <div class="crchktext">Место: <?php echo $this->ConvertPlaceNumber($place_id[1]); ?></div>
                        <input type="hidden" id="placeInput_<?php echo $place_id[1]; ?>" name="placeInput_<?php echo $place_id[1]; ?>" value="0">
                    </div>
                </div>

                <?php
            }
        }
        ?>
        <input type="button" value="Готово" class="place-ok">
        <input type="button" value="Отмена" onclick="$('.popover1').popover('hide');$('a.btn').addClass('popover1');">
    </form>
