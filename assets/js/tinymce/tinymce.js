import tinymce from "tinymce";
import "tinymce/themes/silver/theme";

import "tinymce/plugins/image";
import "tinymce/plugins/code";

let form = document.querySelector("#text_editor");
let currentLanguage = document.querySelector("#userLocale").textContent == "fr" ? "fr_FR" : "en";
console.log(+form.dataset.internalTemplateId)
tinymce.init({
    selector: "textarea",
    language: currentLanguage,
    plugins: 'image code',
    menubar: false,
    height: '480',
    toolbar: 'undo redo | bold italic underline strikethrough forecolor backcolor | fontselect fontsizeselect  | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | image | styleselect | code',
    toolbar_sticky: true,
    fontsize_formats: "8px 10px 12px 14px 18px 24px 36px 48px 56px 64px 72px 80px 88px 92px 100px 110px 120px",
    branding: false,
    automatic_uploads: true,
    images_upload_url: `image/${+form.dataset.internalTemplateId}`,
    file_picker_types: 'image',
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                try {
                    cb(blobInfo.blobUri(), { title: file.name });
                } catch (error) {
                    console.log(error)
                }
            };

            reader.readAsDataURL(file);
        };

        input.click();
    },
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i'
    ]

});
