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




    function rus2translit($string) {
        $converter = array(
            '�' => 'a',   '�' => 'b',   '�' => 'v',
            '�' => 'g',   '�' => 'd',   '�' => 'e',
            '�' => 'e',   '�' => 'zh',  '�' => 'z',
            '�' => 'i',   '�' => 'y',   '�' => 'k',
            '�' => 'l',   '�' => 'm',   '�' => 'n',
            '�' => 'o',   '�' => 'p',   '�' => 'r',
            '�' => 'c',   '�' => 't',   '�' => 'u',
            '�' => 'f',   '�' => 'h',   '�' => 'c',
            '�' => 'ch',  '�' => 'sh',  '�' => 'sch',
            '�' => '\'',  '�' => 'y',   '�' => '\'',
            '�' => 'e',   '�' => 'yu',  '�' => 'ya',

            '�' => 'A',   '�' => 'B',   '�' => 'V',
            '�' => 'G',   '�' => 'D',   '�' => 'E',
            '�' => 'E',   '�' => 'Zh',  '�' => 'Z',
            '�' => 'I',   '�' => 'Y',   '�' => 'K',
            '�' => 'L',   '�' => 'M',   '�' => 'N',
            '�' => 'O',   '�' => 'P',   '�' => 'R',
            '�' => 'C',   '�' => 'T',   '�' => 'U',
            '�' => 'F',   '�' => 'H',   '�' => 'C',
            '�' => 'Ch',  '�' => 'Sh',  '�' => 'Sch',
            '�' => '_',  '�' => 'Y',   '�' => '_',
            '�' => 'E',   '�' => 'Yu',  '�' => 'Ya',
        );
        return strtr($string, $converter);
    }

    function encodestring($str) {
        // ��������� � ��������
        $str = rus2translit($str);
        // � ������ �������
        $str = strtolower($str);
        // ������� ��� �������� ��� �� "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // ������� ��������� � �������� '-'
        $str = trim($str, "-");


        return $str;
    }

//���� �� ��������

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

        //modx_site_tmplvar_templates - ������� ����� ����� ������ � ���������
        //modx_site_tmplvar_contentvalues - ������� �������� ����� � ��������
        //modx_site_tmplvars - ����
        //modx_site_content - ��������

        $sql="select * from " . $this->table_prefix . "site_tmplvar_contentvalues where (contentid='".$page_id."')and(tmplvarid=".$tv_id.") ";
        $c_tv_id=0;
        $query = $this->CI->dbMySQL->query($sql);
        foreach ($query->result_array() as $row_c_tv) {
            $c_tv_id = $row_c_tv['id'];
        }

        if ($c_tv_id == 0) {
            $sql_modx_vars = "INSERT INTO " . $this->table_prefix . "site_tmplvar_contentvalues
(tmplvarid,contentid,value) VALUES ('" . $tv_id . "','".$page_id."','".EscapeString($tv_value)."');";
            //   echo $sql_modx_vars . "<br>";
            $this->dbMySQL->query($sql_modx_vars);

        } else {
            $sql_modx_vars = "update " . $this->table_prefix . "site_tmplvar_contentvalues
            set value='".EscapeString($tv_value)."' where  (tmplvarid='" . $tv_id . "')and(contentid='".$page_id."')";
            //echo $sql_modx_vars;
            $this->CI->dbMySQL->query($sql_modx_vars);
        }
    }


    /*��������� �������� � ModX �� �������*/
    function IncertPage($page)
    {

        /*
       * �������� ������� Ship
       * $page->pagetitle - �������� �������
       * $page->parent=2 - ��������
       * $page->template=2 - ������
       * $page->url=2 - ������
       * $page->TV['t_title']
       * $page->TV['t_inner_id']
       * $page->TV['t_title_img']
       *
       *$page->alias = encodestring($Ship->TV['t_inner_id'].'_'.$Ship->TV['t_title']);
       *$page->url="ships/" .$Ship->alias . ".html"
       * */

        //����������� ��������

        //���� ����� ��������
        $product_id = 0;
        $page->pagetitle = $this->CI->security->xss_clean($page->pagetitle);
        $sql_page = "select * from " . $this->table_prefix . "site_content where pagetitle='" . $page->pagetitle . "'";
        // echo $sql_page;
        $query = $this->CI->dbMySQL->query($sql_page);
        foreach ($query->result_array() as $row_page) {
            $product_id = $row_page['id'];
        }


        if ($product_id == 0) {
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
VALUES (NULL, 'document', 'text/html', '" .  $page->pagetitle . "', '', '', '" . $page->alias . "',
'', true, 0, 0, " . $page->parent . ", false, '', '', true, " . $page->template . ", 1, true, true, 1, 1421901846, 0, 0, false, 0, 0, 1421901846, 1, '',
false, false, false, false, false, 'modDocument', 'web', 1,
 '" . $page->url . "', false, false, true, null
 );

;";

            $this->CI->dbMySQL->query($sql_product);
            $product_id=$this->CI->dbMySQL->last_query();

            if((isset($page->echo))and($page->echo)) echo "INCERT ".$product_id."\r\n"."<br>";
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
            //������ �������������� ����
            // - 1 - ���� ��� �������, �� ��� ���� �������������� ���
            $tv = $this->GetContentTV($page_id);
            $product->TV = $tv;
            $product->TV_Full =$this->GetContentTVFull($page_id);
        }
        return $product;
    }


//���� �� ��� �������� �� ��������������� �� id
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