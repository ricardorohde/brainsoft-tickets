$(document).ready(function () {
    setTimeout(function () {
        $("#example_filter > label > input").focus();
        $("#ticketListOfClient_filter > label > input").focus();
    }, 1000);

    $('input[name=typeUser]').change(function () {
        inputTypeUserChanged();
    });

    inputTypeUserChanged();

    function inputTypeUserChanged() {
        if ($('input[name="typeUser"]').val() === "client") {
            $("select[name=role] option").each(function () {
                $(this).remove();
            });

            if ($("#userInformed").length) {
                $.post("../../../../utils/controller/role/role-js.ctrl.php", { typeUser: $('input[name="typeUser"]').val(), userInformed: $('#userInformed').val() },
                    function (valor) {
                        $("select[name=role]").append(valor);
                    }
                )
            }

            $('.dataOfClient').slideDown("slow");
        } else {
            $('.dataOfClient').slideUp("slow");
        }

        if ($('input[name="typeUser"]').val() === "employee") {
            $("select[name=role] option").each(function () {
                $(this).remove();
            });

            if ($("#userInformed").length) {
                $.post("../../../../utils/controller/role/role-js.ctrl.php", { typeUser: $('input[name="typeUser"]').val(), userInformed: $('#userInformed').val() },
                    function (valor) {
                        $("select[name=role]").append(valor);
                    }
                )
            }

            $('.dataOfEmployee').slideDown("slow");
        } else {
            $('.dataOfEmployee').slideUp("slow");
        }
    }
});