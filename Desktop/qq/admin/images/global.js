/**
 +----------------------------------------------------------
 * 页面加载时运行
 +----------------------------------------------------------
 */
$(function() {
    // 下拉菜单
    if ($('.dropMenu').length && $('.dropMenu').length > 0) {
        $('.dropMenu').hover(function() {
            $(this).addClass('active');
        },
        function() {
            $(this).removeClass('active');
        });
    }
});

/**
 +----------------------------------------------------------
 * 刷新验证码
 +----------------------------------------------------------
 */
function refreshimage() {
    var cap = document.getElementById('vcode');
    cap.src = cap.src + '?';
}

/**
 +----------------------------------------------------------
 * 无组件刷新局部内容
 +----------------------------------------------------------
 */
function dou_callback(page, name, value, target) {
    $.ajax({
        type: 'GET',
        url: page,
        data: name + '=' + value,
        dataType: "html",
        success: function(html) {
            $('#' + target).html(html);
        }
    });
}

/**
 +----------------------------------------------------------
 * 表单全选
 +----------------------------------------------------------
 */
function selectcheckbox(form) {
    for (var i = 0; i < form.elements.length; i++) {
        var e = form.elements[i];
        if (e.name != 'chkall' && e.disabled != true) e.checked = form.chkall.checked;
    }
}

/**
 +----------------------------------------------------------
 * 显示服务端扩展列表
 +----------------------------------------------------------
 */
function get_cloud_list(unique_id, get, localsite) {
    $.ajax({
        type: 'GET',
        url: 'https://cloud.dou.co/extend&rec=client',
        data: {'unique_id':unique_id, 'get':get, 'localsite':localsite},
        dataType: 'jsonp',
        jsonp: 'jsoncallback',
        success: function(cloud) {
            $('.selector').html(cloud.selector)
            $('.cloudList').html(cloud.html)
            $('.pager').html(cloud.pager)
        }
    });
}

/**
 +----------------------------------------------------------
 * 写入可更新数量
 +----------------------------------------------------------
 */
function cloud_update_number(localsite) {
    $.ajax({
        type: 'GET',
        url: 'https://cloud.dou.co/extend&rec=cloud_update_number',
        data: {'localsite':localsite},
        dataType: 'jsonp',
        jsonp: 'jsoncallback',
        success: function(cloud) {
            change_update_number(cloud.update, cloud.patch, cloud.module, cloud.plugin, cloud.theme, cloud.mobile)
        }
    });
}

/**
 +----------------------------------------------------------
 * 修改update_number值
 +----------------------------------------------------------
 */
function change_update_number(update, patch, module, plugin, theme, mobile) {
    $.ajax({
        type: 'POST',
        url: 'cloud.php?rec=update_number',
        data: {'update':update, 'patch':patch, 'module':module, 'plugin':plugin, 'theme':theme}
    });
}

/**
 +----------------------------------------------------------
 * 弹出窗口
 +----------------------------------------------------------
 */
function douFrame(name, frame, url) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {'name':name, 'frame':frame},
        dataType: 'html',
        success: function(html) {
            $(document.body).append(html);
        }
    });
}

/**
 +----------------------------------------------------------
 * 显示和隐藏
 +----------------------------------------------------------
 */
function douDisplay(target, action) {
    var traget = document.getElementById(target);
    if (action == 'show') {
        traget.style.display = 'block';
    } else {
        traget.style.display = 'none';
    }
}

/**
 +----------------------------------------------------------
 * 添加文本
 +----------------------------------------------------------
 */
function douInput(target, text) {
    document.getElementById(target).value = text;
}

/**
 +----------------------------------------------------------
 * 清空对象内HTML
 +----------------------------------------------------------
 */
function douRemove(target) {
    var obj = document.getElementById(target);
    obj.parentNode.removeChild(obj);
}

/**
 +----------------------------------------------------------
 * 无刷新自定义导航名称
 +----------------------------------------------------------
 */
function change(id, choose) {
    document.getElementById(id).value = choose.options[choose.selectedIndex].title;
}

/**
 +----------------------------------------------------------
 * 给指定表单赋值
 +----------------------------------------------------------
 */
function changeInput(targer, value){
    $('#' + targer).attr("value", value);
}

/**
 +----------------------------------------------------------
 * 移动分类至
 +----------------------------------------------------------
 */
function douAction() {
    var frm = document.forms['action'];
    frm.elements['new_cat_id'].style.display = frm.elements['action'].value == 'category_move' ? '' : 'none';
}

/**
 +----------------------------------------------------------
 * 文件盒子.文件上传
 +----------------------------------------------------------
 */
function fileBox(type, target, thumb_width, thumb_height) {
    var status = $('#' + type + 'File .fileStatus');
    var btn = $('#' + type + 'File .btnFile');
    var field = $('#fileBoxField');
    var target = target ? $('#' + target) : '';
    
    // 点击文件域
    field.click();
    
    // 选择文件后文件域状态改变，开始提交表单
    field.change(function(){
        // 还要判断文件域是否为空
        if($(this).val() != '') { 
            $('#fileBoxForm').ajaxForm({
                type: 'POST',
                data: {'type':type, 'thumb_width':thumb_width, 'thumb_height':thumb_height},
                beforeSubmit: function() {
                    status.show();
                    btn.hide();
                },
                success: function(html) {
                    // 如果是添加详情图片则插入到编辑器
                    if (type == 'content') {
                        insertMce(html);
                    } else {
                        target.html(html);
                    }
                    status.hide();
                    btn.show();
                },
                error: function() {
                    status.hide();
                    btn.show();
                },
                clearForm: true
            }).submit();
         
            // 每次执行后要清空文件域
            field.val('');
        } 
   })
}

/**
 +----------------------------------------------------------
 * 文件盒子.文件删除
 +----------------------------------------------------------
 */
function fileDel(number, target) {
    var target = $('#' + target);
    
    $.ajax({
        type: "POST",
        url: 'file.php?rec=del',
        data: {"number":number},
        dataType: "html",
        success: function(html) {
            target.html(html);
        }
    });
}


