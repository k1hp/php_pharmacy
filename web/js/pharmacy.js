// pharmacy.js - ТОЛЬКО для страницы КАТАЛОГА (/product/index)
$(document).ready(function() {
    console.log('pharmacy.js загружен - КАТАЛОГ');
    
    // Обработчик кнопок "В корзину" в каталоге
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        console.log('Клик по add-to-cart-btn в каталоге');
        
        const $btn = $(this);
        
        // Проверяем, не обрабатывается ли уже
        if ($btn.hasClass('processing')) {
            return;
        }
        
        const productId = $btn.data('id');
        const productName = $btn.data('name');
        
        console.log('Добавляем товар:', {id: productId, name: productName});
        
        if (!productId || $btn.prop('disabled')) {
            console.error('Нет ID или кнопка заблокирована');
            return;
        }
        
        // Блокируем кнопку
        $btn.addClass('processing').prop('disabled', true);
        const originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Добавление...');
        
        // AJAX запрос
        $.ajax({
            url: '/index.php?r=cart/add',
            method: 'POST',
            data: { id: productId },
            success: function(response) {
                console.log('Ответ от сервера:', response);
                
                if (response && response.success) {
                    // УСПЕХ
                    if (typeof showNotification === 'function') {
                        showNotification(response.message, 'success');
                    }
                    
                    // Меняем кнопку
                    $btn.html('<i class="fas fa-check me-2"></i>Добавлено')
                         .removeClass('btn-primary')
                         .addClass('btn-success');
                    
                    // Восстанавливаем через 2 секунды
                    setTimeout(function() {
                        $btn.html(originalHtml)
                            .removeClass('btn-success processing')
                            .addClass('btn-primary')
                            .prop('disabled', false);
                    }, 2000);
                    
                    // Обновляем счетчик
                    if (typeof updateCartCounter === 'function') {
                        updateCartCounter();
                    }
                    
                } else {
                    // ОШИБКА
                    if (typeof showNotification === 'function') {
                        showNotification(response?.message || 'Ошибка', 'error');
                    }
                    $btn.html(originalHtml)
                        .removeClass('processing')
                        .prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX ошибка:', error);
                if (typeof showNotification === 'function') {
                    showNotification('Ошибка сервера', 'error');
                }
                $btn.html(originalHtml)
                    .removeClass('processing')
                    .prop('disabled', false);
            }
        });
    });
    
    // Обновляем счетчик при загрузке каталога
    if (typeof updateCartCounter === 'function') {
        updateCartCounter();
    }
});
