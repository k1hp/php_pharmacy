<?php

use yii\helpers\Html;

$this->title = 'Заказ №' . $order->order_id;
?>

<div class="container mt-4">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Информация о заказе</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Номер заказа:</th>
                            <td>#<?= $order->order_id ?></td>
                        </tr>
                        <tr>
                            <th>Дата оформления:</th>
                            <td><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></td>
                        </tr>
                        <tr>
                            <th>Сумма:</th>
                            <td><strong><?= number_format($order->total, 0, '', ' ') ?> ₽</strong></td>
                        </tr>
                        <tr>
                            <th>Статус:</th>
                            <td>
                                <span class="badge 
                                    <?= $order->status == 'delivered' ? 'bg-success' : 
                                       ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= $order->status ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Адрес доставки:</th>
                            <td><?= nl2br(Html::encode($order->delivery_address)) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Состав заказа</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-end">Кол-во</th>
                                    <th class="text-end">Цена</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->orderItems as $item): ?>
                                    <tr>
                                        <td><?= Html::encode($item->product->title) ?></td>
                                        <td class="text-end"><?= $item->quantity ?> шт.</td>
                                        <td class="text-end"><?= number_format($item->product->price, 0, '', ' ') ?> ₽</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <?= Html::a('← Назад к заказам', ['history'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

