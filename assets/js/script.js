document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ypn-like-btn').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const postId = btn.dataset.id;
            const formData = new FormData();
            formData.append('action', 'ypn_like_project');
            formData.append('nonce', ypnData.nonce);
            formData.append('post_id', postId);

            try {
                const res = await fetch(ypnData.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                });
                const json = await res.json();
                if (json.success) {
                    btn.querySelector('.ypn-like-count').textContent = json.data.likes;
                }
            } catch (err) {
                console.error('AJAX error:', err);
            }
        });
    });
});