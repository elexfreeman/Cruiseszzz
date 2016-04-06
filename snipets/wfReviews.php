<?php

/**
 * Created by PhpStorm.
 * User: folle
 * Date: 04.02.2016
 * Time: 5:40
 */
require_once "functions.php";

class wfReviews
{
    public $reviewTemplate = 20;
    public $rootReview = 86817;
    public $rootTemplate = 19;

    function GetAll()
    {
        return GetChildList($this->rootReview,$this->reviewTemplate);
    }

    function tplReviews()
    {
        global $modx;
        include 'templates/tplReviews.php';
    }
    function SendEmail($review)
    {
        $mainPage=GetPageInfo(1);
        //$adminEmail[2] = "irina.matina@bk.ru";
        $adminEmail=explode(';',$mainPage->TV['adminEmailList']);
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
            elex_send_email($email_a, $subject, $message);
        }
    }

    function SendReview()
    {
        $rnd=PassGen();
        $page = new stdClass();
        $page->pagetitle = $rnd.$_GET['review_name'];
        $page->parent = $this->rootReview;
        $page->template = $this->reviewTemplate;

        $page->TV['r_name'] =$_GET['review_text'];
        $page->TV['review_work'] = $_GET['review_work'];
        $page->TV['review_text'] = $_GET['review_text'];

        $page->alias = encodestring($page->pagetitle);
        $page->url = "reviews/" . $page->alias . ".html";
        IncertPage($page);
        $this->SendEmail($page);
    }


    function Run($scriptProperties)
    {

        if($scriptProperties['action']=='tplReviews') $this->tplReviews();

    }

}