import tinymce from "tinymce";
import "tinymce/themes/silver/theme";

import "tinymce/plugins/image";
import "tinymce/plugins/code";

let form = document.querySelector("#text_editor");

tinymce.init({
    selector: "textarea",
    language: document.querySelector("#userLocale").textContent,
    plugins: 'image code',
    menubar: false,
    height: '480',
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | image | code',
    toolbar_sticky: true,
    branding: false,
    automatic_uploads: true,
    images_upload_url: `image/${+form.dataset.pageId}`,
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
