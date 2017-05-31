<?php
use yii\helpers\Html;
use app\components\ModalRolenweb;

echo ModalRolenweb::widget(
	[
		'btnTitle' => $data->countCompany,
		'btnOptions' => [
			'class' => 'btn btn-default spinner',
			'data-target' => '#'.$data->id,
		],
		'modalOptions' => [
			'id' => $data->id,
			'aria-labelledby' => null,
			'modal-dialog' => 'modal-dialog modal-lg',
			'modal-content' => 'modal-content',
			'modal-header' => 'modal-header',
			'title' => $data->name.': companies',
			'modal-title' => null,
			'modal-body' => null,
			'content' => null,//$this->render('_companies_data',['data' => $data])
		]
	]
);
?>

