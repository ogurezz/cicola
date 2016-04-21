<?php 
defined("_JEXEC") or die("Restricted access");

class CicolaControllersCities extends JControllerAdmin
{
  function display($cachable = false, $urlparams = Array())
  {
    // ----- Заголовок ------------------------------------
    JToolBarHelper::title(JText::_('COM_CINEMA_CITIES_TITLE'));

    // ----- Кнопки --------------------------------------
    JToolbarHelper::addNew();
    JToolbarHelper::editList();
    JToolbarHelper::deleteList();

    // ----- Извлечение списка городов из БД------------
    $db = JFactory::getDBO();
    $db->setQuery("SELECT #__ccl_cities.id AS id, #__ccl_cities.name AS name, #__ccl_countries.name AS country, #__ccl_languages.name AS language FROM #__ccl_cities,#__ccl_countries,#__ccl_languages WHERE #__ccl_countries.id = #__ccl_cities.id_country AND #__ccl_languages.id = #__ccl_cities.id_language  ORDER BY 2");
    $rows = $db->loadObjectList();
    if ($rows == null)
    {
      JFactory::getApplication()->enqueueMessage("Ошибка получения списка городов из БД", 'error');
      return;
    }
    echo "<h1>Список городов</h1>";
?>
  <form id="adminForm" name="adminForm" action="index.php" method="POST">
    <input type="hidden" name="task" />
    <input type="hidden" name="option" value="com_cicola" />
    <input type="hidden" name="controller" value="cities" />

  <table class="table table-striped">
    <thead>
      <tr>
        <th width="2%">#</th>
        <th width="38%">Город</th>
        <th width="30%">Страна</th>
        <th width="30%">Язык</th>
      </tr>
    </thead>
    <?php
      for ($i = 0; $i < count($rows); $i++)
      {
        echo "<tr>";
        echo "<td><input type='radio' name='boxchecked' value='{$rows[$i]->id}' /></td>\n";
        echo "<td><a href='index.php?option=com_cicola&controller=cities&task=edit&boxchecked={$rows[$i]->id}' title='Редактировать'>{$rows[$i]->name}<a/></td>\n";
        echo "<td>{$rows[$i]->country}</ td>\n";
        echo "<td>{$rows[$i]->language}</ td>\n";
        echo "</tr>";
      }
    ?>
  </table>
  </form>

<?php
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
    $app = JFactory::getApplication();
    $db = JFactory::getDBO();

    $id = $app->input->get('boxchecked', '');
    if ($id == '')
    {
      $app->enqueueMessage('Не выбран элемент списка для удаления', 'error');
    }
    else
    {
      $query = "DELETE FROM #__ccl_cities WHERE id=".$id;
      $db->setQuery($query);
      $db->execute();
      $app->enqueueMessage("Элемент удален успешно",'message');
    }
    $this->display();
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