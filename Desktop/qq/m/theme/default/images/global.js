/**
 +----------------------------------------------------------
 * 刷新验证码
 +----------------------------------------------------------
 */
function refreshimage()
{
  var cap =document.getElementById("vcode");
  cap.src=cap.src+'?';
}

/**
 +----------------------------------------------------------
 * 表单提交
 +----------------------------------------------------------
 */
function douSubmit(form_id) {
    var formParam = $("#"+form_id).serialize(); //序列化表格内容为字符串
    
    $.ajax({
        type: "POST",
        url: $("#"+form_id).attr("action")+'&do=callback',
        data: formParam,
        dataType: "json",
        success: function(form) {
            if (!form) {
                $("#"+form_id).submit();
            } else {
                for(var key in form) {
                    $("#"+key).html(form[key]);
                }
            }
        }
    });
}

/**
 +----------------------------------------------------------
 * 返回顶部
 +----------------------------------------------------------
 */
$(document).ready(function(e) {
    $(".goTop").live("click",
    function() {
        $('html,body').animate({
            scrollTop: 0
        })
    });
});



function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

$(function(){
    if(window.location.pathname === '/m/page.php' && +getUrlParam('id') === 2){
        console.log( $('.content'))
        $('.content p').attr('data-action', 'zoom').css({'textAlign':'center'})

        $('.content img').attr('data-action', 'zoom').css({
            'width':'200',
            'height':'200',
            'margin':'10',
            'borderWidth':'1',
            'borderColor':'#999',
            'borderStyle':'solid'
        })
    }
})

