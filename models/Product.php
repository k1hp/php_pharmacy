<?php

namespace app\models;

use Yii;

class Product extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'products';
    }

    public function rules()
    {
        return [
            [['title', 'price'], 'required'],
            [['price', 'stock'], 'integer', 'min' => 0],
            [['title'], 'string', 'max' => 100],
            [['image_url'], 'string', 'max' => 500],
            [['category'], 'string', 'max' => 50],
            [['stock'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'product_id' => 'ID товара',
            'title' => 'Название',
            'price' => 'Цена',
            'stock' => 'Количество на складе',
            'image_url' => 'URL изображения',
            'category' => 'Категория',
        ];
    }

    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['product_id' => 'product_id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'product_id']);
    }
}
