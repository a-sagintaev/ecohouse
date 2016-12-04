<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04.12.2016
 * Time: 11:48
 */

// настройки проекта
include_once 'configs.php';

// класс работы с БД и его иницаилизация
include_once SYSTEM.'mysql.php';
$db = new MySQL();

// функции проекта
include_once SYSTEM.'functions.php';