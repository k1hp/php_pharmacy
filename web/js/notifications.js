// notifications.js - общие уведомления
function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const icon = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    }[type] || 'fa-info-circle';
    
    // Создать контейнер если нет
    if ($('#global-notifications').length === 0) {
        $('body').append('<div id="global-notifications" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 350px;"></div>');
    }
    
    const id = 'notif-' + Date.now();
    const html = `
        <div id="${id}" class="alert ${alertClass} alert-dismissible fade show shadow" style="margin-bottom: 10px;">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('#global-notifications').append(html);
    
    // Автоудаление
    setTimeout(() => {
        $('#' + id).fadeOut(300, function() { $(this).remove(); });
    }, 5000);
}

// Обновление счетчика корзины
function updateCartCounter() {
    $.ajax({
        url: '/index.php?r=cart/get-count',
        method: 'GET',
        success: function(response) {
            if (response && response.uniqueCount !== undefined) {
                $('.cart-badge').text(response.uniqueCount);
                $('.cart-badge').toggleClass('bg-danger', response.uniqueCount > 0);
            }
        }
    });
}
