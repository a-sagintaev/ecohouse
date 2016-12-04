<?php
// стартуем сессию, подключаем настройки, классы и т.д. (грубо говоря ядро)
session_start();
include_once 'system/.init.php';

header('Content-Type: charset=utf-8');

// отлавливаем аякс
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    include_once SYSTEM.'ajaxData.php';
}

// обычный шаблон
else
{
    include_once ROOT.TEMPLATE.'html.php';
}

// закрывем все и выходим
$db->close();
exit;
?>