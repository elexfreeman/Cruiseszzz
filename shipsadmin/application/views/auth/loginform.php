<h1 class="center-h1">Администрирование</h1>
<div class="w-form admin-login-div">

        <?php echo form_open("auth/login");?>

        <label for="z_admin_login">Логин:</label>
        <input type="text" name="username" id="z_admin_login" value="" class="w-input">

        <label for="z_admin_password">Пароль:</label>
        <input type="password" name="password" id="z_admin_password" value="" class="w-input">
        <input type="hidden" name="action" id="action" value="zLoginForm">
        <div class="login-error">Не верные логин/пароль!!!</div>
        <input type="submit" value="Войти" data-wait="Please wait..."
               style="width: 182px;"   class="w-button z-form-button">
    </form>
</div>