<?php


namespace reketaka\nodes\components;

use reketaka\nodes\helpers\NodeHelper;
use reketaka\nodes\models\Nodes;
use reketaka\nodes\models\NodesControllerCatalog;
use reketaka\nodes\Module;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\Application;
use yii\web\Request;
use Yii;
use yii\web\UrlManager as UrlManagerYii;

class UrlManager extends UrlManagerYii{

    public function parseRequest($request){


        /**
         * @var $node Nodes
         */
        $node = NodeHelper::getNodeFromRequest($request);

        if(!$node){
            return parent::parseRequest($request);
        }

        $uri = trim($request->getPathInfo(), '/');

        if($node->default && !empty($uri)){
            Yii::$app->getResponse()->redirect(Url::home())->send();
            exit();
        }

        $controller = NodesControllerCatalog::getDb()->cache(function()use($node){
            return NodesControllerCatalog::findOne(['id'=>$node->controller_id]);
        });

        if(!$controller){
            throw new Exception(Module::t('errors', 'not_select_controller_in_node'));
        }

        list($controller, $method) = explode('/', $controller->path);

        $ar = [];
        $ar['controller'] = str_replace('Controller', '', $controller);
        $ar['method'] = strtolower(str_replace('action', '', $method));

        foreach($ar as $key=>$val) {
            $ar[$key] = preg_split('/(?=[A-Z])/', $val);

            if(!is_array($ar[$key])){
                continue;
            }

            $ar[$key] = array_filter($ar[$key]);
            $ar[$key] = array_map(function ($v) {
                return strtolower($v);
            }, $ar[$key]);
            $ar[$key] = implode('-', $ar[$key]);
        }

        $params = $request->getQueryParams();

        if($model = $node->model){
            $params['model'] = $model;
        }else{
            $params['model'] = $node;
        }

        Yii::$app->view->title = $node->title;

        return [$ar['controller'].'/'.$ar['method'], $params];
    }

    public function getNode(){
        return NodeHelper::getNodeFromRequest();
    }
}




