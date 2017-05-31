<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_other_info`.
 */
class m170527_101311_create_company_other_info_table extends Migration
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
        $this->createTable('company_other_info', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'info' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_other_info');
    }
}
