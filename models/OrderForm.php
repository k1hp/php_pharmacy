<?php
// models/OrderForm.php

namespace app\models;

use Yii;
use yii\base\Model;

class OrderForm extends Model
{
    public $delivery_address;
    public $agree_terms = true;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_address'], 'string', 'max' => 500],
            [['delivery_address'], 'default', 'value' => 'Самовывоз из аптеки'],
            [['agree_terms'], 'required', 'requiredValue' => true, 'message' => 'Вы должны согласиться с условиями'],
            [['agree_terms'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delivery_address' => 'Адрес доставки',
            'agree_terms' => 'Я согласен с условиями покупки',
        ];
    }
    
    /**
     * Создаем заказ из корзины
     */
    public function createOrder($cart, $profile)
    {
        if (!$this->validate()) {
            return false;
        }
        
        // Проверяем наличие товаров
        $errors = [];
        $total = 0;
        
        foreach ($cart->cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product) {
                $errors[] = "Товар #{$cartItem->product_id} не найден";
                continue;
            }
            
            if ($product->stock < $cartItem->quantity) {
                $errors[] = "Недостаточно товара '{$product->title}'. В наличии: {$product->stock} шт.";
                continue;
            }
            
            $total += $cartItem->quantity * $product->price;
        }
        
        if (!empty($errors)) {
            $this->addError('delivery_address', implode('<br>', $errors));
            return false;
        }
        
        // Проверяем баланс кошелька
        $wallet = Wallet::findOne(['wallet_id' => $profile->wallet_id]);
        if ($wallet->balance < $total) {
            $this->addError('delivery_address', 
                "Недостаточно средств на кошельке. Ваш баланс: " . 
                number_format($wallet->balance, 0, '', ' ') . " ₽, " .
                "нужно: " . number_format($total, 0, '', ' ') . " ₽"
            );
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Создаем заказ
            $order = new Order();
            $order->profile_id = $profile->profile_id;
            $order->cart_id = $cart->cart_id;
            $order->total = $total;
            $order->status = Order::STATUS_PENDING;
            $order->delivery_address = $this->delivery_address ?: 'Самовывоз из аптеки';
            $order->created_at = date('Y-m-d H:i:s');
            
            if (!$order->save()) {
                throw new \Exception('Не удалось создать заказ: ' . print_r($order->errors, true));
            }
            
            // 2. Создаем товары заказа и уменьшаем остатки
            foreach ($cart->cartItems as $cartItem) {
                $product = $cartItem->product;
                
                // Создаем запись в order_items
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->order_id;
                $orderItem->product_id = $product->product_id;
                $orderItem->quantity = $cartItem->quantity;
                
                if (!$orderItem->save()) {
                    throw new \Exception('Не удалось сохранить товар заказа');
                }
                
                // Уменьшаем остатки товара
                $product->stock -= $cartItem->quantity;
                if (!$product->save()) {
                    throw new \Exception('Не удалось обновить остатки товара');
                }
            }
            
            // 3. Списываем деньги с кошелька
            $wallet->balance -= $total;
            if (!$wallet->save()) {
                throw new \Exception('Не удалось списать средства с кошелька');
            }
            
            // 4. Деактивируем корзину
            $cart->is_active = false;
            if (!$cart->save()) {
                throw new \Exception('Не удалось деактивировать корзину');
            }
            
            // 5. Создаем новую активную корзину для будущих покупок
            $newCart = new Cart();
            $newCart->profile_id = $profile->profile_id;
            $newCart->is_active = true;
            $newCart->created_at = date('Y-m-d H:i:s');
            
            if (!$newCart->save()) {
                throw new \Exception('Не удалось создать новую корзину');
            }
            
            $transaction->commit();
            
            // Обновляем счетчик корзины в сессии
            if (isset(Yii::$app->session)) {
                Yii::$app->session->set('cartCount', 0);
            }
            
            return $order;
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('delivery_address', 'Ошибка при оформлении заказа: ' . $e->getMessage());
            Yii::error($e->getMessage());
            return false;
        }
    }
}
