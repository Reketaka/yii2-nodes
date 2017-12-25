<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reketaka\nodes\models\NodesControllerCatalog */

?>
<div class="row">
	<div class="x_panel">

		<div class="x_title"></div>

		<div class="x_content">


			<p>
				<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				<?= Html::a('Delete', ['delete', 'id' => $model->id], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => 'Are you sure you want to delete this item?',
						'method' => 'post',
					],
				]) ?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'path',
					[
						'label'=>$model->getAttributeLabel('default'),
						'format'=>'raw',
						'value'=>function($model){
                            if($model->default){
                                return Html::tag('span', null, ['class'=>'glyphicon glyphicon-ok']);
                            }
                            return Html::tag('span', null, ['class'=>'glyphicon glyphicon-remove']);
						}
					]
				],
			]) ?>

		</div>

	</div>
</div>
