<?php

use yii\helpers\Html;
use reketaka\nodes\Module;

/* @var $this yii\web\View */
/**
 * @var $model reketaka\nodes\models\Nodes
 * @var $parent integer
 * @var $controllers []
 */


?>
<div class="row">
    <div class="col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <?=Html::a(Module::t('app', 'return_back'), ['base/index'], ['class'=>'btn btn-info'])?>
            </div>

            <div class="x_content">

                <?= $this->render('_form', [
                    'model' => $model,
                    'parent'=>$parent,
                    'controllers'=>$controllers
                ]) ?>

            </div>
        </div>
    </div>
</div>

