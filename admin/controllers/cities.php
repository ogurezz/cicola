<?php 
defined("_JEXEC") or die("Restricted access");

/**
 *class ItemData - инкапсулирует данные одного Города (строка из таблицы #__ccl_cities)
 *(Применяется  в методах Добавить/Редактировать )
 *--------------------------------------
 */

class ItemData
{
  public $id;         // ID города
  public $name;       //Название города
  public $id_country;   //ID страны - integer
  public $id_language;//ID языка - integer

  //$row - строка, выбранная из таблицы #__ccl_cities
  
  public function __construct($row=null)
  {
    if ($row == null ) {
      $this->id          = 0;
      $this->name        = "";
      $this->id_country    = 0;
      $this->id_language = 0;
    }
    else
    {
      $this->id          = $row->id;
      $this->name        = $row->name;
      $this->id_country    = $row->id_country;
      $this->id_language = $row->id_language;
    }
  }
  /**
  * Получение значения переменных из HTTP-запроса
  */
  public function initFromRequest()
  {
    $app               = JFactory::getApplication();
    $this->id          = $app->input->get('id','');
    $this->name        = $app->input->getString('name','');
    $this->id_country    = $app->input->getInt('id_country',''); 
    $this->id_language = $app->input->getInt('id_language',''); 
  }
}


class CicolaControllersCities extends JControllerAdmin
{
  function display($cachable = false, $urlparams = Array())
  {
    // ----- Заголовок ------------------------------------
    JToolBarHelper::title(JText::_('COM_CICOLA_CITIES_TITLE'));

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

  /**
   * Универсальный метод для добавления/ редактирования
   *
   * $item  - объект ItemData
   * $title - Надпись в заголовке страницы
   * $isAdd - признак добавления/ редактирования (true/false)
   */
  public function AddOrEdit($obj, $title, $isAdd)
  {
    //Сделать меню админки недоступным
    JFactory::getApplication()->input->set('hidemainmenu',1);
    // --------Вывод заголовка----------
  JToolbarHelper::title(JText::_('COM_CICOLA_CITIES_TITLE')." - ".$title);
    // ---------Кнопки------------------
    JToolbarHelper::apply();
    JToolbarHelper::cancel();
    echo "<h2>$title</h2>";
    
    $app = JFactory::getApplication();//Объект приложения Joomla
    $db = JFactory::getDBO();//Объект для работы с базой данных

//---Выборка стран из БД-----------
$q = "SELECT * FROM #__ccl_countries ORDER BY NAME";
$db->setQuery($q);
$countries = $db->loadObjectList();

//---Выборка языков из БД-----------
$q = "SELECT * FROM #__ccl_languages ORDER BY NAME";
$db->setQuery($q);
$languages = $db->loadObjectList();


    // ---------Форма-------------------
  ?>
  <form action="index.php" method="POST" name="adminForm" id="adminForm">
    <input type ="hidden" name="task" value="">
    <input type ="hidden" name="option" value="com_cicola">
    <input type ="hidden" name="controller" value="cities">

    <input type ="hidden" name="id" value="<?php echo $obj->id; ?>">
    <input type ="hidden" name="is_add" value="<?php echo $isAdd; ?>">

    <table>
      <tbody>
        <tr>
          <td width = "300">Название</td>
          <td>
            <input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $obj->name;?>">
          </td>
        </tr>
        <tr>
          <td>Страна</td>
          <td>
            <select name="id_country">
            <?php 
            echo ($isAdd)?"<option value=''>Выберите страну</option>":"";
            for ($i=0; $i < count($countries); $i++) 
            { 
              if ($countries[$i]->id == $obj->id_country)
              {
              echo "<option value='{$countries[$i]->id}' selected>{$countries[$i]->name}</option>";
              }
               else
              {
              echo "<option value='{$countries[$i]->id}'>{$countries[$i]->name}</option>";
                }
              }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>Язык</td>
          <td>
            <select name="id_language">
            <?php 
            echo ($isAdd)?"<option value=''>Выберите язык</option>":"";
            for ($i=0; $i < count($languages); $i++) 
            { 
              if ($languages[$i]->id == $obj->id_language)
              {
              echo "<option value='{$languages[$i]->id}' selected>{$languages[$i]->name}</option>";
              }
               else
              {
              echo "<option value='{$languages[$i]->id}'>{$languages[$i]->name}</option>";
                }
              }
            ?>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
<script type="text/javascript">
/**
 * Функция валидации данных форм (вызывается автоматически)
 */
Joomla.submitbutton = function(task)
{
  var form        = document.adminForm;
  var now         = new Date;
  var currentYear = now.getFullYear();
  if (task == 'cancel')
  {
    //----Отмена изменений-------------
    Joomla.submitform(task, document.getElementById('adminForm'));
    return
  }
  if (task == 'apply')
  {
    if (form.name.value == '') 
    {
      alert('Название города не введено');
      return;
    }
    if (form.id_country.value == '') 
    {
      alert('Страна не выбрана');
      return;
    }
    if (form.id_language.value == '') 
    {
      alert('Язык не выбран');
      return;
    }
    return;
    }

  }
  Joomla.submitform(task,document.getElementById('adminForm'));
}
</script>

<?php 
  }

  function add()
  {
    $this->AddOrEdit(new ItemData(),"Добавление нового города",true );
  }

  function edit()
  {
    $app = JFactory::getApplication();
    $db = JFactory::getDBO();
    try
    {
      //Проверка того, что выбран элемент для редактирования
      $id = $app->input->get('boxchecked','');
      if ($id=='')
      {
        throw new Exception("Не выбран элемент списка для редактирования");
      } 
      //Получение города для редактирования из базы даных---
      $db->setQuery("SELECT * FROM #__ccl_cities WHERE id='{$id}'");
      $obj = $db->loadObject();
      if ($obj == null) 
      {
        throw new Exception("Город не найден в Базе Данных");
       }

       $item = new ItemData($obj);//Инициализируем  модель ItemData из строки таблицы (obj->id,obj->name, obj->id_category)
       $this->AddOrEdit ($item, "Редактирование города - ".$item->name, false);
    }
    catch (Exception $e)
    {
      $app->input->set('task','');//Нужно сбросить значение task
      $app->enqueueMessage($e->getMessage(), 'error');
      $this->display();
    }
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
     //Переход на отображение списка городов-------------------
    
    $this->display();
  }
  function apply()
  {
    echo "<h1>Task: apply</h1>";
  }


}