<?php

/**
 * Created by PhpStorm.
 * User: folle
 * Date: 06.01.2016
 * Time: 23:11
 *
 * Класс для работы с отзывами
 */
require_once 'functions.php';
class reviews
{
    public $parent_id = 86817;
    public $template = 20;
    public $info;

    function __construct()
    {
        $this->info = GetChildList($this->parent_id,$this->template);
    }

    function SendEmail($review)
    {
        //$adminEmail[2] = "irina.matina@bk.ru";
        $adminEmail[1] = "elextraza@gmail.com";
        $from = 'shop@berg-kruiz.ru';   // от кого
        $mailheaders = "Content-type:text/html;charset=utf8;From:" . $from;

        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $mailheaders = "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-type: text/html; charset=utf8\r\n";

        /* дополнительные шапки */
        $mailheaders .= "From: berg-kruiz.ru <" . $from . ">\r\n";

        $message = '
        <h3>Отзыв с сайта berg-kruiz.ru<h3>
        <table>
        <tr>
        <td>Имя</td>
        <td>VK</td>
        <td>Текст</td>
        </tr>

        <tr>
        <td>'.$review->TV['r_name'].'</td>
        <td>'.$review->TV['r_vk'].'</td>
        <td>'.$review->TV['r_text'].'</td>
        </tr>
</tr>
        </table>

        ';



        $subject = "berg-kruiz.ru Отзыв от: ".$review->TV['r_name'];

        foreach ($adminEmail as $email_a) {
            mail($email_a, $subject, $message, $mailheaders);
        }
    }

    function SendReview()
    {
        $mainPage=GetPageInfo(1);

        $adminEmail=explode(';',$mainPage->TV['adminEmailList']);
        $rnd=PassGen();
        $page = new stdClass();
        $page->pagetitle = $rnd.'_'.$_GET['review_name'];
        $page->parent = $this->parent_id;
        $page->template = $this->template;

        $page->TV['review_name'] =$_GET['review_name'];
        $page->TV['review_work'] = $_GET['review_work'];
        $page->TV['review_text'] = $_GET['review_text'];

        $page->alias = encodestring($page->pagetitle);
        $page->url = "reviews/" . $page->alias . ".html";
        IncertPage($page);
        $res['page']=$page;

        print_r($page);

        $subject = "berg-kruiz.ru Отзыв от: ".$page->TV['review_name'];
        $message = '
        <h3>Отзыв с сайта berg-kruiz.ru<h3>
        <b>Имя:</b> '.$page->TV['review_name'].'
        <br><br>
        <b>Текст:</b> <br>'.$page->TV['review_text'].'


        ';
        foreach ($adminEmail as $email_a) {
            $res[$email_a]=elex_send_email($email_a, $subject, $message);
        }
        echo json_encode($res);
        //$this->SendEmail($page);
    }
    //выдает отзывы для главной страницы
    function GetForMainPage()
    {
        $reviews=array();
        foreach($this->info as $review)
        {
            if($review->TV['review_show_in_main']=='Да') $reviews[]=$review;
        }
        return $reviews;
    }

    function tplReviewMainPage()
    {
        include 'tpl/tplReviewMainPage.php';
    }

    function tplReviews()
    {

        include 'tpl/tplReviews.php';
    }

    function run($scriptProperties)
    {

        if($scriptProperties['action']=='tplReviewMainPage')
        {
            $this->tplReviewMainPage();
        }
        if($scriptProperties['action']=='tplReviews')
        {
            $this->tplReviews();
        }

    }

}