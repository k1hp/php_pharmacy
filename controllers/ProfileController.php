<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Profile;
use app\models\Wallet;
use app\models\Order;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        
        $profile = Yii::$app->user->identity;
        
        return $this->render('index', [
            'profile' => $profile
        ]);
    }
    
    public function actionWallet()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        
        $profile = Yii::$app->user->identity;
        $wallet = Wallet::findOne(['wallet_id' => $profile->wallet_id]);
        
        return $this->render('wallet', [
            'profile' => $profile,
            'wallet' => $wallet
        ]);
    }
    
    public function actionOrders()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        
        $profile = Yii::$app->user->identity;
        $orders = Order::find()
            ->where(['profile_id' => $profile->profile_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('orders', [
            'profile' => $profile,
            'orders' => $orders
        ]);
    }
}
