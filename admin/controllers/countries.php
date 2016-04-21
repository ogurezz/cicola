<?php 
defined("_JEXEC") or die("Restricted access");

class ItemData
{
  public $id;    // ID
  public $name;  //Название

  //$row - строка, выбранная из таблицы базы данных
  
  public function __construct($row=null)
  {
    if ($row == null ) {
      $this->id = 0;
      $this->name = "";
    }
    else
    {
      $this->id = $row->id;
      $this->name = $row->name;
    }
  }
  /**
   * Получение значения переменных из HTTP-запроса
   */
  public function initFromRequest()
  {
    $app = JFactory::getApplication();
    $this->id = $app->get('id','');
    $this->name = $app->getString('name','');
  }
}

class CicolaControllersCountries extends JControllerAdmin
{
  function display($cachable = false, $urlparams = Array())
  {
    // ----- Заголовок ------------------------------------
    JToolBarHelper::title(JText::_('COM_CICOLA_COUNTRIES_TITLE'));

    // ----- Кнопки ------------------------------------
    JToolbarHelper::addNew();
    JToolbarHelper::editList();
    JToolbarHelper::deleteList();

    // ----- Извлечение списка Стран из БД------------
    $db = JFactory::getDBO();
    $db->setQuery("SELECT id, name FROM #__ccl_countries ORDER BY name");
    $rows = $db->loadObjectList();
    if ($rows == null)
    {
      JFactory::getApplication()->enqueueMessage("Ошибка получения жанров из БД", 'error');
      return;
    }
    echo "<h1>Список Стран</h1>";
?>
    <form id="adminForm" name="adminForm" action="index.php" method="POST">
      <input type="hidden" name="task" />
      <input type="hidden" name="option" value="com_cicola" />
      <input type="hidden" name="controller" value="countries" />

      <table class="table table-striped">
        <thead>
          <tr>
            <th width="2%">#</th>
            <th width="98%">Страна</th>
          </tr>
        </thead>
<?php
        for ($i = 0; $i < count($rows); $i++)
        {
          echo "<tr>";
          echo "<td><input type='radio' name='boxchecked' value='{$rows[$i]->id}' /></td>\n";
          echo "<td><a href='index.php?option=com_cicola&controller=countries&task=edit&boxchecked={$rows[$i]->id}' title='Редактировать'>{$rows[$i]->name}<a/></td>\n";
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

     /**
   * Универсальный метод для добавления/ редактирования
   *
   * $item  - объект ItemData
   * $title - Надпись в заголовке страницы
   * $isAdd - признак добавления/ редактирования (true/false)
   */
  public function AddOrEdit($obj, $title, $isAdd)
  {
    // --------Вывод заголовка----------
    JToolbarHelper::title(JText::_('COM_CICOLA_COUNTRIES_TITLE')." - ".$title);
    // ---------Кнопки------------------
    JToolbarHelper::apply();
    JToolbarHelper::cancel();
    echo "<h2>$title</h2>"
    // ---------Форма-------------------
  ?>

  <form action="index.php" method="POST" name="adminForm" id="adminForm">
    <input type="hidden" name="task" value="">
    <input type="hidden" name="option" value="com_cicola">
    <input type="hidden" name="controller" value="countries">

    <input type="hidden" name="id" value="<?php echo $obj->id; ?>">
    <input type="hidden" name="is_add" value="<?php echo $isAdd; ?>">

    <table>
      <tbody>
        <tr>
          <td width = "300">Страна</td>
          <td>
            <input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $obj->name;?>">
          </td>
        </tr>
      </tbody>
    </table>
  </form>
<?php 
  }


  function add()
  {
    $this->AddOrEdit(new ItemData(),"Добавление новой страны",true );
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
      
      /*Проверка наличия городов в стране*/
      $db->setQuery("SELECT COUNT(*) AS cnt FROM #__ccl_cities WHERE id_country=$id");
      $cnt = $db->loadObject()->cnt;
      if ($cnt > 0)
      {
        $app->enqueueMessage('Невозможно удалить: с данной страной связаны некоторые города', 'error');
      }
      else
      { 
        $query = "DELETE FROM #__ccl_countries WHERE id=".$id;
         $db->setQuery($query);
        $db->execute();
        $app->enqueueMessage("Элемент удален успешно",'message');
      }
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