$(document).ready(function() {
  $("input[name=inlineRadioOptions]").on("change", function() {
    $.post(
      "/controller/employee/js",
      { status: $(this).val() },
      function(valor) {
        location.reload(true);
      }
    );
  });
});
