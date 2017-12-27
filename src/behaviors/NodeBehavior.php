<?php

namespace reketaka\nodes\behaviors;


use Yii;
use reketaka\nodes\models\Nodes;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class NodeBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_DELETE=>'beforeDelete'
        ];
    }

    public function beforeDelete($event){
        $this->deleteAllChildrens();
    }

    public function beforeUpdate($event)
    {
        /**
         * @var $node Nodes
         */
        $node = $event->sender;

        $this->uncheckDefaultElement($node);

    }

    public function beforeInsert($event)
    {
        /**
         * @var $node Nodes
         */
        $node = $event->sender;

        $this->uncheckDefaultElement($node);

    }

    /**
     * Удалит всех потомков на всех уровнях вложенности
     */
    public function deleteAllChildrens(){
        $childrens = $this->owner->getChildrens(0);
        if(!$childrens){
            return true;
        }
        $childrens = ArrayHelper::getColumn($childrens, 'id');

        Nodes::deleteAll("id IN (".implode(',', $childrens).")");
    }

    /**
     * При указание страницы по умолчанию снимает галочку у старой страницы
     */
    public function uncheckDefaultElement(Nodes $node)
    {
        if(!Yii::$app->getModule('nodes')->canEditDefaultNode()){
            return false;
        }

        if (!$node->default) {
            return false;
        }

        Nodes::updateAll(['default' => 0], '`default` = 1');

        return true;
    }


}