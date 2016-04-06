<?php
/**
 * Created by PhpStorm.
 * User: folle
 * Date: 29.02.2016
 * Time: 6:55
 */

?>

<div class="w-form admin-login-div">
    <form id="z-login-form">
        <label for="z_admin_login">Логин:</label>
        <input type="text" name="z_admin_login" id="z_admin_login" value="" class="w-input">

        <label for="z_admin_password">Пароль:</label>
        <input type="password" name="z_admin_password" id="z_admin_password" value="" class="w-input">
        <input type="hidden" name="action" id="action" value="zLoginForm">
        <div class="login-error">Не верные логин/пароль!!!</div>
        <input type="button" value="Войти" data-wait="Please wait..." onclick="ZAdmin.Login();"
               style="width: 182px;"   class="w-button z-form-button">
    </form>
</div>
