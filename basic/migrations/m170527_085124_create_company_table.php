<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company`.
 */
class m170527_085124_create_company_table extends Migration
{
    public function init()
    {
        $this->db = 'db2';
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer(),
            'ypid' => $this->integer(),
            'title' => $this->string(),
            'phone' => $this->string(),
            'address' => $this->string(),
            'lat' => $this->float(),
            'lon' => $this->float(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-company-ypid', '{{%company}}', 'ypid');
        $this->createIndex('idx-company-title', '{{%company}}', 'title');
        $this->createIndex('idx-company-city_id', '{{%company}}', 'city_id');
    }

    public function down()
    {
        $this->dropIndex('idx-company-ypid', '{{%company}}');
        $this->dropIndex('idx-company-title', '{{%company}}');
        $this->dropIndex('idx-company-city_id', '{{%company}}');
        $this->dropTable('{{%company}}');
    }
}
