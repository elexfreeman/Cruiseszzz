<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Functions {

    public $CI;

    public $table_prefix;
    public function __construct()
    {
        global $table_prefix;

        $this->CI =& get_instance();
        $this->CI->dbMySQL = $this->CI->load->database('default', TRUE);
        $this->table_prefix="modx_";
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

//Инфо по продукту

    function GetContentTV($content_id)
    {


        $sql = "select
                            tv.name,
                            cv.value

                            from " . $this->table_prefix . "site_tmplvar_contentvalues cv

                            join " . $this->table_prefix . "site_tmplvars tv
                            on tv.id=cv.tmplvarid

                            where cv.contentid=" . $content_id;

        // echo $sql_tv;
        $query = $this->CI->dbMySQL->query($sql);
        $tv='';
        foreach ($query->result_array() as $row_tv) {
            $tv[$row_tv['name']] = $row_tv['value'];
        }
        return $tv;
    }


    function GetTV_Id_ByName($TV_name)
    {


        $TV_id=0;
        $sql="select * from ".$this->table_prefix."site_tmplvars where name='".$TV_name."'";

        //echo $sql;
        $query = $this->CI->dbMySQL->query($sql);
        foreach ($query->result_array() as $row_tv) {
            $TV_id = $row_tv['id'];
        }

        return $TV_id;
    }

    public function IncertPageTV($page_id,$tv_name,$tv_value)
    {
        $tv_id=$this->GetTV_Id_ByName($tv_name);

        //modx_site_tmplvar_templates - содежит связь между полями и шаблонами
        //modx_site_tmplvar_contentvalues - содежит значения полей в странице
        //modx_site_tmplvars - поля
        //modx_site_content - страницы

        $page_id=$this->CI->security->xss_clean($page_id);
        $tv_id=$this->CI->security->xss_clean($tv_id);
        $tv_value=$this->CI->security->xss_clean($tv_value);


        $sql="select * from " . $this->table_prefix . "site_tmplvar_contentvalues
        where (contentid='".$page_id."')and(tmplvarid=".$tv_id.") ";

        $c_tv_id=0;
        $query = $this->CI->dbMySQL->query($sql);
        foreach ($query->result_array() as $row_c_tv) {
            $c_tv_id = $row_c_tv['id'];
        }

        if ($c_tv_id == 0) {
            $sql_modx_vars = "INSERT INTO " . $this->table_prefix . "site_tmplvar_contentvalues
            (tmplvarid,contentid,value)
             VALUES
            ('" . $tv_id . "','".$page_id."','".$this->CI->dbMySQL->escape_str($tv_value)."');";
              // echo $sql_modx_vars . "<br>";
            $this->CI->dbMySQL->query($sql_modx_vars);

        } else {
            $sql_modx_vars = "update " . $this->table_prefix . "site_tmplvar_contentvalues
            set value='".$this->CI->dbMySQL->escape_str($tv_value)."' where  (tmplvarid='" . $this->CI->dbMySQL->escape_str($tv_id)
                . "')and(contentid='".$this->CI->dbMySQL->escape_str($page_id)."')";
            //echo $sql_modx_vars;
            $this->CI->dbMySQL->query($sql_modx_vars);
        }
    }


    /*Вставляет страницу в ModX из объекта*/
    function IncertPage($page)
    {

        /*

       * $page->id
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

        if(isset($page->pagetitle)) $page->pagetitle = $this->CI->security->xss_clean($page->pagetitle);
        if(isset($page->id)) $page->id = (int)$page->id;
        if(isset($page->alias)) $page->alias = $this->CI->security->xss_clean($page->alias);
        if(isset($page->longtitle)) $page->longtitle = $this->CI->security->xss_clean($page->longtitle);
        if(isset($page->url)) $page->url = $this->CI->security->xss_clean($page->url);


        if((isset($page->id))and($page->id!=0))
        {
            $product_id=$page->id;

            if(isset($page->pagetitle))
            {
                $sql="UPDATE " . $this->table_prefix . "site_content
            SET pagetitle = '" . $page->pagetitle . "' where id='".$page->id."' ;";
                $this->CI->dbMySQL->query($sql);
            }

            if(isset($page->longtitle))
            {
                $sql = "UPDATE " . $this->table_prefix . "site_content
            SET longtitle = '" . $page->pagetitle . "' where id='" . $page->id . "' ;";
                $this->CI->dbMySQL->query($sql);
            }

            if(isset($page->longtitle))
            {
                $sql = "UPDATE " . $this->table_prefix . "site_content
            SET alias = '" . $page->alias . "' where id='" . $page->id . "' ;";
                $this->CI->dbMySQL->query($sql);
            }
        }
        else
        {

            $sql_product = "INSERT INTO " . $this->table_prefix . "site_content
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
VALUES (NULL, 'document', 'text/html', '" . $page->pagetitle . "', '', '', '" . $page->alias . "',
'', true, 0, 0, " . $page->parent . ", false, '', '', true, " . $page->template . ", 1, true, true, 1, 1421901846, 0, 0, false, 0, 0, 1421901846, 1, '',
false, false, false, false, false, 'modDocument', 'web', 1,
 '" . $page->url . "', false, false, true, null
 );

;";
           // echo $sql_product;

            $this->CI->dbMySQL->query($sql_product);

            $product_id = $this->CI->dbMySQL->insert_id();

            if ((isset($page->echo)) and ($page->echo)) echo "INCERT " . $product_id . "\r\n" . "<br>";
        }
        //Ищем такую страницу



        if ($product_id == 0)
        {

        }
        else
        {
            if((isset($page->echo))and($page->echo)) echo "UPDAte PAge".$product_id."\r\n"."<br>";
        }
        foreach($page->TV as $TV_name=>$TV_value)
        {
            $this->IncertPageTV($product_id,$TV_name,$TV_value);
        }
        if((isset($page->echo))and($page->echo)) print_r($page);

        return $product_id;
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

                            from " . $this->table_prefix . "site_tmplvar_contentvalues cv

                            join " . $this->table_prefix . "site_tmplvars tv
                            on tv.id=cv.tmplvarid

                            join " . $this->table_prefix . "categories category
                            on tv.category=category.id

                             join " . $this->table_prefix . "site_tmplvar_templates tvt
                            on tvt.tmplvarid=tv.id


                            where cv.contentid=" . $content_id . " order by category.category ,tvt.rank ";

        // echo $sql_tv;
        $tv=array();
        $query = $this->CI->dbMySQL->query($sql_tv);
        foreach ($query->result_array() as $row_tv) {
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

    public function GetPageInfo($page_id,$delete=true)
    {

        $page_id = $this->CI->security->xss_clean($page_id);

        $product = new stdClass();
        $product->id = 0;
        $sql = "select * from ".$this->table_prefix."site_content where (id=" . $page_id.")";
        if($delete)  $sql.=" and(deleted=0)";
        $query = $this->CI->dbMySQL->query($sql);


        foreach ($query->result_array() as $row) {

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
            $tv = $this->GetContentTV($page_id);
            $product->TV = $tv;
            $product->TV_Full =$this->GetContentTVFull($page_id);
        }
        return $product;
    }


//Инфа по все потомкам не отсортированный по id
    function GetChildListNoSort($obj_id,$template,$deleted=true)
    {

        $objects=Array();
        $sql = "select * from " . $this->table_prefix . "site_content
    where (parent=" . $obj_id.")and(template=".$template.")";
        if(!$deleted) $sql.=" and(deleted='0')";
        $sql.=" order by menuindex";

        //echo $sql;
        $query = $this->CI->dbMySQL->query($sql);
        foreach ($query->result_array() as $row)
        {
            $objects[]=$this->GetPageInfo($row['id']);
        }
        return $objects;
    }

}