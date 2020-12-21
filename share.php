<?php

include_once __DIR__ . '/vendor/autoload.php';

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Dom\Link;
use Prismic\Predicates;
use Prismic\Dom\RichText;

$api = Api::get("https://iconsua.cdn.prismic.io/api/v2");
$path = explode( "/", $_SERVER['REQUEST_URI']);

$response = null;
$configResponse = null;

if($path[1] === "icon" && !empty($path[2])) {
    $response = $api->getByUID('icon', $path[2]);
}

$siteTitle = "";
$siteDescription = "";

$configResponse = $api->getSingle('site_configuration');

if($configResponse) {
    $siteTitle = htmlspecialchars(RichText::asText($configResponse->data->site_title));
    $siteDescription = htmlspecialchars(RichText::asText($configResponse->data->site_description));
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <?php if(!empty($response->data->iconname)) :?>

        <title><?php echo $siteTitle;?> - <?php echo htmlspecialchars(RichText::asText($response->data->iconname)); ?></title>
        <meta property="og:title" content="<?php echo $siteTitle;?> - <?php echo htmlspecialchars(RichText::asText($response->data->iconname)); ?>" />
        <meta property="og:description" content="<?php echo htmlspecialchars(RichText::asText($response->data->icondescription)); ?>" />
        <?php if(!empty($response->data->seo_image->url)): ?>
            <meta property="og:image" content="<?php echo $response->data->seo_image->url; ?>" />
            <meta property="og:image:width" content="<?php echo $response->data->seo_image->dimensions->width; ?>" />
            <meta property="og:image:height" content="<?php echo $response->data->seo_image->dimensions->height; ?>" />
        <?php else: ?>
            <meta property="og:image" content="https://prismic-io.s3.amazonaws.com/iconsofslovenia/9c685de6-ef57-4ea0-a9a1-9440c696d6b7_Icons+of+Slovenia.svg" />
        <?php endif; ?>
    <?php else: ?>

        <title><?php echo $siteTitle;?></title>
        <meta property="og:title" content="<?php echo $siteTitle;?>" />
        <meta property="og:description" content="<?php echo $siteDescription;?>" />
        <meta property="og:image" content="https://prismic-io.s3.amazonaws.com/iconsofslovenia/9c685de6-ef57-4ea0-a9a1-9440c696d6b7_Icons+of+Slovenia.svg" />

    <?php endif; ?>

</head>
<body>
<div id="fb-root"></div>
</body>
</html>
