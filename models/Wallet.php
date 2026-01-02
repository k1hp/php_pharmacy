<?php

namespace app\models;

use Yii;

class Wallet extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'wallets';
    }

    public function rules()
    {
        return [
            [['balance'], 'required'],
            [['balance'], 'integer', 'min' => 0],
            [['balance'], 'default', 'value' => 10000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'wallet_id' => 'ID кошелька',
            'balance' => 'Баланс',
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['wallet_id' => 'wallet_id']);
    }
}
