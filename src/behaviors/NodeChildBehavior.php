<?php


namespace reketaka\nodes\behaviors;

use reketaka\nodes\models\Nodes;
use reketaka\nodes\models\NodesControllerCatalog;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class NodeChildBehavior extends Behavior{

    /**
     * Имя поведения
     * @var string
     */
    const NAME = 'NodeChildBehavior';

    /**
     * Имя поля из которого будет браться title для Node
     * может принимать название атрибута или анономню функцию
     * 'nodeTitle'=>'name'
     * 'nodeTitle'=>function($owner){ return 'title';  }
     * @var
     */
    public $nodeTitle;

    /**
     * Контроллер который отвечает за обработку этой Node
     * @var
     */
    public $nodeController;

    /**
     * Родительская Node на каком уровне создать Node
     * @var int|NodesControllerCatalog
     */
    public $nodeParent = 0;

    /**
     * При создании модели создавать Node на указанном уровне
     * @var bool
     */
    public $eventCreate = false;

    public $eventUpdate = false;

    public $eventDelete = false;

    public function events()
    {
        $parent = parent::events();

        $events = [];

        if($this->eventCreate){
            $events[ActiveRecord::EVENT_AFTER_INSERT] = function($event){
                $event->sender->createNode();
            };
        }

        if($this->eventDelete){
            $events[ActiveRecord::EVENT_BEFORE_DELETE] = function($event){
                if($node = $event->sender->node){
                    $node->delete();
                }
            };
        }

        return array_merge($parent, $events);
    }

    /**
     * Возвращает контроллер который отвечает за обработку элемента этой Node
     * @return NodesControllerCatalog
     */
    public function getControllerNode(){
        if(is_callable($this->nodeController)){
            $f = $this->nodeController;
            return $f();
        }

        return $this->nodeController;
    }

    /**
     * Устанавливает контроллер обрбаотчик элемента Node
     * @param NodesControllerCatalog $controller
     */
    public function setControllerNode(NodesControllerCatalog $controller){
        $this->nodeController = $controller;
    }

    /**
     * Возвращает title для node
     * @return mixed
     */
    public function getTitleNode(){
        if(is_callable($this->nodeTitle)){
            $f = $this->nodeTitle;
            return $f($this->owner);
        }

        return $this->owner->{$this->nodeTitle};
    }

    public function getParentNode(){
        if($this->nodeParent == Nodes::ROOT_ID){
            return Nodes::ROOT_ID;
        }

        if($this->nodeParent instanceof NodesControllerCatalog){
            return $this->nodeParent->id;
        }

       if($this->nodeParent){
            return $this->nodeParent->id;
       }

        return Nodes::ROOT_ID;
    }

    /**
     * Создает Nodes на основании модели потомка, если Nodes уже существует вернет её модель
     * @return array|bool|null|Nodes|\yii\db\ActiveRecord
     */
    public function createNode(){
        return Nodes::create($this->owner);
    }

    /**
     * Возвращает Nodes
     * @return array|null|Nodes|\yii\db\ActiveRecord
     */
    public function getNode(){
        return Nodes::get($this->owner);
    }

    /**
     * Возвращает Url от корня
     * /example/test
     * @return array|string
     */
    public function getUrl(){
        return $this->getNode()->getUrl();
    }
}