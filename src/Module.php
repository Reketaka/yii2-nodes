<?php


namespace reketaka\nodes;

use yii\base\Module as BaseModule;
use Yii;
use yii\db\Exception;

class Module extends BaseModule{

    public $controllerNamespace = 'reketaka\nodes\controllers';

    /**
     * [
     *  'app\base\subsystems\user\controllers'=>'@app/base/subsystems/user/controllers'
     * ]
     *
     * @var array
     */
    public $controllerScanPathAr = [];

    public function init(){
        if(!$this->controllerScanPathAr) {
            throw new \yii\base\Exception(self::t('errors', 'controllersScanPathAr_empty'));
        }
        parent::init();
    }
    public static function t($category, $message, $params = [], $language = null)
    {
        if ( !isset(Yii::$app->i18n->translations['modules/nodes/*']) )
        {
            Yii::$app->i18n->translations['modules/nodes/*'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'basePath'       => '@reketaka/nodes/messages',
                'fileMap'        => [
                    'modules/nodes/app' => 'app.php',
                    'modules/nodes/errors'=>'errors.php',
                    'modules/nodes/titles'=>'titles.php'
                ],
            ];
        }
        return Yii::t('modules/nodes/' . $category, $message, $params, $language);
    }

}