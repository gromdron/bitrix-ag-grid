# Bitrix24: AgGrid with Google Spreadsheet integration

## Установка
1) Установить битрикс последней версии
2) Клонировать репозиторий
3) Завести создать API, получить файл credentials.json. разместить его на сервере
4) В /home/bitrix/www/local/php_interface/constants.php изменить значения констант
<pre>
GS_TOKEN_CREDENTIALS - путь к файлу credentials.json
GS_SHEET_ID - ID excel-файла в google
GS_SHEET_NAME - название листа в excel
</pre>

5) Установить composer-зависимости
Перейти в директорию /local/php_interface/ и выполнить composer init

6) Создать таблицу Fusion\Sheet\DataTable

<pre>\Fusion\Sheet\DataTable::getEntity()->createDBTable();</pre>

7) Повестить скрипт на cron - /local/php_interface/console/gs_sync.php
