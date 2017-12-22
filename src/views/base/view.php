<?php

/**
 * @var $this \yii\web\View
 * @var $model \reketaka\nodes\models\Nodes
 */

use yii\helpers\Html;
use reketaka\nodes\Module;
use yii\widgets\DetailView;
use app\base\common\models\User;


?>


<div class="row">
    <div class="col-xs-12">
        <div class="x_panel">
            <div class="x_title">
				<div class="btn-group">
                	<?=Html::a(Module::t('app', 'return_back'), ['base/index'], ['class'=>'btn btn-success'])?>
					<?php

					if(User::canRoute(['base/update'])):
						echo Html::a(Module::t('app', 'edit'), ['base/update', 'id'=>$model->id], ['class'=>'btn btn-info']);
					endif;

					if(User::canRoute(['base/delete'])):
						echo Html::a(Module::t('app', 'delete'), ['base/delete', 'id'=>$model->id], [
							'class'=>'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'get',
                            ]
						]);
					endif;



					?>
				</div>
            </div>


            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <?=DetailView::widget([
                            'model'=>$model,
                            'attributes' => [
                                'id',
                                [
                                    'label'=>$model->getAttributeLabel('parent_id'),
                                    'value'=>function($model){

                                        return $model->parent_id;

                                    }
                                ],
                                'alias',
                                'title',
								'controller_method',
								[
									'label'=>$model->getAttributeLabel('default'),
									'format'=>'raw',
									'value'=>function($model){
                        				if($model->default){
                        					return Html::tag('span', null, ['class'=>'glyphicon glyphicon-ok']);
                                        }
                                        return Html::tag('span', null, ['class'=>'glyphicon glyphicon-remove']);
									}
								],
								[
									'label'=>$model->getAttributeLabel('link'),
									'format'=>'raw',
									'value'=>function($model){
                        				return Html::a($model->url, $model->url, ['target'=>'_blank']);
									}
								]
                            ]
                        ])

                        ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>


