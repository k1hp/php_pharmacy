<?php
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;

// Получаем текущий параметр сортировки
$currentSort = Yii::$app->request->get('sort', '');

// Функция для создания ссылки с сохранением фильтров
function getSortUrl($sortValue) {
    $params = Yii::$app->request->get();
    $params['sort'] = $sortValue;
    unset($params['page']); // убираем номер страницы
    return Url::to(array_merge(['/product/index'], $params));
}
?>


<div class="catalog-header mb-4">
    <h1 class="catalog-title">Каталог товаров</h1>
    <p class="catalog-subtitle">Найдено товаров: <strong><?= $dataProvider->getTotalCount() ?></strong></p>
</div>

<div class="row">
    <!-- Фильтры -->
    <div class="col-lg-3 mb-4">
        <?= $this->render('_filters', [
            'searchModel' => $searchModel,
            'categories' => $categories,
            'priceRange' => $priceRange,
        ]) ?>
    </div>
    
    <!-- Каталог товаров -->
    <div class="col-lg-9">
        <!-- Блок сортировки и фильтров -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <span class="text-muted">
                            <i class="fas fa-list me-1"></i>
                            Найдено: <strong><?= $dataProvider->getTotalCount() ?></strong> товаров
                        </span>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="d-flex align-items-center justify-content-md-end">
                            <span class="text-muted me-2">
                                <i class="fas fa-sort me-1"></i>Сортировка:
                            </span>
                            
                            <div class="btn-group me-2" role="group">
                                <a href="<?= getSortUrl('price_asc') ?>" 
                                   class="btn btn-sm btn-outline-secondary <?= $currentSort == 'price_asc' ? 'active' : '' ?>">
                                    <i class="fas fa-sort-amount-up me-1"></i>Цена ↑
                                </a>
                                
                                <a href="<?= getSortUrl('price_desc') ?>" 
                                   class="btn btn-sm btn-outline-secondary <?= $currentSort == 'price_desc' ? 'active' : '' ?>">
                                    <i class="fas fa-sort-amount-down me-1"></i>Цена ↓
                                </a>
                                
                                <a href="<?= getSortUrl('name_asc') ?>" 
                                   class="btn btn-sm btn-outline-secondary <?= $currentSort == 'name_asc' ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-up me-1"></i>А-Я
                                </a>
                                
                                <a href="<?= getSortUrl('name_desc') ?>" 
                                   class="btn btn-sm btn-outline-secondary <?= $currentSort == 'name_desc' ? 'active' : '' ?>">
                                    <i class="fas fa-sort-alpha-down me-1"></i>Я-А
                                </a>
                            </div>
                            
                            <?php if ($currentSort): ?>
                                <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times me-1"></i>Сбросить
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Активные фильтры -->
                <?php if ($searchModel->search || $searchModel->category || $searchModel->min_price || $searchModel->max_price): ?>
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="text-muted me-2">
                            <i class="fas fa-filter me-1"></i>Активные фильтры:
                        </span>
                        
                        <?php if ($searchModel->search): ?>
                            <span class="badge bg-info">
                                Поиск: "<?= Html::encode($searchModel->search) ?>"
                                <a href="<?= Url::current(['search' => null]) ?>" class="btn-close btn-close-white ms-1" 
                                   aria-label="Удалить"></a>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($searchModel->category): ?>
                            <span class="badge bg-info">
                                Категория: <?= Html::encode($searchModel->category) ?>
                                <a href="<?= Url::current(['category' => null]) ?>" class="btn-close btn-close-white ms-1" 
                                   aria-label="Удалить"></a>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($searchModel->min_price): ?>
                            <span class="badge bg-info">
                                Цена от: <?= number_format($searchModel->min_price, 0, '', ' ') ?> ₽
                                <a href="<?= Url::current(['min_price' => null]) ?>" class="btn-close btn-close-white ms-1" 
                                   aria-label="Удалить"></a>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($searchModel->max_price): ?>
                            <span class="badge bg-info">
                                Цена до: <?= number_format($searchModel->max_price, 0, '', ' ') ?> ₽
                                <a href="<?= Url::current(['max_price' => null]) ?>" class="btn-close btn-close-white ms-1" 
                                   aria-label="Удалить"></a>
                            </span>
                        <?php endif; ?>
                        
                        <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-times me-1"></i>Очистить все
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Сетка товаров -->
       
        <?php if ($dataProvider->getTotalCount() > 0): ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'itemView' => '_product_card',
                'itemOptions' => ['class' => 'col-md-6 col-lg-4 mb-4'],
                'options' => ['class' => 'row'],
                'pager' => [
                    'options' => ['class' => 'pagination justify-content-center mt-4'],
                    'linkOptions' => ['class' => 'page-link'],
                    'activePageCssClass' => 'active',
                    'disabledPageCssClass' => 'disabled',
                ],
            ]); ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Товары не найдены</h4>
                <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
            </div>
        <?php endif; ?>
        
    </div>
</div>

