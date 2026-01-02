<?php

namespace app\models;

use Yii;

class Cart extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'carts';
    }

    public function rules()
    {
        return [
            [['profile_id'], 'required'],
            [['profile_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at'], 'safe'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cart_id' => 'ID корзины',
            'profile_id' => 'ID профиля',
            'is_active' => 'Активна',
            'created_at' => 'Дата создания',
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['profile_id' => 'profile_id']);
    }

    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['cart_id' => 'cart_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['product_id' => 'product_id'])
            ->via('cartItems');
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, ['cart_id' => 'cart_id']);
    }
}
