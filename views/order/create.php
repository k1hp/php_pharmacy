<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оформление заказа';
?>

<div class="container mt-4">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <!-- Товары -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-center">Кол-во</th>
                                    <th class="text-end">Цена</th>
                                    <th class="text-end">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= Html::encode($item['product']->title) ?></td>
                                        <td class="text-center"><?= $item['cartItem']->quantity ?></td>
                                        <td class="text-end"><?= number_format($item['product']->price, 0, '', ' ') ?> ₽</td>
                                        <td class="text-end"><?= number_format($item['itemTotal'], 0, '', ' ') ?> ₽</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                    <td class="text-end"><strong><?= number_format($total, 0, '', ' ') ?> ₽</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Баланс -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Баланс кошелька:</h6>
                            <p class="h4 text-success mb-0"><?= number_format($wallet->balance, 0, '', ' ') ?> ₽</p>
                        </div>
                        <div class="col-md-6">
                            <h6>После оплаты:</h6>
                            <p class="h4 <?= ($wallet->balance - $total) >= 0 ? 'text-primary' : 'text-danger' ?> mb-0">
                                <?= number_format($wallet->balance - $total, 0, '', ' ') ?> ₽
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Форма -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Доставка</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['id' => 'order-form']); ?>
                    
                    <div class="mb-3">
                        <?= $form->field($order, 'delivery_address')->textarea([
                            'rows' => 5,
                            'class' => 'form-control',
                            'placeholder' => 'Укажите адрес доставки...'
                        ])->label('Адрес доставки') ?>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <?= Html::submitButton('Подтвердить заказ', [
                            'class' => 'btn btn-success btn-lg'
                        ]) ?>
                        
                        <?= Html::a('Назад в корзину', ['/cart/index'], [
                            'class' => 'btn btn-outline-secondary'
                        ]) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
