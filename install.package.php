<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

  // ----- DIRECTORY_SEPARATOR -------------------------------------------
define( 'DS', DIRECTORY_SEPARATOR );

class com_cicolaInstallerScript
{
  // ----- Поля класса -----------------------------------------------

  // ----- Методы класса ---------------------------------------------
  /*
  * Вызывается при инсталляции компонента
  *
  * $parent is the class calling this method
  */
  function install($parent)
  {
    $db = JFactory::getDBO();
    
  // ----- Удаление таблицы "Города", если осталась от предыдущей установки
    $q  = "DROP TABLE IF EXISTS #__ccl_cities";
    $db->setQuery($q);
    $db->execute();

  // ----- Удаление таблицы "Страны", если осталась от предыдущей установки
    $q  = "DROP TABLE IF EXISTS #__ccl_countries";
    $db->setQuery($q);
    $db->execute();

      // ----- Удаление таблицы "Языки", если осталась от предыдущей установки
    $q  = "DROP TABLE IF EXISTS #__ccl_languages";
    $db->setQuery($q);
    $db->execute();

  // ----- Создание таблицы "Города" ---------------------------------
    $q  = "CREATE TABLE #__ccl_cities(".
        "id int not null primary key auto_increment, ".
        "name varchar(64), ".
        "id_country int, ".
        "id_language int".
        ") DEFAULT CHARSET=utf8";

  // ----- Создание таблицы "Страны" --------------------------------
    $q  = "CREATE TABLE #__ccl_countries(".
        "id int not null primary key auto_increment, ".
        "name varchar(32) ".
        ") DEFAULT CHARSET=utf8";

    $db->setQuery($q);
    $db->execute();

  // ----- Создание таблицы "Языки" --------------------------------
    $q  = "CREATE TABLE #__ccl_languages(".
        "id int not null primary key auto_increment, ".
        "name varchar(32) ".
        ") DEFAULT CHARSET=utf8";    

    $db->setQuery($q);
    $db->execute();

  // ----- Вывод информации о результатах инсталляции ----------------
    JFactory::getApplication()->enqueueMessage(JText::_('COM_CICOLA_INSTALL_SUCCESSFULL'), 'message');
    
  }

  /*
  * Выполняется после установки и обновления
  *
  * $parent - is the class calling this method
  * $type - is the type of change (install, update or discover_install)
  */
  function postflight($type, $parent)
  {
    $db = JFactory::getDBO();
    
  // ----- Добавление Пункта Меню на клиентской стороне сайта ----------
    $q  =
      "INSERT INTO `#__menu`".
      "(".
      "menutype,".            // menutype varchar(24)     "mainmenu"
      "title,".             // title  varchar(255)    "Home" - !Заменяемо!
      "alias,".             // alias  varchar(255)    "home" - !Заменяемо!
      "note,".              // note   varchar(255) -new-  ""
      "path,".              // path   varchar(1024)-new-  "home"  - !Заменяемо!
      "link,".              // link   varchar(1024)   "index.php?option=com_content&view=featured" - !Заменяемо!
      "type,".              // type   varchar(16)     "component"
      "published,".           // published  tinyint(4)    1
      "parent_id,".           // parent_id  int(10) unsigned  1
      "level,".             // level  int(10) unsigned -new-  1
      "component_id,".          // component_id int(10) unsigned  22 - !Заменяемо!
      "checked_out,".           // checked_out  int(10) unsigned  0
      "checked_out_time,".        // checked_out_time timestamp "0000-00-00 00:00:00"
      "browserNav,".            // browserNav tinyint(4)    0
      "access,".              // access int(10) unsigned  1
      "img,".               // img    varchar(255)    ""
      "template_style_id,".       // template_style_id  int(10) unsign  0
      "params,".              // params text      ""
      "lft,".               // lft    int(11)     47
      "rgt,".               // rgt    int(11)     48
      "home,".              // home   tinyint(3) unsigned 1
      "language,".            // language char(7)     '*'
      "client_id".            // client_id  tinyint(4)    0
      ")".
      "VALUES".
      "(".
      "'mainmenu',".            // menutype
      "'Cicola',".        // title
      "'cicola',".            // alias
      "'',".                // note
      "'cicola',".            // path
      "'index.php?option=com_cicola',". // link
      "'component',".           // type
      "1,".               // published
      "1,".               // parent_id
      "1,".               // level
      "(select extension_id FROM #__extensions where name='com_cicola'),".  // component_id
      "0,".               // checked_out
      "'0000-00-00 00:00:00',".     // checked_out_time
      "0,".             // browserNav
      "1,".             // access
      "'',".              // img
      "0,".             // template_style_id
      "'',".              // params
      "0,".             // lft
      "0,".             // rgt
      "0,".             // home
      "'*',".             // language
      "0".              // client_id
      ")";

    $db->setQuery($q);
    $db->execute();

  }
    


  /*
  * Вызывается при деинсталляции компонента
  *
  * $parent is the class calling this method
  */
  function uninstall($parent)
  {
    $db = JFactory::getDBO();
    
 // ----- Удаление таблицы "Города"
    $q  = "DROP TABLE IF EXISTS #__ccl_cities";
    $db->setQuery($q);
    $db->execute();

  // ----- Удаление таблицы "Страны"
    $q  = "DROP TABLE IF EXISTS #__ccl_countries";
    $db->setQuery($q);
    $db->execute();

      // ----- Удаление таблицы "Языки"
    $q  = "DROP TABLE IF EXISTS #__ccl_languages";
    $db->setQuery($q);
    $db->execute();

  // ----- Удаление Пункта Меню на клиентской стороне сайта --------------
      $q  = "DELETE FROM #__menu WHERE alias='cicola'";
      $db->setQuery($q);
      $db->execute();


  // ----- Вывод информации о результатах инсталляции ----------------
    JFactory::getApplication()->enqueueMessage(JText::_('COM_CICOLA_UNINSTALL_SUCCESSFULL'), 'message');
    
  }

}

 
