<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Корзина покупок';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cart-index">
    <h1 class="mb-4">
        <i class="fas fa-shopping-cart text-primary me-2"></i>
        <?= Html::encode($this->title) ?>
    </h1>
    
    <?php if (empty($items)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-5x text-muted opacity-25"></i>
                </div>
                <h3 class="text-muted mb-3">Ваша корзина пуста</h3>
                <p class="text-muted mb-4">Добавьте товары из каталога, чтобы продолжить покупки</p>
                <a href="<?= Url::to(['product/index']) ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Перейти в каталог
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Статистика корзины -->
        <div class="card bg-light mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-box text-primary fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Товаров в корзине</h6>
                                <div class="h4 mb-0">
                                    <span class="badge bg-primary rounded-pill fs-6"><?= $uniqueItemsCount ?></span>
                                    <small class="text-muted ms-2">позиций</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-layer-group text-info fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Всего единиц</h6>
                                <div class="h4 mb-0">
                                    <span class="badge bg-info rounded-pill fs-6"><?= $totalItemsCount ?></span>
                                    <small class="text-muted ms-2">штук</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calculator text-success fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Общая сумма</h6>
                                <div class="h4 mb-0 text-success fw-bold">
                                    <?= number_format($total, 0, '', ' ') ?> ₽
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Таблица товаров -->
        <div class="card border-0 shadow-sm mb-4 cart-table-container">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 cart-table">
                        <thead class="table-light">
                            <tr>
                                <th>Товар</th>
                                <th class="text-center">Цена</th>
                                <th class="text-center">Количество</th>
                                <th class="text-center">Сумма</th>
                                <th class="text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): 
                                $cartItem = $item['cartItem'];
                                $product = $item['product'];
                                $itemTotal = $item['itemTotal'];
                            ?>
                            <tr id="cart-item-<?= $cartItem->item_id ?>" class="cart-item-row" data-price="<?= $product->price ?>">
                                <td>
                                    <strong><?= Html::encode($product->title) ?></strong>
                                    <?php if ($product->category): ?>
                                        <div class="text-muted small"><?= Html::encode($product->category) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center fw-bold">
                                    <?= number_format($product->price, 0, '', ' ') ?> ₽
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button class="btn btn-sm btn-outline-secondary quantity-decrease" 
                                                data-item-id="<?= $cartItem->item_id ?>"
                                                title="Уменьшить количество">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="mx-3 quantity-value"><?= $cartItem->quantity ?></span>
                                        <button class="btn btn-sm btn-outline-secondary quantity-increase" 
                                                data-item-id="<?= $cartItem->item_id ?>"
                                                title="Увеличить количество">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-success item-total">
                                    <?= number_format($itemTotal, 0, '', ' ') ?> ₽
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger remove-item" 
                                            data-item-id="<?= $cartItem->item_id ?>"
                                            data-product-name="<?= Html::encode($product->title) ?>"
                                            title="Удалить из корзины">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Итого к оплате:</td>
                                <td id="cart-total" colspan="2" class="text-center fw-bold fs-5 text-success">
                                    <?= number_format($total, 0, '', ' ') ?> ₽
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Кнопки действий -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
            <a href="<?= Url::to(['product/index']) ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Продолжить покупки
            </a>
            
            <div class="d-flex flex-column flex-md-row gap-2">
                <button id="clear-cart-btn" class="btn btn-outline-danger">
                    <i class="fas fa-trash-alt me-2"></i>Очистить корзину
                </button>
                
                <?php if ($total > 0): ?>
                <a href="<?= Url::to(['order/create']) ?>" class="btn btn-success btn-lg">
    <i class="fas fa-credit-card me-2"></i>Оформить заказ
		</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Блок для уведомлений будет создан JS -->
<?php
// В КОНЦЕ файла, после всего HTML
$this->registerJsFile('/js/cart.js', [
    'depends' => [\yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);
?>
