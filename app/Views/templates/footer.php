    <?php if (session()->getFlashdata('success')): ?>
        <div class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-4 show" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <?= session('success') ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-4 show" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <?= session('error') ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('validate-error')): ?>
        <div class="toast align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-4 show" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <?php 
                        $errors = session('validate-error');
                        if (is_array($errors)) {
                            foreach ($errors as $error) {
                                echo '<div>' . esc($error) . '</div>';
                            }
                        } else {
                            echo esc($errors);
                        }
                    ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Hiển thị toast tự động
        document.querySelectorAll('.toast').forEach(function(toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        });
    </script>
    <script src="<?= base_url('public/assets/build/js/app.js') ?>"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                url: '/ajax-get-csrf',
                method: 'GET',
                success: function(data){
                    $('meta[name="csrf-token"]').attr('content', data.csrf_hash);
                    $('form').each(function() {
                        const self = $(this);
                        console.log(self);
                        let csrf_dom = self?.find('input[name="csrf_test_name"]');
                        if(csrf_dom.length == 0){
                            self.append(`<input type="hidden" name="csrf_test_name" value="${data.csrf_hash}">`);
                        }
                    })
                }
            });
        })
    </script>
</body>
</html>