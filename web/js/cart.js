// cart.js - only (/cart/index)
$(document).ready(function() {
    console.log('cart.js загружен - КОРЗИНА');
    
    // Инициализация обработчиков корзины
    initCartHandlers();
    
    // Обновляем счетчик при загрузке
    if (typeof updateCartCounter === 'function') {
        updateCartCounter();
    }
});

function initCartHandlers() {
    // Увеличение количества
    $(document).on('click', '.quantity-increase', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const itemId = $(this).data('item-id');
        console.log('Increase quantity:', itemId);
        
        if (itemId && !$(this).hasClass('processing')) {
            $(this).addClass('processing');
            updateCartItemQuantity(itemId, 'increase', $(this));
        }
    });
    
    // Уменьшение количества
    $(document).on('click', '.quantity-decrease', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const itemId = $(this).data('item-id');
        console.log('Decrease quantity:', itemId);
        
        if (itemId && !$(this).hasClass('processing')) {
            $(this).addClass('processing');
            updateCartItemQuantity(itemId, 'decrease', $(this));
        }
    });
    
    // Удаление товара
    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const itemId = $(this).data('item-id');
        const productName = $(this).data('product-name');
        console.log('Remove item:', itemId, productName);
        
        if (confirm('Удалить "' + productName + '" из корзины?')) {
            $(this).addClass('processing');
            removeCartItem(itemId, $(this));
        }
    });
    
    // Очистка всей корзины
    $('#clear-cart-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Вы действительно хотите очистить всю корзину?')) {
            $(this).addClass('processing');
            clearCart($(this));
        }
    });
}

// Обновление количества товара
function updateCartItemQuantity(itemId, action, button) {
    const $row = $('#cart-item-' + itemId);
    const originalHtml = button.html();
    
    button.html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '/index.php?r=cart/update-quantity',
        method: 'POST',
        data: {
            item_id: itemId,
            action: action
        },
        success: function(response) {
            console.log('Update response:', response);
            
            if (response.success) {
                if (response.action === 'remove') {
                    // Товар удален
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'Товар удален', 'success');
                    }
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        checkEmptyCart();
                    });
                } else {
                    // Количество обновлено
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'Количество обновлено', 'success');
                    }
                    // Обновляем данные в строке
                    if (response.quantity !== undefined) {
                        $row.find('.quantity-value').text(response.quantity);
                        // Пересчитываем сумму товара
                        const price = parseFloat($row.data('price') || 0);
                        const itemTotal = price * response.quantity;
                        $row.find('.item-total').text(itemTotal.toLocaleString('ru-RU') + ' ₽');
                        // Обновляем общую сумму
                        updateCartTotal();
                    }
                }
                // Обновляем счетчик в шапке
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter();
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification(response.message || 'Ошибка обновления', 'error');
                }
            }
            
            button.html(originalHtml).removeClass('processing');
            $row.find('.quantity-increase, .quantity-decrease').removeClass('processing');
        },
        error: function(xhr, status, error) {
            console.error('Update error:', error, xhr.responseText);
            if (typeof showNotification === 'function') {
                showNotification('Ошибка сервера', 'error');
            }
            button.html(originalHtml).removeClass('processing');
            $row.find('.quantity-increase, .quantity-decrease').removeClass('processing');
        }
    });
}

// Удаление товара из корзины
function removeCartItem(itemId, button) {
    const $row = $('#cart-item-' + itemId);
    const originalHtml = button.html();
    
    button.html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '/index.php?r=cart/remove',
        method: 'POST',
        data: {
            item_id: itemId
        },
        success: function(response) {
            console.log('Remove response:', response);
            
            if (response.success) {
                if (typeof showNotification === 'function') {
                    showNotification(response.message || 'Товар удален', 'success');
                }
                // Удаляем строку из таблицы
                $row.fadeOut(300, function() {
                    $(this).remove();
                    updateCartTotal();
                    checkEmptyCart();
                });
                // Обновляем счетчик в шапке
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter();
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification(response.message || 'Ошибка удаления', 'error');
                }
            }
            
            button.html(originalHtml).removeClass('processing');
        },
        error: function(xhr, status, error) {
            console.error('Remove error:', error, xhr.responseText);
            if (typeof showNotification === 'function') {
                showNotification('Ошибка сервера', 'error');
            }
            button.html(originalHtml).removeClass('processing');
        }
    });
}

// Очистка всей корзины
function clearCart(button) {
    const originalHtml = button.html();
    
    button.html('<i class="fas fa-spinner fa-spin me-2"></i>Очистка...');
    
    $.ajax({
        url: '/index.php?r=cart/clear',
        method: 'POST',
        data: {},
        success: function(response) {
            console.log('Clear response:', response);
            
            if (response.success) {
                if (typeof showNotification === 'function') {
                    showNotification(response.message || 'Корзина очищена', 'success');
                }
                // Очищаем таблицу
                $('.cart-item-row').fadeOut(300, function() {
                    $(this).remove();
                    showEmptyCartMessage();
                });
                // Обновляем счетчик
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter();
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification(response.message || 'Ошибка очистки', 'error');
                }
            }
            
            button.html(originalHtml).removeClass('processing');
        },
        error: function(xhr, status, error) {
            console.error('Clear error:', error, xhr.responseText);
            if (typeof showNotification === 'function') {
                showNotification('Ошибка сервера', 'error');
            }
            button.html(originalHtml).removeClass('processing');
        }
    });
}

// Обновление общей суммы
function updateCartTotal() {
    let total = 0;
    let itemCount = 0;
    
    $('.cart-item-row').each(function() {
        const price = parseFloat($(this).data('price') || 0);
        const quantity = parseInt($(this).find('.quantity-value').text() || 0);
        total += price * quantity;
        itemCount += quantity;
    });
    
    $('#cart-total').text(total.toLocaleString('ru-RU') + ' ₽');
    
    // Обновляем общую сумму в таблице если есть
    $('.cart-total-summary').each(function() {
        $(this).text(total.toLocaleString('ru-RU') + ' ₽');
    });
}

// Проверка пустой корзины
function checkEmptyCart() {
    if ($('.cart-item-row').length === 0) {
        showEmptyCartMessage();
    }
}

// Показать сообщение о пустой корзине
function showEmptyCartMessage() {
    const $container = $('.cart-table-container, .cart-table, .cart-index');
    if ($container.length) {
        $container.html(`
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>Корзина пуста</h4>
                <p class="text-muted">Добавьте товары из каталога</p>
                <a href="/index.php?r=product/index" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Вернуться в каталог
                </a>
            </div>
        `);
    }
}
