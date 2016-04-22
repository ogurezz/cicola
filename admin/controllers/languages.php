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
    $this->id = $app->input->get('id','');
    $this->name = $app->input->getString('name','');
  }
}

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

        // ----- Извлечение списка Языков из БД------------
    $db = JFactory::getDBO();
    $db->setQuery("SELECT id, name FROM #__ccl_languages ORDER BY name");
    $rows = $db->loadObjectList();
    if ($rows == null)
    {
      JFactory::getApplication()->enqueueMessage("Ошибка получения языков из БД", 'error');
      return;
    }
    echo "<h1>Список Языков</h1>";
?>
    <form id="adminForm" name="adminForm" action="index.php" method="POST">
      <input type="hidden" name="task" />
      <input type="hidden" name="option" value="com_cicola" />
      <input type="hidden" name="controller" value="languages" />

      <table class="table table-striped">
        <thead>
          <tr>
            <th width="2%">#</th>
            <th width="98%">Язык</th>
          </tr>
        </thead>
<?php
        for ($i = 0; $i < count($rows); $i++)
        {
          echo "<tr>";
          echo "<td><input type='radio' name='boxchecked' value='{$rows[$i]->id}' /></td>\n";
          echo "<td><a href='index.php?option=com_cicola&controller=languages&task=edit&boxchecked={$rows[$i]->id}' title='Редактировать'>{$rows[$i]->name}<a/></td>\n";
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

public function AddOrEdit($obj, $title, $isAdd)
  {
    //Сделать меню админки недоступным
    JFactory::getApplication()->input->set('hidemainmenu',1);
    // --------Вывод заголовка----------
    JToolbarHelper::title(JText::_('COM_CICOLA_LANGUAGES_TITLE')." - ".$title);
    // ---------Кнопки------------------
    JToolbarHelper::apply();
    JToolbarHelper::cancel();
    echo "<h2>$title</h2>"
    // ---------Форма-------------------
  ?>

  <form action="index.php" method="POST" name="adminForm" id="adminForm">
    <input type="hidden" name="task" value="">
    <input type="hidden" name="option" value="com_cicola">
    <input type="hidden" name="controller" value="languages">

    <input type="hidden" name="id" value="<?php echo $obj->id; ?>">
    <input type="hidden" name="is_add" value="<?php echo $isAdd; ?>">

    <table>
      <tbody>
        <tr>
          <td width = "300">Язык</td>
          <td>
            <input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $obj->name;?>">
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
  var form = document.adminForm;
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
      alert('Название языка не введено');
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
    $this->AddOrEdit(new ItemData(),"Добавление нового языка",true );
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
      //Получение Языков для редактирования из базы даных---
      $db->setQuery("SELECT * FROM #__ccl_languages WHERE id='{$id}'");
      $obj = $db->loadObject();
      if ($obj == null) 
      {
        throw new Exception("Язык не найден в Базе Данных");
       }

       $item = new ItemData($obj);//Инициализируем  модель ItemData из строки таблицы (obj->id и obj->name)
       $this->AddOrEdit ($item, "Редактирование языка - ".$item->name, false);
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
      
      /*Проверка наличия городов с выбранным языком*/
      $db->setQuery("SELECT COUNT(*) AS cnt FROM #__ccl_cities WHERE id_language=$id");
      $cnt = $db->loadObject()->cnt;
      if ($cnt > 0)
      {
        $app->enqueueMessage('Невозможно удалить: с данным языком связаны некоторые города', 'error');
      }
      else
      { 
        $query = "DELETE FROM #__ccl_languages WHERE id=".$id;
         $db->setQuery($query);
        $db->execute();
        $app->enqueueMessage("Элемент удален успешно",'message');
      }
    }
    $this->display();
  }
function cancel()
  {
    //Переход на отображение списка-------------------
    $this->display();
  }
function apply()
  {
    $app = JFactory::getApplication();
    $db = JFactory::getDBO();

    //Определение - будет добавление или обновление----------------------
    $isAdd = $app->input->get('is_add',true);
    //--Формирование модели ItemData из HTTP-запроса---------------------
    $obj = new ItemData();
    $obj->initFromRequest();

    if ($isAdd == true) 
    {
      //-------Добавление-----------
      //Чтобы избежать внесение в БД дублей будем проверять наличие в БД языков с тем же названием 
      $q = "SELECT name FROM #__ccl_languages WHERE name='{$obj->name}'";
      $db->setQuery($q);
      if ($db->loadResult())
      {
        $app->enqueueMessage("Язык уже внесен в Базу Данных", "error");
      }
      else
      {
        $q = "INSERT INTO #__ccl_languages(name) VALUES ("."'".$obj->name."'".")";//Название языка
         //---Отправка запроса----
        $db->setQuery($q);
        $db->execute();
        //Сообщение об успешном завершении операции
        $app->enqueueMessage("Добавление успешно осуществлено", "message");
      }
    }
    else 
    {
      //---Редактирование (обновление)--
      $q = "UPDATE #__ccl_languages SET name='{$obj->name}' WHERE id={$obj->id}";
    //---Отправка запроса---------------
    $db->setQuery($q);
    $db->execute();
    //Сообщение об успешном завершении операции
    $app->enqueueMessage("Обновление успешно осуществлено", "message");
    }

    //Переход на отображение списка-------------------
    
    $this->display();
  }

}