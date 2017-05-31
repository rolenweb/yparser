<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_weblink`.
 */
class m170527_100955_create_company_weblink_table extends Migration
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
        $this->createTable('company_weblink', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'link' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_weblink');
    }
}
