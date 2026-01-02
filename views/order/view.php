<?php
// views/order/view.php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Заказ #{$order->order_id}";
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['order/history']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/orders.css');
?>

<div class="order-view">
    <!-- Шапка заказа -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="fas fa-receipt text-primary me-2"></i>
                        Заказ #<?= $order->order_id ?>
                    </h1>
                    <div class="text-muted">
                        <i class="far fa-calendar me-1"></i>
                        <?= Yii::$app->formatter->asDatetime($order->created_at, 'php:j M, Y H:i:s') ?>
                    </div>
                </div>
                
                <div class="text-end">
                    <div class="h4 mb-2 text-success fw-bold">
                        <?= number_format($order->total, 0, '', ' ') ?> ₽
                    </div>
                    <span class="badge bg-<?= $order->getStatusClass() ?> rounded-pill fs-6">
                        <?= $order->getStatusLabel() ?>
                    </span>
                </div>
            </div>
            
            <!-- Информация о заказе -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-muted mb-3">
                                <i class="fas fa-truck me-2"></i>Доставка
                            </h6>
                            <p class="mb-0">
                                <?= Html::encode($order->delivery_address) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-muted mb-3">
                                <i class="fas fa-info-circle me-2"></i>Информация
                            </h6>
                            <div class="small">
                                <div class="mb-1">
                                    <i class="far fa-file me-2"></i>
                                    Номер заказа: #<?= $order->order_id ?>
                                </div>
                                <div class="mb-1">
                                    <i class="far fa-calendar me-2"></i>
                                    Дата: <?= Yii::$app->formatter->asDate($order->created_at) ?>
                                </div>
                                <div class="mb-0">
                                    <i class="far fa-clock me-2"></i>
                                    Время: <?= Yii::$app->formatter->asTime($order->created_at) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Действия -->
            <div class="d-flex gap-2 mt-3">
                <?php if ($order->canComplete()): ?>
                    <button class="btn btn-success" id="complete-order-btn" data-order-id="<?= $order->order_id ?>">
                        <i class="fas fa-check-circle me-1"></i> Получить заказ
                    </button>
                <?php endif; ?>
                
                <?php if ($order->canCancel()): ?>
                    <button class="btn btn-outline-danger" id="cancel-order-btn" data-order-id="<?= $order->order_id ?>">
                        <i class="fas fa-times me-1"></i> Отменить заказ
                    </button>
                <?php endif; ?>
                
                <?php if ($order->canDelete()): ?>
                    <button class="btn btn-danger" id="delete-order-btn" data-order-id="<?= $order->order_id ?>">
                        <i class="fas fa-trash me-1"></i> Удалить заказ
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Товары в заказе -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-boxes me-2 text-info"></i>
                Состав заказа
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Товар</th>
                            <th class="text-center">Цена</th>
                            <th class="text-center">Количество</th>
                            <th class="text-center">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): 
                            $product = $item['product'];
                        ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= Html::encode($product->title) ?></strong>
                                    <?php if ($product->category): ?>
                                        <div class="text-muted small"><?= Html::encode($product->category) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center fw-bold align-middle">
                                <?= number_format($product->price, 0, '', ' ') ?> ₽
                            </td>
                            <td class="text-center align-middle">
                                <?= $item['orderItem']->quantity ?> шт.
                            </td>
                            <td class="text-center fw-bold text-success align-middle">
                                <?= number_format($item['itemTotal'], 0, '', ' ') ?> ₽
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Итого:</td>
                            <td class="text-center fw-bold fs-5 text-success">
                                <?= number_format($order->total, 0, '', ' ') ?> ₽
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Кнопки действий -->
    <div class="mt-4 d-flex justify-content-between">
        <a href="<?= Url::to(['order/history']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>К списку заказов
        </a>
        
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Распечатать
        </button>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Обработка получения заказа
$('#complete-order-btn').on('click', function() {
    if (!confirm('Вы уверены, что получили заказ? После этого статус изменится на "Получен".')) {
        return;
    }
    
    const orderId = $(this).data('order-id');
    const btn = $(this);
    
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Обработка...');
    
    $.ajax({
        url: '/index.php?r=order/complete',
        method: 'POST',
        data: {id: orderId},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showNotification(response.message, 'error');
                btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Получить заказ');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
            btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Получить заказ');
        }
    });
});

// Обработка отмены заказа
$('#cancel-order-btn').on('click', function() {
    if (!confirm('Вы уверены, что хотите отменить заказ? Средства будут возвращены на кошелек.')) {
        return;
    }
    
    const orderId = $(this).data('order-id');
    const btn = $(this);
    
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Отмена...');
    
    $.ajax({
        url: '/index.php?r=order/cancel',
        method: 'POST',
        data: {id: orderId},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showNotification(response.message, 'error');
                btn.prop('disabled', false).html('<i class="fas fa-times me-1"></i> Отменить заказ');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
            btn.prop('disabled', false).html('<i class="fas fa-times me-1"></i> Отменить заказ');
        }
    });
});

// Обработка удаления заказа
$('#delete-order-btn').on('click', function() {
    if (!confirm('Вы уверены, что хотите удалить заказ? Это действие необратимо.')) {
        return;
    }
    
    const orderId = $(this).data('order-id');
    const btn = $(this);
    
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Удаление...');
    
    $.ajax({
        url: '/index.php?r=order/delete',
        method: 'POST',
        data: {id: orderId},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                setTimeout(function() {
                    window.location.href = '/index.php?r=order/history';
                }, 1000);
            } else {
                showNotification(response.message, 'error');
                btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Удалить заказ');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showNotification('Ошибка сети. Проверьте консоль для деталей.', 'error');
            btn.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Удалить заказ');
        }
    });
});
JS
);
?>
