<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\Cart;
use app\models\Order;
use app\models\OrderItem;
use app\models\Product;
use app\models\Wallet;
use app\models\OrderForm;
use yii\web\Response;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Создание заказа из корзины
     */
    public function actionCreate()
    {
        $profile = Yii::$app->user->identity;
        $cart = Cart::find()
            ->where(['profile_id' => $profile->profile_id, 'is_active' => true])
            ->one();
        
        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Ваша корзина пуста. Добавьте товары для оформления заказа.');
            return $this->redirect(['cart/index']);
        }
        
        $model = new OrderForm();
        $wallet = Wallet::findOne(['wallet_id' => $profile->wallet_id]);
        
        // Подготавливаем данные для отображения
        $items = [];
        $total = 0;
        $hasErrors = false;
        
        foreach ($cart->cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product) {
                Yii::$app->session->addFlash('error', "Товар #{$cartItem->product_id} не найден");
                $hasErrors = true;
                continue;
            }
            
            if ($product->stock < $cartItem->quantity) {
                Yii::$app->session->addFlash('error', 
                    "Недостаточно товара '{$product->title}'. В наличии: {$product->stock} шт., в корзине: {$cartItem->quantity} шт."
                );
                $hasErrors = true;
                continue;
            }
            
            $itemTotal = $cartItem->quantity * $product->price;
            $total += $itemTotal;
            
            $items[] = [
                'cartItem' => $cartItem,
                'product' => $product,
                'itemTotal' => $itemTotal,
            ];
        }
        
        // Проверяем баланс
        if ($wallet->balance < $total) {
            Yii::$app->session->setFlash('error', 
                "Недостаточно средств на кошельке. Ваш баланс: " . 
                number_format($wallet->balance, 0, '', ' ') . " ₽, " .
                "нужно: " . number_format($total, 0, '', ' ') . " ₽"
            );
            $hasErrors = true;
        }
        
        if ($hasErrors) {
            return $this->redirect(['cart/index']);
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $order = $model->createOrder($cart, $profile);
            
            if ($order) {
                Yii::$app->session->setFlash('success', 
                    "Заказ #{$order->order_id} успешно оформлен и оплачен! " .
                    "Списано: " . number_format($order->total, 0, '', ' ') . " ₽"
                );
                return $this->redirect(['order/view', 'id' => $order->order_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при оформлении заказа. Проверьте данные.');
            }
        } else {
            // Предзаполняем адрес доставки
            $model->delivery_address = 'Самовывоз из аптеки';
        }
        
        return $this->render('create', [
            'model' => $model,
            'cart' => $cart,
            'items' => $items,
            'total' => $total,
            'wallet' => $wallet,
            'profile' => $profile,
        ]);
    }
    /**
     * Просмотр деталей заказа
     */
    public function actionView($id)
    {
        $order = Order::findOne($id);
        
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }
        
        if ($order->profile_id != Yii::$app->user->identity->profile_id) {
            throw new NotFoundHttpException('Доступ запрещен');
        }
        
        $orderItems = OrderItem::find()
            ->where(['order_id' => $order->order_id])
            ->all();
        
        $items = [];
        foreach ($orderItems as $orderItem) {
            $product = Product::findOne($orderItem->product_id);
            if ($product) {
                $items[] = [
                    'orderItem' => $orderItem,
                    'product' => $product,
                    'itemTotal' => $orderItem->quantity * $product->price,
                ];
            }
        }
        
        return $this->render('view', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * История заказов пользователя
     */
    public function actionHistory()
    {
        $profile = Yii::$app->user->identity;
        $orders = Order::find()
            ->where(['profile_id' => $profile->profile_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('history', [
            'orders' => $orders,
        ]);
    }

    /**
     * AJAX: Отметить заказ как полученный
     */
    public function actionComplete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Требуется авторизация'];
        }
        
        $orderId = Yii::$app->request->post('id');
        $order = Order::findOne($orderId);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Заказ не найден'];
        }
        
        if ($order->profile_id != Yii::$app->user->identity->profile_id) {
            return ['success' => false, 'message' => 'Доступ запрещен'];
        }
        
        if (!$order->canComplete()) {
            return ['success' => false, 'message' => 'Заказ нельзя отметить как полученный'];
        }
        
        if ($order->markAsCompleted()) {
            return [
                'success' => true,
                'message' => 'Заказ отмечен как полученный',
                'newStatus' => $order->status,
                'newStatusLabel' => $order->getStatusLabel(),
            ];
        } else {
            return ['success' => false, 'message' => 'Ошибка при обновлении статуса'];
        }
    }

    /**
     * AJAX: Отмена заказа (только для оплаченных заказов)
     */
    public function actionCancel()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Требуется авторизация'];
        }
        
        $orderId = Yii::$app->request->post('id');
        $order = Order::findOne($orderId);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Заказ не найден'];
        }
        
        if ($order->profile_id != Yii::$app->user->identity->profile_id) {
            return ['success' => false, 'message' => 'Доступ запрещен'];
        }
        
        if (!$order->canCancel()) {
            return ['success' => false, 'message' => 'Заказ уже получен или отменен'];
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Возвращаем товары на склад
            foreach ($order->orderItems as $orderItem) {
                $product = Product::findOne($orderItem->product_id);
                if ($product) {
                    $product->stock += $orderItem->quantity;
                    if (!$product->save()) {
                        throw new \Exception('Не удалось вернуть товар на склад');
                    }
                }
            }
            
            // 2. Возвращаем деньги на кошелек
            $wallet = Wallet::findOne(['wallet_id' => Yii::$app->user->identity->wallet_id]);
            if ($wallet) {
                $wallet->balance += $order->total;
                if (!$wallet->save()) {
                    throw new \Exception('Не удалось вернуть средства');
                }
            }
            
            // 3. Меняем статус заказа на отменен
            $order->status = Order::STATUS_CANCELLED;
            if (!$order->save()) {
                throw new \Exception('Не удалось обновить статус заказа');
            }
            
            $transaction->commit();
            
            return [
                'success' => true,
                'message' => 'Заказ успешно отменен. Средства возвращены на кошелек.',
                'newStatus' => $order->status,
                'newBalance' => $wallet ? $wallet->balance : null,
            ];
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            return ['success' => false, 'message' => 'Ошибка при отмене заказа'];
        }
    }
    
    /**
     * AJAX: Удаление отмененного заказа
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Требуется авторизация'];
        }
        
        $orderId = Yii::$app->request->post('id');
        $order = Order::findOne($orderId);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Заказ не найден'];
        }
        
        if ($order->profile_id != Yii::$app->user->identity->profile_id) {
            return ['success' => false, 'message' => 'Доступ запрещен'];
        }
        
        if (!$order->canDelete()) {
            return ['success' => false, 'message' => 'Можно удалить только отмененные заказы'];
        }
        
        // Удаляем сначала товары заказа
        OrderItem::deleteAll(['order_id' => $order->order_id]);
        
        // Затем удаляем сам заказ
        if ($order->delete()) {
            return [
                'success' => true,
                'message' => 'Заказ успешно удален',
            ];
        } else {
            return ['success' => false, 'message' => 'Ошибка при удалении заказа'];
        }
    }
}
