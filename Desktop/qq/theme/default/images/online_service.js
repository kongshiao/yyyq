/**
 +----------------------------------------------------------
 * 在线客服
 +----------------------------------------------------------
 */
$(document).ready(function(e) {
    // 右侧滚动
    $(".onlineService").css("right", "0px");

    // 弹出窗口
    var button_toggle = true;
    $(".onlineIcon").live("mouseover",
    function() {
        button_toggle = false;
        $("#pop").show();
    }).live("mouseout",
    function() {
        button_toggle = true;
        hideRightTip()
    });
    $("#pop").live("mouseover",
    function() {
        button_toggle = false;
        $(this).show()
    }).live("mouseout",
    function() {
        button_toggle = true;
        hideRightTip()
    });
    function hideRightTip() {
        setTimeout(function() {
            if (button_toggle) $("#pop").hide()
        },
        500)
    }

    // 返回顶部
    $(".goTop").live("click",
    function() {
        var _this = $(this);
        $('html,body').animate({
            scrollTop: 0
        },
        500,
        function() {
            _this.hide()
        })
    });
    $(window).scroll(function() {
        var htmlTop = $(document).scrollTop();
        if (htmlTop > 0) {
            $(".goTop").show()
        } else {
            $(".goTop").hide()
        }
    })
    // console.log(000000000000000)
    // //二级菜单的打开关闭
    // $('.treeBox li').hover(function(){
    //     console.log(1111111111111)
    //     $(this).next('ul').slideDown()
    // }, function(){
    //     console.log(22222222222222)
    //     $(this).next('ul').slideUp()
    // })

    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }


    if(window.location.pathname === '/page.php' && +getUrlParam('id') === 2){

        $('.content img').attr('data-action', 'zoom')
    }
   


});