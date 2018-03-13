<?php
require_once __DIR__ . '/../vendor/autoload.php';

$request = json_decode(file_get_contents('php://input'), true);

$webserverPorts = $webserverVolumes = $databaseEnvvars = [];

foreach ($request['webserver_ports'] as $ports) {
    $webserverPorts[] = $ports['hostPort'] . ':' . $ports['srcPort'];
}

foreach ($request['webserver_mountpoints'] as $volume) {
    $webserverVolumes[] = $volume['localPath'] . ':' . $volume['containerPath'];
}

foreach ($request['database_envvars'] as $dbEnvvar) {
    $databaseEnvvars[$dbEnvvar['name']] =  $dbEnvvar['value'];
}

$requestConfig = [
    'httpd' => [
        'service' => 'httpd',
        'build-options' => [
            'image' => 'php:' . $request['php_version'] . '-apache',
            'extensions' => $request['php_extensions'],
        ],
        'services' => ['httpd' => [
            'ports' => $webserverPorts,
            'volumes' => $webserverVolumes,
        ]
    ]],
    'mysql' => [
        'service' => 'mysql',
        'services' => ['mysql' => [
            'image' => 'mysql:' . $request['database_version'],
            'environment' => $databaseEnvvars
        ]],
        'build-options' => ['image' => 'mysql:' . $request['database_version']],
    ]
];

$cbConfig = [
    'commands' => []
];

if ($request['cb_laravel_artisan']) {
    $cbConfig['commands'][] = '"artisan") docker-compose run --rm -u $UID php-cli php artisan ${ARGS};;';
}

if ($request['cb_symfony_4_console']) {
    $cbConfig['commands'][] = '"console") docker-compose run --rm -u $UID php-cli php bin/console ${ARGS};;';
}

if ($request['cli']) {
    $requestConfig['php-cli'] = [
        'service' => 'php',
        'build-options' => [
            'image' => 'php:' . $request['php_version'] . '-cli',
            'extensions' => $request['php_extensions'],
        ]
    ];
}

if ($request['composer']) {
    $requestConfig['composer'] = [
        'service' => 'composer',
        'services' => ['composer' => [
            'image' => ($request['composer_official'] == 'true') ? 'composer' : 'composer/composer',
        ]],
    ];
}

if ($request['queue']) {
    $requestConfig['queue'] = ['service' => 'queue'];
}

if ($request['mailhog']) {
    $requestConfig['mailhog'] = ['service' => 'mailhog'];
}

$builder = new \ContainerBuilder\ConfigBuilder();
$config = $builder->generateConfig($requestConfig);

$zipfilePath = '/tmp/' . md5(rand() . strtotime('now')) . '.zip';
$filesystem = new \League\Flysystem\Filesystem(new League\Flysystem\ZipArchive\ZipArchiveAdapter($zipfilePath));

$res = $filesystem->write('docker-compose.yml', \Symfony\Component\Yaml\Yaml::dump($config['config']));
foreach ($config['files'] as $zipPath => $content) {
   $filesystem->write($zipPath, $content);
}
$cb = file_get_contents(__DIR__ . '/../data/templates/cb');
$cb = str_replace('{{ commands }}', implode("\n", $cbConfig['commands']), $cb);
$filesystem->write('cb', $cb);
$filesystem->getAdapter()->getArchive()->close();

if (file_exists($zipfilePath)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=container-builder.zip');
    
    readfile($zipfilePath);
    unlink($zipfilePath);
    exit;
} else {
    http_response_code(500);
    echo 'Could not build the zip file';
}
