<?php

namespace app\models;

use Yii;

class CartItem extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'cart_items';
    }

    public function rules()
    {
        return [
            [['cart_id', 'product_id'], 'required'],
            [['cart_id', 'product_id', 'quantity'], 'integer'],
            [['quantity'], 'default', 'value' => 1],
            [['quantity'], 'integer', 'min' => 1],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_id' => 'cart_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'item_id' => 'ID элемента',
            'cart_id' => 'ID корзины',
            'product_id' => 'ID товара',
            'quantity' => 'Количество',
        ];
    }

    public function getCart()
    {
        return $this->hasOne(Cart::class, ['cart_id' => 'cart_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }
}
