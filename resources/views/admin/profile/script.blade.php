<script>
    "use strict";

    $('select#role').select2({
        theme: 'bootstrap4',
        selectOnClose: true,
        ajax: {
            url: "{{ route('roles.search') }}",
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        }
    });

    function createInput(data) {
        $('.socmed').append('<div class="form-group row" id="socmed' + data.id + '"><label for="" class="col-sm-2 col-form-label"> URL ' + data.name + '</label><div class="col-sm-10"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text"> <i class="' + data.icon + '"></i></span></div><input type="text" name="' + data.slug + '" class="form-control" placeholder="' + data.url + '"><div class="input-group-append" onclick="removeInput(' + data.id + ')"><span class="input-group-text" ><i class="fas fa-times"></i></span></div></div></div><input type="hidden" name="socmed[]" value="' + data.id + '"></div>');
    }

    function removeInput(id) {
        document.getElementById("socmed" + id).remove();
    }

    $("select#socialmedia").on('change', function() {
        let dataID = $('select#socialmedia').find(':selected').val() //get id;
        $.ajax({
            url: '/getsocmed',
            data: {
                'id': dataID
            },
            type: 'get',
            dataType: 'json',
            success: function(data) {
                if (!document.getElementById('socmed' + data.id)) {
                    createInput(data);
                }
            }
        })
    });

    $('select#socialmedia').select2({
        theme: 'bootstrap4',
        selectOnClose: false,
        ajax: {
            url: "{{ route('socialmedia.search') }}",
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        }
    });

    $(function() {
        $('.upload-msg').on("click", function() {
            $('#btn-remove').attr('hidden', 'hidden');
            $('#btn-upload-result').removeAttr('hidden');
            $('#btn-upload-reset').removeAttr('hidden');
            $('#upload').trigger("click");
        })

        $('#btn-upload-reset, #btn-remove').on("click", function() {
            $('#btn-remove').attr('hidden', 'hidden');
            $('#display').removeAttr('hidden');
            $('#btn-upload-result').removeAttr('hidden');
            $('#btn-upload-reset').removeAttr('hidden');
            $('.upload-photo').removeClass('ready result');
            $("#display-i").html('');
            $('#upload').val('');
        });

        let $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                if (/^image/.test(input.files[0].type)) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('.upload-photo').addClass('ready');
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        }).then(function() {
                            // console.log('jQuery bind complete');
                        });
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    alert("__('You may only select image files')");
                }
            } else {
                alert("__('Sorry - you're browser doesn't support the FileReader API')");
            }
        }

        $uploadCrop = $('#display').croppie({
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 300
            },
        });

        function popupResult(result) {
            let html = '<img src="' + result.src + '" />';
            $("#display-i").html(html);
        }

        $('#upload').on('change', function() {
            readFile(this);
        });

        $('#btn-upload-result').on('click', function(ev) {
            let fileInput = document.getElementById('upload');
            let fileName = fileInput.files[0].name;
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(resp) {
                $('#btn-upload-result').attr('hidden', 'hidden');
                $('#display').attr('hidden', 'hidden');
                $('.upload-photo').addClass('result');
                $('input[name=image_base64]').val(resp);
                popupResult({
                    src: resp
                });
            });
        });
    });
</script>
