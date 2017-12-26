<?php

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \reketaka\nodes\models\NodesSearch
 */

use yii\helpers\Html;
use yii\grid\GridView;
use app\base\common\models\User;
use reketaka\nodes\Module;
use yii\helpers\Url;




?>



<div class="row">
    <div class="col-xs-12">
        <div class="x_panel">

			<div class="x_title">

				<?php if(User::canRoute(['base/create'])):
					echo Html::a(Module::t('app', 'create-new-page'), ['base/create', 'parent_id'=>$searchModel->parent_id], ['class'=>'btn btn-success']);
				endif; ?>

			</div>

            <div class="x_content">

				<?php


					$page = $searchModel->getPage();

					echo Html::tag('h4', $page?$page->title:Module::t('app', 'root_parent_page'));

					if($page){
						echo Html::a(Module::t('app', 'return_back'), ['base/index', Html::getInputName($searchModel, 'parent_id')=>$page->parent_id]);
					}


				?>


                <?= GridView::widget([
                	'filterModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                    	[
                    		'attribute' => 'id',
							'format' => 'raw',
							'content'=>function($model){
                				if($model->default){
                					return Html::tag('span', null, ['class'=>'glyphicon glyphicon-home']).' '.$model->id;
                                }

                                return $model->id;
                            }
						],
						'id',
//					    'parent_id',
                        'alias',
                        'title',
                        [
                        	'class' => 'yii\grid\ActionColumn',
							'template'=>'{showChildren} {create} {view} {update} {delete}',
							'buttons' => [
								'showChildren'=>function($url, $model, $key)use($searchModel){
									$button = Html::beginTag('a', ['href'=>Url::to(['base/index', Html::getInputName($searchModel, 'parent_id')=>$model->id])]);
									$button .= Html::tag('span', null, ['class'=>'glyphicon glyphicon-chevron-right']);
									$button .= Html::endTag('a');
									return $button;
								},
								'create'=>function($url, $model, $key){
                					$button = Html::beginTag('a', ['href'=>Url::to(['base/create', 'parent_id'=>$model->id])]);
                					$button .= Html::tag('span', null, ['class'=>'glyphicon glyphicon-plus']);
                					$button .= Html::endTag('a');
                					return $button;
								}
							],
							'visibleButtons' =>[
								'showChildren'=>function($model){
                                    /**
                                     * @var $model \reketaka\nodes\models\Nodes
                                     */
                					return $model->hasChildrens();
								},
                                'create'=>User::canRoute(['base/create']),
								'view'=>User::canRoute(['base/view']),
								'update'=>User::canRoute(['base/update']),
								'delete'=>User::canRoute(['base/delete'])
							]
						],
                    ],
                ]); ?>



            </div>
        </div>
    </div>
</div>

