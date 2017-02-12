<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company`.
 */
class m161227_110119_create_company_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer(),
            'keyword_id' => $this->integer(),
            'ypid' => $this->string(),
            'title' => $this->string(),
            'street' => $this->string(),
            'postal' => $this->string(),
            'phone' => $this->string(),
            'category' => $this->string(),
            'image' => $this->string(),
            'website' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-company-ypid', '{{%company}}', 'ypid');
        $this->createIndex('idx-company-title', '{{%company}}', 'title');
        $this->createIndex('idx-company-city_id', '{{%company}}', 'city_id');
        $this->createIndex('idx-company-postal', '{{%company}}', 'postal');
        
    }

    public function down()
    {
        $this->dropIndex('idx-company-ypid', '{{%company}}');
        $this->dropIndex('idx-company-title', '{{%company}}');
        $this->dropIndex('idx-company-city_id', '{{%company}}');
        $this->dropIndex('idx-company-postal', '{{%company}}');
        $this->dropTable('{{%company}}');
    }
}
