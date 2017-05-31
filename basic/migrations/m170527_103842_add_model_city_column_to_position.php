<?php

use yii\db\Migration;

class m170527_103842_add_model_city_column_to_position extends Migration
{
    public function up()
    {
        $this->addColumn('position', 'model_city', $this->string());
    }

    public function down()
    {
        $this->dropColumn('position', 'model_city');
    }
}
