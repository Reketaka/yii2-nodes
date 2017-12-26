<?php

namespace reketaka\nodes\behaviors;


use Yii;
use reketaka\nodes\models\Nodes;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class NodeBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert'
        ];
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