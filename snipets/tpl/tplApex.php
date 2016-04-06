<?php
//https://<serviceUrl>?[LOGIN=value&PASS=value&]ORDER=value
/*Проверяем входные условия*/
/*Логин и пароль*/
/*LOGIN=apex45*/
/*PASS=RTYhgfdr465*/
$today = date("YmdHis");
if(($_GET['LOGIN']=='apex45')and($_GET['PASS']=='RTYhgfdr465'))
{
    $order = GetPageInfo($_GET['CODE1']);

    $cruis = GetPageInfo($order->TV['z_cruis_id']);

    if(($order->id>0)and($order->parent==$this->ZayavkaParent))
    {
        $z_passj = json_decode($order->TV['z_passj']);
        ?><?xml version="1.0" encoding="win-1251" ?>
<RESPONSE>
    <RESULTCODE><?PHP /*сДЕСЬ НУЖНО ПРОВЕРЯТЬ ЗАЯВКУ НА УЖЕ ОПЛАЧЕННОСТЬ*/ ?>0</RESULTCODE>
    <RESULTMESSAGE>string</RESULTMESSAGE>
    <DATE><?php echo $today;    ?></DATE>
    <ADDINFO>
        <AGENCY>
            <NAME>ООО "Берг тур"</NAME>
            <BRANCH></BRANCH>
            <INN>6315008371</INN>
        </AGENCY>
        <FULLPRICE></FULLPRICE>
        <CURRENCY>RUB</CURRENCY>
        <AMOUNTTOPAY></AMOUNTTOPAY>
        <EXCHANGERATE>1</EXCHANGERATE>
        <AMOUNTTOPAYRUB><?php echo $order->TV['z_price']*(count($z_passj)/2); ?></AMOUNTTOPAYRUB>
        <AGENCYCOMISSION></AGENCYCOMISSION>
        <STARTDATE><?php
            $date = new DateTime($cruis->TV['kr_date_start']);
            echo $date->format('YmdHis');

            ?></STARTDATE>
        <ENDDATE><?php
            $date = new DateTime($cruis->TV['kr_date_end']);
            echo $date->format('YmdHis');
            ?></ENDDATE>
        <TOURISTLIST>
            <?php

            $i=0;
            while($i <count($z_passj))
            {
                ?>
                <TOURIST>
                    <FIRSTNAME><?php echo $z_passj[$i]->name; ?></FIRSTNAME>
                    <LASTNAME><?php echo $z_passj[$i]->name;; ?></LASTNAME>
                    <PATRONYMIC></PATRONYMIC>
                    <BIRTHDATE><?php
                        $date = strtotime('01.01.2016');
                        echo date('YmdHis',$date);  ?></BIRTHDATE>
                </TOURIST>
                <?php
                $i=$i+2;
            }
            ?>

        </TOURISTLIST>
        <PAYER>
            <FIRSTNAME><?php echo $order->TV['z_user_name']; ?></FIRSTNAME>
            <LASTNAME><?php echo $order->TV['z_user_lastname']; ?></LASTNAME>
            <PATRONYMIC></PATRONYMIC>
            <BIRTHDATE><?php
                $date = strtotime($order->TV['z_user_birthday']);
                echo date('YmdHis',$date);  ?></BIRTHDATE>
        </PAYER>
        <SERVICELIST>
            <SERVICE></SERVICE>
        </SERVICELIST>
    </ADDINFO>
</RESPONSE>
<?php
    }
    else
    {
        ?>
<?xml version="1.0" encoding="utf8" ?>
<RESPONSE>
    <RESULTCODE>1</RESULTCODE>
    <RESULTMESSAGE>Заявка с данным номером не существует.</RESULTMESSAGE>
    <DATE></DATE>
</RESPONSE>
        <?php
    }


}