<?php

namespace zixnru\chp;

use zixnru\chp;

/**
 * Взаимодействие с js файлами
 */
class jsClass {

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->addaction();
    }

    /**
     * Адды
     */
    public function addaction() {
        //Тест производительности
        add_action('wp_ajax_getTest', array($this, 'getTestHosting'));
        add_action('wp_ajax_nopriv_getTest', array($this, 'getTestHosting'));
        //Запрос на сервер
        add_action('wp_ajax_getPageZixn', array($this, 'getPageZixnPage'));
        add_action('wp_ajax_nopriv_getPageZixn', array($this, 'getPageZixnPage'));
    }

    /**
     * Тест производительности хостинга (HTML)
     * Выводи подключаемый файл с таблицей результата
     */
    public function getTestHosting() {


        $fObj = new chp\functionClass(1);

        $mySQLTest = $fObj->getMySqlTest();
        $mySQLCRUD = $fObj->getMySqlCRUD();
        $phpAllTest = $fObj->getPHPTest();
        $networkTest = $fObj->getNetworkTest();

        $date = date('d.m.Y');
        $os = $fObj->phpuname;
        $vphp = $fObj->phpversion;
        $vmysql = $fObj->mysqlversion;
        $tphp = $phpAllTest['alltime'];
        $tmysql = (empty($mySQLTest['error']) ? $mySQLTest['alltime'] : $mySQLTest['error']);
        $summ = ($mySQLTest['alltime'] + $phpAllTest['alltime']);
        $ipphp = sprintf('%.2f', (10000 / $phpAllTest['alltime']));
        $ipmysql = sprintf('%.2f', (10000 / $mySQLTest['alltime']));


        //Логируем
        $fObj->setJornalTest($date, $os, $vphp, $vmysql, $tphp, $tmysql, $summ, $ipmysql, $ipphp);
        //Отправка теста для сравнения
        $fObj->sendTest($date, $os, $vphp, $vmysql, $tphp, $tmysql, $summ, $ipmysql, $ipphp);
//Подключение таблицы результатов
        require_once (WP_PLUGIN_DIR . '/' . chp\coreClass::PATCH_PLUGIN . '/page/ajax/tab2-testperf.php');

        wp_die();
    }

    /**
     * Получает страницу с таблицей провайдеров
     */
    public function getPageZixnPage() {
        
        if(!chp\functionClass::isFunctionServer('curl_init')) {
            echo "<p>У вас отключен CURL на сервере, статистику получить не возможно!</p>";
            wp_die();
        }
        
        $postData = http_build_query(
                array(
                    'chp' => 'getPage'
                )
        );

        // создаем подключение
        $ch = curl_init(chp\functionClass::getUrlPage());
// устанавлваем даные для отправки
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
// флаг о том, что нужно получить результат
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// отправляем запрос
        $resultPost = curl_exec($ch);
// закрываем соединение
        curl_close($ch);

        $result = chp\functionClass::cutTextStartEnd($resultPost, '<!-- startTab -->', '<!-- endTab -->');

        echo $result;
        wp_die();
    }

}
