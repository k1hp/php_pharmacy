// web/js/orders.js

$(document).ready(function() {
    console.log('Orders JS loaded');
    
    // Инициализация тултипов Bootstrap
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Подтверждение отмены заказа в таблице
    $('.cancel-order-btn').on('click', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');
        const button = $(this);
        
        if (!confirm(`Вы уверены, что хотите отменить заказ #${orderNumber}? Средства будут возвращены на ваш кошелек.`)) {
            return;
        }
        
        cancelOrder(orderId, button);
    });
    
    // Валидация формы заказа
    $('#order-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Показываем индикатор загрузки
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Оформление...');
        
        return true;
    });
    
    // Функция отмены заказа
    function cancelOrder(orderId, button) {
        const originalHtml = button.html();
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/index.php?r=order/cancel',
            method: 'POST',
            data: {id: orderId},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Обновляем интерфейс через 1 секунду
                    setTimeout(function() {
                        if (typeof window.location !== 'undefined') {
                            location.reload();
                        }
                    }, 1000);
                } else {
                    showNotification(response.message, 'error');
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr, status, error) {
                showNotification('Ошибка сети. Попробуйте еще раз.', 'error');
                button.prop('disabled', false).html(originalHtml);
                console.error('Order cancellation error:', error);
            }
        });
    }
    
    // Автозаполнение адреса доставки
    $('#orderform-delivery_address').on('focus', function() {
        const currentVal = $(this).val().trim();
        if (currentVal === 'Самовывоз из аптеки') {
            $(this).select();
        }
    });
    
    // Переключение между самовывозом и доставкой
    $('#delivery-toggle').on('click', function(e) {
        e.preventDefault();
        const addressField = $('#orderform-delivery_address');
        const currentVal = addressField.val().trim();
        
        if (currentVal === 'Самовывоз из аптеки' || currentVal === '') {
            addressField.val('г. Москва, ул. Примерная, д. 1, кв. 1');
            $(this).html('<i class="fas fa-store me-1"></i> Самовывоз');
        } else {
            addressField.val('Самовывоз из аптеки');
            $(this).html('<i class="fas fa-truck me-1"></i> Доставка');
        }
    });
});
