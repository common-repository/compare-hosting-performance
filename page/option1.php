<?php  use zixnru\chp as core; ?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
    <a class="nav-tab <?php core\coreClass::adminActiveTab('general'); ?>" href="<?php echo add_query_arg(array('page' => core\coreClass::URL_ADMIN_MENU_PLUGIN, 'tab' => 'general'), 'admin.php'); ?>"><span class="glyphicon glyphicon-cog"></span> Общая информация о вашем сервере</a>
    <a class="nav-tab <?php core\coreClass::adminActiveTab('project'); ?>" href="<?php echo add_query_arg(array('page' => core\coreClass::URL_ADMIN_MENU_PLUGIN, 'tab' => 'project'), 'admin.php'); ?>"><span class="glyphicon glyphicon-envelope"></span> Тест производительности вашего сервера</a>
    <a class="nav-tab <?php core\coreClass::adminActiveTab('jornal'); ?>" href="<?php echo add_query_arg(array('page' => core\coreClass::URL_ADMIN_MENU_PLUGIN, 'tab' => 'jornal'), 'admin.php'); ?>"><span class="glyphicon glyphicon-list"></span> Показатели других серверов</a>
    <a class="nav-tab <?php core\coreClass::adminActiveTab('about'); ?>" href="<?php echo add_query_arg(array('page' => core\coreClass::URL_ADMIN_MENU_PLUGIN, 'tab' => 'about'), 'admin.php'); ?>"><span class="glyphicon glyphicon-thumbs-up"></span> Разработчик плагина</a>
</h2>
<?php core\coreClass::tabViwer(); //Показать страницу в зависимости от закладки ?>

