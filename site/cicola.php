<?php 
defined("_JEXEC") or die("Restricted access");//Проверка, запускается ли сценарий Джумлой
$app = JFactory::getApplication();//Объект приложения Joomla
$db = JFactory::getDBO();//Объект для работы с базой данных

//Добавляем таблицу CSS. JURI::root(true) - вернет строку "/папка_сайта"
$document =JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/components/com_cicola/assets/CSS/style.css');
//Подключаю javascript
$document->addScript(JURI::root(true).'/components/com_cicola/assets/JS/script.js');

$id_country = $app ->input->get("id_country","");//Выбранная страна
$id_city = $app ->input->get("id_city","");//Выбранный город

//---Выборка стран из БД-----------
$q = "SELECT * FROM #__ccl_countries ORDER BY NAME";
$db->setQuery($q);
$countries = $db->loadObjectList();
?>
<div class="ccl_form">
  <h1>Определяем язык общения</h1>
<form action="index.php" method="POST" name="cicola">
  <input type="hidden" name="option" value="com_cicola">
 
  <h4>1 Выбираем страну :</h4>
  <select name="id_country" onchange="document.forms['cicola'].submit()">
    <option value="">----</option>
    <?php 
    for ($i=0; $i < count($countries); $i++) 
    { 
      if ($countries[$i]->id == $id_country)
      {
        echo "<option value='{$countries[$i]->id}' selected>{$countries[$i]->name}</option>\n";
      }
      else
      {
        echo "<option value='{$countries[$i]->id}'>{$countries[$i]->name}</option>\n";
      }
    }
    ?>
  </select><br />
  <h4>2 Выбираем город :</h4>
  <select name="id_city" <?php echo ($id_country=="")?disabled:"";  ?>  onchange="document.forms['cicola'].submit();">
    <option value="">----</option>
    <?php 
    if ($id_country !="")
    {
      //---Выборка городов из БД-----------
      $q = "SELECT * FROM #__ccl_cities WHERE id_country = $id_country ORDER BY NAME";
      $db->setQuery($q);
      $cities = $db->loadObjectList();

      for ($i=0; $i < count($cities); $i++) 
      { 
        if ($cities[$i]->id == $id_city)
        {
          echo "<option value='{$cities[$i]->id}' selected>{$cities[$i]->name}</option>\n";
        }
        else
        {
          echo "<option value='{$cities[$i]->id}'>{$cities[$i]->name}</option>\n";
        }
      }
    }
    ?>
  </select><br />
  </form>

<?php 
if (isset($cities) && count($cities)==0) 
{
?>
<h4>В этой стране нет данных по городам.</h4>
<?php
}

//Выборка языка из БД
if (isset($cities) && $id_city != "") 
{
  $q = "SELECT #__ccl_cities.name AS city, #__ccl_countries.name AS country, #__ccl_languages.name AS language";
  $q .= " FROM #__ccl_languages, #__ccl_countries, #__ccl_cities";
  $q .= " WHERE #__ccl_cities.id = $id_city AND #__ccl_cities.id_language = #__ccl_languages.id AND #__ccl_cities.id_country = #__ccl_countries.id";

  $db->setQuery($q);
  $T = $db->loadObject();
  ?>
<div class="ccl_table">
<table class="table table-striped">
  <thead>
    <tr>
      <th>Город</th>
      <th>Страна</th>
      <th>Язык общения</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $T->city; ?></td>
      <td><?php echo $T->country; ?></td>
      <td><?php echo $T->language; ?></td>
    </tr>
  </tbody>
</table>
</div>
<?php } ?>
</div>

