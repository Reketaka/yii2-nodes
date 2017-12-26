<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace reketaka\nodes\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NodeAssets extends AssetBundle
{
    public $sourcePath = '@reketaka/nodes/assets/';

    public $css = [
        //        'assets/css/site.css',
    ];
    public $js = [
        'js/nodes.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
