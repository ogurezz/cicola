<?php 
defined("_JEXEC") or die("Restricted access"); //Проверка, запускается ли сценарий джумлой



  /*
  * Отображение Пунктов Меню Компонента
  * ------------------------------------------------------------------
  */
$arr  = array
  (
    'cities'  =>  array(JText::_('Города'),  'index.php?option=com_cicola&controller=cities'), // Города
    'countries'     =>  array('Страны',   'index.php?option=com_cicola&controller=countries'),     // Страны
    'languages'     =>  array('Языки',   'index.php?option=com_cicola&controller=languages')
  );
 
$vName  = JFactory::getApplication()->input->get('controller', 'cities');
// $vName - значение элемента 'controller' в $_GET, по умолчанию $vName = 'cities'
  
foreach($arr as $aKey => $aValue)
{
  JSubMenuHelper::addEntry(JText::_($aValue[0]), $aValue[1], $vName == $aKey);
}
//Создается пункты меню, выбранный пункт меню будет выделен.

  /*
  * Загрузка необходимого контроллера
  * ------------------------------------------------------------------
  */
  // ----- Загрузка класса  ----------------------------------------------
JLoader::registerPrefix('Cicola', JPATH_COMPONENT_ADMINISTRATOR);

  // ----- Приложение -----------------------------------------------
$app = JFactory::getApplication();

  // ----- Запрос контроллера ------------------
$controller = $app->input->get('controller','cities');

$app->input->set('controller', $controller);

  // ----- Создание контроллера -------------------------------------
$classname = 'CicolaControllers'.ucwords($controller);

$controller = new $classname();

  // ----- Выполнение запроса ----------------------------------
$controller->execute($app->input->get('task'));




