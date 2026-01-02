
$(document).ready(function() {
    console.log('Orders JS loaded');
    
    // Вспомогательная функция для получения URL
    function getActionUrl(action, id = null) {
        // Проверяем, как работают URL в вашем проекте
        // Если используется index.php?r=controller/action
        let url = '/index.php?r=order/' + action;
        if (id) {
            url += '&id=' + id;
        }
        return url;
    }
    
    // Инициализация тултипов Bootstrap
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Подтверждение получения заказа
    $(document).on('click', '.complete-order-btn', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');
        const button = $(this);
        
        if (!confirm(`Вы получили заказ #${orderNumber}?`)) {
            return;
        }
        
        completeOrder(orderId, button);
    });
    
    // Подтверждение отмены заказа
    $(document).on('click', '.cancel-order-btn', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');
        const button = $(this);
        
        if (!confirm(`Отменить заказ #${orderNumber}? Средства вернутся на кошелек.`)) {
            return;
        }
        
        cancelOrder(orderId, button);
    });
    
    // Подтверждение удаления заказа
    $(document).on('click', '.delete-order-btn', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');
        const button = $(this);
        
        if (!confirm(`Удалить заказ #${orderNumber}? Это действие необратимо.`)) {
            return;
        }
        
        deleteOrder(orderId, button);
    });
    
    // Функция получения заказа
    function completeOrder(orderId, button) {
        const originalHtml = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: getActionUrl('complete'),
            method: 'POST',
            data: {id: orderId},
            dataType: 'json',
            success: function(response) {
                console.log('Complete response:', response);
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Обновляем страницу через 1 секунду
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(response.message, 'error');
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.log('Response text:', xhr.responseText);
                showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
                button.prop('disabled', false).html(originalHtml);
            }
        });
    }
    
    // Функция отмены заказа
    function cancelOrder(orderId, button) {
        const originalHtml = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: getActionUrl('cancel'),
            method: 'POST',
            data: {id: orderId},
            dataType: 'json',
            success: function(response) {
                console.log('Cancel response:', response);
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Обновляем страницу через 1 секунду
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(response.message, 'error');
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.log('Response text:', xhr.responseText);
                showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
                button.prop('disabled', false).html(originalHtml);
            }
        });
    }
    
    // Функция удаления заказа
    function deleteOrder(orderId, button) {
        const originalHtml = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: getActionUrl('delete'),
            method: 'POST',
            data: {id: orderId},
            dataType: 'json',
            success: function(response) {
                console.log('Delete response:', response);
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Удаляем строку из таблицы
                    const row = button.closest('tr');
                    row.fadeOut(300, function() {
                        row.remove();
                    });
                } else {
                    showNotification(response.message, 'error');
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.log('Response text:', xhr.responseText);
                showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
                button.prop('disabled', false).html(originalHtml);
            }
        });
    }
});
