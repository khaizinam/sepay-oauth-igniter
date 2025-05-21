<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="mb-4 text-center">Login</h3>
            <form action="<?= base_url('auth/ajaxlogin') ?>" method="POST" id="form_login">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div id="login-message"></div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary w-50 me-2">Login</button>
                    <a href="<?= base_url('auth/register') ?>" class="btn btn-outline-secondary w-50 ms-2">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#form_login').on('submit', function(e){
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true);

            // Thu thập dữ liệu form dưới dạng object
            var formData = {};
            form.serializeArray().forEach(function(field) {
                formData[field.name] = field.value;
            });

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                contentType: 'application/json', // Gửi đúng dạng JSON
                data: JSON.stringify(formData),  // Chuyển thành chuỗi JSON
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(res) {
                    $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                    setTimeout(function(){ location.href = '/home'; }, 500);
                },
                error: function(xhr) {
                    let msg = 'Đã có lỗi xảy ra!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                        $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                    }
                    $('#login-message').html('<div class="alert alert-danger">'+msg+'</div>');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>