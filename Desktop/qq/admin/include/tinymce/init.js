/**
 +----------------------------------------------------------
 * tinymce初始化
 +----------------------------------------------------------
 */
tinymce.init({
    selector:'textarea#content',
    language:'zh_CN',
    width:'800px',
    height:'360px',
    menubar:false,
    skin:'simple',
    theme:'simple',
    convert_urls :false,
    plugins: [
      'autolink lists link charmap textcolor',
      'code hr pagebreak map textpattern image imagetools',
      'media table help wordcount fullscreen'
    ],
    toolbar: 'fontsizeselect bold italic strikethrough forecolor backcolor alignleft aligncenter alignright alignjustify outdent indent numlist bullist code | link unlink media map charmap table hr removeformat undo redo help fullscreen'
});

/**
 +----------------------------------------------------------
 *  tinymce插入HTML
 +----------------------------------------------------------
 */
function insertMce(html) {
    tinyMCE.execCommand('mceInsertContent', false, html); 
}