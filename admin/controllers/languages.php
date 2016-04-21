<?php 
defined("_JEXEC") or die("Restricted access");

class CicolaControllersLanguages extends JControllerAdmin
{
  function display($cachable = false, $urlparams = Array())
  {
    // ----- Заголовок ------------------------------------
    JToolBarHelper::title(JText::_('COM_CICOLA_LANGUAGES_TITLE'));

    // ----- Кнопки ------------------------------------
    JToolbarHelper::addNew();
    JToolbarHelper::editList();
    JToolbarHelper::deleteList();
  }

  function __construct($config = array() )
  {
    parent::__construct($config);

    $this->registerTask('add',     'add');    //Добавить
    $this->registerTask('edit',    'edit');   //Изменить
    $this->registerTask('remove',  'remove');   //Удалить
    $this->registerTask('cancel',  'cancel');   //Кнопка "Отмена" в добавлении/редактировании
    $this->registerTask('apply',   'apply');    //Кнопка "Применить" в добавлении/редактировании
  }

  function add()
  {
    echo "<h1>Task: add</h1>";
  }

  function edit()
  {
    echo "<h1>Task: edit</h1>";
  }
  function remove()
  {
    echo "<h1>Task: remove</h1>";
  }
function cancel()
  {
    echo "<h1>Task: cancel</h1>";
  }
function apply()
  {
    echo "<h1>Task: apply</h1>";
  }

}