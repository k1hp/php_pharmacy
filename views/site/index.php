<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Аптека';
?>

<!-- Main секция -->
<section class="hero-section py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Ваше здоровье - 
                    <span class="text-primary">наш приоритет</span>
                </h1>
                <p class="lead mb-4">
                    Широкий выбор лекарств, медицинских товаров и средств ухода. 
                    Быстрая доставка и профессиональная консультация.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>В каталог
                    </a>
                    <a href="<?= Url::to(['/site/about']) ?>" class="btn btn-outline-primary btn-lg">
    <i class="fas fa-info-circle me-2"></i>О проекте
</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <i class="fas fa-heartbeat fa-10x text-primary opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Категории -->
<section class="categories-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Популярные категории</h2>
        
        <div class="row g-4">
            <div class="col-md-3">
                <a href="<?= Url::to(['/product/index', 'ProductSearch[category]' => 'Лекарства']) ?>" 
                   class="category-card card text-decoration-none text-dark h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-pills fa-3x text-primary mb-3"></i>
                        <h4 class="card-title">Лекарства</h4>
                        <p class="card-text text-muted">Широкий спектр лекарственных препаратов</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a href="<?= Url::to(['/product/index', 'ProductSearch[category]' => 'Витамины']) ?>" 
                   class="category-card card text-decoration-none text-dark h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-apple-alt fa-3x text-warning mb-3"></i>
                        <h4 class="card-title">Витамины</h4>
                        <p class="card-text text-muted">Витаминные комплексы и БАДы</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a href="<?= Url::to(['/product/index', 'ProductSearch[category]' => 'Медтехника']) ?>" 
                   class="category-card card text-decoration-none text-dark h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-thermometer fa-3x text-danger mb-3"></i>
                        <h4 class="card-title">Медтехника</h4>
                        <p class="card-text text-muted">Тонометры, глюкометры, ингаляторы</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a href="<?= Url::to(['/product/index', 'ProductSearch[category]' => 'Гигиена']) ?>" 
                   class="category-card card text-decoration-none text-dark h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-hand-sparkles fa-3x text-success mb-3"></i>
                        <h4 class="card-title">Гигиена</h4>
                        <p class="card-text text-muted">Средства гигиены и защиты</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Преимущества -->
<section class="benefits-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Почему выбирают нас</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h4>Гарантия качества</h4>
                    <p class="text-muted">Все товары сертифицированы и соответствуют стандартам</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center p-4">
                    <i class="fas fa-truck fa-3x text-success mb-3"></i>
                    <h4>Быстрая доставка</h4>
                    <p class="text-muted">Доставка в день заказа по городу и всей стране</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center p-4">
                    <i class="fas fa-headset fa-3x text-info mb-3"></i>
                    <h4>Поддержка 24/7</h4>
                    <p class="text-muted">Круглосуточная консультация фармацевтов</p>
                </div>
            </div>
        </div>
    </div>
</section>
