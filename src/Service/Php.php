<?php

namespace ContainerBuilder\Service;

class Php extends AbstractService
{
    protected $versions = ['php:5.6-cli', 'php:7.0-cli', 'php:7.1-cli', 'php:7.2-cli'];

    protected $config = [
        'volumes' => [],
        'services' => [
            'php-cli' => [
                'build' => './docker/php/',
                'working_dir' => '/var/www/',
                'volumes' => ['./:/var/www/']
            ]
        ]
    ];

    protected $files = [
        __DIR__ . '/../../data/templates/php/Dockerfile' => 'docker/php/Dockerfile',
    ];

    protected $serviceName = 'php-cli';

    /**
     * Mapping of extensions we support and how we should install them
     * @var array
     */
    protected $extensions = [
        'pecl' => ['xdebug', 'redis', 'mongodb'],
        'stock' => [
            'cmath', 'bz2', 'calendar', 'ctype', 'curl', 'dba', 'dom', 'enchant', 'exif', 'fileinfo', 'filter', 'ftp',
            'gd', 'gettext', 'gmp', 'hash', 'iconv', 'imap', 'interbase', 'intl', 'json', 'ldap', 'mbstring', 'mcrypt',
            'mysqli', 'oci8', 'odbc', 'opcache', 'pcntl', 'pdo', 'pdo_dblib', 'pdo_firebird', 'pdo_mysql', 'pdo_oci',
            'pdo_odbc', 'pdo_pgsql', 'pdo_sqlite', 'pgsql', 'phar', 'posix', 'pspell', 'readline', 'recode',
            'reflection', 'session', 'shmop', 'simplexml', 'snmp', 'soap', 'sockets', 'spl', 'standard', 'sysvmsg',
            'sysvsem', 'sysvshm', 'tidy', 'tokenizer', 'wddx', 'xml', 'xmlreader', 'xmlrpc', 'xmlwriter', 'xsl', 'zip'
        ],
    ];

    public function getFiles()
    {
        $files = parent::getFiles();

        $extensions = 'true';
        if (isset($this->overrides['build-options'])) {
            if (isset($this->overrides['build-options']['extensions'])) {
                $stockExtensions = array_intersect($this->overrides['build-options']['extensions'], $this->extensions['stock']);
                $peclExtensions = array_intersect($this->overrides['build-options']['extensions'], $this->extensions['pecl']);

                $stockString = 'docker-php-ext-install ' . implode(' ', $stockExtensions);
                $peclStrings = [];
                foreach ($peclExtensions as $extension) {
                    $peclStrings[] = 'pecl install -o -f ' . $extension . ' && docker-php-ext-enable ' . $extension;
                }
                $peclString = implode(' && ', $peclStrings);

                $extensions = '';
                if (count($stockExtensions)) { $extensions .= $stockString; }
                if (count($peclExtensions)) {
                    $extensions .= (strlen($extensions) == 0) ? $peclString : ' && ' . $peclString;
                }
            }
        }

        $files['docker/php/Dockerfile'] = str_replace('{{ extensions }}', $extensions, $files['docker/php/Dockerfile']);
        
        return $files;
    }

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if (!empty($request['php_version'])) {
            if ($request['cli']) {
                $requestConfig['php-cli'] = [
                    'service' => 'php',
                    'build-options' => [
                        'image' => 'php:' . $request['php_version'] . '-cli',
                        'extensions' => $request['php_extensions'],
                    ]
                ];
                $cbConfig[] = '"cli") docker-compose run --rm -u $UID php-cli php ${ARGS};;';
            }
        
            if ($request['cb_laravel_artisan']) {
                $cbConfig[] = '"artisan") docker-compose run --rm -u $UID php-cli php artisan ${ARGS};;';
            }
        
            if ($request['cb_symfony_4_console']) {
                $cbConfig[] = '"console") docker-compose run --rm -u $UID php-cli php bin/console ${ARGS};;';
            }
        
            if ($request['composer']) {
                $requestConfig['composer'] = [
                    'service' => 'composer',
                    'services' => ['composer' => [
                        'image' => ($request['composer_official'] == 'true') ? 'composer' : 'composer/composer',
                    ]],
                ];
                $cbConfig[] = '"composer") docker-compose run --rm -u $UID composer ${ARGS};;';
            }
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}