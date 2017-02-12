<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-index">

    <p class="text-right">
        <?= Html::a('Create Position', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            
            [
                'attribute'=>'keyword_id',
                'label' => 'Keyword',
                'content'=>function($data){
                    return $data->keyword->key;
                }
                
            ],
            [
                'attribute'=>'city_id',
                'label' => 'City',
                'content'=>function($data){
                    return (empty($data->city) === false) ? $data->city->name : 'Not found';
                }
                
            ],
            [
                'attribute'=>'status',
                'label' => 'Status',
                'content'=>function($data){
                    return $data->statusName();
                }
                
            ],
            [
                
                'label' => 'Proces',
                'content'=>function($data){
                    return $data->process.'%';
                }
                
            ],
            [
                'attribute'=>'created_at',
                'label' => 'Created',
                'content'=>function($data){
                    return date("d/m/Y",$data->created_at);
                }
                
            ],
            [
                'attribute'=>'updated_at',
                'label' => 'Updated',
                'content'=>function($data){
                    return date("d/m/Y",$data->updated_at);
                }
                
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
