<?php
use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Cart;
use app\models\CartItem;

AppAsset::register($this);

$this->registerCssFile('@web/css/pharmacy.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Уведомления -->
<div id="notifications" class="notification"></div>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg navbar-pharmacy">
    <div class="container">
        <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
            <i class="fas fa-pills me-2"></i>Аптека
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="fas fa-bars text-white"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <?php
            $menuItems = [
                ['label' => 'Главная', 'url' => ['/site/index'], 'active' => $this->context->route == 'site/index'],
                ['label' => 'Каталог', 'url' => ['/product/index'], 'active' => $this->context->route == 'product/index'],
            ];
            
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
                $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
            } else {
                // Счетчик корзины - количество уникальных товаров
                $cartCount = 0;
                if ($cart = Cart::find()
                    ->where(['profile_id' => Yii::$app->user->identity->profile_id, 'is_active' => true])
                    ->one()) {
                    $cartCount = CartItem::find()
                        ->where(['cart_id' => $cart->cart_id])
                        ->count();
                }
                
                // Счетчик заказов
                $orderCount = \app\models\Order::find()
                    ->where(['profile_id' => Yii::$app->user->identity->profile_id])
                    ->count();
                
                $menuItems[] = [
                    'label' => 'Корзина <span class="cart-badge badge rounded-pill bg-danger">' . $cartCount . '</span>',
                    'url' => ['/cart/index'],
                    'encode' => false,
                    'active' => $this->context->route == 'cart/index'
                ];
                
                $menuItems[] = '<li class="nav-item dropdown">'
                    . Html::a(
                        '<i class="fas fa-user me-1"></i>' . Html::encode(Yii::$app->user->identity->name),
                        '#',
                        [
                            'class' => 'nav-link dropdown-toggle',
                            'data-bs-toggle' => 'dropdown',
                            'role' => 'button',
                            'aria-expanded' => 'false'
                        ]
                    )
                    . '<ul class="dropdown-menu dropdown-menu-end">'
                    . '<li class="dropdown-header text-muted">'
                    . '<i class="fas fa-user-circle me-2"></i>' . Html::encode(Yii::$app->user->identity->name)
                    . '</li>'
                    . '<li><hr class="dropdown-divider"></li>'
                    // Профиль
                    . '<li>' . Html::a(
                        '<i class="fas fa-user me-2"></i>Мой профиль', 
                        ['/profile'], 
                        ['class' => 'dropdown-item']
                    ) . '</li>'
                    // Кошелек
                    . '<li>' . Html::a(
                        '<i class="fas fa-wallet me-2 text-success"></i>Мой кошелек', 
                        ['/profile/wallet'], 
                        ['class' => 'dropdown-item']
                    ) . '</li>'
                    // Заказы со счетчиком
                    . '<li>' . Html::a(
    '<span class="d-flex justify-content-between align-items-center w-100">'
    . '<span><i class="fas fa-shopping-bag me-2"></i>Мои заказы</span>'
    . ($orderCount > 0 ? '<span class="badge bg-primary ms-2">' . $orderCount . '</span>' : '')
    . '</span>', 
    ['/profile/orders'], 
    ['class' => 'dropdown-item', 'encode' => false]
) . '</li>'
                    . '<li><hr class="dropdown-divider"></li>'
                    // Выход
                    . '<li>'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        '<i class="fas fa-sign-out-alt me-2"></i>Выход',
                        ['class' => 'dropdown-item border-0 bg-transparent']
                    )
                    . Html::endForm()
                    . '</li>'
                    . '</ul>'
                    . '</li>';
            }
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => $menuItems,
            ]);
            ?>
        </div>
    </div>
</nav>

<!-- Контент -->
<main class="py-4">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php
// Bootstrap 5 JS, потом jQuery (jQuery подключается через AppAsset)
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [
    'depends' => [yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);

$this->registerJsFile('@web/js/notifications.js', [
    'depends' => [yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);
$this->registerJsFile('@web/js/pharmacy.js', [
    'depends' => [yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);

// Временный CSS для отладки dropdown
$this->registerCss("
    /* Если Bootstrap не работает, этот CSS включит dropdown при наведении */
    @media (min-width: 992px) {
        .navbar-nav .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
    }
    
    /* Стили для кнопки выхода */
    .dropdown-item.border-0.bg-transparent {
        width: 100%;
        text-align: left;
        border: none !important;
        background: transparent !important;
    }
    .dropdown-item.border-0.bg-transparent:hover {
        background-color: #f8f9fa !important;
    }
");
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
