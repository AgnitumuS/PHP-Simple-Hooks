<?php

//Интерфейс для слушателя
interface Hook {
  /**
   * Запускает пользовательскую функцию, передав в нее полученные параметры
   * @param type $arg - параметры переданные при инициализации события.
   */
  function run($arg);
}


/**
 * Интерфейс PluginManager - для класса PM.
 */
interface PluginManager {

  /**
   * Регистрирует пользовательскую функцию в качетсве обработчика для события.
   * @param Hook $hook - объект содержащий информаццию о привязке 
   * пользовательской функции к событию, которое может произойти. 
   */
  static function registration(Hook $hook);

  /**
   * Удаляет обработчика.
   * @param Hook $hook - объект содержащий информаццию о привязке 
   * пользовательской функции к событию, которое может произойти. 
   */
  static function delete(Hook $hook);

  
  /**
   * Создает хук.
   * @param Hook $hook - объект содержащий информаццию о привязке 
   * пользовательской функции к событию, которое может произойти. 
   */
  static function createHook($hookName, $arg);
}
?>