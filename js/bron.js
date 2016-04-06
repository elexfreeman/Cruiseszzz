/**
 * Created by folle on 02.03.2016.
 */


$(function() {
    $( ".cauta-select2" ).click(function() {
        $( ".cauta-select2").removeClass('chk');
        $(this).addClass('chk');
        $('#cauta_inner_id').val($(this).attr('inner_id'));

        $('#cauta_nomer').val($(this).attr('nomer'));
        $('#cauta_id').val($(this).attr('cauta_id'));
        $('#price').val($(this).attr('price'));
        $('#deck').val($(this).attr('deck'));
        $('#type').val($(this).attr('type'));
        $('#free_place').val($(this).attr('free_place'));
        $('.select-cauta').html($(this).attr('nomer'));

        /*Показываем выбор мест*/
        $.get(
            "ajax-bron.html",
            {
                //log1:1,
                action: "tplPlaces",
                cauta_id:$('#cauta_id').val()

            },
            function (data) {
                //  console.info(data);

                $('#places').html(data);
                $('#places').fadeIn();
                setTimeout(function() {
                    Scroll('places');
                },500);


            }, "html"
        ); //$.get  END
    });
});

var CaptchaStatus=0;

var Bron2 = {};

/*Покупка круиза*/
Bron2.OrderBy1 = function()
{
    var cauta_inner_id=parseInt($('#cauta_inner_id').val());
    var price=parseInt($('#price').val());

  //  var name=$("#name1").val();
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
 /*   else if(name.length<4)
    {
        $('.alert').html('Введите свое Имя');
        $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);

    }*/
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
                        /*apex*/
                       // var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                       /*robokassa*/
                       $('#robokassa').html(data.robo_form);
                        setTimeout(function() {$('#z-robo-form').submit();},100);
                        //window.location.href = url;
                    }
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });



        }, 500);


        setTimeout(function() {
            $(".bron").html('<h3 class="form-h3">Спасибо. Вам перезвонят</h3>');
            $(".seo1").html("");

        },300);
    }
}



Bron2.IsCa = function()
{
    var res = 0;
        $.get(
        "ajax-bron.html",
        {
            //log1:1,
            action: "isCa",
            captcha:$('#capcha').val()

        },
        function (data) {
            window.CaptchaStatus=data;
        }, "json"
    ); //$.get  END

    return window.CaptchaStatus;
}

/*Покупка круиза*/
Bron2.Order = function()
{

    $('.alert-input').html('');
    /*Проверка заполнния формы*/
    $.get(
        "ajax-bron.html",
        {
            //log1:1,
            action: "isCa",
            captcha:$('#capcha').val()

        },
        function (data) {
            var cauta_inner_id=parseInt($('#cauta_inner_id').val());
            var price=parseInt($('#price').val());

            var name=$("#name1").val();
            var Phone=$("#Phone").val();
            var Email=$("#Email").val();
            var cruis_id=$("#cruis_id").val();
            var cruis_inner_id=$("#cruis_inner_id").val();
            var info=name+" "+Phone+" "+Email+" "+$("#info").val();

            console.info(name);
           if(data.status==0)

            {
                $('.alert-ca').html('Не верное кодовое слово.');

                $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
            }
            else if(price==0)
            {

                $('.alert-cauta').html('Вы не выбрали каюту.');
                $("html, body").animate({ scrollTop: $('#cauta').offset().top }, 1000);

            }
           else if((name==undefined))
           {
               $('.alert-place').html('Вы не выбрали места.');
               $("html, body").animate({ scrollTop: $('#places').offset().top }, 1000);
           }
            else if (name.length<4)
            {
                $('.alert-psj').html('Введите свое Имя');
                $("html, body").animate({ scrollTop: $('#tplBronForm2').offset().top }, 1000);

            }
            else if(Phone.length<4)
            {
                $('.alert-phone').html('Введите свой номер телефона');
                $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
            }
            else if(!$("#agreement").prop('checked'))
            {
                $('.alert-agreement').html('Вы не дали свое соглашение на обработку данных!');
                $("html, body").animate({ scrollTop: $('#agreement').offset().top }, 1000);
            }
            else
            {
                $('#ModalTimer').modal('show');

                /*Отправляем на свой сервер*/
                /*Меняем action*/
                $("#z-form-action").val('InfoflotBron');
                setTimeout(function() {
                    var s = $('#z-form').serializeArray();
                    $.ajax({
                        type: 'GET',
                        url: '/ajax-bron.html',
                        data: s,
                        dataType : "json",
                        success: function(data) {
                            $('#ModalTimer').modal('hide');
                            $('#ModalBron').modal('show');
                            $('#modal-born-body').html(data.text_body);
                            $('#modal-bron-title').html(data.status_text);
                            $(".bronLoader").hide();


                            //Если есть ID то переходим коплате
                            if(data.id>0)
                            {
                                // window.location.href = '/thx.html';
                            }
                        },
                        error:  function(xhr, str){
                            $('.alert').html('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }, 500);
                setTimeout(function() {
                    $(".bron").html('<h3 class="form-h3">Спасибо. Вам перезвонят</h3>');
                    $(".seo1").html("");

                },300);
            }
        }, "json"
    ); //$.get  END



}


/*Покупка круиза*/
Bron2.OrderBy = function()
{

    $('.alert-input').html('');
    /*Проверка заполнния формы*/
    $.get(
        "ajax-bron.html",
        {
            //log1:1,
            action: "isCa",
            captcha:$('#capcha').val()

        },
        function (data) {
            var cauta_inner_id=parseInt($('#cauta_inner_id').val());
            var price=parseInt($('#price').val());

            var name=$("#name1").val();
            var Phone=$("#Phone").val();
            var Email=$("#Email").val();
            var cruis_id=$("#cruis_id").val();
            var cruis_inner_id=$("#cruis_inner_id").val();
            var info=name+" "+Phone+" "+Email+" "+$("#info").val();

            console.info(name);
            if(data.status==0)

            {
                $('.alert-ca').html('Не верное кодовое слово.');

                $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
            }
            else if(price==0)
            {

                $('.alert-cauta').html('Вы не выбрали каюту.');
                $("html, body").animate({ scrollTop: $('#cauta').offset().top }, 1000);

            }
            else if((name==undefined))
            {
                $('.alert-place').html('Вы не выбрали места.');
                $("html, body").animate({ scrollTop: $('#places').offset().top }, 1000);
            }
            else if (name.length<4)
            {
                $('.alert-psj').html('Введите свое Имя');
                $("html, body").animate({ scrollTop: $('#tplBronForm2').offset().top }, 1000);

            }
            else if(Phone.length<4)
            {
                $('.alert-phone').html('Введите свой номер телефона');
                $("html, body").animate({ scrollTop: $('#BronForm').offset().top }, 1000);
            }
            else if(!$("#agreement").prop('checked'))
            {
                $('.alert-agreement').html('Вы не дали свое соглашение на обработку данных!');
                $("html, body").animate({ scrollTop: $('#agreement').offset().top }, 1000);
            }
            else
            {
                $('#ModalTimer').modal('show');

                /*Отправляем на свой сервер*/
                /*Меняем action*/
                $("#z-form-action").val('InfoflotBron');
                setTimeout(function() {
                    var s = $('#z-form').serializeArray();
                    $.ajax({
                        type: 'GET',
                        url: '/ajax-bron.html',
                        data: s,
                        dataType : "json",
                        success: function(data) {
                            $('#ModalTimer').modal('hide');
                            $('#ModalBron').modal('show');
                            $('#modal-born-body').html(data.text_body);
                            $('#modal-bron-title').html(data.status_text);
                            $(".bronLoader").hide();
                            if((data.id>0)&&(data.infoflot!='null'))
                            {
                             /*   var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                                //var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003';
                                setTimeout(function() {
                                    window.location.href = url;
                                    //console.info(url);

                                },3000);*/
                                /*apex*/
                                // var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                                /*robokassa*/
                                $('#robokassa').html(data.robo_form);
                                setTimeout(function() {$('#z-robo-form').submit();},1000);
                                //window.location.href = url;

                            }


                        },
                        error:  function(xhr, str){
                            $('.alert').html('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }, 500);
                setTimeout(function() {
                    $(".bron").html('<h3 class="form-h3">Спасибо. Вам перезвонят</h3>');
                    $(".seo1").html("");

                },300);
            }
        }, "json"
    ); //$.get  END



}


Bron2.PlaceSelect = function(place_id)
{
    if($('#place_'+place_id).hasClass('chk'))
    {
        $('#place_'+place_id).removeClass('chk');
        $('#placeInput_'+place_id).val(0);
    }
    else
    {
        $('#place_'+place_id).addClass('chk');
        $('#placeInput_'+place_id).val(1);
    }

    /*Показываем выбор мест*/

    $.ajax({
        type: 'GET',
        url: '/ajax-bron.html',
        data: $('#placesForm').serializeArray(),
        dataType : "html",
        success: function(data) {
            $('#tplBronForm2').html(data);
        },
        error:  function(xhr, str){
            $('.alert').html('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}


Bron2.CautaClick = function(cruis_id,cauta_number)
{
    $('#BronModalV2').modal('show');
    $.ajax({
        type: 'GET',
        url: '/ajax-bron.html',
        data: {
            //log1:1,
            action: "GetCautaInfo",
            cruis_id:cruis_id,
            cauta_number:cauta_number

        },
        dataType : "json",
        success: function(data) {
           // $('#tplBronForm2').html(data);
            Bron2.InitBronModal(data);

        },
        error:  function(xhr, str){
            //$('.alert').html('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}


/*Инит модального окна боронирования*/
Bron2.InitBronModal = function(data)
{
    /*Номер каюты*/
    $('#bron-modal-cauta-number').html(data.popover_number);
    $('.cauta_number').val(data.popover_number);
    /*Сумма*/
    $('#bron-modal-cauta-summa').html(0);
    /*Выводим места*/
    $('.bronmodalplacebox').html('');
    /*Цена за место*/
    $('.place_price').val(data.price);

    /*Скрываем показываем продолжение при пустых местах*/
   if(parseInt(data.free_place)>0)
   {
       $.each( data.places, function(i, place){
           if(place.status==0) $('.bronmodalplacebox').append('<div class="bronmodalplaceitem place-selector" place="'+place.inner_number+'">№'+place.number+'</div>');
           if(place.status==1) $('.bronmodalplacebox').append('<div class="bronmodalplaceitem full">(занято)</div>');
       });

       $('.noPlace').hide();
       $('.havePlace').show();
       $('.bronbuttons').show();
   }
    else
   {
       $('.noPlace').show();
       $('#havePlace').hide();
       $('.bronbuttons').hide();

   }



}

Bron2.BronTabClick = function()
{
    $('#wf-form-BronModalForm-bron').show();
    $('#wf-form-BronModalForm-pay').hide();

    $('.bronbuttons').removeClass('by');
    $('.bronbuttons').addClass('bron');
    $('.bronbuttonsubmit').removeClass('pay');

}
Bron2.PayTabClick = function()
{
    $('#wf-form-BronModalForm-pay').show();
    $('#wf-form-BronModalForm-bron').hide();
    $('.bronbuttons').removeClass('bron');
    $('.bronbuttons').addClass('by');
    $('.bronbuttonsubmit').addClass('pay');


}

Bron2.ChangeSex = function(p_id)
{
    if($('.u_img_'+p_id).attr('src')=='/images/bron-man.png')
    {
        $('.u_img_'+p_id).attr('src','/images/bron-wooman.png');
        $('.u_sex_'+p_id).val(2);

    }
    else
    {
        $('.u_img_'+p_id).attr('src','/images/bron-man.png');
        $('.u_sex_'+p_id).val(1);
    }
}

Bron2.PlaceClick = function(place,t)
{
    console.info(place);
    var summa=parseFloat($('#bron-modal-cauta-summa').html());
    var price = parseFloat($(".place_price").val());
    var p='';
    if(t==1)
    {
        summa=summa+price;
        //добавляем пасажира



        p='<div class="bronformfieldsrow passjir p_'+place+'" place_id="'+place+'"> ';
        p+='<img id="u_img_'+place+'"  onclick="Bron2.ChangeSex('+"'"+place+"'"+');"  src="/images/bron-man.png" class="bron-icon-wc u_img_'+place+'">';
        p+='<input class="u_sex_'+place+'" type="hidden" name="u_sex_'+place+'"value="1">';
        p+='<input id="u_surname_'+place+'" type="text" placeholder="Фамилия" name="u_surname_'+place+'" required="required"  class="w-input bronmodalinput">';
        p+=' <input id="u_name_'+place+'" type="text" placeholder="Имя" name="u_name_'+place+'" required="required"  class="w-input bronmodalinput">';
        p+=' <input id="u_patronymic_'+place+'" type="text" placeholder="Отчество" name="u_patronymic_'+place+'" class="w-input bronmodalinput">';
        p+=' <input id="u_birthday_'+place+'" type="text" placeholder="Дата рождения" name="u_birthday_'+place+'" required="required"  class="w-input hasDatepicker2 bronmodalinput u_birthday_'+place+'">';
        p+=' <input  type="hidden" placeholder="Дата рождения" name="placeid_'+place+'" value="'+place+'">';

        p+='  </div>';

        $('.passaj_content-bron').append(p);

        p='<div class="bronformfieldsrow passjir p_'+place+'" place_id="'+place+'" >';
        p+='<img id="u_img_'+place+'"  onclick="Bron2.ChangeSex('+"'"+place+"'"+');"  src="/images/bron-man.png" class="bron-icon-wc u_img_'+place+'">';
        p+='<input class="u_sex_'+place+'" type="hidden" name="u_sex_'+place+'"value="1">';
        p+='<input id="u_surname_'+place+'" type="text" placeholder="Фамилия" name="u_surname_'+place+'" required="required"  class="w-input bronmodalinput">';
        p+=' <input id="u_name_'+place+'" type="text" placeholder="Имя" name="u_name_'+place+'" required="required"  class="w-input bronmodalinput">';
        p+=' <input id="u_patronymic_'+place+'" type="text" placeholder="Отчество" name="u_patronymic_'+place+'" class="w-input bronmodalinput">';
        p+=' <input id="u_birthday_'+place+'" type="text" placeholder="Дата рождения" name="u_birthday_'+place+'" required="required"  class="w-input hasDatepicker2 bronmodalinput u_birthday_'+place+'">';
        p+=' <input  type="hidden" placeholder="Дата рождения" name="placeid_'+place+'" value="'+place+'">';

        p+='  </div>';
        $('.passaj_content-pay').append(p);
        $('.hasDatepicker2').datetimepicker({
            format:'d.m.Y',
            lang:'ru',
            timepicker:false,
            closeOnDateSelect:true,

        });
/*
        $( ".u_birthday_"+place ).datepicker({ dateFormat: 'dd.mm.yy' ,changeMonth: true,
            changeYear: true,yearRange:'-90:+0' });*/



    }

    if(t==0)
    {
        summa=summa-price;
        $(".p_"+place).remove();

    }
    $('#bron-modal-cauta-summa').html(summa);
}


/*Чек я пассажир копируем даннные*/
Bron2.ImPassaj = function()
{
    //console.info($('#is_p_0').prop("checked"));
    if($('#is_p_0').prop("checked")==true)
    {
        var id=$('.passaj_content-pay > .passjir').attr('place_id');
        /*теперь выискиваем куда скопировать значения из покупателя*/
        console.info(id);

        $("#u_name_"+id).val($("#u_name_0").val());
        $("#u_surname_"+id).val($("#u_surname_0").val());
        $("#u_sex_"+id).val($("#u_sex_0").val());
        $("#u_patronymic_"+id).val($("#u_patronymic_0").val());
        $("#u_birthday_"+id).val($("#u_birthday_0").val());
        $("#u_img_"+id).attr('src',$("#u_img_0").attr('src'));

    }

}
/*Палуба*/
$(function() {
   // setTimeout(function() {$('.CautaClick').popover({html: true})},3000);
    ///$('.CautaClick').hover()

    $('.hasDatepicker2').datetimepicker({
        format:'d.m.Y',
        lang:'ru',
        timepicker:false,
        closeOnDateSelect:true,

    });

    $( ".CautaClick" ).hover(
        function() {
            $(this).popover('show');
        }, function() {
            $(this).popover('hide');
        }
    );


    /*проставляем папраметры кают на схеме*/
    var obsh = document.getElementsByClassName('CautaClick');
    var elems = $('.CautaClick').nextAll();
    var lastID = elems.length - 1;
    for (var i = 0; i < lastID; i++) {
        $(obsh[i]).attr('id',"cauta_number_"+$(obsh[i]).html());
        var ee=obsh[i];
        if(parseInt($(ee).html())>0)
        {

            var jqxhr = $.ajax( {
                type: 'GET',
                url: '/ajax-bron.html',
                data: {
                    //log1:1,
                    action: "GetCautaInfo",
                    cruis_id:$('.cruis_id').val(),
                    cauta_number:$(ee).html()

                },
                dataType : "json"})
                .done(function(data) {

                        $('#cauta_number_'+data.popover_number).attr('data-container','body');
                        $('#cauta_number_'+data.popover_number).attr('id',"cauta_number_"+data.popover_number);
                        $('#cauta_number_'+data.popover_number).attr('data-placement','bottom');
                        $('#cauta_number_'+data.popover_number).attr('title',data.popover_title);
                        $('#cauta_number_'+data.popover_number).attr('data-content',data.popover_content);
                        $('#cauta_number_'+data.popover_number).css('fill','black');
                        $('#cauta_number_'+data.popover_number).addClass('popover1');
                        $('#cauta_number_'+data.popover_number).popover({html: true});

                })
                .fail(function() {
                    //alert( "error" );
                })
                .always(function() {
                });
        }

    }




    /*Щелкнули по каюте на схеме*/
    $( ".CautaClick" ).click(function() {

        Bron2.CautaClick($('.cruis_id').val(),$(this).html());
    });

 /*   $( ".btn.popover11" ).click(function() {
        var paluba = $(this).attr('paluba');
        var kauta = $(this).attr('kauta');
        var place = $(this).attr('place');
        $('#BronModalV2').modal('show');

    });*/
/*
    $('.popover11').popover({html: true}).click(function (e) {
        $(this).popover('toggle');
        e.stopPropagation();
    });;
*/


    $(".phone").mask("+7(999) 999-9999");
    /*Когда закрыли окно*/
    $('#BronModalV2').on('hidden.bs.modal', function (e) {

        $('.passaj_content-bron').html('');
        $('.passaj_content-pay').html('');
        $('#wf-form-BronModalForm-bron').hide();
        $('#wf-form-BronModalForm-pay').hide();
        $('.bronbuttons').removeClass('by');
        $('.bronbuttons').removeClass('bron');

        $('.noPlace').hide();
        $('.havePlace').hide();
        $('.bronbuttons').hide();

        $("input").removeClass('error_class');
        $('.PayButtons.agreement').removeClass('alert');

        $('.bronbuttonsubmit.bron').prop('disabled',false);
        $('.bronbuttonsubmit.bron').val('Заказать круиз');

        $('.bronbuttonsubmit.pay').prop('disabled',false);
        $('.bronbuttonsubmit.pay').val('Заказать круиз');


    })


    /*Запомнить эту хрень!!!!*/
    $( document ).ajaxComplete(function() {
        /*
        $.datepicker.setDefaults($.extend(
                $.datepicker.regional["ru"])
        );

*/

/*

        $( "#u_birthday_0" ).datepicker({ dateFormat: 'dd.mm.yy' ,changeMonth: true,
            changeYear: true,yearRange:'-90:+0' });

*/
        /*тыкаем по месту*/
        $( ".place-selector" ).click(function() {
            /*меняем check*/
            if($(this).hasClass('check'))
            {
                $(this).removeClass('check');
                Bron2.PlaceClick($(this).attr('place'),0);
            }
            else
            {
                $(this).addClass('check');
                Bron2.PlaceClick($(this).attr('place'),1);
            }

        });

        /*У меня осталиь вопросы*/
        $( ".call-bron" ).click(function() {
            if($(".call-bron" ).hasClass('check'))
            {
                $(".call-bron" ).removeClass('check');
                $('.need_call').val(0);

            }
            else
            {
                $(this).addClass('check');
                $('.need_call').val(1);
            }
        });

         /*У меня осталиь вопросы*/
        $( ".call-pay" ).click(function() {
            if($(".call-pay").hasClass('check'))
            {
                $(".call-pay").removeClass('check');
                $('.need_call').val(0);

            }
            else
            {
                $(".call-pay").addClass('check');
                $('.need_call').val(1);
            }
        });

    });




/*

    $('.popover11').on('show.bs.popover', function () {

        var paluba = $(this).attr('paluba');
        var kauta = $(this).attr('kauta');
        var place = $(this).attr('place');
        $('.popover-title').html('Каюта №:'+kauta+'. Выберите место');


            $.get(
                "ajax-bron.html",
                {
                    //log1:1,
                    action: "tplPlacesPopover",
                    cauta_id:kauta
                },
                function (data) {
                    $('.popover-content').html(data);
                }, "html"
            ); //$.get  END
    });*/


    $('.btn').on('click', function (e) {
        $('.btn').not(this).popover('hide');
    });


    $( ".fil1" ).hover(
        function() {
            $('.fil5').attr('fill','#008f01');
        }, function() {
            $('.fil5').attr('fill','#000');
        }
    );

    $('.btn.popover11').attr('data-content','<img src="/images/loader.GIF" style="display: block;margin-left:30px;margin-right:30px;">');



});




Bron2.PayClick = function()
{
    var payForm = $('#wf-form-BronModalForm-pay').serializeArray();

    $('.bronbuttonsubmit.pay').prop('disabled',true);
    $('.bronbuttonsubmit.pay').val('Подождите...');

    $("input").removeClass('error_class');
    $('.PayButtons.agreement').removeClass('alert');
 $.ajax({
        type: 'GET',
        url: '/ajax-bron.html',
        data: payForm,
        dataType : "json",
        success: function(data) {

            if(data.error==1)
            {
                $.each( data.errors_list, function(i, error){
                    console.info(i,error);
                    $("input[name="+i+"]").addClass('error_class');
                });
                /*agreement*/
                if(data.agreement==1)
                {
                    $('.PayButtons.agreement').addClass('alert');

                }
                $('.bronbuttonsubmit.pay').prop('disabled',false);
                $('.bronbuttonsubmit.pay').val('Заказать круиз');
            }
            else
            {
                /*переходим к оплате*/

                $('#ModalBron').modal('show');
                $('#BronModalV2').modal('hide');
                $('#modal-born-body').html(data.text_body);
                $('#modal-bron-title').html(data.status_text);
                if((data.id>0)&&(data.infoflot!='null'))
                {
                    /*   var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                     //var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003';
                     setTimeout(function() {
                     window.location.href = url;
                     //console.info(url);

                     },3000);*/
                    /*apex*/
                    // var url='https://b2c.appex.ru/payment/choice?orderSourceCode='+data.id+'&billingCode=BergturSamara003&returnUrl=http://berg-kruiz.ru/payment/';
                    /*robokassa*/
                    $('#robokassa').html(data.robo_form);
                    setTimeout(function() {$('#z-robo-form').submit();},4000);
                    //window.location.href = url;

                }
            }

        },
        error:  function(xhr, str){
            $('.alert').html('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}




Bron2.BronClick = function()
{
    var bronForm = $('#wf-form-BronModalForm-bron').serializeArray();

    $('.bronbuttonsubmit.bron').prop('disabled',true);
    $('.bronbuttonsubmit.bron').val('Подождите...');

    $("input").removeClass('error_class');
    $('.PayButtons.agreement').removeClass('alert');
    $.ajax({
        type: 'GET',
        url: '/ajax-bron.html',
        data: bronForm,
        dataType : "json",
        success: function(data) {

            if(data.error==1)
            {
                $.each( data.errors_list, function(i, error){
                    console.info(i,error);
                    $("input[name="+i+"]").addClass('error_class');
                });
                /*agreement*/
                if(data.agreement==1)
                {
                    $('.PayButtons.agreement').addClass('alert');

                }
                $('.bronbuttonsubmit.bron').prop('disabled',false);
                $('.bronbuttonsubmit.bron').val('Заказать круиз');
            }
            else
            {
                /*переходим к оплате*/

                $('#ModalBron').modal('show');
                $('#BronModalV2').modal('hide');
                $('#modal-born-body').html(data.text_body);
                $('#modal-bron-title').html(data.status_text);

            }

        },
        error:  function(xhr, str){
            $('.alert').html('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}
