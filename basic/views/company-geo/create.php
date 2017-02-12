<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CompanyGeo */

$this->title = 'Create Company Geo';
$this->params['breadcrumbs'][] = ['label' => 'Company Geos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-geo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
