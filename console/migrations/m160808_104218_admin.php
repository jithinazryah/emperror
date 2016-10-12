<?php

use yii\db\Migration;

class m160808_104218_admin extends Migration {

        public function up() {

                $tableOptions = null;
                if ($this->db->driverName === 'mysql') {
                        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                }

                $this->createTable('{{%admin_posts}}', [
                    'id' => $this->primaryKey(),
                    'post_name' => $this->string(100)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%admin_posts}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%admin_users}}', [
                    'id' => $this->primaryKey(),
                    'post_id' => $this->integer()->notNull(),
                    'user_name' => $this->string(30)->notNull(),
                    'password' => $this->string(300)->notNull(),
                    'name' => $this->string(100),
                    'email' => $this->string(100),
                    'phone' => $this->string(15),
                    'address' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%admin_users}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createIndex('post_id', 'admin_users', 'post_id', $unique = false);
                $this->addForeignKey("", "admin_users", "post_id", "admin_posts", "id", "RESTRICT", "RESTRICT");
        }

        public function down() {
                echo "m160808_104218_admin cannot be reverted.\n";

                return false;
        }

        /*
          // Use safeUp/safeDown to run migration code within a transaction
          public function safeUp()
          {
          }

          public function safeDown()
          {
          }
         */
}
