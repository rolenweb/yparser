<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_extra_phones`.
 */
class m170527_092335_create_company_extra_phones_table extends Migration
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
        $this->createTable('company_extra_phones', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'phone' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_extra_phones');
    }
}
