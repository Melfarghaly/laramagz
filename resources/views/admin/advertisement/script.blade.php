<script>
    "use strict";

    // Upload Ad Image

    $(document).on('click', '.upload-msg', function() {
        $("#upload").trigger("click");
    });

    $('#reset').on("click", function() {
        $('#display').removeAttr('hidden');
        $('#reset').attr('hidden');
        $('.upload-image').removeClass('ready result');
        $('#image_preview_container').attr('src', '#');
    });

    function readFile(input) {
        const reader = new FileReader();
        reader.onload = (e) => {
            let image = new Image();
            image.onload = function() {
                $('input[name=width]').val(image.width);
                $('input[name=height]').val(image.height);
            };
            image.src = reader.result;
            $('.upload-image').addClass('ready');
            $('#image_preview_container').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }

    $('#upload').on('change', function() {
        readFile(this);
    });

    // Ad Type

    $('input[type="radio"]').on('click', function() {
        let ads_type = $(this).attr("value");
        if (ads_type == 'image') {
            $('#ad_image').removeAttr('hidden');
            $('#ad_ga').attr('hidden',true);
            $('#ad_script_code').attr('hidden',true);
        } else if (ads_type == 'ga') {
            $('#ad_image').attr('hidden',true);
            $('#ad_ga').removeAttr('hidden');
            $('#ad_script_code').attr('hidden',true);
            $('form#advertisementForm').attr('novalidate',true);
            editor.refresh();
        } else {
            $('#ad_image').attr('hidden',true);
            $('#ad_ga').attr('hidden',true);
            $('#ad_script_code').removeAttr('hidden');
            $('form#advertisementForm').attr('novalidate',true);
            editor1.refresh();
        }
    });

    //Codemirror

    let editor = CodeMirror.fromTextArea(document.getElementById("ga"), {
        mode: "htmlmixed",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true
    });
    editor.setSize(null, 100);
    editor.on('change', (editor) => {
        const text = editor.doc.getValue()
        $('#ga').html(text);
    });

    let editor1 = CodeMirror.fromTextArea(document.getElementById("custom"), {
        mode: "htmlmixed",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true
    });
    editor1.setSize(null, 100);
    editor1.on('change', (editor) => {
        const text = editor.doc.getValue()
        $('#custom').html(text);
    });
</script>
