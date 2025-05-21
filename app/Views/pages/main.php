<?php if (session()->has('user')): ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="alert alert-success text-center">
                    <h5>Xin chào, <?= esc(session('user'))['name'] ?>!</h5>
                    <div class="mb-3" id="change_email_container">
                        <label for="exampleFormControlInput1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" value="<?= esc(session('user'))['email'] ?>" disabled>
                        <button class="btn btn-primary" id="btn_change_email">
                            Change email
                        </button>
                    </div>
                    <div id="html_append"></div>
                    <a href="<?= base_url('auth/ajaxlogout') ?>" class="btn btn-danger mt-2">Đăng xuất</a>
                </div>
            </div>
        </div>
        <h5 class="text-center mt-4">Danh sách người dùng</h5>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($users) && is_array($users)): ?>
                    <?php foreach ($users as $idx => $user): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= esc($user['name']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td><?= esc($user['is_admin']) ?></td>
                            <td>
                                <?php if(session('user')['is_admin'] == 1 && session('user')['id'] != $user['id']): ?>
                                    <button class="btn btn-danger btn-sm ajax_delete_user" data-id="<?= $user['id'] ?>">
                                        Xoá
                                    </button>
                                <?php else: ?>
                                    <!-- <button class="btn btn-primary btn-sm">
                                        Xoá
                                    </button> -->
                                <?php endif; ?> 
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Không có người dùng nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function(){
        $(document).on('click', '#btn_change_email', function(){
            if($('#change_email_container').hasClass('changing')) {
                updateEmail({email: $('#email').val()});
            } else {
                $('#email').attr('disabled', false);
                $('#change_email_container').addClass('changing');
            }
        });

        function updateEmail(data){
            $.ajax({
                url: '<?= base_url('auth/changeemail') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                beforeSend:function(){
                    $('#html_append').html('');
                },
                success: function(res) {
                    if (res.csrf_hash) {
                        $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                    }
                    $('#html_append').html('<div class="alert alert-success">' + res.message + '</div>')
                },
                error:function(xhr){
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.csrf_hash) {
                            $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                        }
                        $('#html_append').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>')
                    }
                },
                complete: function(){
                    $('#email').attr('disabled', true);
                    $('#change_email_container').removeClass('changing');
                }
            })
        }

        $(document).on('click', '.ajax_delete_user', function(){
            const _this = $(this);
            const user_id = _this.data('id');
            $.ajax({
                url: '<?= base_url('auth/delete-user') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ user_id }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                beforeSend:function(){
                    $('#html_append').html('');
                },
                success: function(res) {
                    if (res.csrf_hash) {
                        $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                    }
                    setTimeout(()=> {location.reload();}, 1000);
                },
                error:function(xhr){
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.csrf_hash) {
                            $('meta[name="csrf-token"]').attr('content', xhr.responseJSON.csrf_hash);
                        }
                    }
                },
                complete: function(){
                    $('#email').attr('disabled', true);
                    $('#change_email_container').removeClass('changing');
                }
            })
        });
    })
</script>
