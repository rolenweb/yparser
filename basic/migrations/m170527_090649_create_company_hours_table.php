<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_hours`.
 */
class m170527_090649_create_company_hours_table extends Migration
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

        $this->createTable('company_hours', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'type' => $this->string(),
            'day' => $this->smallInteger(),
            'start' => $this->time(),
            'end' => $this->time(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company_hours');
    }
}
