<?php

use yii\db\Migration;

class m160811_035311_sqls extends Migration {

        public function up() {
                $this->execute("INSERT INTO `emperor`.`admin_posts` (`id`, `post_name`, `status`, `CB`, `UB`, `DOC`, `DOU`) VALUES (NULL, 'Super Admin', '1', '1', '1', '2016-08-11', CURRENT_TIMESTAMP);");
                $this->execute("INSERT INTO `emperor`.`employee` (`id`, `post_id`, `user_name`, `password`, `name`, `email`, `phone`, `address`, `status`, `CB`, `UB`, `DOC`, `DOU`, `branch_id`, `employee_code`, `gender`, `date_of_join`, `maritual_status`, `salary_package`, `photo`) VALUES (NULL, '1', 'testing', 'testing', 'Jithin Wails', 'jithin@azryah.com', '+919895573839', 'testing', '1', '1', '1', '2016-08-11', CURRENT_TIMESTAMP, '1', 'A001', '1', '2016-08-01', '1', '1', NULL);");
                $this->execute("INSERT INTO `emperor`.`branch` (`id`, `branch_name`, `branch_code`, `responisible_peerson`, `phone1`, `phone2`, `email`, `address`, `status`, `CB`, `UB`, `DOC`, `DOU`) VALUES (NULL, 'Ras al khaimah', 'A001', 'John Doe', '9895573839', '9895573839', 'john@emperror.ae', NULL, '1', '1', '1', '2016-08-11', CURRENT_TIMESTAMP);");
                $this->addColumn('{{%admin_posts}}', 'close_estimate', 'SMALLINT NULL DEFAULT "0" AFTER `admin`');
                $this->addColumn('{{%admin_posts}}', 'port_call_data', 'SMALLINT NULL DEFAULT "0" AFTER `admin`');
                $this->addColumn('{{%admin_posts}}', 'estimated_proforma', 'SMALLINT NULL DEFAULT "0" AFTER `admin`');
                $this->addColumn('{{%admin_posts}}', 'appointments', 'SMALLINT NULL DEFAULT "0" AFTER `admin`');
                $this->addColumn('{{%admin_posts}}', 'masters', 'SMALLINT NULL DEFAULT "0" AFTER `admin`');


                $this->createTable('{{%purpose}}', [
                    'id' => $this->primaryKey(),
                    'purpose' => $this->string(100)->notNull(),
                    'time_required' => $this->smallInteger()->notNull()->defaultValue(0),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%purpose}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%contacts}}', [
                    'id' => $this->primaryKey(),
                    'name' => $this->string(200)->notNull(),
                    'person' => $this->string(100)->notNull(),
                    'email' => $this->string(100)->notNull(),
                    'phone_1' => $this->string(20)->notNull(),
                    'phone_2' => $this->string(20)->notNull(),
                    'address' => $this->text()->notNull(),
                    'comment' => $this->text()->notNull(),
                    'contact_type' => $this->string(100)->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%contacts}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
        }

        public function down() {
                echo "m160811_035311_sqls cannot be reverted.\n";

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
