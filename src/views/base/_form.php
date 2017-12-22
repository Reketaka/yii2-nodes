<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reketaka\nodes\Module;

/* @var $this yii\web\View */
/**
 * @var $model reketaka\nodes\models\Nodes
 * @var $form yii\widgets\ActiveForm
 * @var $parent \reketaka\nodes\models\Nodes|false
 * @var $controllers_methods []
 */

/**
 * @var $nodeModule Module
 */
$nodeModule = Yii::$app->getModule('nodes');
?>

<div class="nodes-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php if($parent):?>
		<?=Html::hiddenInput(Html::getInputName($model, 'parent_id'), $parent->id)?>
		<div class="form-group">
			<p><b><?=Module::t('app', 'parent_name')?>:</b> <?=Html::a($parent->title, ['base/view', 'id'=>$parent->id])?></p>
		</div>
	<?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'primary_key')->textInput() ?>

	<?=$form->field($model, 'controller_method')->dropDownList($controllers_methods)?>

	<?php if($nodeModule->canEditDefaultNode()):?>
		<?=$form->field($model, 'default')->checkbox()?>
	<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('app', 'create') : Module::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>