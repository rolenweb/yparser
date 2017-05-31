<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Keyword */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Keywords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keyword-view">

    

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            [
                'label' => 'Created',
                'value' => date("d/m/Y",$model->created_at),
            ],
            [
                'label' => 'Updated',
                'value' => date("d/m/Y",$model->updated_at),
            ],
        ],
    ]) ?>

    <p class="text-right">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <p>
        <?= GridView::widget([
            'dataProvider' => $provider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                [
                    'attribute'=>'countCompany',
                    'label' => 'Companies',
                    'content'=>function($data){
                        return $this->render('_companies',['data' => $data]);
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

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'view') {
                            $url = Url::to(['city/view','id' => $model->id]);
                            return $url;
                        }

                        if ($action === 'update') {
                            $url ='index.php?r=client-login/lead-update&id='.$model->id;
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url ='index.php?r=client-login/lead-delete&id='.$model->id;
                            return $url;
                        }

                    }

                ],
            ],
        ]); ?>
    </p>

</div>
