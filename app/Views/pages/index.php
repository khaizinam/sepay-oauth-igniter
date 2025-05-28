<div class="container py-4">
    <button id="connectBtn" type="button" class="btn btn-primary">Connect</button>
</div>

<script>
    $(document).ready(function() {
        $('#connectBtn').on('click', function(e) {
            e.preventDefault();

            let url = 'https://my.sepay.vn/oauth/authorize?response_type=code&client_id=test-9YbmyEca&redirect_uri=https://oauth.khaizinam.site/oauth/callback&state=RANDOM_STATE_VALUE';
            const popupWidth = 600;
            const popupHeight = 700;
            const left = (screen.width / 2) - (popupWidth / 2);
            const top = (screen.height / 2) - (popupHeight / 2);

            window.open(
                url,
                'OAuthLogin',
                `width=${popupWidth},height=${popupHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
            );
        });
    });
</script>
