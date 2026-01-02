<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['cart/index']];
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS и JS для этой страницы
$this->registerCssFile('@web/css/orders.css');
$this->registerJsFile('@web/js/orders.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="order-create">
    <h1 class="mb-4">
        <i class="fas fa-shopping-bag text-primary me-2"></i>
        <?= Html::encode($this->title) ?>
    </h1>
    
    <div class="row">
        <!-- Левая колонка - Товары -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2 text-info"></i>
                        Состав заказа
                    </h5>
                </div>
                <div class="card-body">
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
                                    $cartItem = $item['cartItem'];
                                    $product = $item['product'];
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= Html::encode($product->title) ?></strong>
                                        <?php if ($product->category): ?>
                                            <div class="text-muted small"><?= Html::encode($product->category) ?></div>
                                        <?php endif; ?>
                                        <div class="small <?= $product->stock >= $cartItem->quantity ? 'text-success' : 'text-danger' ?>">
                                            <?php if ($product->stock >= $cartItem->quantity): ?>
                                                <i class="fas fa-check-circle me-1"></i>
                                                В наличии: <?= $product->stock ?> шт.
                                            <?php else: ?>
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Недостаточно: <?= $product->stock ?> шт.
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <?= number_format($product->price, 0, '', ' ') ?> ₽
                                    </td>
                                    <td class="text-center">
                                        <?= $cartItem->quantity ?> шт.
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        <?= number_format($item['itemTotal'], 0, '', ' ') ?> ₽
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Итого к оплате:</td>
                                    <td class="text-center fw-bold fs-5 text-success">
                                        <?= number_format($total, 0, '', ' ') ?> ₽
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Правая колонка - Форма оформления -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2 text-success"></i>
                        Оформление
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Информация о балансе -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-wallet fa-lg text-success me-3"></i>
                            <div>
                                <div class="small text-muted">Баланс кошелька</div>
                                <div class="h5 mb-0 fw-bold <?= $wallet->balance >= $total ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($wallet->balance, 0, '', ' ') ?> ₽
                                </div>
                            </div>
                        </div>
                        <?php if ($wallet->balance >= $total): ?>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-check-circle me-1"></i>
                                Достаточно средств для оплаты
                            </div>
                        <?php else: ?>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                Недостаточно средств
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Форма оформления -->
                    <?php if ($wallet->balance >= $total): ?>
                        <?php $form = ActiveForm::begin([
                            'id' => 'order-form',
                            'enableClientValidation' => true,
                        ]); ?>
                        
                        <?= $form->field($model, 'delivery_address')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Укажите адрес доставки или оставьте для самовывоза',
                            'class' => 'form-control'
                        ])->label('Адрес доставки') ?>
                        
                        <div class="form-text mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Оставьте "Самовывоз из аптеки" или укажите свой адрес
                        </div>
                        
                        <?= $form->field($model, 'agree_terms')->checkbox([
                            'label' => 'Я согласен с <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">условиями покупки</a>',
                            'template' => '<div class="form-check">{input} {label}</div>{error}'
                        ]) ?>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-lock me-2"></i>
                                Оплатить и оформить заказ
                            </button>
                            
                            <a href="<?= Url::to(['cart/index']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Вернуться в корзину
                            </a>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Для оформления заказа пополните кошелек
                        </div>
                        <div class="d-grid gap-2">
                            <a href="<?= Url::to(['profile/wallet']) ?>" class="btn btn-warning">
                                <i class="fas fa-wallet me-2"></i>
                                Пополнить кошелек
                            </a>
                            <a href="<?= Url::to(['cart/index']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Вернуться в корзину
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно с условиями -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Условия покупки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Общие положения</h6>
                <p>1. Все товары продаются в соответствии с законодательством РФ.</p>
                <p>2. Рецептурные лекарства отпускаются только по рецепту врача.</p>
                
                <h6 class="mt-3">Оплата</h6>
                <p>1. Оплата производится с внутреннего кошелька пользователя.</p>
                <p>2. При отмене заказа средства возвращаются на кошелек.</p>
                
                <h6 class="mt-3">Доставка</h6>
                <p>1. Самовывоз доступен в течение 24 часов после оформления заказа.</p>
                <p>2. Доставка осуществляется в течение 1-3 рабочих дней.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
