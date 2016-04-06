function ZChange(z_id)
{
    var s = $('#z-form').serializeArray();
    $.ajax({
        type: 'GET',
        url: '/ajax.html',
        data: s,
        dataType : "json",
        success: function(data) {

        },
        error:  function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });

    var z_status=$( "#z_status option:selected" ).val();


}

function tplZForm(z_id)
{
    //$(".zayavka-div").fadeOut("slow");

    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "tplZForm",
            z_id: z_id
        },
        function (data) {

            $(".z_form_edit").html(data);
            ZAdmin.ShowModal();

        }, "html"
    ); //$.get  END

}

function ZCancel()
{

    $(".z_form_edit").fadeOut();
}


var ZAdmin = {};

//Показывает модал
ZAdmin.ShowModal = function()
{
    $('#zEditModal').modal('show')
}

//скрывает мода
ZAdmin.HideModal = function()
{
    $('#zEditModal').modal('hide')
}

//Модал с теплоходом
ZAdmin.ShipEdit = function(ship_id)
{
    $('#ZAdminEditModal').modal('show');
    $(".ship-modal").html('<img src="images/loader.GIF" style="width: 100px; margin: 20px auto; ">');
    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "tplZAdminShipEditModal",
            ship_id: ship_id
        },
        function (data)
        {
            $(".ship-modal").html(data);
        }, "html"
    ); //$.get  END
}


//todo то не понадобиться
ZAdmin.ShipUpdate = function(ship_id)
{
    $(".chk_pop").each(function (i) {
        console.info($(this).attr('id'));

    });
}

/*Показывает окно редактировния популярного круиза*/
ZAdmin.PopCruisEdit  = function(cruis_id)
{
    $('#PopCruisEdtModal').modal('show');
    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "GetPopCrusModalInfo",
            cruis_id: cruis_id
        },
        function (data)
        {
            $('.ce_id').html(data.id);
            $('#cruis_id').val(data.id);
            $('#pop_direction').val(data.pop_direction);
            $('#pop_title_description').val(data.pop_title_description);
            $('#pop_description').text(data.pop_description);
            setInterval(function() { tinymce.init({ selector:'textarea' }); }, 1000);
           // $(".ship-modal").html(data);
        }, "json"
    ); //$.get  END
}

/*Отправка в инфофлот заявки на круиз*/
ZAdmin.InfoflotSend = function()
{
    var action = $("#action").val();
    $('#infoflot-reponse').html('Загрузка...');
    $('#InfoflotSend').button('loading');
    $("#action").val('InfoflotSend');
    var s = $('#z-form').serializeArray();
    $.ajax({
        type: 'GET',
        url: '/ajax.html',
        data: s,
        dataType : "json",
        success: function(data) {
            $("#action").val('z_update');
            $('#InfoflotSend').button('reset');
            $('#infoflot-reponse').html('Ответ с Инфофлота: '+data.infoflot);
        },
        error:  function(xhr, str){
            $("#action").val('z_update');
            alert('Возникла ошибка: ' + xhr.responseCode);
            $('#InfoflotSend').button('reset');
            $('#infoflot-reponse').html('');
        }
    });
    $("#action").val('z_update');
    $('#InfoflotSend').button('reset');
}


ZAdmin.Login = function()
{
    var s = $('#z-login-form').serializeArray();
    $.ajax({
        type: 'GET',
        url: '/ajax.html',
        data: s,
        dataType : "json",
        success: function(data) {
            if(data.status=='done')
            {
                window.location.href = "/admin-login/z-admin.html";
            }
            else
            {
                $('.login-error').html('Не верные логин/пароль!!!');
                $('.login-error').fadeIn();
                setTimeout( function() {  $('.login-error').fadeOut(); } , 6000)


            }

        },
        error:  function(xhr, str){
            $('.login-error').html('Возникла ошибка: ' + xhr.responseCode);
            $('.login-error').fadeIn();
            setTimeout( function() {  $('.login-error').fadeOut(); } , 6000)
        }
    });
}

ZAdmin.Logout = function()
{
    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "AdminLogout"
        },
        function (data)
        {
          if(data.status='done') window.location.href = "/admin-login/";
        }, "json"
    ); //$.get  END
}



/*Показывает окно редактировния популярного круиза*/
ZAdmin.Delete  = function(cruis_id)
{
    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "CruisSetPop",
            cruis_id: cruis_id,
            val: 'Нет'
        },
        function (data)
        {
            if(data.status='done') window.location.href = "/admin-login/p-admin.html";
        }, "json"
    ); //$.get  END
}
