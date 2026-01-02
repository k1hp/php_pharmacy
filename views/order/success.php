<?php

use yii\helpers\Html;

$this->title = 'Заказ оформлен';
?>

<div class="container mt-4">
    <div class="text-center">
        <div class="display-1 text-success mb-3">
            <i class="bi bi-check-circle"></i>
        </div>
        
        <h1 class="mb-3">Спасибо за заказ!</h1>
        
        <div class="card border-success mb-4">
            <div class="card-body">
                <h4 class="card-title">Заказ №<?= $order->order_id ?></h4>
                <p class="card-text">
                    <strong>Сумма:</strong> <?= number_format($order->total, 0, '', ' ') ?> ₽<br>
                    <strong>Статус:</strong> <span class="badge bg-warning"><?= $order->status ?></span><br>
                    <strong>Адрес:</strong> <?= nl2br(Html::encode($order->delivery_address)) ?>
                </p>
            </div>
        </div>
        
        <div class="d-grid gap-2 d-md-block">
            <?= Html::a('Продолжить покупки', ['/product/index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Мои заказы', ['history'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>
</div>
