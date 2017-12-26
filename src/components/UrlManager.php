<?php


namespace reketaka\nodes\components;

use reketaka\nodes\helpers\NodeHelper;
use reketaka\nodes\models\Nodes;
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

        if(!$node->controller){
            throw new Exception(Module::t('errors', 'not_select_controller_in_node'));
        }

        list($controller, $method) = explode('/', $node->controller->path);

        $controller = str_replace('Controller', '', $controller);
        $method = strtolower(str_replace('action', '', $method));

        $controller = preg_split('/(?=[A-Z])/', $controller);
        $controller = array_filter($controller);
        $controller = array_map(function($v){
            return strtolower($v);
        }, $controller);
        $controller = implode('-', $controller);

        $params = $request->getQueryParams();
        $params['node'] = $node;

        return [$controller.'/'.$method, $params];
    }
}




