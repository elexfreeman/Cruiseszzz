/**
 * Created by elex on 15-Oct-15.
 */


function Scroll(id) {
    $("html, body").animate({scrollTop: ($('#' + id).offset().top-80)}, 1000);
}


$(function() {

    var date = new Date();
    $("#dtBox").DateTimePicker({"language":"ru"});
    myWidth = parseInt(document.documentElement.clientWidth);
    if(myWidth<750)
    {
        //data-field="date"
        $(".hasDatepicker3").attr("type","date");
    }
    else
    {
        //hasDatepicker3
        $(".hasDatepicker3").addClass("date_picker");

        $('#date_start').datetimepicker({
            format:'Y-m-d',
            lang:'ru',
            timepicker:false,
            closeOnDateSelect:true,
        });

        $('#date_stop').datetimepicker({
            format:'Y-m-d',
            lang:'ru',
            timepicker:false,
            closeOnDateSelect:true,

        });
    }



    console.info(date);
    /*Размеры окна*/

    console.info(myWidth);

    $( ".cauta-select" ).click(function() {
        $( ".cauta-select").removeClass('chk');
        $(this).addClass('chk');
        $('#cauta_inner_id').val($(this).attr('inner_id'));

        $('#cauta_nomer').val($(this).attr('nomer'));
        $('#cauta_id').val($(this).attr('cauta_id'));
        $('#price').val($(this).attr('price'));
        $('#deck').val($(this).attr('deck'));
        $('#type').val($(this).attr('type'));
        $('#free_place').val($(this).attr('free_place'));
        $('.select-cauta').html($(this).attr('nomer'));
    });


    $( ".address-b" ).click(function() {
        var city = $(this).attr('city');
        $( ".address-b").removeClass('active');
        $(this).addClass('active');

        $('.address-tab').removeClass('active');
        $('.'+city).addClass('active');

    });

});

$(function () {
    //  $('[data-toggle="popover"]').popover()

   // $("#Phone").mask("+7(999) 999-99-99");
 /*   $.datepicker.setDefaults($.extend(
            $.datepicker.regional["ru"])

    );

    $( "#date_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#date_stop" ).datepicker({ dateFormat: 'yy-mm-dd' });*/



})


function Search()
{
    var city_start=$("#city_start option:selected").val();
    var city=$("#city option:selected").val();
    var date_start=$("#date_start").val();
    var date_stop=$("#date_stop").val();
    var ship=$("#ship option:selected").val();
    var weekend=0;
    if($("#weekend").prop('checked'))
    {
        weekend=1;
    }

   // $(".search-resul").html("Поиск...");
    $('.loader').show();
    $.get(
        "ajax.html",
        {
            //log1:1,
            action: "Search",
            city_start: city_start,
            city: city,
            date_start: date_start,
            date_stop: date_stop,
            ship: ship,
            weekend: weekend,
        },
        function (data) {
          //  console.info(data);
            $(".search-resul").html(data);
            Scroll('search-resul');
            $('.loader').hide();


        }, "html"
    ); //$.get  END
}

var CallBack = [];
CallBack.SendWaterRent = function()
{
    var r_phone = $("#r_phone").val();
    if (r_phone.length > 5)  {
        $.get(
            "ajax.html",
            {
                //log1:1,
                action: "CallBackSendWaterRent",
                r_phone: r_phone

            },
            function (data) {
                //window.location.href = "/thx.html"
                $('.Water1').hide();
                $('.WaterThx').show();

            }, "html"
        ); //$.get  END
    }
    else {
        alert('Введите имя и номер телефона');
    }
}

CallBack.SendMainPage = function()
{
    var m_name = $("#m_name").val();
    var m_phone = $("#m_phone").val();

    console.info(m_name);
    if ((m_name.length > 3) && (m_phone.length > 4)) {
        $.get(
            "ajax.html",
            {
                //log1:1,
                action: "CallBackSendMainPage",
                m_name: m_name,
                m_phone: m_phone
            },
            function (data) {
                //window.location.href = "/thx.html"
                $('.callMain').hide();
                $('#callformThx').show();

            }, "html"
        ); //$.get  END
    }
    else {
        alert('Введите имя и номер телефона');
    }
}

CallBack.SendContakts = function()
{
    var cf_name = $("#cf_name").val();
    var cf_phone = $("#cf_phone").val();

    if ((cf_name.length > 3) && (cf_phone.length > 4)) {
        $.get(
            "ajax.html",
            {
                //log1:1,
                action: "CallBackSendContakts",
                cf_name: cf_name,
                cf_phone: cf_phone
            },
            function (data) {
                window.location.href = "/thx.html"

            }, "html"
        ); //$.get  END
    }
    else {
        alert('Введите имя и номер телефона');
    }
}

var Bron = [];

/*Покупка круиза*/
Bron.OrderBy = function()
{
    var cauta_inner_id=parseInt($('#cauta_inner_id').val());
    var price=parseInt($('#price').val());

    var name=$("#FirstName").val();
    var Phone=$("#Phone").val();
    var Email=$("#Email").val();
    var cruis_id=$("#cruis_id").val();
    var cruis_inner_id=$("#cruis_inner_id").val();
    var info=name+" "+Phone+" "+Email+" "+$("#info").val();

    /*Проверка заполнния формы*/
    if(price==0)
    {
        $('.alert').html('Вы не выбрали каюту.');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);

    }
    else if(name.length<4)
    {
        $('.alert').html('Введите свое Имя');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);

    }
    else if(Phone.length<4)
    {
        $('.alert').html('Введите свой номер телефона');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
    }
    else if(!$("#agreement").prop('checked'))
    {
        $('.alert').html('Вы не дали свое соглашение на обработку данных!');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
    }
    else
    {
        $(".bronLoader").show();
        var url="http://www.riverlines.ru/remote/js/order.js?";
        url=url+"id="+cruis_inner_id;
        url=url+"&info="+info;
        url=url+"&host=berg-cruis-develop.about-seo.info";
        url=url+"&name="+name;
        url=url+"&phone="+Phone;
        url=url+"&email="+Email;
        //url=url+"";
        url=url+'&cabins['+cauta_inner_id+"]["+cauta_inner_id+"-1][number]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-1][birthday]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][name]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][number]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][birthday]=";
        url=url+"&_=0.05300467601045966";
        setTimeout(function() {
            /*Отправляем на свой сервер*/
            var s = $('#z-form').serializeArray();
            $.ajax({
                type: 'GET',
                url: '/ajax.html',
                data: s,
                dataType : "json",
                success: function(data) {
                    //Если есть ID то переходим коплате
                    if(data.id>0)
                    {
                        var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                        //var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003';
                        window.location.href = url;
                    }
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });



        }, 500);

        setTimeout(function() {

            $.get(
                url,
                {
                    //log1:1,
                    /* host: "berg-cruis-develop.about-seo.info",
                     id: cruis_id,
                     info: "sss",
                     'cabins['+cauta_inner_id+"]["+cauta_inner_id+"-1][number]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-1][birthday]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][name]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][number]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][birthday]": "",
                     "_":"0.05300467601045966"*/

                },
                function (data) {
                    console.info(data);


                }, "html"
            ); //$.get  END

        }, 1000);
        setTimeout(function() {
            $(".bron").html('<h3 class="form-h3">Спасибо. Вам перезвонят</h3>');
            $(".seo1").html("");

        },300);
    }






}


/*Покупка круиза*/
Bron.Order = function()
{
    var cauta_inner_id=parseInt($('#cauta_inner_id').val());
    var price=parseInt($('#price').val());

    var name=$("#FirstName").val();
    var Phone=$("#Phone").val();
    var Email=$("#Email").val();
    var cruis_id=$("#cruis_id").val();
    var cruis_inner_id=$("#cruis_inner_id").val();
    var info=name+" "+Phone+" "+Email+" "+$("#info").val();

    /*Проверка заполнния формы*/
    if(price==0)
    {
        $('.alert').html('Вы не выбрали каюту.');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);

    }
    else if(name.length<4)
    {
        $('.alert').html('Введите свое Имя');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);

    }
    else if(Phone.length<4)
    {
        $('.alert').html('Введите свой номер телефона');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
    }
    else if(!$("#agreement").prop('checked'))
    {
        $('.alert').html('Вы не дали свое соглашение на обработку данных!');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
    }
    else
    {
        $(".bronLoader").show();
        var url="http://www.riverlines.ru/remote/js/order.js?";
        url=url+"id="+cruis_inner_id;
        url=url+"&info="+info;
        url=url+"&host=berg-cruis-develop.about-seo.info";
        url=url+"&name="+name;
        url=url+"&phone="+Phone;
        url=url+"&email="+Email;
        //url=url+"";
        url=url+'&cabins['+cauta_inner_id+"]["+cauta_inner_id+"-1][number]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-1][birthday]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][name]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][number]=";
        url=url+"&cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][birthday]=";
        url=url+"&_=0.05300467601045966";
        setTimeout(function() {
            /*Отправляем на свой сервер*/
            var s = $('#z-form').serializeArray();
            $.ajax({
                type: 'GET',
                url: '/ajax.html',
                data: s,
                dataType : "json",
                success: function(data) {
                    //Если есть ID то переходим коплате
                    if(data.id>0)
                    {

                        window.location.href = '/thx.html';
                    }
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });



        }, 500);

        setTimeout(function() {

            $.get(
                url,
                {
                    //log1:1,
                    /* host: "berg-cruis-develop.about-seo.info",
                     id: cruis_id,
                     info: "sss",
                     'cabins['+cauta_inner_id+"]["+cauta_inner_id+"-1][number]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-1][birthday]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][name]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][number]": "",
                     "cabins["+cauta_inner_id+"]["+cauta_inner_id+"-2][birthday]": "",
                     "_":"0.05300467601045966"*/

                },
                function (data) {
                    console.info(data);


                }, "html"
            ); //$.get  END

        }, 1000);
        setTimeout(function() {
            $(".bron").html('<h3 class="form-h3">Спасибо. Вам перезвонят</h3>');
            $(".seo1").html("");

        },300);
    }






}




