
<?php

use zixnru\chp\functionClass;

$fObj = new functionClass(1);

if (functionClass::isFunctionServer('curl_init')) {
    $curl = 'CURL работает';
} else {
    $curl = 'CURL не работает';
}
?>

<fieldset>
    <legend>Текущие параметры вашего Веб сервера</legend>
    <table class="form-table">
        <thead>
            <tr>
                <th>Опция</th>
                <th>Значение/пояснение</th> 

            </tr>
        </thead>
        <tr valign="top">
            <th scope="row">Сервер</th>
            <td>
                <p><?php echo $fObj->servername; ?> </p>
                <span class="description">Имя вашего сервера/сайта</span>
            </td>

        </tr>
        <tr valign="top">
            <th scope="row">IP адрес</th>
            <td>
                <p><?php echo $fObj->serveraddr; ?> </p>
                <span class="description">IP адрес вашего сервера/сайта</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Ядро ОС</th>
            <td>
                <p><?php echo $fObj->phpuname; ?> </p>
                <span class="description">Информация о ядре системы, версия операционной системы сервера</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">PHP версия</th>
            <td>
                <p><?php echo $fObj->phpversion; ?> </p>
                <span class="description">Версия вашего PHP. Лучше когда версия больше или равна 5.5</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Лимит памяти PHP</th>
            <td>
                <p><?php echo $fObj->memorylimit; ?> </p>
                <span class="description">Максимальное количество оперативной памяти, которое может быть выделено скрипту. Т.е - это максимум, что может запросить скрипт для своей работы</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">POST лимит PHP</th>
            <td>
                <p><?php echo $fObj->postmaxsize; ?> </p>
                <span class="description">Оно же post_max_size.Чаще измеряется в байтах. Ограничение на размер данных, передаваемых в POST запросе.Это значение также влияет на загрузку файлов. Для загрузки больших файлов это значение должно быть больше значения директивы upload_max_filesize</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Размер загружаемого файла PHP</th>
            <td>
                <p><?php echo $fObj->uploadmaxsize; ?> </p>
                <span class="description">Оно же upload_max_filesize.Чаще измеряется в байтах. Максимальный размер файла, который допускается для загрузки на сервер.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Время выполнения PHP скрипта</th>
            <td>
                <p><?php echo $fObj->maxexectime . " сек"; ?> </p>
                <span class="description">Эта директива задает максимальное время в секундах, в течение которого скрипт должен полностью загрузиться. Если этого не происходит, анализатор завершает его работу. Этот механизм помогает предотвратить зависание сервера из-за криво написанного скрипта.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Путь до php.ini</th>
            <td>
                <p><?php echo $fObj->phpinilocation; ?> </p>
                <span class="description">Путь до файла, который позволяет настроить ваш PHP</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Webserver</th>
            <td>
                <p><?php echo $fObj->apacheversion; ?> </p>
                <span class="description">Версия веб сервера</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Модули вебсервера</th>
            <td>
                <p><?php print_r($fObj->loadedmodules); ?> </p>
                <span class="description">Модули активные на сервер. Модули расширяют возможности вашего сервера.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Платформа</th>
            <td>
                <p><?php echo $fObj->phpos; ?> </p>
                <span class="description">Операционная система сервера</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Интерфейс шлюза</th>
            <td>
                <p><?php echo $fObj->servermode; ?> </p>
                <span class="description">Стандарт интерфейса, используемого для связи внешней программы с веб-сервером. Программу, которая работает по такому интерфейсу совместно с веб-сервером, принято называть шлюзом, хотя многие предпочитают названия «скрипт» (сценарий) или «CGI-программа».</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Абсолютный путь</th>
            <td>
                <p><?php echo $fObj->pathserver; ?> </p>
                <span class="description">Абсолютный путь от корня сервера(вашей учётной записи хостинга) до исполняемого сейчас файла</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">MySQL версия</th>
            <td>
                <p><?php echo $fObj->mysqlversion; ?> </p>
                <span class="description">Версия вашего MySQL</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Нагрузка за 1 мин</th>
            <td>
                <p><?php echo $fObj->loadserver[0]; ?> </p>
                <span class="description">Средняя загруженность вашего сервера за 1 минут.Число процессов в очереди системных процессов. Если этот показатель очень высок, можно судить о том, что сервер перегружен.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Нагрузка за 5 мин</th>
            <td>
                <p><?php echo $fObj->loadserver[1]; ?> </p>
                <span class="description">Средняя загруженность вашего сервера за 5 минут.Число процессов в очереди системных процессов</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Нагрузка за 15 мин</th>
            <td>
                <p><?php echo $fObj->loadserver[2]; ?> </p>
                <span class="description">Средняя загруженность вашего сервера за 15 минут.Число процессов в очереди системных процессов</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">CURL</th>
            <td>
                <p><?php echo $curl; ?> </p>
                <span class="description">CURL Является обязательным условием работы многих плагинов и сервисов. Если у вас не работает CURL, сообщите это вашему хостинг провайдеру.</span>
            </td>
        </tr>

    </table>
</fieldset>

