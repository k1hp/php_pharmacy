<?php
use yii\helpers\Html;

$this->title = 'Мой кошелек';
?>

<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-wallet me-2 text-success"></i>Мой кошелек
    </h1>
    
    <div class="card">
        <div class="card-body">
            <div class="text-center py-4">
                <i class="fas fa-wallet fa-5x text-success mb-3"></i>
                <h2 class="text-success"><?= number_format($wallet->balance, 0, '', ' ') ?> ₽</h2>
                <p class="text-muted">Текущий баланс</p>
            </div>
            
            <div class="mt-4">
                <h5><i class="fas fa-history me-2"></i>История операций</h5>
                <div class="text-center py-4">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <p class="text-muted">История операций пока пуста</p>
                    <p class="text-muted small">Здесь будут отображаться все пополнения и списания</p>
                </div>
            </div>
        </div>
    </div>
</div>
