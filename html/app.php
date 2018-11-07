<?php
require_once __DIR__ . '/../vendor/autoload.php';

$request = json_decode(file_get_contents('php://input'), true);

$builder = new \ContainerBuilder\ConfigBuilder();
$config = $builder->generateConfig($request);

$zipfilePath = '/tmp/' . md5(rand() . strtotime('now')) . '.zip';
$filesystem = new \League\Flysystem\Filesystem(new League\Flysystem\ZipArchive\ZipArchiveAdapter($zipfilePath));

$res = $filesystem->write('docker-compose.yml', \Symfony\Component\Yaml\Yaml::dump($config['config']));
foreach ($config['files'] as $zipPath => $content) {
   $filesystem->write($zipPath, $content);
}

$cb = file_get_contents(__DIR__ . '/../data/templates/cb');
$commands = '';
if (!empty($config['commands'])) {
    $commands = implode("\n", $config['commands']);
}
$cb = str_replace('{{ commands }}', $commands, $cb);

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
