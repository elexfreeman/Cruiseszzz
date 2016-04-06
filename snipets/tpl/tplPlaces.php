<?php
/**
 * Created by PhpStorm.
 * User: folle
 * Date: 02.03.2016
 * Time: 12:17
 * див с выбором мест
 *
 * запрашмвате сразу с инфофлота
 */
ClearGet();
$cruis = GetPageInfo($_GET['cruis_id']);
$ship = GetPageInfo($Z->TV['z_ship_id']);;

$cauta = GetPageInfo($_GET['cauta_id']);;

$places = $cauta->TV_Full['k_places'];


?>

<div class="cruish2">Выберите место</div>
<div class="alert-input alert-place"></div>
<div class="cruistablebox">
    <form id="placesForm" target="">
        <input type="hidden" name="action"  value="tplBronForm2">

        <?php
        $places = explode('||', $places->value);
        foreach ($places as $place) {

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
    </form>
</div>


