<?php
/*
  Plugin Name: Compare hosting performance
  Plugin URI: http://www.zixn.ru/compare-hosting-performance.html
  Description: Выполняет тест производительности вашего сервера
  Version: 1.2
  Author: Djo
  Author URI: http://zixn.ru
  Text Domain: chp_zixnru
  Domain Path: /lang
 */

/*  Copyright 2016  Djo  (email: izm@zixn.ru)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */
//register_activation_hook(__FILE__, 'chpActivationLogin');
add_action('plugins_loaded', 'chpActivationLogin');
add_action('plugins_loaded', 'chp_load_plugin_textdomain', 10);

function chp_load_plugin_textdomain() {
    load_plugin_textdomain('chp_zixnru', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

function chpActivationLogin() {
    if (version_compare(phpversion(), '5.3', '>=')) { //5.3
//    use zixnru\chp as core;
        require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/core-class.php');
        require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/function-class.php'); //Основной функционал плагина
        require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/javascript-class.php');



        $objCore = new \zixnru\chp\coreClass();
        $objJs = new \zixnru\chp\jsClass();
        register_deactivation_hook(__FILE__, array($objCore, 'deactivationPlugin'));
        return true;
    } else {

        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', 'chpAlertMessage');
        return false;
    }
}

function chpAlertMessage() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('Внимание! Ваша версия PHP не поддерживается!. Плагин автоматически деактивировался. Требуется версия PHP>=5.3','chp_zixnru'); ?></p>
    </div>
    <?php
}
