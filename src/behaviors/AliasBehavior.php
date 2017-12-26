<?php


namespace reketaka\nodes\behaviors;


use reketaka\nodes\models\Nodes;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Inflector;

class AliasBehavior extends Behavior{

    public $level_id;
    public $alias;
    public $title;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT=>'setSlug'
        ];
    }

    public function setSlug($event){

        /**
         * @var $sender Nodes
         */
        $sender = $event->sender;

        if(!$sender->isNewRecord){
            return;
        }


        $sender->{$this->alias} = Inflector::slug(TransliteratorHelper::process($sender->{$this->title}), '-', true);

        for($suffix=2;!$this->checkUniqAlias($sender);$suffix++){
            $sender->{$this->alias} = $sender->{$this->alias}.'-'.$suffix;
        }



    }

    /**
     * Проверяет на уникальность алиас у модели в её уровне
     * true если уникальный false если нет
     * @param $model
     * @param $alias
     * @return bool
     */
    public function checkUniqAlias($model){

        $r = $model::find()->where([
            $this->level_id=>$model->{$this->level_id},
            $this->alias=>$model->{$this->alias}
        ])->one();

        return is_null($r);

    }
}