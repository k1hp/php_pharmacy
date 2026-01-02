<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\models\ProductSearch;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add-to-cart' => ['POST'],
                    'filter' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Главная страница с фильтрацией товаров
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Получаем минимальную и максимальную цену для подсказок
        $priceRange = Product::find()
            ->select([
                'MIN(price) as min_price',
                'MAX(price) as max_price'
            ])
            ->asArray()
            ->one();

        // Получаем все категории для фильтра
        $categories = Product::find()
            ->select('category')
            ->distinct()
            ->where(['not', ['category' => null]])
            ->andWhere(['<>', 'category', ''])
            ->orderBy('category')
            ->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'priceRange' => $priceRange,
            'categories' => $categories,
        ]);
    }

    /**
     * AJAX endpoint для фильтрации товаров
     */
    public function actionFilter()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $html = $this->renderPartial('_product_grid', [
            'dataProvider' => $dataProvider,
        ]);

        $pagination = $dataProvider->getPagination();

        return [
            'success' => true,
            'html' => $html,
            'pagination' => [
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'totalCount' => $pagination->totalCount,
            ],
        ];
    }

    /**
     * AJAX добавление товара в корзину
     */
    public function actionAddToCart($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'message' => 'Для добавления товара в корзину необходимо авторизоваться',
            ];
        }

        $product = $this->findProduct($id);
        
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Товар не найден',
            ];
        }

        if ($product->stock <= 0) {
            return [
                'success' => false,
                'message' => 'Товара нет в наличии',
            ];
        }

        $profileId = Yii::$app->user->identity->profile_id;
        
        // Получаем активную корзину пользователя
        $cart = Cart::find()
            ->where(['profile_id' => $profileId, 'is_active' => true])
            ->one();

        // Если нет активной корзины, создаем новую
        if (!$cart) {
            $cart = new Cart();
            $cart->profile_id = $profileId;
            $cart->is_active = true;
            if (!$cart->save()) {
                return [
                    'success' => false,
                    'message' => 'Ошибка создания корзины',
                    'errors' => $cart->errors,
                ];
            }
        }

        // Проверяем, есть ли уже такой товар в корзине
        $cartItem = CartItem::find()
            ->where(['cart_id' => $cart->cart_id, 'product_id' => $id])
            ->one();

        if ($cartItem) {
            // Если товар уже есть в корзине, увеличиваем количество
            if ($cartItem->quantity < $product->stock) {
                $cartItem->quantity += 1;
                if (!$cartItem->save()) {
                    return [
                        'success' => false,
                        'message' => 'Ошибка обновления количества товара',
                        'errors' => $cartItem->errors,
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Недостаточно товара на складе',
                ];
            }
        } else {
            // Если товара нет в корзине, создаем новый элемент
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->cart_id;
            $cartItem->product_id = $id;
            $cartItem->quantity = 1;
            
            if (!$cartItem->save()) {
                return [
                    'success' => false,
                    'message' => 'Ошибка добавления товара в корзину',
                    'errors' => $cartItem->errors,
                ];
            }
        }

        // Получаем общее количество товаров в корзине
        $cartTotalItems = CartItem::find()
            ->where(['cart_id' => $cart->cart_id])
            ->sum('quantity');

        return [
            'success' => true,
            'message' => 'Товар добавлен в корзину',
            'cartTotalItems' => $cartTotalItems ?: 0,
        ];
    }

    /**
     * Находит товар по ID
     */
    protected function findProduct($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Товар не найден.');
    }
}
