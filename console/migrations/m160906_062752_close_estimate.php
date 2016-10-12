<?php

use yii\db\Migration;

class m160906_062752_close_estimate extends Migration
{
    public function up()
    {
             $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%close_estimate}}', [
                    'id' => $this->primaryKey(),
                    'apponitment_id' => $this->integer()->notNull(),
                    'service_id' => $this->integer()->notNull(),
                    'supplier' => $this->integer(),
                    'currency' => $this->integer(),
                    'unit_rate' => $this->string(50),
                    'unit' => $this->string(50),
                    'roe' => $this->string(15),
                    'epda' => $this->integer(),
                    'fda' => $this->integer(),
                    'payment_type' => $this->string(15),
                    'total' => $this->integer(),
                    'invoice_type' => $this->integer(),
                    'principal' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%close_estimate}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
        

    }

    public function down()
    {
        echo "m160906_062752_close_estimate cannot be reverted.\n";

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
