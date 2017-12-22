Иерархическая структура сайта
=============================
Иерархическая структура сайта

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require reketaka/yii2-nodes-tree
```

or add

```
"reketaka/yii2-nodes-tree": "*"
```

Add to your web.php

```
'components' => [
        'urlManager' => [
            'class'=>'\reketaka\nodes\components\UrlManager'
        ]
    ]
    
'modules'=>[
	'nodes'=>[
            'class'=>'reketaka\nodes\Module',
            'controllerScanPathAr'=>[
                'namespace'=>'dir_path'
            ]
        ]
]
```

to the require section of your `composer.json` file.


