<?php

use yii\db\Migration;

class m251231_153101_seed_test_data extends Migration
{
	public function safeUp()
    {
        echo "Добавляем тестовые данные...\n";

        // Кошельки
        $this->batchInsert('wallets', ['balance'], [
            [10000],
            [15000],
            [20000],
            [7500],
            [30000],
        ]);

        // Профили (пароль для всех: 123456)
        $this->batchInsert('profiles', [
            'email', 'name', 'password_hash', 'wallet_id'
        ], [
            ['user1@example.com', 'Иван Иванов', Yii::$app->security->generatePasswordHash('123456'), 1],
            ['user2@example.com', 'Мария Петрова', Yii::$app->security->generatePasswordHash('123456'), 2],
            ['admin@pharmacy.ru', 'Администратор', Yii::$app->security->generatePasswordHash('admin123'), 3],
            ['customer@mail.ru', 'Алексей Смирнов', Yii::$app->security->generatePasswordHash('123456'), 4],
            ['test@test.com', 'Тест Тестов', Yii::$app->security->generatePasswordHash('123456'), 5],
        ]);

        // Товары для аптеки
        $this->batchInsert('products',
            ['title', 'price', 'stock', 'category', 'image_url'],
            [
                // Лекарства
                ['Аспирин', 150, 100, 'Лекарства', 'https://via.placeholder.com/300x200/FF6B6B/FFFFFF?text=Aspirin'],
                ['Нурофен', 350, 50, 'Лекарства', 'https://via.placeholder.com/300x200/4ECDC4/FFFFFF?text=Nurofen'],
                ['Парацетамол', 120, 200, 'Лекарства', 'https://via.placeholder.com/300x200/FFE66D/000000?text=Paracetamol'],
                ['Ибупрофен', 280, 75, 'Лекарства', 'https://via.placeholder.com/300x200/1A535C/FFFFFF?text=Ibuprofen'],

                // Витамины
                ['Витамин C', 250, 200, 'Витамины', 'https://via.placeholder.com/300x200/FFD166/000000?text=Vitamin+C'],
                ['Витамин D3', 420, 150, 'Витамины', 'https://via.placeholder.com/300x200/06D6A0/FFFFFF?text=Vitamin+D3'],
                ['Комплекс витаминов B', 560, 80, 'Витамины', 'https://via.placeholder.com/300x200/118AB2/FFFFFF?text=Vitamin+B'],
                ['Рыбий жир', 320, 120, 'Витамины', 'https://via.placeholder.com/300x200/EF476F/FFFFFF?text=Fish+Oil'],

                // Перевязочные материалы
                ['Бинт стерильный', 120, 300, 'Перевязочные', 'https://via.placeholder.com/300x200/073B4C/FFFFFF?text=Bandage'],
                ['Пластырь', 85, 500, 'Перевязочные', 'https://via.placeholder.com/300x200/7209B7/FFFFFF?text=Plaster'],
                ['Марля', 95, 400, 'Перевязочные', 'https://via.placeholder.com/300x200/F72585/FFFFFF?text=Gauze'],
                ['Вата медицинская', 110, 250, 'Перевязочные', 'https://via.placeholder.com/300x200/3A0CA3/FFFFFF?text=Cotton'],

                // Медтехника
                ['Термометр электронный', 890, 30, 'Медтехника', 'https://via.placeholder.com/300x200/4361EE/FFFFFF?text=Thermometer'],
                ['Тонометр', 2450, 15, 'Медтехника', 'https://via.placeholder.com/300x200/4CC9F0/000000?text=Tonometer'],
                ['Ингалятор', 1800, 25, 'Медтехника', 'https://via.placeholder.com/300x200/3F37C9/FFFFFF?text=Inhaler'],
                ['Глюкометр', 3200, 10, 'Медтехника', 'https://via.placeholder.com/300x200/560BAD/FFFFFF?text=Glucometer'],

                // Гигиена
                ['Маска медицинская (10 шт)', 250, 1000, 'Гигиена', 'https://via.placeholder.com/300x200/F15BB5/FFFFFF?text=Mask'],
                ['Перчатки латексные (50 шт)', 450, 300, 'Гигиена', 'https://via.placeholder.com/300x200/9B5DE5/FFFFFF?text=Gloves'],
                ['Антисептик для рук', 320, 180, 'Гигиена', 'https://via.placeholder.com/300x200/00BBF9/FFFFFF?text=Sanitizer'],
                ['Влажные салфетки', 190, 220, 'Гигиена', 'https://via.placeholder.com/300x200/00F5D4/000000?text=Wipes'],

                // Косметика
                ['Крем увлажняющий', 540, 60, 'Косметика', 'https://via.placeholder.com/300x200/FEE440/000000?text=Cream'],
                ['Шампунь лечебный', 420, 90, 'Косметика', 'https://via.placeholder.com/300x200/00F5D4/000000?text=Shampoo'],
                ['Зубная паста', 210, 150, 'Косметика', 'https://via.placeholder.com/300x200/FF9E00/FFFFFF?text=Toothpaste'],
                ['Дезодорант', 380, 110, 'Косметика', 'https://via.placeholder.com/300x200/FF0054/FFFFFF?text=Deodorant'],
            ]
        );

        echo "Тестовые данные добавлены успешно!\n";
    }

    public function safeDown()
    {
        echo "Удаляем тестовые данные...\n";

        $this->delete('products');
        $this->delete('profiles');
        $this->delete('wallets');

        // Сброс последовательностей (для PostgreSQL)
        $this->execute("ALTER SEQUENCE wallets_wallet_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE profiles_profile_id_seq RESTART WITH 1");
        $this->execute("ALTER SEQUENCE products_product_id_seq RESTART WITH 1");
    }
}
