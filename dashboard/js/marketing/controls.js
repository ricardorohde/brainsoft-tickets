$(document).ready(function() {
  $("#destiny").change(function() {
    var destinySelected = $("#destiny option:selected").val();
    hideElement(".tableClients");
    hideElement(".divAllStates");
    hideElement(".divSetRegistry");
    $(".checkbox-role").addClass("hide");

    if (destinySelected != "choose") {
      uncheckAllBoxes();

      if (destinySelected == "all") {
        $("table[name=tableWithData] > tbody").html("");
        addElement(
          "#sendEmail > div:eq(1)",
          '<div id="status" class="form-group row"><div id="searching" class="offset-sm-2 col-sm-10">Buscando clientes...</div></div>'
        );

        $.post(
          "../../../utils/controller/root/root.ctrl.php",
          {
            toController: "EmailController",
            action: "getAllClients",
            value: ""
          },
          function(valor) {
            $("table[name=tableWithData] > tbody").append(valor);
          }
        );

        setTimeout(function() {
          removeElement("#status");
          $(".tableClients").show();
          $(".checkbox-role").removeClass("hide");
        }, 2000);
      } else if (destinySelected == "state") {
        $("select[name=allStates]").html("");
        $("table[name=tableWithData] > tbody").html("");
        addElement(
          "#sendEmail > div:eq(1)",
          '<div id="status" class="form-group row"><div id="searching" class="offset-sm-2 col-sm-10">Buscando estados...</div></div>'
        );

        $.post(
          "../../../utils/controller/root/root.ctrl.php",
          {
            toController: "EmailController",
            action: "getAllStates",
            value: ""
          },
          function(valor) {
            $("select[name=allStates]").append(valor);
          }
        );

        setTimeout(function() {
          removeElement("#status");
          $(".divAllStates").show();
        }, 2000);
      } else {
        $("table[name=tableWithData] > tbody").html("");
        $(".divSetRegistry").show();
      }
    }
  });

  $("#allStates").change(function() {
    var stateSelected = $("#allStates option:selected").val();
    if (stateSelected != "chooseState") {
      removeElement("#status");
      hideElement(".tableClients");
      $("table[name=tableWithData] > tbody").html("");

      addElement(
        "#sendEmail > div:eq(2)",
        '<div id="status" class="form-group row"><div id="searching" class="offset-sm-2 col-sm-10">Buscando clientes...</div></div>'
      );

      $.post(
        "../../../utils/controller/root/root.ctrl.php",
        {
          toController: "EmailController",
          action: "getAllClientsOfState",
          value: stateSelected
        },
        function(valor) {
          $("table[name=tableWithData] > tbody").append(valor);
        }
      );

      setTimeout(function() {
        removeElement("#status");
        $(".tableClients").show();
        $(".checkbox-role").removeClass("hide");
      }, 2000);
    }
  });

  $(function() {
    $("#setRegistry").autocomplete({
      highlightClass: "bold-text",
      source: "../../../utils/buscaDados.php"
    });
  });

  $("input[name=setRegistry]").blur(function() {
    $("table[name=tableWithData] > tbody").html("");
    hideElement(".tableClients");

    var registrySelected = $(this).val();
    addElement(
      ".divSetRegistry > div:eq(0)",
      '<div id="status" class="form-group row"><div id="searching" class="offset-sm-2 col-sm-10">Buscando clientes...</div></div>'
    );

    $.post(
      "../../../utils/controller/root/root.ctrl.php",
      {
        toController: "EmailController",
        action: "getAllClientsOfRegistry",
        value: registrySelected
      },
      function(valor) {
        $("table[name=tableWithData] > tbody").append(valor);
      }
    );

    setTimeout(function() {
      removeElement("#status");
      $(".tableClients").show();
      $(".checkbox-role").removeClass("hide");
    }, 2000);
  });
});

function addElement(reference, element) {
  $(reference).after(element);
}

function removeElement(element) {
  $(element).remove();
}

function hideElement(element) {
  $(element).hide();
}

function uncheckAllBoxes() {
  $("input[type=checkbox]:checked").each(function() {
    $(this).prop("checked", false);
  });
}
