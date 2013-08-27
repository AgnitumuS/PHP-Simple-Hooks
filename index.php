<?php
//Флаг использования плагинов
$plugin = true;
//подключаем библиотеку для хуков
include 'libs/core.lib.php';
include 'libs/interfaces.php';
include 'libs/eventhook.lib.php';
include 'libs/pm.lib.php';
// Инициализация менеджера плагинов, он будет следить за произходящими событиями
Core::init();
PM::init();
//Подключение плагина
if($plugin) {
  include 'modules/test/index.php';
}


//псевдо ядро системы 
class System{
  public function __construct(){
  global $plugin;
    /*
    Core::createHook('headerbefore',array(
    'test1' => time(),
    'test2' => date('Y'),
    'test3' => $plugin
    ));
    */
    //echo "...Шапка ядра...<br />";
    //echo "...Конец ядра...";
    Core::createHook('headerafter',array(
    'test1' => time(),
    'test2' => date('Y'),
    'test3' => $plugin
    ));
  }
}

//Эмуляция запуска ядра системы
$System = new System();
?>