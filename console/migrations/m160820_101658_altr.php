<?php

use yii\db\Migration;

class m160820_101658_altr extends Migration
{
    public function up()
    {
            $this->execute("ALTER TABLE `appointment` CHANGE `terminal` `terminal` VARCHAR(100) NULL DEFAULT NULL;");
            $this->execute("ALTER TABLE `appointment` CHANGE `principal` `principal` VARCHAR(100) NULL DEFAULT NULL;");
//            $this->execute("ALTER TABLE `estimated_proforma` ADD `principal` INT NULL AFTER `epda`;");
//            	 $this->createTable('{{%invoice_type}}', [
//            'id' => $this->primaryKey(),
//            'invoice_type' => $this->string(100),
//            'comment' => $this->text(),
//            'status' => $this->smallInteger()->notNull()->defaultValue(0),
//            'CB' => $this->integer()->notNull(),
//            'UB' => $this->integer()->notNull(),
//            'DOC' => $this->date(),
//            'DOU' => $this->timestamp(),
//                ], $tableOptions);
//        $this->alterColumn('{{%invoice_type}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
//        
//
//        $this->createTable('{{%units}}', [
//            'id' => $this->primaryKey(),
//            'unit_name' => $this->string(100),
//            'unit_symbol' => $this->string(100),
//            'base_unit' => $this->string(100),
//            'unit_relation' => $this->string(225),
//            'comment' => $this->text(),
//            'status' => $this->smallInteger()->notNull()->defaultValue(0),
//            'CB' => $this->integer()->notNull(),
//            'UB' => $this->integer()->notNull(),
//            'DOC' => $this->date(),
//            'DOU' => $this->timestamp(),
//                ], $tableOptions);
//        $this->alterColumn('{{%units}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
//
//
//        $this->createTable('{{%currency}}', [
//            'id' => $this->primaryKey(),
//            'currency_name' => $this->string(100),
//            'currency_symbol' => $this->string(100),
//            'currency_value' => $this->string(100),
//            'comment' => $this->text(),
//            'status' => $this->smallInteger()->notNull()->defaultValue(0),
//            'CB' => $this->integer()->notNull(),
//            'UB' => $this->integer()->notNull(),
//            'DOC' => $this->date(),
//            'DOU' => $this->timestamp(),
//                ], $tableOptions);
//        $this->alterColumn('{{%currency}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
            
    }

    public function down()
    {
        echo "m160820_101658_altr cannot be reverted.\n";

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
