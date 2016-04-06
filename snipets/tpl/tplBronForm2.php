<?php
/**
 * Created by PhpStorm.
 * User: folle
 * Date: 04.03.2016
 * Time: 11:06
 * форма бронирования с заполнением мест
 */

$i=1;
foreach ( $_GET as $input=>$val)
{
    $input_array=explode('_',$input);

    if(($input_array[0]=='placeInput')and((int)$val==1))
    {
        ?>

        <script>
            $(function() {
                $.datepicker.setDefaults($.extend(
                        $.datepicker.regional["ru"])

                );
                $( "#pasjBirthday<?php echo $i; ?>" ).datepicker({ dateFormat: 'dd.mm.yy',    yearRange: "-100:+0",
                    changeMonth: true,
                    changeYear: true});
            });
        </script>
        <div class="alert-input alert-psj"></div>
        <div class="psjItem">
            <label for="FirstName" class="cruisformlabel">Пассажир <?php echo $i; ?>:</label>
            <input id="name<?php echo $i; ?>" type="text" placeholder="например Иванов Пётр Сергеевич" name="name<?php echo $i; ?>" required="required"
                   class="w-input cruisforminput">

<!--
            <label for="birthday" class="cruisformlabel">Дата рождения:</label>
            <input id="pasjBirthday<?php echo $i; ?>" type="text" placeholder="" name="pasjBirthday<?php echo $i; ?>" required="required"
                   class="w-input cruisforminput date_picker_birthday">
-->
            <input name="placeid_<?php echo $i; ?>" type="hidden" value="<?php echo $input_array[1] ?>">

        </div>

        <?php
        $i++;
    }

}


?>

