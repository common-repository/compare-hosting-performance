<?php

namespace zixnru\chp;

/**
 * Функции, логика, взаимодействие
 * Все возможности плагина и его обработки
 */
class functionClass {

    /**
     * Текущий каталог
     */
    public $pathserver;

    /**
     * Модификация сервера (cgi и т.д)
     */
    public $servermode;
    /*
     * Имя сервера
     */
    public $servername;
    /*
     * Адрес сервера 
     */
    public $serveraddr;
    /*
     * Версия PHP
     */
    public $phpversion;
    /*
     * Текущая "целая" версия PHP
     */
    public $phpmajorversion;
    /*
     * ОС php
     */
    public $phpos;

    /**
     * Имя ядра ОС
     */
    public $phpuname;

    /*
     * Лимит памяти PHP
     */
    public $memorylimit;
    /*
     * Размер пост запроса Php
     */
    public $postmaxsize;

    /**
     * Размер загружаемого файла php
     */
    public $uploadmaxsize;
    /*
     * Версия MySQL
     */
    public $mysqlversion;
    /*
     * Время выполнения скрипта PHP
     */
    public $maxexectime;
    /*
     * Путь до файла php.ini если он доступен
     * 
     */
    public $phpinilocation;
    /*
     * Версия Апач если доступен
     */
    public $apacheversion;
    /*
     * Загруженные модули Apache
     */
    public $loadedmodules;
    /*
     * Загруженносить сервера 
     */
    public $loadserver;

    /*
     * Произвольная повторяющаяся строка для теста запроса к MySQL
     */
    protected $mysqlquerydata;


    /*
     * Количество заросов к MySQL
     */
    protected $runquerycount;

    /*
     * Путь до файла для теста скачивания
     */
    protected $patchremotefile;
    /*
     * Сервер статистики
     */
    protected $urlserverstat;

    /**
     * Конструктор
     * @global \zixnru\chp\type $wpdb
     */
    public function __construct($load) {
        if ($load !== 0) {
            global $wpdb;
            $this->pathserver = getcwd();
            $this->servermode = $_SERVER['GATEWAY_INTERFACE'];
            $this->servername = $_SERVER['SERVER_NAME'];
            $this->serveraddr = $_SERVER['SERVER_ADDR'];
            $this->phpversion = PHP_VERSION;
            $this->phpmajorversion = PHP_MAJOR_VERSION;
            $this->phpos = PHP_OS;
            $this->phpuname = php_uname();
            $this->memorylimit = ini_get("memory_limit");
            $this->postmaxsize = ini_get("post_max_size");
            $this->uploadmaxsize = ini_get("upload_max_filesize");
            $this->mysqlversion = $wpdb->get_var("select version();");
            $this->maxexectime = ini_get('max_execution_time');
            $this->phpinilocation = php_ini_loaded_file();
            $apacheversion = (function_exists('apache_get_version')) ? apache_get_version() : '';
            if ($apacheversion == "") {
                $apacheversion = "Не известный WebServer";
            }
            $this->apacheversion = $apacheversion;

            $loadedmodules = (function_exists('apache_get_modules')) ? apache_get_modules() : '';
            if ($loadedmodules == "") {
                $loadedmodules = "Не известный сервер с модулями";
            }
            $this->loadedmodules = $loadedmodules;
            $this->mysqlquerydata = str_repeat("Y", 1000);

            $this->runquerycount = 200;
            $this->loadserver = sys_getloadavg();
            $this->patchremotefile = 'http://zixn.ru/wp-content/uploads/zixn/compare-hosting-performance/1mb';
            $this->urlserverstat = 'http://www.zixn.ru/wp-content/themes/i-transform/habPlugin.php';
        }
    }

    /**
     * Проверка обязательных функций
     * @param string $name название проверяемой функции
     * @return bool результат присутствия функции
     */
    static public function isFunctionServer($name) {
        if ($name == 'curl_init') {
            if (function_exists('curl_init')) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Возвращает url расположения страницы с тестами хостинга
     * @return string
     */
    static public function getUrlPage() {
        return 'http://www.zixn.ru/compare-hosting-performance-table-html';
    }

    /**
     * Тест MySQL
     * Возвращает массив с операциями к базе данных
     * alltime - в массиве общее время выполненеия запросов
     * error в массиве покатазель ошибки к базе
     * @return array $arRes(BENCHMARK5000000,BENCHMARK10000000,BENCHMARK250000,alltime,error)
     */
    public function getMySqlTest() {
        global $wpdb;

//Параметры для запроса к базе
        $arQuery = array(
            'select BENCHMARK(500000000, EXTRACT(YEAR FROM NOW()))',
            'select BENCHMARK(10000000,ENCODE(\'привет!\',\'Пока!\'))',
            'select BENCHMARK(25000000,1+1*2);'
        );
//Результат 
        $arRes = array();

        $cnt = count($arQuery);
        for ($i = 0; $i < $cnt; $i++) {
            $time_start = microtime(true);
            $dotest = $wpdb->query("$arQuery[$i]");

            $result = number_format(microtime(true) - $time_start, 3);
//Если результат меньше 0.02 скорее всего mySQL не работает или сломался
            if ($result < 0.02) {
                $mysqlerror = 1;
                $result = 99.99;
            }

            $totalTime = $totalTime + $result;
            $arRes[$i] = $result;
        }


        $count = count($arRes);
        for ($i = 0; $i < $count; $i++) {
            $mysqltemp = $mysqltemp . "," . $arRes[$i];
        }
        if (!isset($mysqlerror)) {
            $arRes['alltime'] = $totalTime;
        } else {
            $MySQLtotaltime = 99.99;
            $arRes['error'] = "Ошибка теста MySQL";
        }

        return $arRes;
    }

    /**
     * Тест MySQL CRUD
     * Создаёт, вставляет, обновляет, удалёт запись в таблице
     * Возвращет массив 0 - время на все опепации 1 - количество операций в секунду
     * @global \zixnru\chp\type $wpdb
     * @return array $arResult($result, $count)
     */
    public function getMySqlCRUD() {
        global $wpdb;
        $arResult = array();
//
        $tableprefix = $wpdb->prefix . "zixn_chp";
        $nameInput = 'testdate';

        $createtable = $wpdb->query("CREATE TABLE if not exists `$tableprefix` (`$nameInput` text NOT NULL DEFAULT '')");

        $time_start = microtime(true);
        for ($i = 0; $i < $this->runquerycount; $i++) {
            $test = $wpdb->query("insert into $tableprefix ($nameInput) values ('$this->mysqlquerydata');");
            $test = $wpdb->query("select * from $tableprefix;");
            $test = $wpdb->query("update $tableprefix set $nameInput='';");
            $test = $wpdb->query("delete from $tableprefix;");
        }
        $test = $wpdb->query("DROP TABLE IF EXISTS $tableprefix;");
        $result = number_format(microtime(true) - $time_start, 3);
        $count = $this->runquerycount / $result;
        $arResult[0] = $result;
        $arResult[1] = $count;

        return $arResult;
    }

    /**
     * Комплексный тест PHP при помощи вскпомогательных функций
     * @return array $arResult = array(match','string','loop','cond','alltime');
     */
    public function getPHPTest() {
        $arResult = array();
        $PHPtotaltime = 0;
        $testmathresult = $this->getPHPMath(); //Математика
        $PHPtotaltime = $PHPtotaltime + $testmathresult;
//
        $teststringresult = $this->getPHPString(); //Работа с строками
        $PHPtotaltime = $PHPtotaltime + $teststringresult;
//
        $testloopresult = $this->getPHPLoop(); //скорость в циклах
        $PHPtotaltime = $PHPtotaltime + $testloopresult;
//
        $testifelseresult = $this->getPHPCond(); //Скорость сравнений
        $PHPtotaltime = $PHPtotaltime + $testifelseresult;
        $arResult = array(
            'match' => $testmathresult,
            'string' => $teststringresult,
            'loop' => $testloopresult,
            'cond' => $testifelseresult,
            'alltime' => $PHPtotaltime
        );

        return $arResult;
    }

    /**
     * Вспомогательная функция тест PHP
     * Выполняет встроенные математические вычисления PHP, передаёт число count
     * @return string
     */
    protected function getPHPMath() {
        $count = 50000;

        $time_start = microtime(true);
//математические Функции php
        $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
        foreach ($mathFunctions as $key => $function) {
            if (!function_exists($function))
                unset($mathFunctions[$key]);
        }
//Запуск функций в цикле с передачей числа $count
        for ($i = 0; $i < $count; $i++) {
            foreach ($mathFunctions as $function) {
                $r = call_user_func_array($function, array($i));
            }
        }
        $result = number_format(microtime(true) - $time_start, 3);
        return $result;
    }

    /**
     * Вспомогательная функция тест PHP
     * Выполняет встроенные операции с строками PHP
     * @return string
     */
    protected function getPHPString() {
        $count = 100000;
        $time_start = microtime(true);
        $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");
        foreach ($stringFunctions as $key => $function) {
            if (!function_exists($function))
                unset($stringFunctions[$key]);
        }
        $string = "From the Western seas to the gate East Not many minds from the benefits direct and strong Evil can distinguish... the reason we rarely Inspires";
        for ($i = 0; $i < $count; $i++) {
            foreach ($stringFunctions as $function) {
                $r = call_user_func_array($function, array($string));
            }
        }
        $result = number_format(microtime(true) - $time_start, 3);
        return $result;
    }

    /**
     * Вспомогательная функция тест PHP
     * Прогоняет число через циклы(скорость циклической работы)
     * @return string
     */
    protected function getPHPLoop() {
        $count = 10000000;
        $time_start = microtime(true);
        for ($i = 0; $i < $count; ++$i) {
            ;
        }
        $i = 0;
        while ($i < $count) {
            ++$i;
        }
        $result = number_format(microtime(true) - $time_start, 3);
        return $result;
    }

    /**
     * Вспомогательная функция тест PHP
     * Прогоняет число через циклы(скорость циклической работы)
     * @return string
     */
    protected function getPHPCond() {
        $count = 10000000;
        $time_start = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            if ($i == -1) {
                
            } elseif ($i == -2) {
                
            } else if ($i == -3) {
                
            }
        }
        $result = number_format(microtime(true) - $time_start, 3);
        return $result;
    }

    /**
     * Сетевой тест
     * Тест DNS + скачивание файла с сервера датацентра
     * @return array array('dns','file')
     */
    public function getNetworkTest() {

        $arResult = array();

//Бьёмся до гугла
        $time_start = microtime(true);
        $data = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $data .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $data .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $data .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $data .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $data .= file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js');
        $time_end = microtime(true) - $time_start;
        $lenfile = strlen($data);
//Результат dns
        $cdnDNS = (($lenfile * 8) / 1024 / 1024) / $time_end;

//Доступ к серверу 
        $whtime_start = microtime(true);
        $whdata = file_get_contents($this->patchremotefile);
        $whtime_end = microtime(true) - $whtime_start;
        $whlenfile = strlen($whdata);
//Результат получения файла 
        $loadfile = (($whlenfile * 8) / 1024 / 1024) / $whtime_end;
        $arResult['dns'] = sprintf('%.2f', $cdnDNS);
        $arResult['file'] = sprintf('%.2f', $loadfile);
        return $arResult;
    }

    /**
     * Записывает значения тестов в базу
     * @param string $date Дата теста
     * @param string $hosting Имя сервера
     * @param string $vphp версия PHP
     * @param string $vmysql версия MySQL
     * @param string $tphp тест php
     * @param string $tmysql тест MySQL
     * @param string $summ суммарный тест php mysql
     * @param string $ipmysql Индекс производительности MySQL
     * @param string $ipphp Индекс производительности PHP
     */
    public function setJornalTest($date, $hosting, $vphp, $vmysql, $tphp, $tmysql, $summ, $ipmysql, $ipphp) {


        $jornal_old = get_option('chp_jornal');
        $jornal_temp = array('date' => $date, 'hosting' => $hosting, 'vphp' => $vphp,
            'vmysql' => $vmysql, 'tphp' => $tphp, 'tmysql' => $tmysql, 'summ' => $summ,
            'ipmysql' => $ipmysql, 'ipphp' => $ipphp);
        $jornal_new = array();
        $jornal_new = $jornal_old;
        array_push($jornal_new, $jornal_temp);
        update_option('chp_jornal', $jornal_new);
    }

    /**
     * Отправляет тест на удалённый сервер для сравнения 
     * @param string $date Дата теста
     * @param string $hosting Имя сервера
     * @param string $vphp версия PHP
     * @param string $vmysql версия MySQL
     * @param string $tphp тест php
     * @param string $tmysql тест MySQL
     * @param string $summ суммарный тест php mysql
     * @param string $ipmysql Индекс производительности MySQL
     * @param string $ipphp Индекс производительности PHP
     */
    public function sendTest($date, $hosting, $vphp, $vmysql, $tphp, $tmysql, $summ, $ipmysql, $ipphp) {

        if (!self::isFunctionServer('curl_init')) {
            return FALSE;
        }

        $setTest = get_option('chp_sendtest');
        $nsserver = array(
            'WHOIS' => self::getWhoisInfo(),
            'NS' => self::getNsServer(),
        );
        $nsserver = json_encode($nsserver);

//Отправка только одного теста (первого)
        if ($setTest == 0) {
        //if (0 == 0) {

            $postData = http_build_query(
                    array(
                        'date' => $date,
                        'hosting' => $hosting,
                        'vphp' => $vphp,
                        'vmysql' => $vmysql,
                        'tphp' => $tphp,
                        'tmysql' => $tmysql,
                        'summ' => $summ,
                        'ipmysql' => $ipmysql,
                        'ipphp' => $ipphp,
                        'nsserver' => $nsserver
                    )
            );

// создаем подключение
            $ch = curl_init($this->urlserverstat);
// устанавлваем даные для отправки
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
// флаг о том, что нужно получить результат
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// отправляем запрос
            $resultPost = curl_exec($ch);
// закрываем соединение
            curl_close($ch);

            if ($resultPost == 'yes') {
                update_option('chp_sendtest', '1');
            }
        }
    }

    /**
     * Обрезка контента по условию начала и конца
     * @param string $text Текст который мы хотим обрезать
     * @param string $start Начало от куда нужно обрезать
     * @param string $end Конец до куда нужно резать
     * @return string строку обрезанную от старт до енд(не включая края)
     */
    static public function cutTextStartEnd($text, $start, $end) {
        $posStart = stripos($text, $start);
        if ($posStart === false)
            return FALSE;

        $text = substr($text, $posStart + strlen($start));
        $posEnd = stripos($text, $end);
        if ($posEnd === false)
            return FALSE;

        $result = substr($text, 0, 0 - (strlen($text) - $posEnd));
        return $result;
    }

    /**
     * Возвращает информацию из Whois
     * Как вариант определения хостинга
     * Работает только с доменами второго уровня
     */
    public static function getWhoisInfo($site = null) {
        if (empty($site)) {
            $site = parse_url(home_url());
            $site = $site['host'];
        }
        $urlservice = 'http://htmlweb.ru/analiz/api.php?whois';
        $resultPost = file_get_contents($urlservice . '&url=' . $site . '&json');
        $arResult = json_decode($resultPost, true);
        if (empty($arResult['whois'])) {
            return false;
        } else {
            $strNsserver = self::cutTextStartEnd($arResult['whois'], 'nserver:', 'state:');

            return $strNsserver;
        }
    }

    /**
     * Получает информацию об NS серверах,
     * Возвращает только ns сервер, без домена сайта
     * 
     */
    public static function getNsServer($site = null) {
        if (empty($site)) {
            $site = parse_url(home_url());
            $site = $site['host'];
        }
        $arResult = array();
        $arNs = dns_get_record($site, DNS_NS);
        if (!empty($arNs)) {
            if (is_array($arNs)) {
                foreach ($arNs as $key => $value) {
                    $arResult['ns' . $key] = $value['target'];
                }
            }
        }
        return $arResult;
    }

}
