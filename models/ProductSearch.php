<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProductSearch extends Product
{
    public $category;
    public $min_price;
    public $max_price;
    public $search;

    public function rules()
    {
        return [
            [['category', 'search'], 'safe'],
            [['min_price', 'max_price'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => ['product_id' => SORT_DESC], // оставляем, но это не "новинки"
                'attributes' => [
                    'price',
                    'title',
                    'product_id',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Поиск по названию
        if (!empty($this->search)) {
            $query->andWhere(['ilike', 'title', $this->search]);
        }

        // Фильтр по категории
        if (!empty($this->category)) {
            $query->andWhere(['category' => $this->category]);
        }

        // Фильтр по цене
        if ($this->min_price !== null && $this->min_price !== '') {
            $query->andWhere(['>=', 'price', $this->min_price]);
        }

        if ($this->max_price !== null && $this->max_price !== '') {
            $query->andWhere(['<=', 'price', $this->max_price]);
        }

        // Применяем сортировку из GET параметров
        $sortParam = Yii::$app->request->get('sort');
        if ($sortParam) {
            switch ($sortParam) {
                case 'price_asc':
                    $query->orderBy(['price' => SORT_ASC]);
                    break;
                case 'price_desc':
                    $query->orderBy(['price' => SORT_DESC]);
                    break;
                case 'name_asc':
                    $query->orderBy(['title' => SORT_ASC]);
                    break;
                case 'name_desc':
                    $query->orderBy(['title' => SORT_DESC]);
                    break;
                
            }
        }

        return $dataProvider;
    }
}
