<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои заказы';
?>

<div class="container mt-4">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            <p class="mb-0">У вас еще нет заказов.</p>
            <?= Html::a('Перейти к покупкам →', ['/product/index'], ['class' => 'alert-link']) ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>№ заказа</th>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order->order_id ?></strong></td>
                                    <td><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></td>
                                    <td><?= number_format($order->total, 0, '', ' ') ?> ₽</td>
                                    <td>
                                        <span class="badge 
                                            <?= $order->status == 'delivered' ? 'bg-success' : 
                                               ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') ?>">
                                            <?= $order->status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= Html::a('Просмотр', ['view', 'id' => $order->order_id], [
                                            'class' => 'btn btn-sm btn-outline-primary'
                                        ]) ?>
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
