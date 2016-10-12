<?php

use yii\db\Migration;

class m160810_101530_principals extends Migration {

        public function up() {
                $this->execute("ALTER TABLE `employee` ADD UNIQUE(`user_name`);");
                $tableOptions = null;
                if ($this->db->driverName === 'mysql') {
                        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                }



                $this->createTable('{{%debtor}}', [
                    'id' => $this->primaryKey(),
                    'principal_name' => $this->string(100)->notNull(),
                    'address' => $this->text()->notNull(),
                    'mobile' => $this->string(15)->notNull(),
                    'tele_phone' => $this->string(15),
                    'fax' => $this->string(15),
                    'invoicing_address' => $this->text(),
                    'da_dispatch_addresss_1' => $this->text(),
                    'da_dispatch_addresss_2' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%debtor}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%vessel}}', [
                    'id' => $this->primaryKey(),
                    'vessel_type' => $this->smallInteger()->notNull(),
                    'vessel_name' => $this->string(200)->notNull(),
                    'imo_no' => $this->string(100),
                    'official' => $this->string(100),
                    'mmsi_no' => $this->string(100),
                    'owners_info' => $this->text(),
                    'mobile' => $this->string(20),
                    'land_line' => $this->string(20),
                    'direct_line' => $this->string(20),
                    'fax' => $this->string(20),
                    'picture' => $this->string(100),
                    'dwt' => $this->string(20),
                    'grt' => $this->string(20),
                    'nrt' => $this->string(20),
                    'loa' => $this->string(20),
                    'beam' => $this->string(20),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%vessel}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%service_categorys}}', [
                    'id' => $this->primaryKey(),
                    'category_name' => $this->string(200)->notNull(),
                    'invoice_type' => $this->string(100)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%service_categorys}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%services}}', [
                    'id' => $this->primaryKey(),
                    'category_id' => $this->integer()->notNull(),
                    'service' => $this->string(200)->notNull(),
                    'invocie_type' => $this->integer()->notNull(),
                    'supplier' => $this->integer()->notNull(),
                    'unit_rate' => $this->integer(),
                    'unit' => $this->integer(),
                    'currency' => $this->smallInteger(),
                    'roe' => $this->string(100),
                    'epda_value' => $this->integer(),
                    'cost_allocation' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%services}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%stage_categorys}}', [
                    'id' => $this->primaryKey(),
                    'category_name' => $this->string(200)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%stage_categorys}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%stages}}', [
                    'id' => $this->primaryKey(),
                    'category_id' => $this->smallInteger()->notNull(),
                    'stage' => $this->string(200)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%stages}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%ports}}', [
                    'id' => $this->primaryKey(),
                    'port_name' => $this->string(200)->notNull(),
                    'code' => $this->string(200)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%stages}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
        }

        public function down() {
                echo "m160810_101530_principals cannot be reverted.\n";

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
