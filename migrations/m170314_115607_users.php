<?php

use yii\db\Migration;
use yii\db\Schema;

class m170314_115607_users extends Migration {
    public function safeUp() {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull()->unique(),
            'authKey' => $this->string(32),
            'accessToken' => $this->string(32),
            'hash' => $this->string(60)->notNull(),
        ]);
        $this->insert('user', [
            'username' => 'admin',
            'hash' => '$2y$13$fTGz1DollRuFSKJyMrX/3uKgwjBVgi8sTj1ujAdtP/L57Q4uMBKKa'
        ]);
        $this->createTable('guest_token', [
            'code' => $this->string(4) . ' primary key',
            'valid_until' => $this->datetime()->notNull()
        ]);
    }

    public function safeDown() {
        $this->dropTable('guest_token');
        $this->dropTable('user');
    }
}
