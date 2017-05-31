<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_payment_method`.
 */
class m170527_092522_create_company_payment_method_table extends Migration
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
        $this->createTable('company_payment_method', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'method' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_payment_method');
    }
}
