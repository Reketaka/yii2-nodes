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


$this->registerJs("
    $('#delete_chosen').click(function(e){
        e.preventDefault();
        
        var ids = $('#nodes_table').yiiGridView('getSelectedRows');
        
        if(ids == ''){
            return;
        }
        
        $.getJSON('".Url::to(['base/delete-multiple'])."?ids='+ids, function(data){
            if(data.success){
                window.location.reload();
                return;
            }
        });
    });
", \yii\web\View::POS_END);

$model = \app\base\common\models\CertificatesItems::findOne(1);

var_Dump($model->getNode()->url);

//var_Dump($model);
//var_Dump($model->createNode());

#$model->createNode();
//exit();

//var_Dump($model->beh('NodeChildBehavior'));
//exit();

//$model->createNode();


//$controller = \reketaka\nodes\models\NodesControllerCatalog::findOne(2);

//\reketaka\nodes\models\Nodes::create($model, false, $controller);

?>



<div class="row">
    <div class="col-xs-12">
        <div class="x_panel">

			<div class="x_title">

				<div class="btn-group">
					<?php if(User::canRoute(['base/create'])):
						echo Html::a(Module::t('app', 'create-new-page'), ['base/create', 'parent_id'=>$searchModel->parent_id], ['class'=>'btn btn-success']);
					endif; ?>
					<?php if(User::canRoute(['base/delete-multiple'])):
						echo Html::a(Module::t('app', 'delete_chosen'), ['delete-multiple'], ['class'=>'btn btn-danger', 'id'=>'delete_chosen']);
					endif; ?>
				</div>

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
                    'id'=>'nodes_table',
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'id'
                        ],
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

