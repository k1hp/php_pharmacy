## Краткое руководство по запуску проекта

#### 1. Склонируйте репозиторий
---
```
git clone https://github.com/k1hp/php_pharmacy.git
```
либо другая ссылка, если проект переедет

#### 2. Запуск проекта осуществляется через docker compose
---
- перейдите в корень проекта (там вы сможете найти файл docker-compose.yml)
- запустите
```
docker compose up --build
```

P.S.
Сначала возможно ругательство из-за отсутствия .lock файла
Логи будут примерно такие 
```
Attaching to apteka_db, php-1
apteka_db  |
apteka_db  | PostgreSQL Database directory appears to contain a database; Skipping initialization
apteka_db  |
apteka_db  | 2026-01-02 19:07:49.998 UTC [1] LOG:  starting PostgreSQL 15.15 (Debian 15.15-1.pgdg13+1) on x86_64-pc-linux-gnu, compiled by gcc (Debian 14.2.0-19) 14.2.0, 64-bit
apteka_db  | 2026-01-02 19:07:49.999 UTC [1] LOG:  listening on IPv4 address "0.0.0.0", port 5432
apteka_db  | 2026-01-02 19:07:49.999 UTC [1] LOG:  listening on IPv6 address "::", port 5432
apteka_db  | 2026-01-02 19:07:50.003 UTC [1] LOG:  listening on Unix socket "/var/run/postgresql/.s.PGSQL.5432"
apteka_db  | 2026-01-02 19:07:50.008 UTC [29] LOG:  database system was shut down at 2026-01-02 19:06:56 UTC
apteka_db  | 2026-01-02 19:07:50.017 UTC [1] LOG:  database system is ready to accept connections
php-1      | Switching uid for 'www-data' to 33
php-1      | usermod: no changes
php-1      | The repository at "/app" does not have the correct ownership and git refuses to use it:
php-1      |
php-1      | fatal: detected dubious ownership in repository at '/app'
php-1      | To add an exception for this directory, call:
php-1      |
php-1      |    git config --global --add safe.directory /app
php-1      |
php-1      | No composer.lock file present. Updating dependencies to latest instead of installing from lock file. See https://getcomposer.org/install for more information.
php-1      | Loading composer repositories with package information
php-1      | https://repo.packagist.org could not be fully loaded (curl error 28 while downloading https://repo.packagist.org/packages.json: Connection timed out after 10003 milliseconds), package information was loaded from the local cache and may be out of date
php-1      | A connection timeout was encountered. If you intend to run Composer without connecting to the internet, run the command again prefixed with COMPOSER_DISABLE_NETWORK=1 to make Composer run in offline mode.
```
В этом случае вам необходимо подождать 1-2 минуты и появятся логи установки, дождитесь пожалуйста.
```
php-1      | Updating dependencies
php-1      | Lock file operations: 80 installs, 0 updates, 0 removals
php-1      |   - Locking behat/gherkin (v4.16.1)
php-1      |   - Locking bower-asset/inputmask (5.0.9)
php-1      |   - Locking bower-asset/jquery (3.7.1)
php-1      |   - Locking bower-asset/punycode (v1.4.1)
php-1      |   - Locking bower-asset/yii2-pjax (2.0.8)
php-1      |   - Locking cebe/markdown (1.2.1)
php-1      |   - Locking codeception/codeception (5.3.3)
php-1      |   - Locking codeception/lib-asserts (3.1.0)
php-1      |   - Locking codeception/lib-innerbrowser (4.0.8)
php-1      |   - Locking codeception/lib-web (2.0.1)
php-1      |   - Locking codeception/module-asserts (3.3.0)
php-1      |   - Locking codeception/module-filesystem (3.0.2)
php-1      |   - Locking codeception/module-yii2 (1.1.12)
php-1      |   - Locking codeception/stub (4.2.1)
php-1      |   - Locking codeception/verify (3.3.0)
php-1      |   - Locking doctrine/lexer (3.0.1)
php-1      |   - Locking egulias/email-validator (4.0.4)
php-1      |   - Locking ezyang/htmlpurifier (v4.19.0)
php-1      |   - Locking fakerphp/faker (v1.24.1)
php-1      |   - Locking guzzlehttp/psr7 (2.8.0)
php-1      |   - Locking myclabs/deep-copy (1.13.4)
php-1      |   - Locking nikic/php-parser (v5.7.0)
php-1      |   - Locking phar-io/manifest (2.0.4)
php-1      |   - Locking phar-io/version (3.2.1)
php-1      |   - Locking phpspec/php-diff (v1.1.3)
php-1      |   - Locking phpunit/php-code-coverage (12.5.2)
php-1      |   - Locking phpunit/php-file-iterator (6.0.0)
php-1      |   - Locking phpunit/php-invoker (6.0.0)
php-1      |   - Locking phpunit/php-text-template (5.0.0)
php-1      |   - Locking phpunit/php-timer (8.0.0)
php-1      |   - Locking phpunit/phpunit (12.5.4)
php-1      |   - Locking psr/container (2.0.2)
php-1      |   - Locking psr/event-dispatcher (1.0.0)
php-1      |   - Locking psr/http-factory (1.1.0)
php-1      |   - Locking psr/http-message (2.0)
php-1      |   - Locking psr/log (3.0.2)
php-1      |   - Locking psy/psysh (v0.12.18)
php-1      |   - Locking ralouphie/getallheaders (3.0.3)
php-1      |   - Locking sebastian/cli-parser (4.2.0)
php-1      |   - Locking sebastian/comparator (7.1.3)
php-1      |   - Locking sebastian/complexity (5.0.0)
php-1      |   - Locking sebastian/diff (7.0.0)
php-1      |   - Locking sebastian/environment (8.0.3)
php-1      |   - Locking sebastian/exporter (7.0.2)
php-1      |   - Locking sebastian/global-state (8.0.2)
php-1      |   - Locking sebastian/lines-of-code (4.0.0)
php-1      |   - Locking sebastian/object-enumerator (7.0.0)
php-1      |   - Locking sebastian/object-reflector (5.0.0)
php-1      |   - Locking sebastian/recursion-context (7.0.1)
php-1      |   - Locking sebastian/type (6.0.3)
php-1      |   - Locking sebastian/version (6.0.0)
php-1      |   - Locking staabm/side-effects-detector (1.0.5)
php-1      |   - Locking symfony/browser-kit (v8.0.3)
php-1      |   - Locking symfony/console (v8.0.3)
php-1      |   - Locking symfony/css-selector (v8.0.0)
php-1      |   - Locking symfony/deprecation-contracts (v3.6.0)
php-1      |   - Locking symfony/dom-crawler (v8.0.1)
php-1      |   - Locking symfony/event-dispatcher (v8.0.0)
php-1      |   - Locking symfony/event-dispatcher-contracts (v3.6.0)
php-1      |   - Locking symfony/finder (v8.0.3)
php-1      |   - Locking symfony/mailer (v8.0.3)
php-1      |   - Locking symfony/mime (v8.0.0)
php-1      |   - Locking symfony/polyfill-ctype (v1.33.0)
php-1      |   - Locking symfony/polyfill-intl-grapheme (v1.33.0)
php-1      |   - Locking symfony/polyfill-intl-idn (v1.33.0)
php-1      |   - Locking symfony/polyfill-intl-normalizer (v1.33.0)
php-1      |   - Locking symfony/polyfill-mbstring (v1.33.0)
php-1      |   - Locking symfony/service-contracts (v3.6.1)
php-1      |   - Locking symfony/string (v8.0.1)
php-1      |   - Locking symfony/var-dumper (v8.0.3)
php-1      |   - Locking symfony/yaml (v8.0.1)
php-1      |   - Locking theseer/tokenizer (2.0.1)
php-1      |   - Locking twbs/bootstrap (v5.3.8)
php-1      |   - Locking yiisoft/yii2 (2.0.53)
php-1      |   - Locking yiisoft/yii2-bootstrap5 (2.0.51)
php-1      |   - Locking yiisoft/yii2-composer (2.0.11)
php-1      |   - Locking yiisoft/yii2-debug (2.1.27)
php-1      |   - Locking yiisoft/yii2-faker (2.0.5)
php-1      |   - Locking yiisoft/yii2-gii (2.2.7)
php-1      |   - Locking yiisoft/yii2-symfonymailer (2.0.4)
php-1      | Writing lock file
php-1      | Installing dependencies from lock file (including require-dev)
php-1      | Package operations: 80 installs, 0 updates, 0 removals
php-1      |     0 [>---------------------------]    0 [->--------------------------]
php-1      |   - Installing yiisoft/yii2-composer (2.0.11): Extracting archive
php-1      |   - Installing behat/gherkin (v4.16.1): Extracting archive
php-1      |   - Installing bower-asset/jquery (3.7.1): Extracting archive
php-1      |   - Installing bower-asset/inputmask (5.0.9): Extracting archive
php-1      |   - Installing bower-asset/punycode (v1.4.1): Extracting archive
php-1      |   - Installing bower-asset/yii2-pjax (2.0.8): Extracting archive
php-1      |   - Installing cebe/markdown (1.2.1): Extracting archive
php-1      |   - Installing symfony/css-selector (v8.0.0): Extracting archive
php-1      |   - Installing staabm/side-effects-detector (1.0.5): Extracting archive
php-1      |   - Installing sebastian/version (6.0.0): Extracting archive
php-1      |   - Installing sebastian/type (6.0.3): Extracting archive
php-1      |   - Installing sebastian/recursion-context (7.0.1): Extracting archive
php-1      |   - Installing sebastian/object-reflector (5.0.0): Extracting archive
php-1      |   - Installing sebastian/object-enumerator (7.0.0): Extracting archive
php-1      |   - Installing sebastian/global-state (8.0.2): Extracting archive
php-1      |   - Installing symfony/polyfill-mbstring (v1.33.0): Extracting archive
php-1      |   - Installing sebastian/exporter (7.0.2): Extracting archive
php-1      |   - Installing sebastian/environment (8.0.3): Extracting archive
php-1      |   - Installing sebastian/diff (7.0.0): Extracting archive
php-1      |   - Installing sebastian/comparator (7.1.3): Extracting archive
php-1      |   - Installing sebastian/cli-parser (4.2.0): Extracting archive
php-1      |   - Installing phpunit/php-timer (8.0.0): Extracting archive
php-1      |   - Installing phpunit/php-text-template (5.0.0): Extracting archive
php-1      |   - Installing phpunit/php-invoker (6.0.0): Extracting archive
php-1      |   - Installing phpunit/php-file-iterator (6.0.0): Extracting archive
php-1      |   - Installing theseer/tokenizer (2.0.1): Extracting archive
php-1      |   - Installing symfony/polyfill-ctype (v1.33.0): Extracting archive
php-1      |   - Installing nikic/php-parser (v5.7.0): Extracting archive
php-1      |   - Installing sebastian/lines-of-code (4.0.0): Extracting archive
php-1      |   - Installing sebastian/complexity (5.0.0): Extracting archive
php-1      |   - Installing phpunit/php-code-coverage (12.5.2): Extracting archive
php-1      |   - Installing phar-io/version (3.2.1): Extracting archive
php-1      |   - Installing phar-io/manifest (2.0.4): Extracting archive
php-1      |   - Installing myclabs/deep-copy (1.13.4): Extracting archive
php-1      |   - Installing phpunit/phpunit (12.5.4): Extracting archive
php-1      |   - Installing ralouphie/getallheaders (3.0.3): Extracting archive
php-1      |   - Installing psr/http-message (2.0): Extracting archive
php-1      |   - Installing psr/http-factory (1.1.0): Extracting archive
php-1      |   - Installing guzzlehttp/psr7 (2.8.0): Extracting archive
php-1      |   - Installing codeception/lib-web (2.0.1): Extracting archive
php-1      |   - Installing codeception/lib-asserts (3.1.0): Extracting archive
php-1      |   - Installing symfony/yaml (v8.0.1): Extracting archive
php-1      |   - Installing symfony/var-dumper (v8.0.3): Extracting archive
php-1      |   - Installing symfony/finder (v8.0.3): Extracting archive
php-1      |   - Installing psr/event-dispatcher (1.0.0): Extracting archive
php-1      |   - Installing symfony/event-dispatcher-contracts (v3.6.0): Extracting archive
php-1      |   - Installing symfony/event-dispatcher (v8.0.0): Extracting archive
php-1      |   - Installing symfony/polyfill-intl-normalizer (v1.33.0): Extracting archive
php-1      |   - Installing symfony/polyfill-intl-grapheme (v1.33.0): Extracting archive
php-1      |   - Installing symfony/string (v8.0.1): Extracting archive
php-1      |   - Installing symfony/deprecation-contracts (v3.6.0): Extracting archive
php-1      |   - Installing psr/container (2.0.2): Extracting archive
php-1      |   - Installing symfony/service-contracts (v3.6.1): Extracting archive
php-1      |   - Installing symfony/console (v8.0.3): Extracting archive
php-1      |   - Installing psy/psysh (v0.12.18): Extracting archive
php-1      |   - Installing codeception/stub (4.2.1): Extracting archive
php-1      |   - Installing codeception/codeception (5.3.3): Extracting archive
php-1      |   - Installing codeception/module-asserts (3.3.0): Extracting archive
php-1      |   - Installing codeception/module-filesystem (3.0.2): Extracting archive
php-1      |   - Installing symfony/dom-crawler (v8.0.1): Extracting archive
php-1      |   - Installing symfony/browser-kit (v8.0.3): Extracting archive
php-1      |   - Installing codeception/lib-innerbrowser (4.0.8): Extracting archive
php-1      |   - Installing codeception/module-yii2 (1.1.12): Extracting archive
php-1      |   - Installing codeception/verify (3.3.0): Extracting archive
php-1      |   - Installing symfony/polyfill-intl-idn (v1.33.0): Extracting archive
php-1      |   - Installing doctrine/lexer (3.0.1): Extracting archive
php-1      |   - Installing egulias/email-validator (4.0.4): Extracting archive
php-1      |   - Installing ezyang/htmlpurifier (v4.19.0): Extracting archive
php-1      |   - Installing psr/log (3.0.2): Extracting archive
php-1      |   - Installing symfony/mime (v8.0.0): Extracting archive
php-1      |   - Installing yiisoft/yii2 (2.0.53): Extracting archive
php-1      |   - Installing twbs/bootstrap (v5.3.8): Extracting archive
php-1      |   - Installing yiisoft/yii2-bootstrap5 (2.0.51): Extracting archive
php-1      |   - Installing yiisoft/yii2-debug (2.1.27): Extracting archive
php-1      |   - Installing fakerphp/faker (v1.24.1): Extracting archive
php-1      |   - Installing yiisoft/yii2-faker (2.0.5): Extracting archive
php-1      |   - Installing phpspec/php-diff (v1.1.3): Extracting archive
php-1      |   - Installing yiisoft/yii2-gii (2.2.7): Extracting archive
php-1      |   - Installing symfony/mailer (v8.0.3): Extracting archive
php-1      |   - Installing yiisoft/yii2-symfonymailer (2.0.4): Extracting archive
php-1      |   0/79 [>---------------------------]   0%
php-1      |  36/79 [============>---------------]  45%
php-1      |  57/79 [====================>-------]  72%
php-1      |  74/79 [==========================>-]  93%
php-1      |  79/79 [============================] 100%
php-1      | 13 package suggestions were added by new dependencies, use `composer suggest` to see details.
php-1      | Generating autoload files
php-1      | 54 packages you are using are looking for funding.
php-1      | Use the `composer fund` command to find out more!
php-1      | Yii Migration Tool (based on Yii v2.0.53)
php-1      |
php-1      | No new migrations found. Your system is up-to-date.
php-1      | AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 172.18.0.3. Set the 'ServerName' directive globally to suppress this message
php-1      | AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 172.18.0.3. Set the 'ServerName' directive globally to suppress this message
php-1      | [Fri Jan 02 19:10:38.416286 2026] [mpm_prefork:notice] [pid 198:tid 198] AH00163: Apache/2.4.65 (Debian) configured -- resuming normal operations
php-1      | [Fri Jan 02 19:10:38.416366 2026] [core:notice] [pid 198:tid 198] AH00094: Command line: 'apache2 -D FOREGROUND'
```

#### 3. Можете потрогать проект по следующему адресу  
---
```
http://localhost:8000
```
