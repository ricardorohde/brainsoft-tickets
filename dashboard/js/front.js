/*global $, document, Chart, LINECHART, data, options, window*/
$(document).ready(function () {

    $("#submit-search-register").click(function () {
        var idChat = $("#id_chat").val();
        var idAttendant = $("input[name='id_attendant']:checked").val();
        var error = false;

        if (!error) {
            if (idChat == "" || idChat == null) {
                $("#id_chat").addClass("border-danger");
                error = true;
            }
        }

        if (!error) {
            if (idAttendant == "" || idAttendant == null) {
                error = true;
            }
        }

        if (error) {
            return false;
        }

        redirectoToTicketRemovingTextTicket(idChat, idAttendant);
    });

    var hrefRaw = window.location.pathname;
    var href = hrefRaw.toString().split('/');
    $('#side-admin-menu > li > a[href="' + href[2] + '"]').parent().addClass('active');

    // Associa o evento do popover ao clicar no link.
    $('[data-toggle="popover"]').popover(); 

    $("#initial_date").blur(function(){
        var initial_date = $(this).val().split('-');

        actual_year = verifyActualDate("actual_year");
        
        if (initial_date[0] != actual_year) {
            $(this).val(actual_year+"-"+initial_date[1]+"-"+initial_date[2]);
        }
    });

    $("#final-date").blur(function () {
        var final_date = $(this).val().split('-');

        actual_year = verifyActualDate("actual_year");
        actual_month = verifyActualDate("actual_month");

        if (final_date[1] > actual_month) {
            $(this).val(actual_year+"-"+actual_month+"-"+final_date[2]);
        }

        if (final_date[0] != actual_year) {
            $(this).val(verifyActualDate(""));
        }

        if ($(this).val() < $("#initial-date").val()) {
            $(this).val($("#initial-date").val());
        }
    });

    $("#final-date").change(function () {
        $("#btn-generate-report").prop("disabled", false);
    });

    $(document).bind("contextmenu", function (e) {
        //return false;
    });

    var pressedCtrl = false;  
    $(document).keyup(function (e) {  
        if(e.which == 17) pressedCtrl = false; 
    })
    $(document).keydown(function (e) { 
        if (e.which == 17) pressedCtrl = true; 
        if ((e.which == 80 || e.keyCode == 80) && pressedCtrl == true) { 
            $("#label-initial-date").addClass("to-print");
            $("#initial-date").addClass("to-print");
            $("#label-final-date").addClass("to-print");
            $("#final-date").addClass("to-print");
            $(".generate-report").addClass("to-print");
            $(".title-report").hide();
        }

        setTimeout(function () { 
            $(".generate-report").removeClass("to-print");
            $("#label-initial-date").removeClass("to-print");
            $("#initial-date").removeClass("to-print");
            $("#label-final-date").removeClass("to-print");
            $("#final-date").removeClass("to-print");
            $(".title-report").show();
        }, 3000); 
    });

    $("#date_filter").change(function () {
        var date_required = $(this).val();
        
        setCookie("date_to_filter", 0.5);

        function setCookie(name, exdays) { //função universal para criar cookie
            var expires;
            var date; 

            date = new Date(); //criando o COOKIE com a data atual
            date.setHours(date.getHours() - 3);
            date.setTime(date.getTime() + (exdays*24*60*60*1000));
            expires = date.toUTCString();

            document.cookie = name+"="+date_required+"; expires="+expires+"; path=/";

            window.setTimeout('location.reload()');
        }
    });

    $('.card-body').each(function () {
        if ($(this).children().eq(1) != null) {
            $(this).children().eq(1).addClass("second-ticket");
        };
    });

    for (var i = 1; i < 50; i++) {
        var user = $(".user"+i).find("a").length;
        setBorderClass(user, ".user"+i);
    }

    $("#status-sql").fadeTo(3000, 500).slideUp(500, function () {
        $("#status-sql").alert('close');
    });

    $("#txtBusca").keyup(function () {
        var texto = $(this).val();
        
        if (texto == "") {
            location.reload();
        }

        $("#conteudo .mb-3").css("display", "block");
        $("#conteudo .mb-3").each(function () {
            if ($(this).text().indexOf(texto) < 0)
               $(this).parent().parent().parent().remove();
        });
    });

    $("td[name=limit]").dblclick(function () {
        var conteudoOriginal = $(this).text();
        var idModule = $(this).attr('id');
        
        $(this).addClass("celulaEmEdicao");
        $(this).html("<input type='text' value='" + conteudoOriginal + "' />");
        $(this).children().first().focus();

        $(this).children().first().keypress(function (e) {
            if (e.which == 13) {
                var novoConteudo = $(this).val();
                $(this).parent().text(novoConteudo);
                $(this).parent().removeClass("celulaEmEdicao");
                $.post("../utils/controller/module/module-data.ctrl.php", {id:idModule, valor:novoConteudo},
                    function (valor) {
                    }
                )
            }
        });
        
        $(this).children().first().blur(function () {
            $(this).parent().text(conteudoOriginal);
            $(this).parent().removeClass("celulaEmEdicao");
        });
    });
    
    $('#example').dataTable( {
        "order": [[ 0, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
        }
    });

    $('#example2').dataTable( {
        "order": [[ 0, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
        }
    });

    $('#ticketListOfClient').dataTable( {
        "order": [[ 0, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
        }
    });

    $(".control").remove();

    $("#table input").keyup(function () {       
        var index = $(this).parent().index();
        var nth = "#table td:nth-child("+(index+1).toString()+")";
        var valor = $(this).val().toUpperCase();
        $("#table tbody tr").show();
        $(nth).each(function () {
            if($(this).text().toUpperCase().indexOf(valor) < 0){
                $(this).parent().hide();
            }
        });
    });
 
    $("#table input").blur(function(){
        $(this).val("");
    });

    $(".justNumbers").bind("keyup blur focus", function(e) {
        e.preventDefault();
        var expre = /[^\d]/g;
        $(this).val($(this).val().replace(expre,''));
    });

    // Custom autocomplete instance.
    $.widget("app.autocomplete", $.ui.autocomplete, {
        // Which class get's applied to matched text in the menu items.
        options: {
            highlightClass: "ui-state-highlight"
        },
        
        _renderItem: function( ul, item ) {

            // Replace the matched text with a custom span. This
            // span uses the class found in the "highlightClass" option.
            var re = new RegExp( "(" + this.term + ")", "gi" ),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                label = item.label.replace( re, template ),
                $li = $( "<li/>" ).appendTo( ul );
            
            // Create and return the custom menu item content.
            $( "<a/>" ).attr( "href", "#" )
                       .html( label )
                       .appendTo( $li );
            
            return $li;
        }
    });

    // Create autocomplete instances.
    $(function() {
        $("#registry").autocomplete({
            highlightClass: "bold-text",
            source: '../../../utils/buscaDados.php',
        });
        
        $("#registryListToAdm").autocomplete({
            highlightClass: "bold-text",
            source: '../../utils/buscaDados.php'
        });

        $("#category").autocomplete({
            highlightClass: "bold-text",
            source: '../../utils/buscaCategories.php'
        });
    });

    $("select[name=listUser]").change(function(){
        $.post("../utils/controller/ctrl-auth.php", {user:$(this).val()},
          function(auth){
            $(".auth").empty();
            $(".auth").append(auth);
          }
        )
    });

    $(".generate-report").click(function(){
        $.post("../utils/controller/ctrl-report.php", {makeReport:$(this).val(), initial_date:$("#initial_date").val(), final_date:$("#final_date").val()},
          function(makeReport){
            $(".report").empty();
            $(".report").append(makeReport);
          }
        )
    });

    $("select[name=group]").change(function(){
        $("select[name=attendant]").html('<option value="">Selecione um atendente</option>');
        $.post("../../../utils/controller/employee/employee-js.ctrl.php", {group:$(this).val()},
          function(valor){
            $("select[name=attendant]").append(valor);
          }
        )
    });

    $("input[name=login]").blur(function(){
        $.post("../../utils/controller/credential/credential-js.ctrl.php", {userToVerify:$(this).val().trim()},
          function(result){
            if (result > 0){
                document.formAdd.login.style.boxShadow = "0 0 5px #ff0000"; 
                document.formAdd.login.style.border = "1px solid #ff0000";
                $("button[type=submit]").hide();
            } else if (result == 0){
                document.formAdd.login.style.boxShadow = "0 0 5px #28a745"; 
                document.formAdd.login.style.border = "1px solid #28a745";
                $("button[type=submit]").show();
            }
          }
        )
    });

    $("input[name=category]").blur(function(){
        $.post("../../utils/controller/category/category-js.ctrl.php", {fromCategory:$(this).val()},
          function(fromCategory){
            $("#id_category").val(fromCategory);
          }
        )
    });

    $("input[name=registry]").blur(function(){
        $("select[name=client]").html('<option value="">Selecione o cliente requerente</option>');
        $.post("../../../utils/controller/client/client-js.ctrl.php", {registry:$(this).val()},
          function(client){
            $("select[name=client]").append(client);
          }
        )
    });

    $('input[name=typeUser]').change(function () {
        inputTypeUserChanged();
    });

    inputTypeUserChanged();

    $("select[name=state]").change(function(){
        $("select[name=city]").html('<option value="">aguarde...</option>');
        $("select[name=registry]").html('<option value="">Selecione uma cidade</option>');
        $.post("../../utils/controller/city/city-js.ctrl.php", {valor:$(this).val()},
          function(valor){
            $("select[name=city]").html(valor);
          }
        )
    });

    $("select[name=city]").change(function(){
        $("select[name=registry]").html('<option value="">aguarde...</option>');
        $.post("../../utils/controller/registry/registry-js.ctrl.php", {valor:$(this).val()},
          function(valor){
            $("select[name=registry]").html(valor);
          }
        )
    });

    $("input[name=registryListToAdm]").blur(function(){
        $.post("../utils/controller/ctrl_registry.php", {fromFile:$(this).val()},
          function(fromFile){
            $("#id_registry").val(fromFile);
          }
        )

        $.post("../utils/controller/ctrl_registry.php", {registryToAdm:$(this).val()},
          function(registryToAdm){
            $("tbody").html(registryToAdm);
          }
        )
    });

    'use strict';

    // Main Template Color
    var brandPrimary = '#33b35a';

    // ------------------------------------------------------- //
    // Custom Scrollbar
    // ------------------------------------------------------ //

    if ($(window).outerWidth() > 992) {
         $(window).on("load",function(){
            $("nav.side-navbar").mCustomScrollbar({
                scrollInertia: 200
            });
        });
    }


    // ------------------------------------------------------- //
    // Side Navbar Functionality
    // ------------------------------------------------------ //
    $('#toggle-btn').on('click', function (e) {

        e.preventDefault();

        if ($(window).outerWidth() > 1194) {
            $('nav.side-navbar').toggleClass('shrink');
            $('.root-page').toggleClass('active');
        } else {
            $('nav.side-navbar').toggleClass('show-sm');
            $('.root-page').toggleClass('active-sm');
        }
    });


    // ------------------------------------------------------- //
    // Login  form validation
    // ------------------------------------------------------ //
    $('#login-form').validate({
        messages: {
            loginUsername: 'please enter your username',
            loginPassword: 'please enter your password'
        }
    });

    // ------------------------------------------------------- //
    // Register form validation
    // ------------------------------------------------------ //
    $('#register-form').validate({
        messages: {
            registerUsername: 'please enter your first name',
            registerEmail: 'please enter a vaild Email Address',
            registerPassword: 'please enter your password'
        }
    });

    // ------------------------------------------------------- //
    // Transition Placeholders
    // ------------------------------------------------------ //


    // ------------------------------------------------------- //
    // Jquery Progress Circle
    // ------------------------------------------------------ //
    var progress_circle = $("#progress-circle").gmpc({
        color: brandPrimary,
        line_width: 5,
        percent: 80
    });
    progress_circle.gmpc('animate', 80, 3000);

    // ------------------------------------------------------- //
    // External links to new window
    // ------------------------------------------------------ //

    $('.external').on('click', function (e) {

        e.preventDefault();
        window.open($(this).attr("href"));
    });

    // ------------------------------------------------------ //
    // For demo purposes, can be deleted
    // ------------------------------------------------------ //

    var stylesheet = $('link#theme-stylesheet');
    $( "<link id='new-stylesheet' rel='stylesheet'>" ).insertAfter(stylesheet);
    var alternateColour = $('link#new-stylesheet');

    if ($.cookie("theme_csspath")) {
        alternateColour.attr("href", $.cookie("theme_csspath"));
    }

    $("#colour").change(function () {

        if ($(this).val() !== '') {

            var theme_csspath = 'css/style.' + $(this).val() + '.css';

            alternateColour.attr("href", theme_csspath);

            $.cookie("theme_csspath", theme_csspath, { expires: 365, path: document.URL.substr(0, document.URL.lastIndexOf('/')) });

        }

        return false;
    });
});

function inputTypeUserChanged(){
    if ($('input[name="typeUser"]:checked').val() === "client") {
        $("select[name=role] option").each(function() {
            $(this).remove();
        });
        
        if($("#userInformed").length){
            $.post("../../utils/controller/role/role-js.ctrl.php", {typeUser:$('input[name="typeUser"]:checked').val(), userInformed:$('#userInformed').val()},
              function(valor){
                $("select[name=role]").append(valor);
              }
            )
        }

        $('.dataOfClient').slideDown("slow");
    } else {
        $('.dataOfClient').slideUp("slow");
    }

    if ($('input[name="typeUser"]:checked').val() === "employee") {
        $("select[name=role] option").each(function() {
            $(this).remove();
        });
        
        if($("#userInformed").length){
            $.post("../../utils/controller/role/role-js.ctrl.php", {typeUser:$('input[name="typeUser"]:checked').val(), userInformed:$('#userInformed').val()},
              function(valor){
                $("select[name=role]").append(valor);
              }
            )
        }

        $('.dataOfEmployee').slideDown("slow");
    } else {
        $('.dataOfEmployee').slideUp("slow");
    }
}

function proximoCampo(atual, proximo){
    if(atual.value.length >= atual.maxLength){
        document.getElementById(proximo).focus();
    }
}

$(document).on("click", "#set_expiration_date", function () {
    var info = $(this).attr('data-id');
    var str = info.split('|');
    var title = str[0];
    var id = str[1];
    var description = str[2];
    var exp_date = " - " + str[3];

    title = title + exp_date;

    $("#expiration_date .modal-content h4").html(title);
    $("#expiration_date .modal-body #desc_of_file").val(description);
    $("#expiration_date .modal-body #id_of_file").val(id);
});

$(document).on("click", "#set_paid_date", function () {
    var info = $(this).attr('data-id');
    var str = info.split('|');
    var id = str[0];
    var description = str[1];
    $("#paid_date .modal-body #desc_of_file").val(description);
    $("#paid_date .modal-body #id_of_file").val(id);
});

function ir_para_pagina(numero_da_pagina) {
    //Pegamos o número de itens definidos que seria exibido por página
    var mostrar_por_pagina = parseInt($('#mostrar_por_pagina').val(), 0);
    //pegamos  o número de elementos por onde começar a fatia
    inicia = numero_da_pagina * mostrar_por_pagina;
    //o número do elemento onde terminar a fatia
    end_on = inicia + mostrar_por_pagina;
    $('#conteudo').children().css('display', 'none').slice(inicia, end_on).css('display', 'block');
    $('.page[longdesc=' + numero_da_pagina+ ']').addClass('active')
    .siblings('.active').removeClass('active');
    $('#current_page').val(numero_da_pagina);
}

function anterior() {
    nova_pagina = parseInt($('#current_page').val(), 0) - 1;
    //se houver um item antes do link ativo atual executar a função
    if ($('.active').prev('.page').length != true) {
      ir_para_pagina(nova_pagina);
    }
}

function proxima() {
    nova_pagina = parseInt($('#current_page').val(), 0) + 1;
    //se houver um item após o link ativo atual executar a função
    if ($(".active").next(".page").length != true) {
      ir_para_pagina(nova_pagina);
    }
}

function setBorderClass(qtd, element){
    if (qtd == 0){
        $(element).addClass("border-primary");
        $(element + " .card-header").addClass("text-white bg-primary");
    } else if (qtd < 2) {
        $(element).addClass("border-warning");
        $(element + " .card-header").addClass("bg-warning");
        $(element + " .card-body .btn").addClass("btn-warning");
    } else {
        $(element).addClass("border-danger");
        $(element + " .card-header").addClass("text-white bg-danger");
        $(element + " .card-body .btn").addClass("btn-danger");
    }
}

function verifyActualDate(iNeed){
    var get_date = new Date(),
        actual_day  = get_date.getDate(),
        actual_month  = get_date.getMonth() + 1,
        actual_year  = get_date.getFullYear();

    if (actual_day < 10){
        actual_day = "0" + actual_day;
    }
    if (actual_month < 10) {
        actual_month = "0" + actual_month;
    }

    var actual_date = actual_year+'-'+actual_month+'-'+actual_day;

    if (iNeed == "actual_year") {
        return actual_year;
    } else if (iNeed == "actual_month"){
        return actual_month;
    } else if (iNeed == "actual_day"){
        return actual_day;
    }

    return actual_date;
}

function redirectoToTicketRemovingTextTicket(idChat, idAttendant){
  var url = idChat + '/' + idAttendant;

  window.open(url, "_self");
}

function redirectToTicket(idChat, idAttendant){
  var url = 'ticket/' + idChat + '/' + idAttendant;

  window.open(url, "_blank");
}