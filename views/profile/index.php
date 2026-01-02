<?php
use yii\helpers\Html;

$this->title = 'Мой профиль';
?>

<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-user me-2"></i>Мой профиль
    </h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h4><?= Html::encode($profile->name) ?></h4>
                    <p class="text-muted"><?= Html::encode($profile->email) ?></p>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <h5><i class="fas fa-info-circle me-2"></i>Информация</h5>
                    <p><strong>ID:</strong> <?= $profile->profile_id ?></p>
                    <p><strong>Зарегистрирован:</strong> <?= date('d.m.Y', strtotime($profile->created_at)) ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-tachometer-alt me-2"></i>Быстрые действия</h5>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <a href="/index.php?r=profile/wallet" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-wallet fa-2x mb-2"></i><br>
                                Мой кошелек
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/index.php?r=profile/orders" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-shopping-bag fa-2x mb-2"></i><br>
                                Мои заказы
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/index.php?r=product/index" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                                В каталог
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/index.php?r=cart/index" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-cart-arrow-down fa-2x mb-2"></i><br>
                                В корзину
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

