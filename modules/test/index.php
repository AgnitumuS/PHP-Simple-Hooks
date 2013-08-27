<?php
class plugin {
//пользовательская функция выполняющаяся при определенном событии
function getHead(){

  echo "<br/> >>>Начало $test1 $test2 $test3 Шапки<<<";
}

function afterHead(){
  echo "<br/> >>>Конец Шапки<<<";
  print_r(func_get_args());
}
}

class plugin2 {
//пользовательская функция выполняющаяся при определенном событии
function getHead(){

  echo "<br/> >>>Начало $test1 $test2 $test3 Шапки<<<";
}

function afterHead($agrv1){
  echo __METHOD__."<br/> >>>Конец Шапки $agrv1<<<";
  print_r(func_get_args());
}
}
//регистрируем пользовательскую функцию как обработчика для события demoHook
//Core::addAction('headerbefore', 'getHead',3);
Core::addAction('headerafter', array('plugin2','afterHead'),3);

?>