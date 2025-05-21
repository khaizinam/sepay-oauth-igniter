<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="mb-4 text-center">Đăng ký</h3>
            <form action="<?= base_url('auth/ajaxregister') ?>" method="POST" id="form_register">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="mb-3">
                    <label for="name" class="form-label" >Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div id="name_error" class="txt-error"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="false" required>
                    <div id="email_error" class="txt-error"></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="false" required>
                    <div id="password_error" class="txt-error"></div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <div id="confirm_password_error" class="txt-error"></div>
                </div>
                <div id="register-message"></div>
                <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                <div class="text-center mt-3">
                    <a href="<?= base_url('auth/login') ?>">Đã có tài khoản? Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
        

       
    $('#form_register').on('submit', function(e) {
        e.preventDefault();
        $('.txt-error').text('');
        $('#register-message').html('');
        
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const confirm_password = $('#confirm_password').val();
        console.log({
            name,
            email,
            password,
            confirm_password
        });
        
        let hasError = false;

        if (name.length < 3) {
            $('#name_error').text('Tên phải có ít nhất 3 ký tự.');
            hasError = true;
        }

        if (!email.match(/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
            $('#email_error').text('Email không hợp lệ.');
            hasError = true;
        }

        if (password.length < 8) {
            $('#password_error').text('Mật khẩu phải có ít nhất 8 ký tự.');
            hasError = true;
        }

        if (password !== confirm_password) {
            $('#confirm_password_error').text('Mật khẩu xác nhận không khớp.');
            hasError = true;
        }

        if (hasError) return;

        var form = $(this);
        var btn = form.find('button[type="submit"]');
        btn.prop('disabled', true);

        var formData = {};
        form.serializeArray().forEach(function(field) {
            formData[field.name] = field.value;
        });

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(res) {
                if (res.csrf_hash) {
                    $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                }
                if (res.status === 'success') {
                    $('#register-message').html('<div class="alert alert-success">' + res.message + '</div>');
                    form.trigger('reset');
                } else {
                    $('#register-message').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            },
            error: function(xhr) {
                let msg = 'Đã có lỗi xảy ra!';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.csrf_hash) {
                        $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                    }
                    if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    if(xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(e => {
                            $(`#${e}_error`).text(errors[e]);
                        });
                    }
                }
                $('#register-message').html('<div class="alert alert-danger">' + msg + '</div>');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
