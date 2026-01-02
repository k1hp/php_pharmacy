<?php

use yii\db\Migration;

class m251231_144740_init_pharmacy extends Migration
{
public function safeUp()
    {
        // Кошельки
        $this->createTable('wallets', [
            'wallet_id' => $this->bigPrimaryKey(),
            'balance' => $this->integer()->defaultValue(10000)->notNull()->check('balance >= 0'),
        ]);

        // Профили пользователей
        $this->createTable('profiles', [
            'profile_id' => $this->bigPrimaryKey(),
            'email' => $this->string(100)->notNull()->unique(),
            'name' => $this->string(100)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'wallet_id' => $this->bigInteger()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_profiles_wallets',
            'profiles',
            'wallet_id',
            'wallets',
            'wallet_id',
            'CASCADE'
        );

        // Товары
        $this->createTable('products', [
            'product_id' => $this->bigPrimaryKey(),
            'title' => $this->string(100)->notNull(),
            'price' => $this->integer()->notNull()->check('price >= 0'),
            'stock' => $this->integer()->defaultValue(0)->notNull()->check('stock >= 0'),
            'category' => $this->string(50),
        ]);

        // Корзины
        $this->createTable('carts', [
            'cart_id' => $this->bigPrimaryKey(),
            'profile_id' => $this->bigInteger()->notNull(),
            'is_active' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_carts_profiles',
            'carts',
            'profile_id',
            'profiles',
            'profile_id',
            'CASCADE'
        );

        // Уникальный индекс для активной корзины
        $this->execute("
            CREATE UNIQUE INDEX unique_active_cart_per_profile 
            ON carts (profile_id) 
            WHERE (is_active = true)
        ");

        // Товары в корзине
        $this->createTable('cart_items', [
            'item_id' => $this->bigPrimaryKey(),
            'cart_id' => $this->bigInteger()->notNull(),
            'product_id' => $this->bigInteger()->notNull(),
            'quantity' => $this->integer()->defaultValue(1)->notNull()->check('quantity > 0'),
        ]);

        $this->addForeignKey(
            'fk_cart_items_carts',
            'cart_items',
            'cart_id',
            'carts',
            'cart_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_cart_items_products',
            'cart_items',
            'product_id',
            'products',
            'product_id',
            'CASCADE'
        );

        $this->createIndex(
            'idx_cart_items_unique',
            'cart_items',
            ['cart_id', 'product_id'],
            true
        );

        // Заказы
        $this->createTable('orders', [
            'order_id' => $this->bigPrimaryKey(),
            'profile_id' => $this->bigInteger()->notNull(),
            'cart_id' => $this->bigInteger()->notNull(),
            'total' => $this->integer()->notNull()->check('total >= 0'),
            'status' => $this->string(20)->defaultValue('pending')->check("status IN ('pending', 'paid', 'shipped', 'completed', 'cancelled')"),
            'delivery_address' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_orders_profiles',
            'orders',
            'profile_id',
            'profiles',
            'profile_id'
        );

        $this->addForeignKey(
            'fk_orders_carts',
            'orders',
            'cart_id',
            'carts',
            'cart_id'
        );

        // Товары в заказе
        $this->createTable('order_items', [
            'order_item_id' => $this->bigPrimaryKey(),
            'order_id' => $this->bigInteger()->notNull(),
            'product_id' => $this->bigInteger()->notNull(),
            'quantity' => $this->integer()->notNull()->check('quantity > 0'),
        ]);

        $this->addForeignKey(
            'fk_order_items_orders',
            'order_items',
            'order_id',
            'orders',
            'order_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_order_items_products',
            'order_items',
            'product_id',
            'products',
            'product_id'
        );
    }

    /**
     * safeDown
     */
    public function safeDown()
    {
        $this->dropTable('order_items');
        $this->dropTable('orders');
        $this->dropTable('cart_items');
        $this->dropTable('carts');
        $this->dropTable('products');
        $this->dropTable('profiles');
        $this->dropTable('wallets');
    }
}
