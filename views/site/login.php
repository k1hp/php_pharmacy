<?php
use yii\helpers\Html;

$this->title = 'Вход в систему';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">
                    <i class="fas fa-sign-in-alt text-primary me-2"></i>
                    Вход в систему
                </h2>
                
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Пароль" required>
                        <label for="password">Пароль</label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Войти
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Нет аккаунта? 
                        <?= Html::a('Зарегистрироваться', ['/site/signup'], [
                            'class' => 'text-decoration-none'
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
