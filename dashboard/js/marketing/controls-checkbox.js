function verifyCheckbox() {
  verifyAllCheckBox();
}

function verifyAllCheckBox() {
  $filters = "";
  $("input[type=checkbox]:checked").each(function() {
    $filters = $filters + $(this).val() + ",";
  });
  $filters = $filters.substring(0, $filters.length - 1);

  if ($filters != "") {
    hideElement(".tableClients");
    $("table[name=tableWithData] > tbody").html("");
    addElement(
      "#sendEmail > div:eq(2)",
      '<div id="status" class="form-group row"><div id="searching" class="offset-sm-2 col-sm-10">Filtrando clientes...</div></div>'
    );

    verifySelectToPost();
  } else {
    verifySelect();
  }
}

function verifySelectToPost() {
  var destinySelected = $("#destiny option:selected").val();
  if (destinySelected != "choose") {
    if (destinySelected == "all") {
      makePostByAll();
    } 
    if (destinySelected == "state") {
      makePostByState();
    }
    if (destinySelected == "registry") {
      makePostByRegistry();
    }
  }
}

function makePostByAll() {
  $.post(
    "../../../utils/controller/root/root.ctrl.php",
    {
      toController: "EmailController",
      action: "getAllClientsWithFilter",
      filters: $filters
    },
    function(valor) {
      $("table[name=tableWithData] > tbody").append(valor);
    }
  );

  setTimeout(function() {
    removeElement("#status");
    $(".tableClients").show();
  }, 2000);
}

function makePostByState() {
  $state = $("#allStates option:selected").val();
  $.post(
    "../../../utils/controller/root/root.ctrl.php",
    {
      toController: "EmailController",
      action: "getAllClientsOfStateWithFilter",
      value: $state,
      filters: $filters
    },
    function(valor) {
      $("table[name=tableWithData] > tbody").append(valor);
    }
  );

  setTimeout(function() {
    removeElement("#status");
    $(".tableClients").show();
  }, 2000);
}

function makePostByRegistry() {
  var registry = $("input[name=setRegistry]").val();
  $.post(
    "../../../utils/controller/root/root.ctrl.php",
    {
      toController: "EmailController",
      action: "getAllClientsOfRegistryWithFilter",
      value: registry,
      filters: $filters
    },
    function(valor) {
      $("table[name=tableWithData] > tbody").append(valor);
    }
  );

  setTimeout(function() {
    removeElement("#status");
    $(".tableClients").show();
  }, 2000);
}

function verifySelect() {
  var destinySelected = $("#destiny option:selected").val();
  hideElement(".tableClients");
  $("table[name=tableWithData] > tbody").html("");

  if (destinySelected != "choose") {
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
        $(".checkbox-role").removeClass("hide");
      }, 2000);
    } else {
      $("table[name=tableWithData] > tbody").html("");
      $(".divSetRegistry").show();
    }
  }
}

function removeElement(element) {
  $(element).remove();
}