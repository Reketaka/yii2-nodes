<?php

namespace reketaka\nodes\models;

use reketaka\nodes\behaviors\AliasBehavior;
use reketaka\nodes\behaviors\NodeBehavior;
use Yii;
use reketaka\nodes\Module;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\Module as DateModule;
use reketaka\nodes\behaviors\NodeChildBehavior;

/**
 * This is the model class for table "nodes".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $alias
 * @property string $title
 * @property integer $primary_key
 * @property string $controller_id
 * @property integer $default
 * @property string $model_class
 */
class Nodes extends \yii\db\ActiveRecord
{

    const ROOT_ID = 0;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update']
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => Yii::$app->formatter->asDate('now', Yii::$app->params['dateControlSave'][DateModule::FORMAT_DATETIME]),
            ],
            [
                'class' => AliasBehavior::className(),
                'level_id' => 'parent_id',
                'alias' => 'alias',
                'title' => 'title'
            ],
            [
                'class' => NodeBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'primary_key'], 'integer'],
            [['model_class'], 'string'],
            [['parent_id'], 'default', 'value' => self::ROOT_ID],
            [['title'], 'string', 'max' => 255],
            [['title'], 'default', 'value' => 'test'],
            [['default'], 'integer'],
            [['default'], 'default', 'value' => 0],
            [['controller_id'], 'integer'],
            [['controller_id'], 'exist', 'skipOnError' => false, 'targetClass' => NodesControllerCatalog::className(), 'targetAttribute' => ['controller_id' => 'id']],
//            [['primary_key', 'model_class'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('app', 'ID'),
            'parent_id' => Module::t('app', 'parent_page'),
            'alias' => Module::t('app', 'alias'),
            'title' => Module::t('app', 'title'),
            'primary_key' => Module::t('app', 'primary_key'),
            'controller_id' => Module::t('app', 'controller_method'),
            'default' => Module::t('app', 'is_default'),
            'link' => Module::t('app', 'link')
        ];
    }

    public function getController(){
        return $this->hasOne(NodesControllerCatalog::className(), ['id'=>'controller_id']);
    }

    /**
     * Возвращает родителя если таковой имеется
     *
     * @return boolean|self
     */
    public function getParent()
    {
        if (!$this->parent_id) {
            return null;
        }

        return self::findOne($this->parent_id);
    }

    /**
     * Возвращает полный Url без учета домена сайта
     *
     * @return array|string
     */
    public function getUrl()
    {
        $fullUrl   = [];
        $fullUrl[] = $this->alias;

        $parentId = $this->parent_id;
        while ($parentId && $parent = self::getDb()->cache(function()use($parentId){ self::findOne($parentId); })) {
            array_unshift($fullUrl, $parent->alias);
            $parentId = $parent->parent_id;
        }

        $fullUrl = Yii::$app->urlManager->baseUrl . '/' . implode('/', $fullUrl);

        return $fullUrl;
    }

    /**
     * Говорит есть ли дети у элемента
     *
     * @return bool
     */
    public function hasChildrens()
    {
        return !empty($this->getChildrens(1));
    }

    /**
     * Возвращает всех детей ноды на указанный глубины уровень, где ключом массива является id Node
     * указание $level = 0 вернет всех детей на всей глубине иерархии
     *
     * @return Nodes[]|[]
     */
    public function getChildrens($level = 1)
    {

        $result = $this->getChild($this, $level);


        return $result;
    }

    /**
     * Функция помошник
     */
    private function getChild(Nodes $node, $level = 1)
    {
        static $aLevel = 0;

        $items = self::find()->where([
            'parent_id' => $node->id
        ])->all();

        if (!$items) {
            return $items;
        }

        $items = ArrayHelper::index($items, function ($elem) {
            return $elem->id;
        });

        $aLevel++;

        if ($aLevel == $level && $level != 0) {
            return $items;
        }

        foreach ($items as $item) {
            if ($childs = $item->getChild($item, $aLevel)) {
                $items += $childs;
            }
        }

        return $items;
    }

    /**
     * Возвращает Node для указанной модели если таковая существует
     * @param $model
     * @return array|null|Nodes|ActiveRecord
     */
    public static function get($model){
        return self::getDb()->cache(function()use($model){
            return self::find()
                ->where([
                    'model_class'=>$model::className(),
                    'primary_key'=>$model->getPrimaryKey()
                ])
                ->one();
        });
    }

    public static function create($model){

        if($checkNode = self::get($model)){
            return $checkNode;
        }

        /**
         * @var $model ActiveRecord
         */
        if(!$model->getBehavior(NodeChildBehavior::NAME)){
            return false;
        }

        $node = new self();

        $node->primary_key = $model->getPrimaryKey();
        $node->model_class = $model::className();


        $node->title = $model->titleNode;
        $node->parent_id = $model->parentNode;
        $node->controller_id = $model->controllerNode->id;


        $node->save();

        return $node;
    }

    public function getModel(){
        $modelClass = $this->model_class;

        if(!class_exists($modelClass)){
            return null;
        }

        return $modelClass::findOne($this->primary_key);

    }

}
