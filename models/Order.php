<?php

namespace app\models;

use Yii;

class Order extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['profile_id', 'cart_id', 'total'], 'required'],
            [['profile_id', 'cart_id', 'total'], 'integer'],
            [['total'], 'integer', 'min' => 0],
            [['status'], 'string'],
            [['delivery_address'], 'string'],
            [['created_at'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_SHIPPED, self::STATUS_DELIVERED, self::STATUS_CANCELLED]],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_id' => 'cart_id']],
        ];
    }

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
}
