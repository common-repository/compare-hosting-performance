<?php
namespace zixnru\chp;
defined( 'ABSPATH' ) or die( 'Good die' );
/**
 * Базовый Класс
 * Создаёт административные разделы плагина, подключает скрипты, настройки т.д
 * Только базовый функционал активации плагина
 */
class coreClass {

    const NAME_PLUGIN = 'Сompare hosting performance';
    const PATCH_PLUGIN = 'compare-hosting-performance'; //Директория плагина
    const URL_ADMIN_MENU_PLUGIN = 'compare-hosting-performance'; //Адрес в админке
    const OPTIONS_NAME_PAGE = 'page/option1.php'; //страница опций плагина
    const NAME_TITLE_PLUGIN_PAGE = 'Характеристики вашего Хостинга'; // Название титульной страницы плагина
    const NAME_MENU_OPTIONS_PAGE = 'Test Performance'; // Название пунтка меню
    const URL_PLUGIN_CONTROL = 'options-general.php?page=compare-hosting-performance'; //Адрес админки плагина полный
    /**
     * Префикс для скриптов и стилей
     */
    const PREF_JS_CSS='chp';

    /**
     * Констурктора класса
     */

    public function __construct() {
        $this->addActios();
        $this->addOptions();
    }

    /**
     * Опции вызываемые деактивацией
     */
    public function deactivationPlugin() {
        delete_option('chp_jornal');
        delete_option('chp_sendtest');
    }

    /**
     * Активация фишек
     */
    public function addActios() {
        add_action('admin_menu', array($this, 'adminOptions'));
        add_filter('plugin_action_links', array($this, 'pluginLinkSetting'), 10, 2); //Настройка на странице плагинов

        
    }

    /**
     * Добавление опций в базу данных
     */
    public function addOptions() {
        add_option('chp_jornal', array()); //Массив с журналом
        add_option('chp_sendtest', '0'); // Статуст отправки теста
    }

    /**
     * Добавляет пункт настроек на странице активированных плагинов
     */
    public function pluginLinkSetting($links, $file) {
        $this_plugin = self::PATCH_PLUGIN . '/index.php';
        if ($file == $this_plugin) {
            $settings_link1 = '<a href="' . self::URL_PLUGIN_CONTROL . '">' . __("Settings", "default") . '</a>';
            array_unshift($links, $settings_link1);
        }
        return $links;
    }

    /**
     * Параметры активируемого меню
     */
    public function adminOptions() {
        $page_option = add_options_page(self::NAME_TITLE_PLUGIN_PAGE, self::NAME_MENU_OPTIONS_PAGE, 'activate_plugins', self::URL_ADMIN_MENU_PLUGIN, array($this, 'showSettingPage'));
        add_action('admin_print_styles-' . $page_option, array($this, 'syleScriptAddpage')); //загружаем стили только для страницы плагина
        add_action('admin_print_scripts-' . $page_option, array($this, 'scriptAddpage')); //Скрипты админки
    }

    /**
     * Стили, скрипты
     */
    public function syleScriptAddpage() {
        wp_register_script(self::PREF_JS_CSS.'_bootstrapjs1', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/js/bootstrap.js');
        wp_enqueue_script(self::PREF_JS_CSS.'_bootstrapjs1');
        wp_register_style(self::PREF_JS_CSS.'_bootstrapcss1', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'bootstrap/css/bootstrap.css');
        wp_enqueue_style(self::PREF_JS_CSS.'_bootstrapcss1');
        wp_register_style(self::PREF_JS_CSS.'_adminpagecss', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'css/adminpag.css');
        wp_enqueue_style(self::PREF_JS_CSS.'_adminpagecss');
    }

    /**
     * Сприпты
     */
    public function scriptAddpage() {
        wp_register_script(self::PREF_JS_CSS.'_admin', plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'js/admin_order.js');
        wp_enqueue_script(self::PREF_JS_CSS.'_admin');
    }

    /**
     * Страница меню
     */
    public function showSettingPage() {
        include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/' . self::OPTIONS_NAME_PAGE;
    }

    /**
     * Активная вкладка в админпанели плагина
     * @return string css Класс для активной вкладки
     */
    static public function adminActiveTab($tab_name = null, $tab = null) {

        if (isset($_GET['tab']) && !$tab)
            $tab = $_GET['tab'];
        else
            $tab = 'general';

        $output = '';
        if (isset($tab_name) && $tab_name) {
            if ($tab_name == $tab)
                $output = ' nav-tab-active';
        }
        echo $output;
    }

    /**
     * Подключает нужную страницу исходя из вкладки на страницы настроек плагина
     * @result include_once tab{номер вкладки}-option1.php
     */
    static public function tabViwer() {
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
        } else {
            $tab = 'general';
        }
        switch ($tab) {
            case 'general':
                include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab1-option1.php';
                break;
            case 'project':
                include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab2-option1.php';
                break;
            case 'jornal':
                include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab3-option1.php';
                break;
            case 'about':
                include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab4-option1.php';
                break;
            default :
                include_once WP_PLUGIN_DIR . '/' . self::PATCH_PLUGIN . '/page/tab1-option1.php';
        }
    }

}
