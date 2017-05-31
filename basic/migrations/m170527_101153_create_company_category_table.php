<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_category`.
 */
class m170527_101153_create_company_category_table extends Migration
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
        $this->createTable('company_category', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'title' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_category');
    }
}
