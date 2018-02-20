<?php


namespace reketaka\nodes\behaviors;


use reketaka\nodes\models\Nodes;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Inflector;

class AliasBehavior extends Behavior{

    public $alias = 'alias';
    public $title = 'title';
    public $callbackUniqAlias;
    public $event = ActiveRecord::EVENT_BEFORE_INSERT;
    public $active;

    public function events()
    {
        return [
            $this->event=>'setSlug'
        ];
    }

    public function setSlug($event){

        /**
         * @var $sender Nodes
         */
        $sender = $event->sender;

        if(empty($sender->{$this->alias})) {
            $sender->{$this->alias} = Inflector::slug(TransliteratorHelper::process($sender->{$this->title}), '-', true);
        }

        if(!is_callable($this->callbackUniqAlias)){
            return true;
        }

        $callback = $this->callbackUniqAlias;

        for ($suffix = 2; !$callback($sender); $suffix++) {
            $sender->{$this->alias} = $sender->{$this->alias} . '-' . $suffix;
        }

    }
}