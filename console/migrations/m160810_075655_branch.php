<?php

use yii\db\Migration;

class m160810_075655_branch extends Migration {

        public function up() {
                $tableOptions = null;
                if ($this->db->driverName === 'mysql') {
                        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                }

                $this->renameTable('{{%admin_users}}', '{{%employee}}');
                $this->addColumn('{{%employee}}', 'branch_id', $this->string(100));
                $this->addColumn('{{%employee}}', 'employee_code', $this->string(100));
                $this->addColumn('{{%employee}}', 'gender', $this->smallInteger());
                $this->addColumn('{{%employee}}', 'date_of_join', $this->date());
                $this->addColumn('{{%employee}}', 'maritual_status', $this->smallInteger());
                $this->addColumn('{{%employee}}', 'salary_package', $this->smallInteger());
                $this->addColumn('{{%employee}}', 'photo', $this->string(100));

                $this->createTable('{{%branch}}', [
                    'id' => $this->primaryKey(),
                    'branch_name' => $this->string(100)->notNull(),
                    'branch_code' => $this->string(15)->notNull(),
                    'responisible_peerson' => $this->string(100),
                    'phone1' => $this->string(15)->notNull(),
                    'phone2' => $this->string(15),
                    'email' => $this->string(50)->notNull(),
                    'address' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%branch}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
        }

        public function down() {
                echo "m160810_075655_branch cannot be reverted.\n";

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
