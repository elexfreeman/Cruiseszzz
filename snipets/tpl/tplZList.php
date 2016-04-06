<?php

if((isset($_POST['action']))and($_POST['action']=='z_update'))
{
    
    $Z=GetPageInfo(mysql_escape_string( $_POST['z_id']));


    /*оБновление статуса заявки*/
 

    //****************************************************
    //====================================================
    
    /*
    $today = date("Y-m-d_H_i_s");
    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/zayavki/';
    $uploadfile = $uploaddir . basename($_FILES['importFile']['name']);

    echo  '<pre>';
    if (move_uploaded_file($_FILES['importFile']['tmp_name'], $uploadfile)) {
        echo  "Файл корректен и был успешно загружен.\n";
      //  $bk_file= $uploaddir."history/".$today."_".$_SERVER['REMOTE_ADDR']."_".basename($_FILES['importFile']['name']);
        //echo $bk_file."<br>";
        //copy($uploadfile,$bk_file);
        try {
          


            }
        catch (Exception $e) { //Если csv файл не существует, выводим сообщение
            echo  "Ошибка: " . $e->getMessage();
        }
            } else {
            echo  "Возможная атака с помощью файловой загрузки!\n";
        }

    */
    //************************************************
    //===============================================
    
    /*Теперь проверяем аакой пришел статус*/
    if($_POST['z_status']=='Проверка свободных мест')
    {
        //Письмо клиенту о провеке свободных мест менеджером
        $clientEmail=$Z->TV['z_user_email'];
      //  $clientEmail='elextraza@gmail.com';
        $subject = "Проверка свободных мест";

        $message = '
        <html>
            <head>
                <title>Проверка свободных мест</title>
            </head>
            <body>
                <p>Наши менеджеры проверяют свободные места в круизе</p>
            </body>
        </html>';

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: berg <info@berg-kruiz.ru>\r\n";
       // $headers .= "Bcc: birthday-archive@example.com\r\n";

        mail($clientEmail, $subject, $message, $headers);
        IncertPageTV($Z->id,'z_status',$_POST['z_status']);
        header("Refresh:1");

    }
    elseif($_POST['z_status']=='Ожидает оплаты')
    {
        //свободные места есть,
        //письмо клиенту с сылкой на оплату
        $clientEmail=$Z->TV['z_user_email'];
        //  $clientEmail='elextraza@gmail.com';
        $subject = "Ожидает оплаты";

        $message = '
        <html>
            <head>
                <title>Ожидает оплаты</title>
            </head>
            <body>
                <p>письмо клиенту с сылкой на оплату</p>
            </body>
        </html>';

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: berg <info@berg-kruiz.ru>\r\n";
        // $headers .= "Bcc: birthday-archive@example.com\r\n";

        mail($clientEmail, $subject, $message, $headers);
        IncertPageTV($Z->id,'z_status',$_POST['z_status']);
        header("Refresh:1");
    }
    elseif($_POST['z_status']=='Подписание документов')
    {
        //Клиент все оплатил
        //высылаем документы клиенту на подпись и получение данных
        //Письмо клиенту о провеке свободных мест менеджером
        $clientEmail=$Z->TV['z_user_email'];
        //  $clientEmail='elextraza@gmail.com';
        $subject = "Подписание документов";

        $message = '
        <html>
            <head>
                <title>Подписание документов</title>
            </head>
            <body>
                <p>Письмо клиенту с документами на подписание</p>
            </body>
        </html>';

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: berg <info@berg-kruiz.ru>\r\n";
        // $headers .= "Bcc: birthday-archive@example.com\r\n";

        mail($clientEmail, $subject, $message, $headers);
        IncertPageTV($Z->id,'z_status',$_POST['z_status']);header("Refresh:1");

    }
    elseif($_POST['z_status']=='Нет свободных кают')
    {
        //писбмо клиенту об отсутствие свободных кают
        //Письмо клиенту о провеке свободных мест менеджером
        $clientEmail=$Z->TV['z_user_email'];
        //  $clientEmail='elextraza@gmail.com';
        $subject = "Нет свободных кают";

        $message = '
        <html>
            <head>
                <title>Нет свободных кают</title>
            </head>
            <body>
                <p>Отсутствую свободные каюты в круизе</p>
            </body>
        </html>';

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: berg <info@berg-kruiz.ru>\r\n";
        // $headers .= "Bcc: birthday-archive@example.com\r\n";

        mail($clientEmail, $subject, $message, $headers);
        IncertPageTV($Z->id,'z_status',$_POST['z_status']);header("Refresh:1");
    }
    elseif($_POST['z_status']=='Закрыта')
    {
        //письмо клиенту за спасибо
        //писбмо клиенту об отсутствие свободных кают
        //Письмо клиенту о провеке свободных мест менеджером
        $clientEmail=$Z->TV['z_user_email'];
        //  $clientEmail='elextraza@gmail.com';
        $subject = "Закрыта";

        $message = '
        <html>
            <head>
                <title>Спасибо</title>
            </head>
            <body>
                <p>Приятного отдыха</p>
            </body>
        </html>';

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: berg <info@berg-kruiz.ru>\r\n";
        // $headers .= "Bcc: birthday-archive@example.com\r\n";

        mail($clientEmail, $subject, $message, $headers);
        IncertPageTV($Z->id,'z_status',$_POST['z_status']);header("Refresh:1");
    }
    elseif($_POST['z_status']=='Отправить в инфофлот')
    {
        echo '<pre>';
        print_R($Z);
        echo '<pre>';
        $result = get_web_page( "http://ya.ru" );
        if (($result['errno'] != 0 )||($result['http_code'] != 200))
        {
            echo $result['errmsg'];
        }
        else
        {
            $page = $result['content'];
            echo $page;
        }
        /*
         * http://api.infoflot.com/JSON/a04a83e5ccb19b661c4c0873d3234287982fb5d3/Requests/8565?
         * cabins[491084]=&
         * places[491085][name]=Турист&
         * places[491085][type]=0&
         * places[491085][birthday]=01.01.1990&
         * submit=1&customer=Покупатель&
         * email=mail@domain.tld&
         * phone=+7(926)123-45-67
         */

    }
}
else
{

$ZList = $this->GetZ();

?>
    <div class="orders">
    <?php
    foreach($ZList as $z_id=>$Z)
    {
        $cruis=GetPageInfo($Z->TV['z_cruis_id']);
        //print_r($cruis);
        $ship=GetPageInfo($Z->TV['z_ship_id']);;
        ?>
        <div style="display: none"><?php print_r($Z); ?></div>
        <div class="w-clearfix ordersitem
        <?php if( $Z->TV['z_status']=='Новая') echo 'new'; ?>
        " onclick="tplZForm(<?php echo $Z->id;?>);">
            <div class="ordernumber">№ <?php echo $Z->id;?><br><?php echo $Z->TV['z_date'];?></div>
            <div class="orderstaus"><?php echo $Z->TV['z_status'];?></div>
            <div class="orderuser"><strong data-new-link="true"><?php echo $Z->TV['z_user_name']; ?></strong>
                <br><?php echo $Z->TV['z_user_email']; ?><br>
                <strong data-new-link="true"><?php echo $Z->TV['z_user_phone']; ?></strong><br>

                <?php echo $Z->TV['z_info']; ?>
            </div>
            <div class="ordership"><?php echo $ship->title; ?></div>
            <div class="ordercruis"><strong data-new-link="true">Дата круиза</strong>: <?php echo $cruis->TV['kr_date_start'];?>
                <br><em data-new-link="true"><?php echo $cruis->TV['kr_route'];?></em><br><strong
                    data-new-link="true">Каюта</strong>: 1154 <br><strong data-new-link="true">Палуба</strong>: Верхня
                супер палуба
            </div>
        </div>

        <?php
    }

    ?>

    </div>
<div class="zayavka-div">

    <?php
    /*
    foreach($ZList as $z_id=>$Z)
    {
        $cruis=GetPageInfo($Z->TV['z_cruis_id']);
        //print_r($cruis);
        $ship=GetPageInfo($Z->TV['z_ship_id']);;
        ?>
        <div class="w-clearfix zayavka">
            <div class="z-user">
                <div class="w-clearfix z-user-block-top"><img src="/images/User_No-Frame.png"  class="z-user-img">

                    <div class="z-user-fio"><?php echo $Z->TV['z_user_name']; ?></div>
                </div>
                <div class="w-clearfix z-user-phone-block"><img src="/images/phone-white.png" class="z-user-img">

                    <div class="z-user-phone"><?php echo $Z->TV['z_userphone']; ?></div>
                </div>
                <div class="w-clearfix z-email"><img src="/images/email-white.png" class="z-user-img">

                    <div class="z-email-text"><?php echo $Z->TV['z_user_email']; ?></div>
                </div>
                <div class="z-user-info-text"><?php echo $Z->TV['z_info']; ?></div>
            </div>
            <div class="z-ship-img">
                <div class="z-ship-title"><?php echo $ship->title; ?></div>
                <div class="z-nomer">№ <?php echo $Z->id;?></div>
                <div class="z-kauta-n">Номер каюты: <?php echo $Z->TV['z_cauta_nomer'];?></div>
            </div>
            <div class="z-status"><strong data-new-link="true">Статус заявки:</strong>
                <?php echo $Z->TV['z_status'];?></div>
            <div class="z-button-edit" onclick="tplZForm(<?php echo $Z->id;?>);">Изменить</div>
            <div class="z-date-create"><strong data-new-link="true">
                    Дата создания:</strong> <?php echo $Z->TV['z_date'];?></div>
            <div class="z-cruis-date"><strong data-new-link="true">
                    Дата круиза</strong>: <?php echo $cruis->TV['kr_date_start'];?></div>
            <div class="z-cr-route"><?php echo $cruis->TV['kr_route'];?></div>
        </div>




        <?php
    }
*/
    ?>
</div>



    <!-- Modal -->
    <div class="modal fade" id="zEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content z_form_edit">

            </div>
        </div>
    </div>


<?php
}
