<?php

/**
 * Класс PM - плагин менеджер, управляет плагинами и
 * регистрирует их. Устанавливает взаимодействие пользовательских функций с системой.
 */
class PM implements PluginManager {

  static private $_instance = null;

  // Заристрированные обработчики хуков
  static private $_eventHook;

  // Список зарегистрированных шорткодов

  public function __construct() {
    self::$_eventHook = array();
  }
  
  private function __clone() {

  }


  private function __wakeup() {

  }


  /**
   * Возвращет единственный экземпляр данного класса
   * @return object - объект класса PM
   */
  public static function getInstance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  /**
   * Возвращает массив названий зарегистрированных хуков.
   * @return array -  массив названий зарегистрированных хуков.
   */
  public static function getListNameHooks() {
    $result = array();
      if (sizeof(self::$_eventHook)) {
        foreach (self::$_eventHook as $eventHook) {
          $result[] = strtolower($eventHook->getHookName());
        }
      }
     return $result;
  }


  /**
   * Проверяет зарегистрирован ли хук.
   * @param $hookname - имя хука, который надо проверить на регистрацию.
   * @return bool.
   */
  public static function isHookInReg($hookname) {
     return in_array(strtolower($hookname), self::getListNameHooks());
  }


  /**
   * Инициализирует объект данного класса
   */
  public static function init() {
    self::getInstance();
  }


  /**
   * Регистрирует обработчик для действия, занося его в реестр обработчиков.
   * @param Hook $eventHook - объект содержащий информацию об обработчике и событии.
   */
  public static function registration(Hook $eventHook) {
    self::$_eventHook[] = $eventHook;
  }


  /**
   * Удаляет из рееестра данные об обработчике.
   * @param Hook $eventHook  - объект содержащий информацию об обработчике и событии.
   */
  public static function delete(Hook $eventHook) {
    if ($id = array_search($eventHook, self::$_eventHook, TRUE)) {
      unset(self::$_eventHook[$id]);
    }
  }


  /**
   * Вычисляет приоритетность пользовательских функций, назначеных на обработку одного и тогоже события.
   * Используется для сравнения приоритетов в функции.
   *
   * @param $a - приоритет текущей функции.
   * @param $a - приоритет предыдущей функции usort  в методе 'PM::createHook'.
   */
  public static function prioritet($a, $b) {
    return $a['priority'] - $b['priority'];
  }


  /**
   * Инициализирует то или иное событие в коде программы,
   * сообщая об этом всем зарегистрированных обработчикам.
   * Если существуют обработчики назначенные на данное событие,
   * то запускает их пользовательские функции, в порядке очереди
   * определенной приоритетами.
   *
   * @param string $hookName - название  события.
   * @param type $arg - массив арументов.
   * @param type $result - флаг, определяющий, должена ли пользовательская
   *   функция вернуть результат для дальнейшей работы в месте инициализации события.
   * @return array
   */
  public static function createHook($hookName, $arg, $result = false) {
    $hookName = strtolower($hookName);

    if ($result) {
    
      if (sizeof(self::$_eventHook)) {
        foreach (self::$_eventHook as $eventHook) {

          // Если нашлись пользовательские функции которые хотя обработать событие.
          if ($eventHook->getHookName() == $hookName
            && $eventHook->getCountArg() == 1) {
		
            // В массив найденых обработчиков записываем все обработчики и их порядок выполнения.
            $handleEventHooks[] = array(
              'eventHook' => $eventHook,
              'priority' => $eventHook->getPriority()
            );
          }
        }


        // Запускает функции всех подходящих обработчиков.
        if (!empty($handleEventHooks)) {

          // Сортировка в порядке приоритетов.
          usort($handleEventHooks, array(__CLASS__, "prioritet"));

          foreach ($handleEventHooks as $handle) {
            $arg['result'] = $handle['eventHook']->run($arg);
          }
          return $arg['result'];
        }
      }
      return $arg['result'];
    } else {
      // для варианта  создания хука: createHook(__CLASS__."_".__FUNCTION__, '$title');

      $countArg = count($arg);
      if (sizeof(self::$_eventHook)) {
        foreach (self::$_eventHook as $eventHook) {

          if ($eventHook->getHookName() == $hookName
            && $eventHook->getCountArg() == $countArg) {

            $eventHook->run($arg);
          }
        }
      }
    }
  }

}
