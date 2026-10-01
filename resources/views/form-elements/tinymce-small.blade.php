
<script>
    tinymce.init({
        selector: ".tinymce",
        language: "pl",
        skin: "oxide",
        content_css: 'default',
        branding: false,
        menubar:false,
        statusbar: false,
        toolbar_location: 'bottom',
        height: 200,
        plugins: [
            ""
        ],
        toolbar1: "bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | superscript subscript | numlist bullist outdent indent | responsivefilemanager",
        relative_urls: false,
        image_advtab: true,
        external_filemanager_path:"/js/editor/plugins/filemanager/",
        filemanager_title:"kCMS Filemanager" ,
        external_plugins: { "filemanager" : "{{ asset('/js/editor/plugins/filemanager/plugin.min.js') }}"}
    });
</script>
