<?php

namespace reketaka\nodes\behaviors;


use reketaka\nodes\models\NodesControllerCatalog;
use Yii;
use reketaka\nodes\models\Nodes;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class NodesControllerCatalogBehavior extends Behavior
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
     * При указание страницы по умолчанию снимает галочку у старого контроллера
     */
    public function uncheckDefaultElement(NodesControllerCatalog $node)
    {
        if (!$node->default) {
            return false;
        }

        NodesControllerCatalog::updateAll(['default' => 0], '`default` = 1');

        return true;
    }


}