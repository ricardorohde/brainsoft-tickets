(function ($) {
                
    // Smooth scrolling using jQuery easing
    $('.navbar-nav li a').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 54)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
    });
    
    // Add smooth scrolling to all links in navbar
    /*$("a.mouse-hover, a.get-quote").on('click', function(event) {
      var hash = this.hash;
      if( hash ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 1300, 'easeInOutExpo');
      }
    });*/
})(jQuery);

function iframeRedirect(url){
    var iframe = document.getElementById('iframe'); // Busca o IFRAME por ID
    iframe.src = url; // REDIRECIONA O IFRAME
}

$(document).ready(function () {
    $('.phone-mask').mask('(99) 9 9999-9999');

    //rotation speed and timer
    var speed = 10000;
    
    var run = setInterval(rotate, speed);
    var slides = $('.slide');
    var container = $('#slides ul');
    var elm = container.find(':first-child').prop("tagName");
    var item_width = container.width();
    var previous = 'prev'; //id of previous button
    var next = 'next'; //id of next button
    slides.width(item_width); //set the slides to the correct pixel width
    container.parent().width(item_width);
    container.width(slides.length * item_width); //set the slides container to the correct total width
    container.find(elm + ':first').before(container.find(elm + ':last'));
    resetSlides();
    
    
    //if user clicked on prev button
    
    $('#buttons a').click(function (e) {
        //slide the item
        
        if (container.is(':animated')) {
            return false;
        }
        if (e.target.id == previous) {
            container.stop().animate({
                'left': 0
            }, 1500, function () {
                container.find(elm + ':first').before(container.find(elm + ':last'));
                resetSlides();
            });
        }
        
        if (e.target.id == next) {
            container.stop().animate({
                'left': item_width * -2
            }, 1500, function () {
                container.find(elm + ':last').after(container.find(elm + ':first'));
                resetSlides();
            });
        }
        
        //cancel the link behavior            
        return false;
        
    });
    
    //if mouse hover, pause the auto rotation, otherwise rotate it    
    container.parent().mouseenter(function () {
        clearInterval(run);
    }).mouseleave(function () {
        run = setInterval(rotate, speed);
    });
    
    
    function resetSlides() {
        //and adjust the container so current is in the frame
        container.css({
            'left': -1 * item_width
        });
    }
    
});
//a simple function to click next link
//a timer will call this function, and the rotation will begin

function rotate() {
    $('#next').click();
}

$(document).ready(function() {
    $("#moreCharacteristics").click(moreCharacteristics);
    $("#showDivService").click(showDivService);
    $("#showDivProcess").click(showDivProcess);
    $("#showDivWebBackup").click(showDivWebBackup);
    $("#moreDocumentScanning").click(moreDocumentScanning);
    $("#moreSearchProcess").click(moreSearchProcess);
    $("#moreWebBackup").click(moreWebBackup);
});
 
function moreCharacteristics(){
    moreAboutImob('Sistema Imob', '');
}
function showDivService(){
    verifyDivOpened('messageProcess', 'messageWebBackup', 'messageService');
}
function showDivProcess(){
    verifyDivOpened('messageService', 'messageWebBackup', 'messageProcess');
}
function showDivWebBackup(){
    verifyDivOpened('messageService', 'messageProcess', 'messageWebBackup');
}
function moreDocumentScanning(){
    moreAboutImob('Digitalização de Documentos', '#messageService');
}
function moreSearchProcess(){
    moreAboutImob('Consulta de Processos', '#messageProcess');
}
function moreWebBackup(){
    moreAboutImob('Imob WebBackup', '#messageWebBackup');   
}

function verifyDivOpened(divToVerify, divToElseFadeOut, rootDiv){
    /*
        divToVerify = Primeira div a ser verificada se ja está visivel para o usuário
        divToElseFadeOut = Segunda div a ser verificada caso a primeira ja esteja fechada
        rootDiv = div principal que deve ser mostrada no momento em que o botão é clicado
    */
    var isAnotherDivVisible = $('#' + divToVerify + '').is(':visible');
    if (isAnotherDivVisible) $('#' + divToVerify + '').slideUp("slow");
    else $('#' + divToElseFadeOut + '').slideUp("slow"); 

    var isRootDivVisible = $('#' + rootDiv + '').is(':visible');
    if (isRootDivVisible) { $('#' + rootDiv + '').slideUp("slow"); scrollWithDiv('#services', 600); }
    else { $('#' + rootDiv + '').slideDown("slow"); scrollWithDiv('.last-icon', 600); }
}

function moreAboutImob(val, divToClose){
    $("#selectAssunto select").val("" + val + "");
    scrollWithDiv('#contact', 1300);
    $("#name").focus();
    $('' + divToClose + '').slideUp();   
}

function scrollWithDiv(divTarget, timeScroll){
    var target_offset = $("" + divTarget + "").offset();
    var target_top = target_offset.top;
    $('html, body').animate({ scrollTop: target_top }, timeScroll);
}
