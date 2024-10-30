<?php

/* 
 * Code is distributed as-is
 * the Developer may change the code at its discretion without prior notice
 * Developers: Djo
 * Website: http://zixn.ru
 * Twitter: https://twitter.com/Zixnru
 * Email: izm@zixn.ru
 */

?>
<fieldset>
            <legend>Производительность вашего сервера</legend>
            <table class="form-table">
                <thead>
                    <tr>
                        <th>Опция</th>
                        <th>Значение/пояснение</th> 

                    </tr>
                </thead>
                <tr valign="top">
                    <th scope="row">Дата теста</th>
                    <td>
                        <p><?php echo $date; ?> </p>
                        <span class="description">Дата текущего теста</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Тест MySQL №1</th>
                    <td>
                        <p><?php echo $mySQLCRUD[0] . "сек"; ?> </p>
                        <span class="description">Операции записи, чтения, обновления, удаления. Всего операций в секунду <?php echo  sprintf('%.2f',$mySQLCRUD[1]); ?> </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Тест MySQL №2</th>
                    <td>
                        <p><?php echo (empty($mySQLTest['error']) ? $mySQLTest[0] . "сек" : $mySQLTest['error']); ?> </p>
                        <span class="description">Цикл операций в базе по вытаскиванию интервала дат</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Тест MySQL №3</th>
                    <td>
                        <p><?php echo (empty($mySQLTest['error']) ? $mySQLTest[1] . "сек" : $mySQLTest['error']); ?> </p>
                        <span class="description">Цикл операций в базе по кодированию простой строки</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Тест MySQL №4</th>
                    <td>
                        <p><?php echo (empty($mySQLTest['error']) ? $mySQLTest[2] . "сек" : $mySQLTest['error']); ?> </p>
                        <span class="description">Цикл операций в базе по вычислению математического выражения</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">MySQL общее время</th>
                    <td>
                        <p><?php echo $tmysql. "сек" ; ?> </p>
                        <span class="description">Общее значение MySQL теста</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PHP математика</th>
                    <td>
                        <p><?php echo $phpAllTest['match'] . "сек"; ?> </p>
                        <span class="description">Различные математические операции php в цикле</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PHP строки</th>
                    <td>
                        <p><?php echo $phpAllTest['string'] . "сек"; ?> </p>
                        <span class="description">Различные операции с строкой в цикле</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PHP циклы</th>
                    <td>
                        <p><?php echo $phpAllTest['loop'] . "сек"; ?> </p>
                        <span class="description">Скорость работы с циклами</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PHP сравнения</th>
                    <td>
                        <p><?php echo $phpAllTest['cond'] . "сек"; ?> </p>
                        <span class="description">Скорость работы с операциями сравнения</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PHP общее время</th>
                    <td>
                        <p><?php echo $tphp . "сек"; ?> </p>
                        <span class="description">Общее время php теста</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Сетевой тест 1</th>
                    <td>
                        <p><?php echo $networkTest['dns'] . "Мегабит в сек"; ?> </p>
                        <span class="description">Скорость доступа к ближайшим серверам google</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Сетевой тест 2</th>
                    <td>
                        <p><?php echo $networkTest['file'] . "Мегабит в сек"; ?> </p>
                        <span class="description">Скорость доступа к одному из серверов в России</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">SQL + PHP</th>
                    <td>
                        <p><?php echo $summ . "сек"; ?> </p>
                        <span class="description">Суммарное время теста</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Производительность MySQL</th>
                    <td>
                        <p><?php echo $ipmysql; ?> </p>
                        <span class="description">Условный индекс производительности MySQL. Чем это значение выше, тем быстрее работают операции с базой данных на вашем сайте.</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Производительность PHP</th>
                    <td>
                        <p><?php echo $ipphp; ?> </p>
                        <span class="description">Условный индекс производительности PHP. Чем это значение выше, тем быстрее исполняется ваш код на сервере.</span>
                    </td>
                </tr>

            </table>
        </fieldset>