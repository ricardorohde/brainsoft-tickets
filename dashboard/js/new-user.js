$(document).ready(function () {
    $("input[name=login]").keyup(function () {
        $.post("../../../utils/controller/credential/credential-js.ctrl.php", { userToVerify: $(this).val().trim() },
            function (result) {
                if (result > 0 || $("input[name=login]").val() == "") {
                    document.formAdd.login.style.boxShadow = "0 0 5px #ff0000";
                    document.formAdd.login.style.border = "1px solid #ff0000";
                    $("button[type=submit]").prop("disabled", true);
                } else if (result == 0) {
                    document.formAdd.login.style.boxShadow = "0 0 5px #28a745";
                    document.formAdd.login.style.border = "1px solid #28a745";
                    $("button[type=submit]").prop("disabled", false);
                }
            }
        )
    });

    $("input[name=login]").change(function () {
        $("input[name=login]").val($(this).val().toLowerCase());
    });

    $('input[name=typeUser]').change(function () {
        inputTypeUserChanged();
    });

    inputTypeUserChanged();

    function inputTypeUserChanged() {
        if ($('input[name="typeUser"]:checked').val() === "client") {
            $("select[name=role] option").each(function () {
                $(this).remove();
            });

            if ($("#userInformed").length) {
                $.post("../../../../utils/controller/role/role-js.ctrl.php", { typeUser: $('input[name="typeUser"]:checked').val(), userInformed: $('#userInformed').val() },
                    function (valor) {
                        $("select[name=role]").append(valor);
                    }
                )
            }

            $('.dataOfClient').slideDown("slow");
        } else {
            $('.dataOfClient').slideUp("slow");
        }

        if ($('input[name="typeUser"]:checked').val() === "employee") {
            $("select[name=role] option").each(function () {
                $(this).remove();
            });

            if ($("#userInformed").length) {
                $.post("../../../../utils/controller/role/role-js.ctrl.php", { typeUser: $('input[name="typeUser"]:checked').val(), userInformed: $('#userInformed').val() },
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