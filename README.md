<p align="center"><a href="https://moscow.kursivalut.ru" target="_blank"><img src="https://kursivalut.ru/img/pig.svg" width="150" alt="Kursivalut Logo"></a></p>

## Currency ads aggregator
<p>This app is fast, robust and fully automatic telegram & vk posts analyzer.</p>
<p>It collects currency exchange ads from different sources and stores them in the website with sorting, filtering and searching functions. The app can even parse currency rate from the ad and store it in DB. It can fetch data from website's api by cron, like it works with vk.com or receive data on it's own api like it works with telegram-websocket server.</p>

<p>Telegram websocket server is based on <a href="https://github.com/xtrime-ru/TelegramApiServer">this repo</a> with some customization, which you can find in app/Http/websocket-example.php (need to put this file in 'examples' folder of TelegramApiServer).</p>
<p>This app has multilocalization which means that you can use different domains for different sets of telegram channels or vk publics. Each locale settings stores in config/locales.php.</p>
<p>Regex patterns, currency sets and other configs are in config/common.php</p>
<p>App is based on PHP8.2 and Laravel 10 with Nginx on the server side.</p>
<p>Working version you can find here <a href="https://moscow.kursivalut.ru">kursivalut.ru</a>.</p>
