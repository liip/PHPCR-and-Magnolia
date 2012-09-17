<?php

require_once __DIR__.'/vendor/autoload.php';

$path = "/demo-project/about/subsection-articles";

// setup PHPCR sessions
$parameters = array(
    'jackalope.jackrabbit_uri' => 'http://localhost:8080/magnoliaAuthor/.davex/'
);

$creds = new \PHPCR\SimpleCredentials('superuser','superuser');

$repository = \Jackalope\RepositoryFactoryJackrabbit::getRepository($parameters);
$website = $repository->login($creds, 'website');

$repository = \Jackalope\RepositoryFactoryJackrabbit::getRepository($parameters);
$dms = $repository->login($creds, 'dms');

// read from Magnolia website repository
$node = $website->getNode("$path/article");
$template = $node->getNode('MetaData')->getPropertyValue('mgnl:template');
$imgNode = $dms->getNodeByIdentifier($content['imageDmsUUID'])->getNode('document');
$img = $imgNode->getPropertiesValues();
$img['jcr:data'] = base64_encode(stream_get_contents($imgNode->getProperty('jcr:data')->getBinary()));

// writing to Magnolia website repository
$subnode = $node->getNode("content");
$subnode->setProperty('phpcr', 'was here!!');
$website->save();
$content = $subnode->getNode('00')->getPropertiesValues();

// setting up optional PHPCR ODM data mapper
$reader = new \Doctrine\Common\Annotations\AnnotationReader();
$reader->addGlobalIgnoredName('group');

$modelPaths = array(__DIR__);
$metaDriver = new \Doctrine\ODM\PHPCR\Mapping\Driver\AnnotationDriver($reader, $modelPaths);

$config = new \Doctrine\ODM\PHPCR\Configuration();
$config->setMetadataDriverImpl($metaDriver);

// Setting up optional custom node to class mapper logic
$customDocumentClassMapper = new \MagnoliaDocumentClassMapper(array(
    'standard-templating-kit:pages/stkSection' => 'Section'
));
$config->setDocumentClassMapper($customDocumentClassMapper);

// Getting data mapper instance
$dm = \Doctrine\ODM\PHPCR\DocumentManager::create($website, $config);

// Reading node from Magnolia via PHPCR ODM
$doc = $dm->find(null, $path);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<div>
    <h1><?php echo $doc->title ?>! (template <?php echo $template; ?>)</h1>

    <p><?php echo $node->getPropertyValue('abstract'); ?></p>
    <?php echo $content['text'] ?>

    <img src="data:<?php echo $img['jcr:mimeType']; ?>;base64,<?php echo $img['jcr:data']; ?>">
</div>
</body>
</html>
