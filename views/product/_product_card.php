<?php
use yii\helpers\Html;
use yii\helpers\Url;


$stockClass = $model->stock > 10 ? 'stock-in' : ($model->stock > 0 ? 'stock-low' : 'stock-out');
$stockText = $model->stock > 10 ? 'В наличии' : ($model->stock > 0 ? 'Мало осталось' : 'Нет в наличии');
$stockIcon = $model->stock > 10 ? 'fa-check' : ($model->stock > 0 ? 'fa-exclamation-triangle' : 'fa-times');
?>

<div class="product-card card h-100 border">
    <div class="card-body d-flex flex-column">
        <!-- Категория -->
        <?php if ($model->category): ?>
            <div class="product-category mb-2 text-muted">
                <small><i class="fas fa-tag me-1"></i><?= Html::encode($model->category) ?></small>
            </div>
        <?php endif; ?>
        
        <!-- Название -->
        <h5 class="product-title card-title">
            <?= Html::encode($model->title) ?>
        </h5>
        
        <!-- Статус наличия -->
        <div class="product-stock <?= $stockClass ?> mb-2">
            <i class="fas <?= $stockIcon ?> me-1"></i>
            <small><?= $stockText ?></small>
            <?php if ($model->stock > 0): ?>
                <small class="text-muted ms-2">(<?= $model->stock ?> шт.)</small>
            <?php endif; ?>
        </div>
        
        <!-- Цена -->
        <div class="product-price h4 text-primary mb-3">
            <?= number_format($model->price, 0, '', ' ') ?> ₽
        </div>
        
        <!-- Кнопка добавления -->
        <div class="mt-auto">
            <?php if ($model->stock > 0 && !Yii::$app->user->isGuest): ?>
                <button class="btn btn-add-to-cart add-to-cart-btn w-100" 
                        data-id="<?= $model->product_id ?>"
                        data-name="<?= Html::encode($model->title) ?>"
                        data-product-id="<?= $model->product_id ?>"
                        data-product-name="<?= Html::encode($model->title) ?>">
                    <i class="fas fa-cart-plus me-2"></i>В корзину
                </button>
            <?php elseif ($model->stock > 0): ?>
                <a href="<?= Url::to(['/site/login', 'returnUrl' => Yii::$app->request->url]) ?>" 
                   class="btn btn-outline-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Войти для покупки
                </a>
            <?php else: ?>
                <button class="btn btn-secondary w-100" disabled>
                    <i class="fas fa-ban me-2"></i>Нет в наличии
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
