<?php
// models/Order.php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $order_id
 * @property int $profile_id
 * @property int $cart_id
 * @property int $total
 * @property string $status
 * @property string|null $delivery_address
 * @property string $created_at
 *
 * @property Cart $cart
 * @property Profile $profile
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_PAID = 'paid';           // Оплачен
    const STATUS_COMPLETED = 'completed'; // Получен
    const STATUS_CANCELLED = 'cancelled'; // Отменен

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'cart_id', 'total'], 'required'],
            [['profile_id', 'cart_id', 'total'], 'integer'],
            [['total'], 'integer', 'min' => 0],
            [['status'], 'string'],
            [['delivery_address'], 'string'],
            [['created_at'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_PAID],
            [['status'], 'in', 'range' => [
                self::STATUS_PAID, 
                self::STATUS_COMPLETED, 
                self::STATUS_CANCELLED
            ]],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_id' => 'cart_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID заказа',
            'profile_id' => 'ID профиля',
            'cart_id' => 'ID корзины',
            'total' => 'Общая сумма',
            'status' => 'Статус',
            'delivery_address' => 'Адрес доставки',
            'created_at' => 'Дата создания',
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['profile_id' => 'profile_id']);
    }

    public function getCart()
    {
        return $this->hasOne(Cart::class, ['cart_id' => 'cart_id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'order_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['product_id' => 'product_id'])
            ->via('orderItems');
    }
    
    /**
     * Получаем человекочитаемый статус заказа
     */
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PAID => 'Оплачен',
            self::STATUS_COMPLETED => 'Получен',
            self::STATUS_CANCELLED => 'Отменен',
        ];
        
        return $labels[$this->status] ?? $this->status;
    }
    
    /**
     * Получаем CSS класс для статуса
     */
    public function getStatusClass()
    {
        $classes = [
            self::STATUS_PAID => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ];
        
        return $classes[$this->status] ?? 'secondary';
    }
    
    /**
     * Проверяем, можно ли отметить как полученный
     */
    public function canComplete()
    {
        return $this->status === self::STATUS_PAID;
    }
    
    /**
     * Проверяем, можно ли отменить заказ
     */
    public function canCancel()
    {
        return $this->status === self::STATUS_PAID;
    }
    
    /**
     * Проверяем, можно ли удалить заказ
     */
    public function canDelete()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    
    /**
     * Получаем количество товаров в заказе
     */
    public function getItemsCount()
    {
        return OrderItem::find()
            ->where(['order_id' => $this->order_id])
            ->sum('quantity') ?: 0;
    }
    
    /**
     * Получаем все статусы
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PAID => 'Оплачен',
            self::STATUS_COMPLETED => 'Получен',
            self::STATUS_CANCELLED => 'Отменен',
        ];
    }
    
    /**
     * Отмечаем заказ как полученный
     */
    public function markAsCompleted()
    {
        if ($this->canComplete()) {
            $this->status = self::STATUS_COMPLETED;
            return $this->save(false, ['status']);
        }
        return false;
    }
}
