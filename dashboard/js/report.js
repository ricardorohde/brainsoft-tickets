$(document).ready(function () {

	var doc = new jsPDF();

	var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
	};

	$('#generate-pdf').click(function () {
    doc.fromHTML($('.report').html(), 10, 10, {
        'width': 110,
        'elementHandlers': specialElementHandlers
    });
    doc.save('sample-file.pdf');
   });
});