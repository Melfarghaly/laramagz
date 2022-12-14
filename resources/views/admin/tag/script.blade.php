<script>
    "use strict";

    $(document).on("click", "#btn-reset", function() {
        $(".card-form.card-title").html("Add New Tag");
        $("form#insert").removeAttr("href");
        $("#name").val("").removeClass("is-invalid")
        $(".msg-error-name").html("");
        $("#categories").val(null).trigger("change");
        $("#btn-reset").attr("hidden", true);
        $("button[type=submit]").attr("id", "btn-submit").html("Add New Tag");
    })

    $(document).on("click", "#btn-submit-update", function(e) {
        e.preventDefault();
        $("#update button[type=submit]").html("<div class=\"spinner-grow spinner-grow-sm\" role=\"status\"><span class=\"sr-only\">Loading...</span></div> Sending...");
        $("#name").removeClass("is-invalid");
        $(".msg-error-name").html("");

        let editurl = $("form#insert").attr("href");
        $.ajax({
            url: editurl,
            method: 'PUT',
            data: $("#insert").serialize(),
            success: function(response) {
                if (response.errors) {
                    if (response.errors.name) {
                        $("#name").addClass("is-invalid");
                        $("#insert button[type=submit]").html("Resending");
                        $(".msg-error-name").html(response.errors.name);
                    }
                } else if (response.info) {
                    $(".spinner-grow").attr("hidden");
                    toastr.info(response.info)
                    $("#tag-table").DataTable().ajax.reload();
                    $("input[type=text], textarea").val("");
                    $("#insert button[type=submit]").html("Add New Tag");
                    $("#update").attr("id", "insert");
                    $("#btn-reset").attr("hidden", true);
                } else {
                    $(".spinner-grow").attr("hidden");
                    toastr.success(response.success);
                    $("#tag-table").DataTable().ajax.reload();
                    $("input[type=text], textarea").val("");
                    $("#insert button[type=submit]").html("Add New Tag");
                    $("#update").attr("id", "insert");
                    $("#btn-reset").attr("hidden", true);
                }
            },
            error: function(resp) {
                $(".spinner-grow").attr("hidden");
                toastr.error(resp.responseJSON.message);
                $("#tag-table").DataTable().ajax.reload();
                $("input[type=text]").val("");
                $("#insert button[type=submit]").html("Add New Tag");
                $("#update").attr("id", "insert");
                $("#btn-reset").attr("hidden", true);
                $("form#insert").removeAttr("href");
                $("#btn-submit-update").attr("id","btn-submit");
            }
        });
    });

    $(document).on("click", "#btn-submit", function(e) {
        e.preventDefault();
        $("#insert button[type=submit]").html("<div class=\"spinner-grow spinner-grow-sm\" role=\"status\"><span class=\"sr-only\">Loading...</span></div> Sending...");

        $("#name").removeClass("is-invalid")
        $(".msg-error-name").html("");

        $.ajax({
            url: "{{ route('tags.store') }}",
            method: 'POST',
            data: $("#insert").serialize(),
            success: function(response) {
                if (response.errors) {
                    if (response.errors.name) {
                        $("#name").addClass("is-invalid");
                        $("#insert button[type=submit]").html("Resending");
                        $(".msg-error-name").html(response.errors.name);
                        $("#btn-reset").removeAttr("hidden");
                    }
                } else if (response.error_exists) {
                    $("#name").addClass("is-invalid");
                    $("#insert button[type=submit]").html("Resending");
                    $(".msg-error-name").html(response.error_exists);
                } else {
                    $(".spinner-grow").attr("hidden");
                    toastr.success(response.success);
                    $("#tag-table").DataTable().ajax.reload();
                    $("input[type=text]").val("");
                    $("#insert button[type=submit]").html("Add New Tag");
                }
            }
        });
    });
</script>
