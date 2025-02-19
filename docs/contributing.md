# Початок роботи з проектом

## Розробка за допомогою docker

Для початку у вашій системі повині бути встановлені залежності:

- docker
- docker-compose
- make

### Робота через cli

**Ініціалізація проекта** (повністю пересобирає увесь проект, встановлює бібілотеки і т.п.)

```bash
make init
```

Після чого сервіс повинен бути доступний за посиланням localhost:8080

**Завершення роботи з проектом**

```bash
make down
```

Зупиняє роботу усіх докер контейнерів пов'язанних з сервісом.

**Запуск проекту** (без пересбирання)

```bash
make up
```

Більше корисних команд ви можете знайти у файлі [Makefile](../Makefile)

**Запуск процесу в контейнері**

```bash
docker-compose exec php-fpm {your command}
```

Де {your command} - будь яка cli команда, що виконається всередині контейнеру з php.

Наприклад, це може знадобитись, щоб обновити залежності через composer

```bash
docker-compose exec php-fpm composer update
```

Оскільки файлові системи пов'язані через volume, то будь-яка зміна всередині контейнеру
відображається на host машині (тобто якщо змінити файл в середині контейнеру, то він зміниться і на основній ОС). Але це
стосується тільки папки з застосунком (яка знаходиться в /var/app всередині контейнеру).

## Налаштування PhpStorm

1. Додайте інтерпретатор
   ![Cli-interpreter 1](img/getting-started/php-storm/cli-interpreter-1.png)
   ![Cli-interpreter 2](img/getting-started/php-storm/cli-interpreter-2.png)
   ![Cli-interpreter 3](img/getting-started/php-storm/cli-interpreter-3.png)
   ![Cli-interpreter 4](img/getting-started/php-storm/cli-interpreter-4.png)

2. Налаштуйте composer
   ![Composer settings](img/getting-started/php-storm/composer-settings.png)

3. Налаштуйте Xdebug
   ![Debug settings](img/getting-started/php-storm/debug-settings.png?raw=true)
   ![Debug settings](img/getting-started/php-storm/debug-settings-server-name.png)
   ![Xdebug server settings](img/getting-started/php-storm/servers-settings.png)
   ![Docker settings](img/getting-started/php-storm/docker-settings.png)

## Локальні конфіги

Локальні конфіги - це ті які не потрабляють в гіт репозиторій, а отже будуть доступні лише на вашій машині.
Для того, щоб створити локальний конфіг достатньо щоб назва файлу конфігу відповідала шаблону `*.local.php`

Також окрім локальних конфігів можна створювати конфіги, що будуть залежати від оточення в якому запущенно сервер, тобто
від змінної оточення APP_ENV (dev, prod, test). Для цього достатньо щоб назва файлу конфігу відповідала шаблону
`{{,*.}global.{$appEnv}.php`, наприклад 'db.global.dev.php' - буде підключенно тільки якщо змінна APP_ENV = dev

## Режим роботи з Xdebug

Xdebug можна сконфігурувати по різному і кожен розробник може мати особисті переваги. Тому в `docker/php-fpm/conf.d`
можна додавати свої локальні файли з конфігурацією, що закінчуються на `.local.ini`. Наприклад можна додати файл
`xdebug.local.ini` і дописати туди конфігурації під свою IDE:

```
xdebug.idekey = PHPSTORM
```

Ще одна опція, яку можна визначити під себе,
це [xdebug.start_with_request](https://xdebug.org/docs/all_settings#start_with_request). Вона контролює те, коли буде
запускатись xdebug:

- Якщо поставити `yes`, то він буде запускатись кожен раз при виконанні php скрита (як fpm так і cli).
  Це може бути незручно у випадках, коли хочеться виконати скрипт без запуску дебагера, і якщо вимкнути debug
  listener в PhpStorm, то буде писатись ворнінг `Xdebug: [Step Debug] Could not connect to debugging client. Tried:
  host.docker.internal:9003 (fallback through xdebug.client_host/xdebug.client_port) :-(`.
- Якщо поставити `trigger`, то xdebug буде запускатись тільки якщо xdebug знайде ключ `XDEBUG_TRIGGER` в $_ENV, $_GET,
  $_POST чи $_COOKIE. В PhpStorm кожен скрипт чи запит можна запускати як з дебагером, так і без, якщо запускати з
  дебагером, то PhpStorm сам встановить цей ключ. Для запитів з браузера є спеціальне розширення xdebug, яке буде 
  додавати цей ключ.
