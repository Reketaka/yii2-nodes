<?php


namespace reketaka\nodes;

use reketaka\nodes\models\Nodes;
use yii\base\Module as BaseModule;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;

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

    /**
     * Запрещает любые операции с корневой страницей перемещение изменение удаление
     * @var bool
     */
    public $disableChangeRootPage = false;


    /**
     * Устанавливает домашную страницу можно использовать когда сайт поделен на две части админ и клиент
     * @var bool
     */
    public $homePage = false;

    /**
     * Поведения которые будут подцепляться к контроллерам расширения
     * @var array
     */
    public $behaviorsController = [
        'ghost-access'=> [
            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        ]
    ];

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

    /**
     * Если разрешенно редактирование атрубута default у node то всегда true
     * если нет то ращрешенно только первый раз редактирвоание
     */
    public function canEditDefaultNode(){
        if(!$this->disableChangeRootPage){
            return true;
        }

        return empty(Nodes::find()->where(['default'=>1])->all());
    }

    /**
     * Возвращает адрес до домашней страницы проекта
     * @return string
     */
    public function getHomePage(){
        if(!$this->homePage){
            return Url::home();
        }

        return Url::to($this->homePage);
    }
}