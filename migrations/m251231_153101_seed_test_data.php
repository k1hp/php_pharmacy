<?php

use yii\db\Migration;

class m251231_153101_seed_test_data extends Migration
{
    public function safeUp()
    {
        echo "Добавляем тестовые данные...\n";

        // Кошельки (PK автогенерируется)
        $this->batchInsert('wallets', ['balance'], [
            [150000],
            [155000],
            [250000],
            [75500],
            [350000],
        ]);

        // Профили (PK автогенерируется, FK на wallets)
        $this->batchInsert('profiles', [
            'email', 'name', 'password_hash', 'wallet_id'
        ], [
            ['user1@example.com', 'Иван Иванов', Yii::$app->security->generatePasswordHash('123456'), 1],
            ['user2@example.com', 'Мария Петрова', Yii::$app->security->generatePasswordHash('123456'), 2],
            ['admin@pharmacy.ru', 'Администратор', Yii::$app->security->generatePasswordHash('admin123'), 3],
            ['customer@mail.ru', 'Алексей Смирнов', Yii::$app->security->generatePasswordHash('123456'), 4],
            ['test@test.com', 'Тест Тестов', Yii::$app->security->generatePasswordHash('123456'), 5],
        ]);

        // Товары 
        $this->batchInsert('products', [
            'title', 'price', 'stock', 'category'
        ], [
            // Лекарства
            ['Аспирин', 150, 100, 'Лекарства'],
            ['Нурофен', 350, 50, 'Лекарства'],
            ['Парацетамол', 120, 200, 'Лекарства'],
            ['Ибупрофен', 280, 75, 'Лекарства'],

            // Витамины
            ['Витамин C', 250, 200, 'Витамины'],
            ['Витамин D3', 420, 150, 'Витамины'],
            ['Комплекс витаминов B', 560, 80, 'Витамины'],
            ['Рыбий жир', 320, 120, 'Витамины'],

            // Перевязочные материалы
            ['Бинт стерильный', 120, 300, 'Перевязочные'],
            ['Пластырь', 85, 500, 'Перевязочные'],
            ['Марля', 95, 400, 'Перевязочные'],
            ['Вата медицинская', 110, 250, 'Перевязочные'],

            // Медтехника
            ['Термометр электронный', 890, 30, 'Медтехника'],
            ['Тонометр', 2450, 15, 'Медтехника'],
            ['Ингалятор', 1800, 25, 'Медтехника'],
            ['Глюкометр', 3200, 10, 'Медтехника'],
            ['Стетоскоп', 1200, 20, 'Медтехника'],
            ['Небулайзер', 3500, 0, 'Медтехника'],
            ['Термометр инфракрасный', 2900, 12, 'Медтехника'],
            ['Весы медицинские', 4500, 5, 'Медтехника'],
            ['Пульсоксиметр', 2800, 15, 'Медтехника'],
            ['Электрокардиограф', 12500, 3, 'Медтехника'],
            ['Аппарат для измерения давления', 3200, 10, 'Медтехника'],
            ['Ирригатор', 5400, 7, 'Медтехника'],
            
            // Гигиена
            ['Маска медицинская (10 шт)', 250, 1000, 'Гигиена'],
            ['Перчатки латексные (50 шт)', 450, 300, 'Гигиена'],
            ['Антисептик для рук', 320, 180, 'Гигиена'],
            ['Влажные салфетки', 190, 220, 'Гигиена'],

            // Косметика
            ['Крем увлажняющий', 540, 60, 'Косметика'],
            ['Шампунь лечебный', 420, 90, 'Косметика'],
            ['Зубная паста', 210, 150, 'Косметика'],
            ['Дезодорант', 380, 110, 'Косметика'],
        ]);

        // Корзины (PK автогенерируется, FK на profiles)
        $this->batchInsert('carts', [
            'profile_id', 'is_active', 'created_at'
        ], [
            [1, true, date('Y-m-d H:i:s')],
            [2, true, date('Y-m-d H:i:s')],
            [3, true, date('Y-m-d H:i:s')],
            [4, true, date('Y-m-d H:i:s')],
            [5, true, date('Y-m-d H:i:s')],
        ]);

        // Товары в корзинах (PK автогенерируется)
        $this->batchInsert('cart_items', [
            'cart_id', 'product_id', 'quantity'
        ], [
            [1, 1, 2], [1, 5, 1], [1, 9, 3],  // Корзина 1
            [2, 2, 1], [2, 13, 1],              // Корзина 2
            [3, 17, 5], [3, 18, 2],             // Корзина 3 (админ)
        ]);

        // Заказы (PK автогенерируется)
        $this->batchInsert('orders', [
            'profile_id', 'cart_id', 'total', 'status', 'delivery_address', 'created_at'
        ], [
            // Заказ 1: оплачен
            [1, 1, (150*2)+(250*1)+(120*3), 'paid', 'ул. Ленина, д. 10', date('Y-m-d H:i:s', strtotime('-5 days'))],
            // Заказ 2: завершен
            [2, 2, (350*1)+(890*1), 'completed', 'ул. Мира, д. 15', date('Y-m-d H:i:s', strtotime('-10 days'))],
            // Заказ 3: отменен
            [4, 4, 0, 'cancelled', 'ул. Центральная', date('Y-m-d H:i:s', strtotime('-2 days'))],
        ]);

        // Товары в заказах (PK автогенерируется)
        $this->batchInsert('order_items', [
            'order_id', 'product_id', 'quantity'
        ], [
            [1, 1, 2], [1, 5, 1], [1, 9, 3],  // Заказ 1
            [2, 2, 1], [2, 13, 1],             // Заказ 2
        ]);

        echo "Тестовые данные добавлены успешно!\n";
    }

    public function safeDown()
    {
        echo "Удаляем тестовые данные...\n";

        $this->delete('order_items');
        $this->delete('orders');
        $this->delete('cart_items');
        $this->delete('carts');
        $this->delete('products');
        $this->delete('profiles');
        $this->delete('wallets');

        // Сброс последовательностей
        $this->execute("ALTER SEQUENCE wallets_wallet_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE profiles_profile_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE products_product_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE carts_cart_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE cart_items_item_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE orders_order_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE order_items_order_item_id_seq RESTART WITH 1");
    }
}
