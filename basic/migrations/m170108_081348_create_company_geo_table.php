<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_geo`.
 */
class m170108_081348_create_company_geo_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company_geo}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'lon' => $this->float(),
            'lat' => $this->float(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-company_geo-company_id', '{{%company_geo}}', 'company_id');
        
    }

    public function down()
    {
        $this->dropIndex('idx-company_geo-company_id', '{{%company_geo}}');
        $this->dropTable('{{%company_geo}}');
    }
}
