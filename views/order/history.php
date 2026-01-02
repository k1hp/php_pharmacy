<?php
// views/order/history.php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/orders.css');
$this->registerJsFile('@web/js/orders.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="order-history">
    <h1 class="mb-4">
        <i class="fas fa-history text-primary me-2"></i>
        <?= Html::encode($this->title) ?>
    </h1>
    
    <?php if (empty($orders)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-bag fa-5x text-muted opacity-25"></i>
                </div>
                <h3 class="text-muted mb-3">У вас еще нет заказов</h3>
                <p class="text-muted mb-4">Сделайте свой первый заказ в нашем магазине</p>
                <a href="<?= Url::to(['product/index']) ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Перейти к покупкам
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>№ Заказа</th>
                                <th>Дата</th>
                                <th class="text-center">Товаров</th>
                                <th class="text-center">Сумма</th>
                                <th class="text-center">Статус</th>
                                <th class="text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="fw-bold">#<?= $order->order_id ?></td>
                                <td>
                                    <div class="small text-muted">
                                        <?= Yii::$app->formatter->asDate($order->created_at) ?>
                                    </div>
                                    <div class="small">
                                        <?= Yii::$app->formatter->asTime($order->created_at) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill"><?= $order->getItemsCount() ?></span>
                                </td>
                                <td class="text-center fw-bold text-success">
                                    <?= number_format($order->total, 0, '', ' ') ?> ₽
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-<?= $order->getStatusClass() ?> rounded-pill">
                                        <?= $order->getStatusLabel() ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= Url::to(['order/view', 'id' => $order->order_id]) ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Просмотреть детали">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($order->canCancel()): ?>
                                        <button class="btn btn-sm btn-outline-danger cancel-order-btn"
                                                data-order-id="<?= $order->order_id ?>"
                                                data-order-number="<?= $order->order_id ?>"
                                                title="Отменить заказ">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
