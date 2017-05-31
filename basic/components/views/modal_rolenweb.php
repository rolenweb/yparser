<?php
use yii\helpers\Html;

echo Html::button($btnTitle,$btnOptions);

echo Html::beginTag('div',[
		'class' => 'modal fade',
		'id' => (empty($modalOptions['id']) === false) ? $modalOptions['id'] : 'modal',
		'tabindex' => '-1',
		'role' => 'dialog',
		'aria-labelledby' => (empty($modalOptions['aria-labelledby']) === false) ? $modalOptions['aria-labelledby'] : 'myModalLabel',
	]);
	echo Html::beginTag('div',[
			'class' => (empty($modalOptions['modal-dialog']) === false) ? $modalOptions['modal-dialog'] : 'modal-dialog', 'role' => 'document'
		]);
		echo Html::beginTag('div',[
			'class' => (empty($modalOptions['modal-content']) === false) ? $modalOptions['modal-content'] : 'modal-content',
		]);
			echo Html::beginTag('div',[
					'class' => (empty($modalOptions['modal-header']) === false) ? $modalOptions['modal-header'] : 'modal-header'
				]);
				echo Html::button(Html::tag('span','&times;',['aria-hidden' => 'true']),['class' => 'close','data-dismiss' => 'modal','aria-label' => 'Close']);
				echo Html::tag('h4',(empty($modalOptions['title']) === false) ? $modalOptions['title'] : 'Modal Title',['class' => (empty($modalOptions['modal-title']) === false) ? $modalOptions['modal-title'] : 'modal-title']);
			echo Html::endTag('div');
			echo Html::beginTag('div',[
					'class' => (empty($modalOptions['modal-body']) === false) ? $modalOptions['modal-body'] : 'modal-body',
				]);
				echo (empty($modalOptions['content']) === false) ? $modalOptions['content'] : null;
			echo Html::endTag('div');
		echo Html::endTag('div');
	echo Html::endTag('div');
echo Html::endTag('div');
?>
