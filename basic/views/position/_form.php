<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Keyword;

/* @var $this yii\web\View */
/* @var $model app\models\Position */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'keyword_id')->dropDownList(Keyword::dd()) ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList($model::ddStatus()) ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
