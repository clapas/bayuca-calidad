<?php

if (YII_ENV_PROD) 
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'sqlsrv:Server=RIOSOLSRV1\RIOSOL;Database=calidad',
        'charset' => 'utf8',
    ];
else return [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=localhost;dbname=calidad',
        'username' => 'calidad',
        'password' => '`RR9d2L~X8y.tzmG',
        'charset' => 'utf8',
    ];
