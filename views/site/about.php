<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'О нас';
?>

<div class="container py-5">
    <!-- Заголовок -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-4">
            <i class="fas fa-heartbeat text-primary me-2"></i>О нашей аптеке
        </h1>
        <p class="lead text-muted">Забота о нашем здоровье - миссия с 2005 года</p>
    </div>

    <!-- О компании -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="mb-4">Наша история</h2>
            <p class="mb-3">
                Начав с небольшой аптеки в центре города, мы выросли в сеть, 
                которой доверяют тысячи семей. Наш секрет прост: качественные 
                лекарства, профессиональные фармацевты и искренняя забота о каждом клиенте.
            </p>
            <p class="mb-3">
                Мы тщательно отбираем поставщиков, регулярно обновляем ассортимент 
                и следим за новинками фармацевтического рынка, чтобы предложить вам лучшее.
            </p>
            <div class="d-flex gap-3 mt-4">
                <div class="text-center">
                    <div class="h2 text-primary">±0</div>
                    <div class="text-muted">Лет на рынке</div>
                </div>
                <div class="text-center">
                    <div class="h2 text-success">Мало</div>
                    <div class="text-muted">Довольных клиентов</div>
                </div>
                <div class="text-center">
                    <div class="h2 text-warning">50</div>
                    <div class="text-muted">Товаров в каталоге</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="about-image text-center">
                <i class="fas fa-clinic-medical fa-10x text-primary opacity-25"></i>
            </div>
        </div>
    </div>

    <!-- Наши ценности -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-5">Наши принципы</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-award fa-3x text-warning mb-3"></i>
                    <h4 class="card-title">Качество</h4>
                    <p class="card-text">Все лекарства сертифицированы и хранятся в соответствии с нормами</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                    <h4 class="card-title">Доверие</h4>
                    <p class="card-text">Честность и прозрачность во взаимоотношениях с клиентами</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="fas fa-headset fa-3x text-info mb-3"></i>
                    <h4 class="card-title">Поддержка</h4>
                    <p class="card-text">Круглосуточные консультации и помощь в подборе лекарств</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Контакты -->
   <div class="card border-0 shadow-lg">
    <div class="card-body p-5">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4">
                    <i class="fas fa-handshake me-2"></i>Связь с нами
                </h2>
                <div class="row mb-4">
                    <div class="col-md-12">
                        
                        <p class="fs-5">
                            <i class="fas fa-location-dot text-primary me-2"></i>
                            Мурманск, ул. Спортивная, д. 13 (корпус А)
                        </p>
                        <p class="fs-5">
                            <i class="fas fa-location-dot text-primary me-2"></i>
                            Мурманск, пр. Ленина, д. 57
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="fs-5">
                            <a href="https://mauniver.ru" target="_blank" class="text-decoration-none fw-bold">
                                <i class="fas fa-graduation-cap me-2 text-warning"></i>MAUNIVER.RU
                            </a>
                            <br>
                            <small class="text-muted">Образовательные программы</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center mt-4 mt-lg-0">
                    <i class="fas fa-map-signs fa-4x text-muted mb-3"></i>
                    <p class="mb-1"><strong>Основные адреса:</strong></p>
                    <p class="small">ул. Спортивная, 13</p>
                    <p class="small">пр. Ленина, 57</p>
                    <p class="text-muted small mt-3">Работаем для вашего здоровья!</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Благодарность -->
    <div class="mt-5 pt-5 border-top">
        <div class="text-center">
            <div class="mb-3">
                <i class="fas fa-robot fa-3x text-secondary opacity-50"></i>
            </div>
            <h4 class="text-muted">Сделано не без помощи искусственного интеллекта</h4>
            <p class="text-muted">
                Этот проект был создан с использованием современных технологий и AI-ассистентов.<br>
                Но самое главное - с душой и заботой о вашем здоровье! 🤖❤️
            </p>
            <p class="text-muted small mt-3">
                P.S. Ни один робот не был обижен при создании этого сайта.<br>
                Все лекарства проверены людьми, а код написан с любовью.
            </p>
        </div>
    </div>

    <!-- Кнопка назад -->
    <div class="text-center mt-5">
        <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-arrow-left me-2"></i>На главную
        </a>
        <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-outline-primary btn-lg ms-3">
            <i class="fas fa-shopping-cart me-2"></i>В каталог
        </a>
    </div>
</div>

<style>
.about-image {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.card {
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
