<?php
$z_id=mysql_escape_string($_GET['z_id']);
$Z=GetPageInfo($z_id);
$cruis=GetPageInfo($Z->TV['z_cruis_id']);
$ship=GetPageInfo($Z->TV['z_ship_id']);;

$cauta=GetPageInfo($Z->TV['z_cauta_id']);;

$places = $cauta->TV_Full['k_places'];

$z_status=$Z->TV['z_status'];

?>
<script src="/js/ui.datepicker-ru.js"></script>
<script>

    $(function () {
        //  $('[data-toggle="popover"]').popover()


        $.datepicker.setDefaults($.extend(
                $.datepicker.regional["ru"])

        );

        $( "#z_user_birthday" ).datepicker({ dateFormat: 'dd.mm.yy' });

    })

</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Редактирование заявки №<?php echo $z_id; ?></h4>
</div>
<div class="modal-body">
    <div class="z-modal-form">
        <div class="w-form">
            <form    id="z-form"
                     name="email-form-5" data-name="Email Form 5"
                     enctype="multipart/form-data" method="POST">

                <input type="hidden" name="z_id" id="z_id" value="<?php echo $z_id; ?>">
                <input type="hidden" name="action" id="action" value="z_update">

                <label for="z_status">Статус:</label>
                <select id="z_status" name="z_status" data-name="z_status"  class="w-select">
                    <option value="Новый" <?php if($z_status=='Новый') echo ' selected '?>>Новый</option>
                    <option value="Отправить в инфофлот" <?php if($z_status=='Отправить в инфофлот') echo ' selected '?>>Отправить в инфофлот</option>
                    <option value="Проверка свободных мест"<?php if($z_status=='Проверка свободных мест') echo ' selected '?>>Проверка свободных мест</option>
                    <option value="Ожидает оплаты"<?php if($z_status=='Ожидает оплаты') echo ' selected '?>>Ожидает оплаты</option>


                    <option value="Подписание документов"<?php if($z_status=='Подписание документов') echo ' selected '?>>Подписание документов</option>
                    <option value="Нет свободных кают"<?php if($z_status=='Нет свободных кают') echo ' selected '?>>Нет свободных кают</option>
                    <option value="Закрыта"<?php if($z_status=='Закрыта') echo ' selected '?>>Закрыта</option>
                </select>

                <div id="infoflotAjax">
                    <label for="z_places">Список мест:</label>

                    <?php
                    $places=explode('||',$places->value);
                    foreach($places as $place)
                    {

                        $place=explode('-',$place);

                       $place_id=explode(':',$place[0]);
                       $place_name=explode(':',$place[1]);
                       $place_type=explode(':',$place[2]);
                       $place_position=explode(':',$place[3]);
                       $place_status=explode(':',$place[4]);
                        if( $place_name[1]!='')
                        {
                            ?>
                            <div class="w-radio">
                                <input type="radio" name="z_place" value="<?php echo $place_id[1]; ?>"
                                <label  class="w-form-label"> Место: <?php echo $place_id[1]; ?></label>
                            </div>
                            <?php
                        }


                    }
                    ?>
                    <div id="infoflot-reponse"></div>
                    <input onclick="ZAdmin.InfoflotSend();"
                        type="button" value="Отправить в инфофлот"  data-loading-text="Секундочку..."
                           class="w-button z-form-button" id="InfoflotSend" />
                </div>

                <?php
                /*Поля заявки*/
                $tv_array=array('z_status','z_doc1');
                foreach($Z->TV_Full as $tv)
                {
                    if(!in_array($tv->name,$tv_array))
                    {
                        if($tv->type=='textarea')
                        {
                            ?>
                            <label for="<?php echo $tv->name; ?>"><?php echo $tv->caption .' ('.$tv->name.')';?>:</label>
                            <textarea id="<?php echo $tv->name ?>" type="text" placeholder=""
                                   name="<?php echo $tv->name ?>" class="w-input"><?php echo $tv->value ?></textarea>
                            <?php
                        }
                        else {
                            ?>
                            <label for="<?php echo $tv->name ?>"><?php echo $tv->caption .' ('.$tv->name.')';?>:</label>
                            <input id="<?php echo $tv->name ?>" type="text" placeholder=""
                                   name="<?php echo $tv->name ?>" id="<?php echo $tv->name ?>"
                                   value="<?php echo $tv->value ?>" class="w-input">
                            <?php
                        }
                    }
                }
                ?>


                <h4 class="z-form-h4">Сопроводительные документы:</h4>
                <label for="doc">Документ1:</label>
                <input id="doc" type="file" placeholder="Example Text" name="doc1" data-name="doc1"
                       class="w-input">
                <input type="submit" value="Изменить" data-wait="Please wait..."
                       class="w-button z-form-button">
                <input type="button" value="Отмена"  data-wait="Please wait..."
                       class="w-button z-form-button cacel" data-dismiss="modal">
            </form>
        </div>
    </div>
</div>

<!--
<div class="z-modal">
    <h2 class="z-modal-title">Редактирование заявки №<?php echo $z_id; ?></h2>
    <div class="z-modal-form">
        <div class="w-form">
            <form    id="z-form"
                   name="email-form-5" data-name="Email Form 5"
                   enctype="multipart/form-data" method="POST">

                <input type="hidden" name="z_id" id="z_id" value="<?php echo $z_id; ?>">
                <input type="hidden" name="action" value="z_update">

                <label for="z_status">Статус:</label>
                <select id="z_status" name="z_status" data-name="z_status"  class="w-select">
                    <option value="Новый" <?php if($z_status=='Новый') echo ' selected '?>>Новый</option>
                    <option value="Отправить в инфофлот" <?php if($z_status=='Отправить в инфофлот') echo ' selected '?>>Отправить в инфофлот</option>
                    <option value="Проверка свободных мест"<?php if($z_status=='Проверка свободных мест') echo ' selected '?>>Проверка свободных мест</option>
                    <option value="Ожидает оплаты"<?php if($z_status=='Ожидает оплаты') echo ' selected '?>>Ожидает оплаты</option>


                    <option value="Подписание документов"<?php if($z_status=='Подписание документов') echo ' selected '?>>Подписание документов</option>
                    <option value="Нет свободных кают"<?php if($z_status=='Нет свободных кают') echo ' selected '?>>Нет свободных кают</option>
                    <option value="Закрыта"<?php if($z_status=='Закрыта') echo ' selected '?>>Закрыта</option>

                </select>
                <h4 class="z-form-h4">Сопроводительные документы:</h4>
                <label for="doc">Документ1:</label>
                <input id="doc" type="file" placeholder="Example Text" name="doc1" data-name="doc1"
                    class="w-input">
                <input type="submit" value="Изменить" data-wait="Please wait..."
                                           class="w-button z-form-button">
                <input type="button" value="Отмена"  data-wait="Please wait..." onclick="ZCancel();"
                                     class="w-button z-form-button cacel">
            </form>
        </div>
    </div>
</div>
-->
<!--
<form id="email-form" name="email-form" enctype="multipart/form-data" method="POST">
    <label>Заказчик: <?php echo $Z->TV['z_user_name'] ;?></label>
    <label>Телефон: <?php echo $Z->TV['z_user_phone'] ;?></label>

    <label>Эл. почта: <?php echo $Z->TV['z_user_email'] ;?></label>
    <br>
    <label>Теплоход: <?php echo $ship->title; ?></label>
    <label>Круиз: <?php echo $cruis->TV['kr_route'];?></label>

    <label>Дата круиза: <?php echo $cruis->TV['kr_date_start'];?></label>
    <label>Каюта: <?php echo $Z->TV['k_name'] ;?></label>
    <label>Палуба: <?php echo $Z->TV['k_deck'] ;?></label>
    <br>
    <input type="hidden" name="z_id" id="z_id" value="<?php echo $z_id; ?>">
    <input type="hidden" name="action" value="z_update">
    <label for="name">Статус:</label>
    <select class="w-select" id="z_status" name="z_status" data-name="status">
        <option value="Новый" <?php if($z_status=='Новый') echo ' selected '?>>Новый</option>
        <option value="Проверка свободных мест"<?php if($z_status=='Проверка свободных мест') echo ' selected '?>>Проверка свободных мест</option>
        <option value="Ожидает оплаты"<?php if($z_status=='Ожидает оплаты') echo ' selected '?>>Ожидает оплаты</option>


        <option value="Подписание документов"<?php if($z_status=='Подписание документов') echo ' selected '?>>Подписание документов</option>
        <option value="Нет свободных кают"<?php if($z_status=='Нет свободных кают') echo ' selected '?>>Нет свободных кают</option>
        <option value="Закрыта"<?php if($z_status=='Закрыта') echo ' selected '?>>Закрыта</option>


    </select>
    <label for="doc">Сопроводительные документы:</label>
    <label for="doc">doc1:</label>
    <input class="w-input"
           id="doc"
           type="file"
           name="doc1"
           data-name="doc1"
           >
    <input class="w-button" type="submit" value="Изменить" onclick="ZChange();">
    <input class="w-button" type="button" value="Отмена" onclick="ZCancel();">
</form>
-->