<?php
/**
 * Created by PhpStorm.
 * User: elex
 * Date: 29.09.15
 * Time: 6:40
 */
require_once "Security.php";


/*Безопасная вставка вместо mysql_escape_String*/
/*Взято из CodeIgniter*/
function EscapeString($s)
{
    $sec = New CI_Security();

    return $sec->xss_clean($s);
    ///return $s;
}

$shipKey='ad441cf7449bc9af3977e6b0c2a6806e3655247c';
/*Очищает $_GET*/
function ClearGet()
{
    foreach ($_GET as $key=>$val)
    {
        $_GET[$key]=EscapeString($_GET[$key]);
    }
}


function tmbImg($tv,$options='&h=300&w=300&zc=1')
{
    global $modx;
    $properties = Array();
    $properties['input'] = $tv;
    $properties['options'] = $options;
    return $modx->runSnippet('phpthumbsup', $properties);
}


function elex_send_email($to,$Subject,$Body)
{
    require_once 'mailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->CharSet = "UTF-8";
    $mail->Host = 'smtp.yandex.ru';  // Specify main and backup server
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'zakaz@berg-tour.ru';                            // SMTP username
    $mail->Password = 'niceberg';                           // SMTP password
    $mail->SMTPSecure = 'tls';
    // Enable encryption, 'ssl' also accepted

    $mail->SMTP_PORT = 465;

    $mail->From = 'zakaz@berg-tour.ru';
    $mail->FromName = 'Круиз';
#$mail->addAddress('josh@example.net', 'Josh Adams');  // Add a recipient
    $mail->addAddress($to);               // Name is optional
#$mail->addReplyTo('info@example.com', 'Information');
    //  $mail->addCC('elex@medlan.samara.ru');
    //   $mail->addBCC('elex@medlan.samara.ru');

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
#$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
#$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $Subject;
    $mail->Body    = $Body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $res['error']=0;
    if(!$mail->send()) {
        $res['error']=1;
        $res['error_msg']=$mail->ErrorInfo;

    }

    return $res;
}

function get_web_page( $url )
{
    $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

    $ch = curl_init( $url );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
    curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
    curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

//генератор паролей
function PassGen($max=10)
{
    // Символы, которые будут использоваться в пароле.
    $chars="qazxswedcvfrtgbnhyujmkip23456789QAZXSWEDCVFRTGBNHYUJMKLP";
    // Количество символов в пароле.

    // Определяем количество символов в $chars
    $size=StrLen($chars)-1;

    // Определяем пустую переменную, в которую и будем записывать символы.
    $password=null;

    // Создаём пароль.
    while($max--)
        $password.=$chars[rand(0,$size)];

    // Выводим созданный пароль.
    return $password;
}


function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 'c',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'C',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '_',  'Ы' => 'Y',   'Ъ' => '_',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}

function encodestring($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");


    return $str;
}

function GetPageInfo($page_id)
{
    global $modx;
    global $table_prefix;
    $product = new stdClass();
    $product->id = 0;
    $sql = "select * from " . $table_prefix . "site_content

    where (id=" . $page_id.")and(deleted=0)";
    foreach ($modx->query($sql) as $row) {

        $product->id = $row['id'];
        $product->introtext = $row['introtext'];
        $product->description = $row['description'];
        $product->title = $row['pagetitle'];
        $product->url = $row['uri'];
        $product->alias = $row['alias'];
        $product->parent = $row['parent'];
        $product->content = $row['content'];
        //теперь дополнительные поля
        // - 1 - если это подарки, то тут нету дополнительных цен
        $tv = GetContentTV($page_id);
        $product->TV = $tv;
        $product->TV_Full =GetContentTVFull($page_id);


    }
    return $product;
}

/*Пометить на удаление страницу*/
function PageDelete($page_id)
{
    global $modx;
    global $table_prefix;
    $sql="update  " . $table_prefix . "site_content
set deleted=1
where id=".$page_id;
    echo $sql;
    $modx->query($sql);
}

//Инфо по продукту

function GetContentTV($content_id)
{
    global $modx;
    global $table_prefix;
    $sql_tv = "select
                            tv.name,
                            cv.value

                            from " . $table_prefix . "site_tmplvar_contentvalues cv

                            join " . $table_prefix . "site_tmplvars tv
                            on tv.id=cv.tmplvarid

                            where cv.contentid=" . $content_id;

    // echo $sql_tv;
    foreach ($modx->query($sql_tv) as $row_tv) {
        $tv[$row_tv['name']] = $row_tv['value'];
    }
    return $tv;
}


function GetContentTVFull($content_id)
{
    global $modx;
    global $table_prefix;
    $sql_tv = "select
                              tv.name,
                           tv.caption,
                           tv.`type` m_type,
                           cv.value,
                           category.category,
                           tvt.rank

                            from " . $table_prefix . "site_tmplvar_contentvalues cv

                            join " . $table_prefix . "site_tmplvars tv
                            on tv.id=cv.tmplvarid

                            join " . $table_prefix . "categories category
                            on tv.category=category.id

                             join modx_site_tmplvar_templates tvt
                            on tvt.tmplvarid=tv.id


                            where cv.contentid=" . $content_id . " order by category.category ,tvt.rank ";

    // echo $sql_tv;
    $tv=array();
    foreach ($modx->query($sql_tv) as $row_tv) {
        $obj = new stdClass();
        $obj->name=$row_tv['name'];
        $obj->caption=$row_tv['caption'];
        $obj->type=$row_tv['m_type'];
        $obj->value=$row_tv['value'];
        $obj->category=$row_tv['category'];
        $tv[$row_tv['name']]=$obj;

    }
    return $tv;
}

function GetTV_Id_ByName($TV_name)
{
    global $modx;
    global $table_prefix;

    $TV_id=0;
    $sql="select * from ".$table_prefix."site_tmplvars where name='".$TV_name."'";

    //echo $sql;
    foreach ($modx->query($sql) as $row_tv) {
        $TV_id = $row_tv['id'];
    }

    return $TV_id;
}

function IncertPageTV($page_id,$tv_name,$tv_value)
{
    global $modx;
    global $table_prefix;

    $tv_id=GetTV_Id_ByName($tv_name);


    //modx_site_tmplvar_templates - содежит связь между полями и шаблонами
    //modx_site_tmplvar_contentvalues - содежит значения полей в странице
    //modx_site_tmplvars - поля
    //modx_site_content - страницы

    $sql="select * from " . $table_prefix . "site_tmplvar_contentvalues where (contentid='".$page_id."')and(tmplvarid=".$tv_id.") ";
    $c_tv_id=0;
    foreach ($modx->query($sql) as $row_c_tv) {
        $c_tv_id = $row_c_tv['id'];
    }

    if ($c_tv_id == 0) {
        $sql_modx_vars = "INSERT INTO " . $table_prefix . "site_tmplvar_contentvalues
(tmplvarid,contentid,value) VALUES ('" . $tv_id . "','".$page_id."','".EscapeString($tv_value)."');";
     //   echo $sql_modx_vars . "<br>";
        $modx->query($sql_modx_vars);
    } else {
        $sql_modx_vars = "update " . $table_prefix . "site_tmplvar_contentvalues
            set value='".EscapeString($tv_value)."' where  (tmplvarid='" . $tv_id . "')and(contentid='".$page_id."')";

       //echo $sql_modx_vars;
        $modx->query($sql_modx_vars);
    }
}


/*Вставляет страницу в ModX из объекта*/
function IncertPage($page)
{
    global $modx;
    global $table_prefix;

    /*
   * Описание объекта Ship
   * $page->pagetitle - Название корабля
   * $page->parent=2 - Родитель
   * $page->template=2 - Шаблон
   * $page->url=2 - Шаблон
   * $page->TV['t_title']
   * $page->TV['t_inner_id']
   * $page->TV['t_title_img']
   *
   *$page->alias = encodestring($Ship->TV['t_inner_id'].'_'.$Ship->TV['t_title']);
   *$page->url="ships/" .$Ship->alias . ".html"
   * */

    //импортируем страницы

    //Ищем такую страницу
    $product_id = 0;
    $sql_page = "select * from " . $table_prefix . "site_content where pagetitle='" . EscapeString($page->pagetitle) . "'";
   // echo $sql_page;
    foreach ($modx->query($sql_page) as $row_page) {
        $product_id = $row_page['id'];
    }


    if ($product_id == 0) {
        $sql_product = "INSERT INTO " . $table_prefix . "site_content
(id, type, contentType, pagetitle, longtitle,
description, alias, link_attributes,
published, pub_date, unpub_date, parent,
isfolder, introtext, content, richtext,
template, menuindex, searchable,
cacheable, createdby, createdon,
editedby, editedon, deleted, deletedon,
deletedby, publishedon, publishedby,
menutitle, donthit, privateweb, privatemgr,
content_dispo, hidemenu, class_key, context_key,
content_type, uri, uri_override, hide_children_in_tree,
show_in_tree, properties)
VALUES (NULL, 'document', 'text/html', '" .  EscapeString($page->pagetitle) . "', '', '', '" . $page->alias . "',
'', true, 0, 0, " . $page->parent . ", false, '', '', true, " . $page->template . ", 1, true, true, 1, 1421901846, 0, 0, false, 0, 0, 1421901846, 1, '',
false, false, false, false, false, 'modDocument', 'web', 1,
 '" . $page->url . "', false, false, true, null
 );

;";

        $modx->query($sql_product);
        $product_id = $modx->lastInsertId();
        if($page->echo) echo "INCERT ".$product_id."\r\n"."<br>";
    }
    else
    {
        if($page->echo) echo "UPDAte PAge".$product_id."\r\n"."<br>";
    }
    foreach($page->TV as $TV_name=>$TV_value)
    {
        IncertPageTV($product_id,$TV_name,$TV_value);
    }
    if($page->echo) print_r($page);

    return $product_id;
}

//Инфа по все потомкам
function GetChildList($obj_id,$template)
{
    global $modx;
    global $table_prefix;

    $objects=Array();
    $sql = "select * from " . $table_prefix . "site_content
    where (parent=" . $obj_id.")and(template=".$template.")
    order by menuindex";

    //echo $sql;
    foreach ($modx->query($sql) as $row)
    {
        $objects[$row['id']]=GetPageInfo($row['id']);
    }
    return $objects;
}



//Инфа по все потомкам не отсортированный по id
function GetChildListNoSort($obj_id,$template)
{
    global $modx;
    global $table_prefix;

    $objects=Array();
    $sql = "select * from " . $table_prefix . "site_content
    where (parent=" . $obj_id.")and(template=".$template.")
    order by menuindex";

    //echo $sql;
    foreach ($modx->query($sql) as $row)
    {
        $objects[]=GetPageInfo($row['id']);
    }
    return $objects;
}


/*удаляет из номера телефона лишние символы*/
function regexPhone($phone)
{
    return preg_replace('/[^0-9]/', '', $phone);
}