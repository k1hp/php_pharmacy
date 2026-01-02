<?php
use yii\helpers\Html;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    Регистрация
                </h2>
                
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Имя" required>
                        <label for="name">Имя</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Пароль" required minlength="6">
                        <label for="password">Пароль</label>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-gift me-2"></i>
                        После регистрации вам будет начислено <strong>10 000 ₽</strong> на баланс!
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Зарегистрироваться
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Уже есть аккаунт? 
                        <?= Html::a('Войти', ['/site/login'], [
                            'class' => 'text-decoration-none'
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
