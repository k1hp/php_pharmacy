<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Order;
use app\models\OrderItem;
use app\models\Cart;
use app\models\CartItem;
use app\models\Product;
use app\models\Wallet;
use yii\db\Transaction;

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
     * Форма оформления заказа
     */
    public function actionCreate()
    {
        $profileId = Yii::$app->user->identity->profile_id;
        $cart = $this->getActiveCart($profileId);
        
        if (!$cart) {
            Yii::$app->session->setFlash('error', 'Корзина пуста.');
            return $this->redirect(['/cart/index']);
        }
        
        $cartItems = $cart->cartItems;
        if (empty($cartItems)) {
            Yii::$app->session->setFlash('error', 'Корзина пуста.');
            return $this->redirect(['/cart/index']);
        }
        
        // Считаем сумму и проверяем наличие
        $items = [];
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            
            if (!$product || $product->stock < $cartItem->quantity) {
                Yii::$app->session->setFlash('error', 'Некоторые товары недоступны.');
                return $this->redirect(['/cart/index']);
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
        $wallet = Wallet::findOne(Yii::$app->user->identity->wallet_id);
        if ($wallet->balance < $total) {
            Yii::$app->session->setFlash('error', 'Недостаточно средств.');
            return $this->redirect(['/cart/index']);
        }
        
        // Создаем заказ
        $order = new Order();
        $order->profile_id = $profileId;
        $order->cart_id = $cart->cart_id;
        $order->total = $total;
        $order->status = Order::STATUS_PENDING;
        
        if ($order->load(Yii::$app->request->post()) && $order->save()) {
            return $this->processOrder($order, $cart, $items, $wallet);
        }
        
        return $this->render('create', [
            'order' => $order,
            'items' => $items,
            'total' => $total,
            'wallet' => $wallet,
        ]);
    }

    /**
     * Обработка заказа
     */
    private function processOrder($order, $cart, $items, $wallet)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Создаем OrderItems
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->order_id;
                $orderItem->product_id = $item['product']->product_id;
                $orderItem->quantity = $item['cartItem']->quantity;
                if (!$orderItem->save()) {
                    throw new \Exception('Ошибка создания OrderItem');
                }
                
                // Обновляем остатки
                $item['product']->stock -= $item['cartItem']->quantity;
                if (!$item['product']->save()) {
                    throw new \Exception('Ошибка обновления остатков');
                }
            }
            
            // Списание средств
            $wallet->balance -= $order->total;
            if (!$wallet->save()) {
                throw new \Exception('Ошибка списания средств');
            }
            
            // Деактивируем корзину
            $cart->is_active = false;
            if (!$cart->save()) {
                throw new \Exception('Ошибка обновления корзины');
            }
            
            $transaction->commit();
            
            return $this->redirect(['success', 'id' => $order->order_id]);
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ошибка: ' . $e->getMessage());
            return $this->refresh();
        }
    }

    /**
     * Страница успеха
     */
    public function actionSuccess($id)
    {
        $order = Order::findOne($id);
        
        if (!$order || $order->profile_id != Yii::$app->user->identity->profile_id) {
            throw new \yii\web\NotFoundHttpException('Заказ не найден.');
        }
        
        return $this->render('success', ['order' => $order]);
    }

    /**
     * История заказов
     */
    public function actionHistory()
    {
        $orders = Order::find()
            ->where(['profile_id' => Yii::$app->user->identity->profile_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('history', ['orders' => $orders]);
    }

    /**
     * Просмотр заказа
     */
    public function actionView($id)
    {
        $order = Order::findOne($id);
        
        if (!$order || $order->profile_id != Yii::$app->user->identity->profile_id) {
            throw new \yii\web\NotFoundHttpException('Заказ не найден.');
        }
        
        return $this->render('view', ['order' => $order]);
    }

    /**
     * Вспомогательный метод
     */
    private function getActiveCart($profileId)
    {
        return Cart::find()
            ->where(['profile_id' => $profileId, 'is_active' => true])
            ->one();
    }
}
