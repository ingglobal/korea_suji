setTimeout(function(){
    if($('iframe.cheditor-editarea').length){
        $('iframe.cheditor-editarea').each(function(){
            $(this).css('background-color','rgb(6,13,27)')
            $(this).contents().find('body').css('color','rgba(255,255,255)');
        });
    }
},2000);
$(function(){
    var ft_p = $('#ft p');
    $('<strong style="color:#fff;"><span style="color:yellow;">한국수지</span> EPCS(MES) 시스템</strong>').appendTo(ft_p);
});