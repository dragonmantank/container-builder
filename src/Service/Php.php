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
        'pecl' => ['xdebug', 'redis'],
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
}