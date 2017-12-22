<?php


namespace reketaka\nodes\components;

use reketaka\nodes\helpers\NodeHelper;
use reketaka\nodes\models\Nodes;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\Request;
use Yii;
use yii\web\UrlManager as UrlManagerYii;

class UrlManager extends UrlManagerYii{


    public function parseRequest($request){


        /**
         * @var $node Nodes
         */
        $node = NodeHelper::getNodeFromRequest($request);


        if($node->default && !empty($request->getPathInfo())){
            Yii::$app->getResponse()->redirect(Url::home())->send();
            exit();
        }

        list($controller, $method) = explode('/', $node->controller_method);

        $controller = strtolower(str_replace('Controller', '', $controller));
        $method = strtolower(str_replace('action', '', $method));

        $params = $request->getQueryParams();
        $params['node'] = $node;

        return [$controller.'/'.$method, $params];
    }
}




