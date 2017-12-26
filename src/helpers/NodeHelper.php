<?php

namespace reketaka\nodes\helpers;


use reketaka\nodes\models\Nodes;
use reketaka\nodes\Module;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\web\Request;

class NodeHelper{

    /**
     * Возвращает все доступные контроллеры и их методы которые могут отвечать за обработку Node
     * @return array
     */
    public static function getControllerMethods(){
        $controllerPathAr = Yii::$app->getModule('nodes')->controllerScanPathAr;

        $ourControllerAr = [];

        foreach($controllerPathAr as $namespace=>$path){
            $path = Yii::getAlias($path);
            $files = FileHelper::findFiles($path, [
                'filter'=>function($v){
                    return substr_count(basename($v), "Controller.php");
                }
            ]);


            foreach($files as $file) {

                $className = basename($file);
                $className = substr($className, 0, -4);

                $controller = Yii::createObject($namespace.'\\'.$className, [null, null]);

                $class = new \ReflectionClass($controller);
                foreach ($class->getMethods() as $method)
                {
                    $name = $method->getName();
                    if ( $method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions' )
                    {
//                        $comment = trim($method->getDocComment(), '/');
//                        $comment = str_replace('*', '', $comment);
                        $ourControllerAr[$className.'/'.$name] = $className.'/'.$name;
                    }
                }


            }


        }

        return $ourControllerAr;
    }


    /**
     * Возвращает Node из текущего Request
     * @param Request $request
     * @return bool|null|static
     */
    public static function getNodeFromRequest(Request $request){
        /**
         * @var $request Request
         */
        $requestAr = trim($request->getPathInfo(), '/');
        if(empty($requestAr)){
            $defaultNode = Nodes::findOne(['default'=>1]);

            if(!$defaultNode){
                throw new Exception(Module::t('errors', 'not_find_default_node'));
            }

            return $defaultNode;
        }

        $requestAr = explode("/", $requestAr);

        $parent = false;
        $lastKey = count($requestAr)-1;
        $node = false;
        foreach($requestAr as $key=>$part){

            $parent = Nodes::findOne([
                'parent_id'=>!$parent?0:$parent->id,
                'alias'=>$part
            ]);

            if(!$parent){
                break;
            }

            if($lastKey == $key){
                $node = $parent;
                break;
            }
        }

        return $node;
    }

}