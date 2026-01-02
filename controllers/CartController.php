<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['POST'],
                    'get-count' => ['GET'],
                    'update-quantity' => ['POST'],
                    'remove' => ['POST'],
                    'clear' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Добавление товара в корзину
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Логирование для отладки
    Yii::info('CartController::actionAdd вызван. POST данные: ' . print_r(Yii::$app->request->post(), true));
    Yii::info('Пользователь: ' . (Yii::$app->user->isGuest ? 'гость' : Yii::$app->user->identity->profile_id));
        
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Для добавления в корзину необходимо авторизоваться',
            ];
        }
        
        $id = Yii::$app->request->post('id');
        
        if (!$id) {
            return [
                'success' => false,
                'message' => 'Не указан ID товара',
            ];
        }
        
        $product = Product::findOne($id);
        
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Товар не найден',
            ];
        }
        
        // Проверка наличия на складе
        if ($product->stock <= 0) {
            return [
                'success' => false,
                'message' => 'Товара нет в наличии',
            ];
        }
        
        $cart = $this->getCurrentCart();
        
        if (!$cart) {
            return [
                'success' => false,
                'message' => 'Ошибка работы с корзиной',
            ];
        }
        
        // Ищем уже существующий товар в корзине
        $cartItem = CartItem::find()
            ->where(['cart_id' => $cart->cart_id, 'product_id' => $product->product_id])
            ->one();
            
        if ($cartItem) {
            // Проверяем, не превышает ли новое количество доступный запас
            $newQuantity = $cartItem->quantity + 1;
            if ($newQuantity > $product->stock) {
                return [
                    'success' => false,
                    'message' => 'Недостаточно товара на складе. Доступно: ' . $product->stock . ' шт.',
                ];
            }
            
            $cartItem->quantity = $newQuantity;
        } else {
            // Создаем новый элемент корзины
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->cart_id;
            $cartItem->product_id = $product->product_id;
            $cartItem->quantity = 1;
        }
        
        if ($cartItem->save()) {
            $cartTotalItems = $this->getCartTotalItems($cart);
            $cartUniqueItems = $this->getCartUniqueItems($cart);
            
            return [
                'success' => true,
                'message' => 'Товар "' . $product->title . '" добавлен в корзину',
                'cartTotalItems' => $cartTotalItems,
                'cartUniqueItems' => $cartUniqueItems,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ошибка добавления товара',
                'errors' => $cartItem->errors,
            ];
        }
    }

    /**
     * Получение количества товаров в корзине
     */
    public function actionGetCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return [
                'count' => 0,
                'uniqueCount' => 0,
            ];
        }
        
        $cart = $this->getCurrentCart();
        
        if (!$cart) {
            return [
                'count' => 0,
                'uniqueCount' => 0,
            ];
        }
        
        $totalCount = CartItem::find()
            ->where(['cart_id' => $cart->cart_id])
            ->sum('quantity') ?: 0;
        
        $uniqueCount = CartItem::find()
            ->where(['cart_id' => $cart->cart_id])
            ->count();
        
        return [
            'count' => (int)$totalCount,
            'uniqueCount' => (int)$uniqueCount,
        ];
    }

    /**
     * Обновление количества товара в корзине
     */
    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $itemId = Yii::$app->request->post('item_id');
        $action = Yii::$app->request->post('action'); // 'increase' или 'decrease'
        
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Необходимо авторизоваться',
            ];
        }
        
        $cartItem = CartItem::findOne($itemId);
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Товар в корзине не найден',
            ];
        }
        
        // Проверяем, принадлежит ли корзина текущему пользователю
        $cart = $cartItem->cart;
        if (!$cart || $cart->profile_id != Yii::$app->user->identity->profile_id) {
            return [
                'success' => false,
                'message' => 'Нет доступа к этой корзине',
            ];
        }
        
        $product = $cartItem->product;
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Товар не найден',
            ];
        }
        
        if ($action === 'increase') {
            // Проверяем наличие на складе
            if ($cartItem->quantity + 1 > $product->stock) {
                return [
                    'success' => false,
                    'message' => 'Недостаточно товара на складе',
                ];
            }
            $cartItem->quantity += 1;
        } elseif ($action === 'decrease') {
            if ($cartItem->quantity <= 1) {
                // Если количество станет 0, удаляем товар
                if ($cartItem->delete()) {
                    return [
                        'success' => true,
                        'message' => 'Товар удален из корзины',
                        'action' => 'remove',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Ошибка удаления товара',
                    ];
                }
            }
            $cartItem->quantity -= 1;
        }
        
        if ($cartItem->save()) {
            return [
                'success' => true,
                'message' => 'Количество обновлено',
                'quantity' => $cartItem->quantity,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ошибка обновления количества',
                'errors' => $cartItem->errors,
            ];
        }
    }

    /**
     * Удаление товара из корзины
     */
    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $itemId = Yii::$app->request->post('item_id');
        
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Необходимо авторизоваться',
            ];
        }
        
        $cartItem = CartItem::findOne($itemId);
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Товар в корзине не найден',
            ];
        }
        
        // Проверяем, принадлежит ли корзина текущему пользователю
        $cart = $cartItem->cart;
        if (!$cart || $cart->profile_id != Yii::$app->user->identity->profile_id) {
            return [
                'success' => false,
                'message' => 'Нет доступа к этой корзине',
            ];
        }
        
        if ($cartItem->delete()) {
            return [
                'success' => true,
                'message' => 'Товар удален из корзины',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ошибка удаления товара',
            ];
        }
    }

    /**
     * Очистка всей корзины
     */
    public function actionClear()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Необходимо авторизоваться',
            ];
        }
        
        $userId = Yii::$app->user->identity->profile_id;
        
        $cart = Cart::find()
            ->where(['profile_id' => $userId, 'is_active' => true])
            ->one();
            
        if (!$cart) {
            return [
                'success' => false,
                'message' => 'Корзина не найдена',
            ];
        }
        
        // Удаляем все товары из корзины
        $deleted = CartItem::deleteAll(['cart_id' => $cart->cart_id]);
        
        return [
            'success' => true,
            'message' => 'Корзина очищена. Удалено товаров: ' . $deleted,
        ];
    }

    /**
     * Возвращает текущую активную корзину пользователя
     */
    private function getCurrentCart()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }
        
        $profileId = Yii::$app->user->identity->profile_id;
        
        // Ищем активную корзину
        $cart = Cart::find()
            ->where(['profile_id' => $profileId, 'is_active' => true])
            ->one();
            
        // Если нет активной корзины, создаем новую
        if (!$cart) {
            $cart = new Cart();
            $cart->profile_id = $profileId;
            $cart->is_active = true;
            $cart->created_at = date('Y-m-d H:i:s');
            
            if (!$cart->save()) {
                return null;
            }
        }
        
        return $cart;
    }

    /**
     * Получение общего количества товаров в корзине (сумма quantity)
     */
    private function getCartTotalItems($cart)
    {
        return CartItem::find()
            ->where(['cart_id' => $cart->cart_id])
            ->sum('quantity') ?: 0;
    }

    /**
     * Получение количества уникальных товаров в корзине
     */
    private function getCartUniqueItems($cart)
    {
        return CartItem::find()
            ->where(['cart_id' => $cart->cart_id])
            ->count();
    }
    
    /**
     * Просмотр корзины
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Для доступа к корзине необходимо авторизоваться');
            return $this->redirect(['site/login']);
        }
        
        $cart = $this->getCurrentCart();
        
        if (!$cart) {
            return $this->render('index', [
                'items' => [],
                'total' => 0,
                'cart' => null,
            ]);
        }
        
        $total = 0;
        $items = [];
        $uniqueItemsCount = 0;
        $totalItemsCount = 0;
        
        foreach ($cart->cartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product) {
                $itemTotal = $cartItem->quantity * $product->price;
                $total += $itemTotal;
                $totalItemsCount += $cartItem->quantity;
                $uniqueItemsCount++;
                
                $items[] = [
                    'cartItem' => $cartItem,
                    'product' => $product,
                    'itemTotal' => $itemTotal,
                ];
            }
        }
        
        return $this->render('index', [
            'items' => $items,
            'total' => $total,
            'uniqueItemsCount' => $uniqueItemsCount,
            'totalItemsCount' => $totalItemsCount,
            'cart' => $cart,
        ]);
    }
}
