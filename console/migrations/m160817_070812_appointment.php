<?php

use yii\db\Migration;

class m160817_070812_appointment extends Migration {

        public function up() {
                $tableOptions = null;
                if ($this->db->driverName === 'mysql') {
                        // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                }



                $this->createTable('{{%appointment}}', [
                    'id' => $this->primaryKey(),
                    'vessel_type' => $this->integer()->notNull(),
                    'vessel' => $this->integer()->notNull(),
                    'port_of_call' => $this->integer()->notNull(),
                    'terminal' => $this->integer(),
                    'birth_no' => $this->string(50),
                    'appointment_no' => $this->string(50),
                    'no_of_principal' => $this->string(15),
                    'principal' => $this->integer(),
                    'nominator' => $this->integer(),
                    'charterer' => $this->integer(),
                    'shipper' => $this->integer(),
                    'purpose' => $this->string(200),
                    'cargo' => $this->string(100),
                    'quantity' => $this->string(15),
                    'last_port' => $this->string(200),
                    'next_port' => $this->string(200),
                    'eta' => $this->dateTime(),
                    'stage' => $this->integer()->notNull(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%appointment}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%estimated_proforma}}', [
                    'id' => $this->primaryKey(),
                    'apponitment_id' => $this->integer()->notNull(),
                    'service_id' => $this->integer()->notNull(),
                    'supplier' => $this->integer(),
                    'currency' => $this->integer(),
                    'unit_rate' => $this->string(50),
                    'unit' => $this->string(50),
                    'roe' => $this->string(15),
                    'epda' => $this->integer(),
                    'invoice_type' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%estimated_proforma}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%appointment_stage}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'stage' => $this->integer()->notNull(),
                    'details' => $this->integer(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%appointment_stage}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%port_call_data}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'eta' => $this->dateTime(),
                    'ets' => $this->dateTime(),
                    'eosp' => $this->dateTime(),
                    'arrived_anchorage' => $this->dateTime(),
                    'nor_tendered' => $this->dateTime(),
                    'dropped_anchor' => $this->dateTime(),
                    'anchor_aweigh' => $this->dateTime(),
                    'arrived_pilot_station' => $this->dateTime(),
                    'pob_inbound' => $this->dateTime(),
                    'first_line_ashore' => $this->dateTime(),
                    'all_fast' => $this->dateTime(),
                    'gangway_down' => $this->dateTime(),
                    'agent_on_board' => $this->dateTime(),
                    'immigration_commenced' => $this->dateTime(),
                    'immigartion_completed' => $this->dateTime(),
                    'cargo_commenced' => $this->dateTime(),
                    'cargo_completed' => $this->dateTime(),
                    'pob_outbound' => $this->dateTime(),
                    'lastline_away' => $this->dateTime(),
                    'cleared_channel' => $this->dateTime(),
                    'cosp' => $this->dateTime(),
                    'fasop' => $this->dateTime(),
                    'eta_next_port' => $this->dateTime(),
                    'additional_info' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%port_call_data}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');


                $this->createTable('{{%port_call_data_additional}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'type' => $this->smallInteger(),
                    'data_id' => $this->integer(),
                    'label' => $this->string(200),
                    'value' => $this->dateTime(),
                    'comment' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%port_call_data_additional}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%port_call_data_draft}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'data_id' => $this->integer(),
                    'intial_survey_commenced' => $this->dateTime(),
                    'intial_survey_completed' => $this->dateTime(),
                    'finial_survey_commenced' => $this->dateTime(),
                    'finial_survey_completed' => $this->dateTime(),
                    'fwd_arrival_unit' => $this->integer(),
                    'fwd_arrival_quantity' => $this->integer(),
                    'aft_arrival_unit' => $this->integer(),
                    'aft_arrival_quantity' => $this->integer(),
                    'mean_arrival_unit' => $this->integer(),
                    'mean_arrival_quantity' => $this->integer(),
                    'fwd_sailing_unit' => $this->integer(),
                    'fwd_sailing_quantity' => $this->integer(),
                    'aft_sailing_unit' => $this->integer(),
                    'aft_sailing_quantity' => $this->integer(),
                    'mean_sailing_unit' => $this->integer(),
                    'mean_sailing_quantity' => $this->integer(),
                    'additional_info' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%port_call_data_draft}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');


                $this->createTable('{{%port_call_data_rob}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'fo_arrival_unit' => $this->integer(),
                    'fo_arrival_quantity' => $this->integer(),
                    'do_arrival_unit' => $this->integer(),
                    'do_arrival_quantity' => $this->integer(),
                    'go_arrival_unit' => $this->integer(),
                    'go_arrival_quantity' => $this->integer(),
                    'lo_arrival_unit' => $this->integer(),
                    'lo_arrival_quantity' => $this->integer(),
                    'fresh_water_arrival_unit' => $this->integer(),
                    'fresh_water_arrival_quantity' => $this->integer(),
                    'fo_sailing_unit' => $this->integer(),
                    'fo_sailing_quantity' => $this->integer(),
                    'do_sailing_unit' => $this->integer(),
                    'do_sailing_quantity' => $this->integer(),
                    'go_sailing_unit' => $this->integer(),
                    'go_sailing_quantity' => $this->integer(),
                    'lo_sailing_unit' => $this->integer(),
                    'lo_sailing_quantity' => $this->integer(),
                    'fresh_water_sailing_unit' => $this->integer(),
                    'fresh_water_sailing_quantity' => $this->integer(),
                    'additional_info' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%port_call_data_rob}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');

                $this->createTable('{{%close_estimate}}', [
                    'id' => $this->primaryKey(),
                    'appointment_id' => $this->integer()->notNull(),
                    'fo_arrival_unit' => $this->integer(),
                    'fo_arrival_quantity' => $this->integer(),
                    'do_arrival_unit' => $this->integer(),
                    'do_arrival_quantity' => $this->integer(),
                    'go_arrival_unit' => $this->integer(),
                    'go_arrival_quantity' => $this->integer(),
                    'lo_arrival_unit' => $this->integer(),
                    'lo_arrival_quantity' => $this->integer(),
                    'fresh_water_arrival_unit' => $this->integer(),
                    'fresh_water_arrival_quantity' => $this->integer(),
                    'fo_sailing_unit' => $this->integer(),
                    'fo_sailing_quantity' => $this->integer(),
                    'do_sailing_unit' => $this->integer(),
                    'do_sailing_quantity' => $this->integer(),
                    'go_sailing_unit' => $this->integer(),
                    'go_sailing_quantity' => $this->integer(),
                    'lo_sailing_unit' => $this->integer(),
                    'lo_sailing_quantity' => $this->integer(),
                    'fresh_water_sailing_unit' => $this->integer(),
                    'fresh_water_sailing_quantity' => $this->integer(),
                    'additional_info' => $this->integer(),
                    'comments' => $this->text(),
                    'status' => $this->smallInteger()->notNull()->defaultValue(0),
                    'CB' => $this->integer()->notNull(),
                    'UB' => $this->integer()->notNull(),
                    'DOC' => $this->date(),
                    'DOU' => $this->timestamp(),
                        ], $tableOptions);
                $this->alterColumn('{{%close_estimate}}', 'id', $this->integer() . ' NOT NULL AUTO_INCREMENT');
        }

        public function down() {
                echo "m160817_070812_appointment cannot be reverted.\n";

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
