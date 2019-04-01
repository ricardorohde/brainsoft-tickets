$(document).ready(function () {
	var send = true;
	var position = 0;
	var statusOfEmail = 1;

	$("input").blur(function () {
		verifyInput(this);
	});

	$("select").blur(function () {
		verifySelect(this);
	});

	$('.btnAction').click(function () {
		send = true;
		position = 0;
		$("input:visible").each(function () {
			if ($(this).attr("id") != "email") {
				verifyInput(this);
			} else {
				if ($(this).val() != "") {
					validateEmail(this);
				}
			}
		});


		$("select:visible").each(function () {
			verifySelect(this);
		});

		if (send && statusOfEmail == 1) {
			return true;
		} else {
			return false;
		}
	});

	function validateEmail(field) {
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if (!filter.test($(field).val())) {
			checkPosition(field);
			$(field).addClass("required");
			return false;
		} else {
			$("input[name=email]").removeClass("required");
			return true;
		}
	}

	function verifyInput(field) {
		if ($(field).val() == "" && $(field).attr("id") != "email" && $(field).attr("type") != "search") {
			checkPosition(field);
			$(field).addClass("required");
			return false;
		} else {
			$(field).removeClass("required");
			return true;
		}
	}

	function verifySelect(field) {
		element = "#" + $(field).attr("id") + " option:selected";

		if ($(element).val() < 1) {
			checkPosition(field);
			$(field).addClass("required");
			return false;
		} else {
			$(field).removeClass("required");
			return true;
		}
	}

	function checkPosition(field)
	{
		if (position == 0) {
			$(field).focus();
		}
		position = 1;
		send = false;
	}
});