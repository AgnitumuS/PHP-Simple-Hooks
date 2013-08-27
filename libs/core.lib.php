<?php
class Core {

  
  private function __construct() {
   
  }
  
  public static function init() {
  
  }
  
  public static function addAction($hookName, $userFunction, $countArg = 0, $priority = 10) {
    PM::registration(new EventHook($hookName, $userFunction, $countArg, $priority * 10));
  }
  
  public static function createHook($hookName) {

    // Вариант 1. createHook('userFunction');
    $arg = array();
    $result = false;

    // Вариант 2. createHook('userFunction', $args);
    //  Не удалять, он работает.
    //  Для случая:
    //    createHook(__CLASS__."_".__FUNCTION__, $title);
    //    mgAddAction('mg_titlepage', 'myTitle', 1);
    if (func_num_args() == 2) {
      $arg = func_get_args();
      $arg = $arg[1];
    }

    // Вариант 3. return createHook('thisFunctionInUserEnviroment', $result, $args);
    if (func_num_args() == 3) {
      $arg = func_get_args();
      $result = isset($arg[1]) ? true : false;
      if ($result) {
        $argumets = array(
          'result' => $arg[1],
          'args' => $arg[2]
        );
        $arg = $argumets;
      }
    }
	
	
    if ($result) {
      return PM::createHook($hookName, $arg, $result);
    }
	
	
	
    PM::createHook($hookName, $arg, $result);
  }
}
?>