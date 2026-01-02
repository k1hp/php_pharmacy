<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои заказы';
?>

<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-shopping-bag me-2"></i>Мои заказы
    </h1>
    
    <?php if ($orders): ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>№</th>
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
                                <td class="fw-bold"><?= number_format($order->total, 0, '', ' ') ?> ₽</td>
                                <td>
                                    <?php if ($order->status == 'pending'): ?>
                                        <span class="badge bg-warning">Ожидает оплаты</span>
                                    <?php elseif ($order->status == 'paid'): ?>
                                        <span class="badge bg-success">Оплачен</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= $order->status ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Заказов пока нет</h4>
                <p class="text-muted mb-4">Сделайте первый заказ в нашем каталоге</p>
                <a href="/index.php?r=product/index" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Перейти в каталог
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
