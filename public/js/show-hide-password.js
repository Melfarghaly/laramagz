$(".password").on('click', function (event) {
    event.preventDefault();
    if ($('input[name=password]').attr('type') === 'password') {
        $('input[name=password]').attr('type', 'text');
        $('.password span.fas').addClass("fa-eye-slash").removeClass("fa-eye");
    } else if ($('input[name=password]').attr('type') === 'text') {
        $('input[name=password]').attr('type', 'password');
        $('.password span.fas').addClass("fa-eye").removeClass("fa-eye-slash");
    }
});

$(".password_confirmation").on('click', function (event) {
    event.preventDefault();
    if ($('input[name=password_confirmation]').attr('type') === 'password') {
        $('input[name=password_confirmation]').attr('type', 'text');
        $('.password_confirmation span.fas').addClass("fa-eye-slash").removeClass("fa-eye");
    } else if ($('input[name=password_confirmation]').attr('type') === 'text') {
        $('input[name=password_confirmation]').attr('type', 'password');
        $('.password_confirmation span.fas').addClass("fa-eye").removeClass("fa-eye-slash");
    }
});

$(".old_password").on('click', function (event) {
    event.preventDefault();
    if ($('input[name=old_password]').attr('type') === 'password') {
        $('input[name=old_password]').attr('type', 'text');
        $('.old_password span.fas').addClass("fa-eye-slash").removeClass("fa-eye");
    } else if ($('input[name=old_password]').attr('type') === 'text') {
        $('input[name=old_password]').attr('type', 'password');
        $('.old_password span.fas').addClass("fa-eye").removeClass("fa-eye-slash");
    }
});
