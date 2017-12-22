<?php

namespace reketaka\nodes\models;

use reketaka\nodes\behaviors\AliasBehavior;
use reketaka\nodes\behaviors\NodeBehavior;
use Yii;
use reketaka\nodes\Module;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nodes".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $alias
 * @property string $title
 * @property integer $primary_key
 * @property string $controller_method
 * @property integer $default
 */
class Nodes extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
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
            [['parent_id'], 'default', 'value' => 0],
            [['title'], 'string', 'max' => 255],
            [['default'], 'integer'],
            [['default'], 'default', 'value' => 0],
            [['controller_method'], 'string'],
            [['primary_key'], 'required']
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
            'controller_method' => Module::t('app', 'controller_method'),
            'default' => Module::t('app', 'is_default'),
            'link' => Module::t('app', 'link')
        ];
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
        while ($parentId && $parent = self::findOne($parentId)) {
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
        return empty($this->getChildrens(1));
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


}
