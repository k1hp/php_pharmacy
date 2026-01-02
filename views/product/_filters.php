<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="filter-card">
    <h5 class="filter-title mb-3"><i class="fas fa-filter me-2"></i>Фильтры</h5>
    
    <?php $form = ActiveForm::begin([
        'id' => 'filter-form',
        'method' => 'get',
        'action' => ['index'],
        'options' => ['data-pjax' => 1],
    ]); ?>
    
    <!-- Поиск -->
    <div class="mb-3">
        <label class="form-label fw-bold">Поиск</label>
        <?= Html::input('text', 'ProductSearch[search]', $searchModel->search, [
            'class' => 'form-control',
            'placeholder' => 'Название товара'
        ]) ?>
    </div>
    
    <!-- Категории - выпадающий список -->
    <div class="mb-3">
        <label class="form-label fw-bold">Категория</label>
        <select class="form-select" name="ProductSearch[category]">
            <option value="">Все категории</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= Html::encode($category) ?>" 
                    <?= $searchModel->category == $category ? 'selected' : '' ?>>
                    <?= Html::encode($category) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Цена - поля ввода -->
    <div class="mb-4">
        <label class="form-label fw-bold">Цена, ₽</label>
        <div class="row g-2">
            <div class="col">
                <?= Html::input('number', 'ProductSearch[min_price]', $searchModel->min_price, [
                    'class' => 'form-control',
                    'placeholder' => 'От',
                    'min' => 0
                ]) ?>
            </div>
            <div class="col">
                <?= Html::input('number', 'ProductSearch[max_price]', $searchModel->max_price, [
                    'class' => 'form-control',
                    'placeholder' => 'До',
                    'min' => 0
                ]) ?>
            </div>
        </div>
    </div>
    
    <!-- Кнопки -->
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search me-2"></i>Применить
        </button>
        <a href="<?= Url::to(['index']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-times me-2"></i>Сбросить
        </a>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
