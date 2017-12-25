<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel reketaka\nodes\models\NodesControllerCatalogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="row">
	<div class="x_panel">

		<div class="x_title"></div>

		<div class="x_content">

			<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

			<p>
				<?= Html::a('Create Nodes Controller Catalog', ['create'], ['class' => 'btn btn-success']) ?>
			</p>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],

					'id',
					'path',

					['class' => 'yii\grid\ActionColumn'],
				],
			]); ?>

		</div>

	</div>
</div>

