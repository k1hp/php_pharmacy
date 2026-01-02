<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Profile;
use app\models\Wallet;

class SiteController extends Controller
{
    /**
     * Главная страница
     */
    public function actionIndex()
    {
	    /* $this->layout = false; */ 
	    return $this->render('index', [
            'message' => 'Добро пожаловать в аптеку!'
        ]);
    }
    
    /**
     * Страница входа
     */
    public function actionLogin()
    {
        // Если уже авторизован - на главную
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isPost) {
            $email = Yii::$app->request->post('email');
            $password = Yii::$app->request->post('password');
            
            // Ищем пользователя
            $user = Profile::findOne(['email' => $email]);
            
            if ($user && $user->validatePassword($password)) {
                // Логиним
                Yii::$app->user->login($user);
                return $this->goHome();
            }
            
            Yii::$app->session->setFlash('error', 'Неверный email или пароль');
        }
        
        return $this->render('login');
    }
    
    /**
     * Страница регистрации
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isPost) {
            $email = Yii::$app->request->post('email');
            $name = Yii::$app->request->post('name');
            $password = Yii::$app->request->post('password');
            
            // Простая валидация
            if (empty($email) || empty($name) || empty($password)) {
                Yii::$app->session->setFlash('error', 'Все поля обязательны');
                return $this->refresh();
            }
            
            // Проверяем, есть ли уже такой email
            if (Profile::findOne(['email' => $email])) {
                Yii::$app->session->setFlash('error', 'Пользователь с таким email уже существует');
                return $this->refresh();
            }
            
            // Начинаем транзакцию
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // 1. Создаем кошелек
                $wallet = new Wallet();
                $wallet->balance = 10000;
                if (!$wallet->save()) {
                    throw new \Exception('Не удалось создать кошелек');
                }
                
                // 2. Создаем профиль
                $profile = new Profile();
                $profile->email = $email;
                $profile->name = $name;
                $profile->wallet_id = $wallet->wallet_id;
                $profile->setPassword($password);
                $profile->created_at = date('Y-m-d H:i:s');
                
                if (!$profile->save()) {
                    throw new \Exception('Не удалось создать профиль');
                }
                
                $transaction->commit();
                
                // Автологин
                Yii::$app->user->login($profile);
                Yii::$app->session->setFlash('success', 'Регистрация успешна! Вам начислено 10 000 ₽.');
                return $this->goHome();
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::error($e->getMessage());
            }
        }
        
        return $this->render('signup');
    }
    
    /**
     * Выход
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    /**
     * Страница ошибки
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error', [
            'exception' => $exception
        ]);
    }
    
    public function actionAbout()
	{
    return $this->render('about');
	}
}
