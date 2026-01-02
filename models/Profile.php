<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

class Profile extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'profiles';
    }

    public function rules()
    {
        return [
            [['email', 'name', 'password_hash', 'wallet_id'], 'required'],
            [['wallet_id'], 'integer'],
            [['created_at'], 'safe'],
            [['email'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 100],
            [['password_hash'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['wallet_id'], 'unique'],
            [['wallet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::class, 'targetAttribute' => ['wallet_id' => 'wallet_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'profile_id' => 'ID профиля',
            'email' => 'Email',
            'name' => 'Имя',
            'password_hash' => 'Пароль',
            'wallet_id' => 'ID кошелька',
            'created_at' => 'Дата создания',
        ];
    }

    public function getWallet()
    {
        return $this->hasOne(Wallet::class, ['wallet_id' => 'wallet_id']);
    }

    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['profile_id' => 'profile_id']);
    }

    public function getActiveCart()
    {
        return $this->hasOne(Cart::class, ['profile_id' => 'profile_id'])
            ->andOnCondition(['is_active' => true]);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, ['profile_id' => 'profile_id']);
    }
    
    // IdentityInterface методы 
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    
    public function getId()
    {
        return $this->profile_id;
    }
    
    public function getAuthKey()
    {
        return null;
    }
    
    public function validateAuthKey($authKey)
    {
        return false;
    }
    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
