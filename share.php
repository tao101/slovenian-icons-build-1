<?php

include_once __DIR__ . '/vendor/autoload.php';

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Dom\Link;
use Prismic\Predicates;
use Prismic\Dom\RichText;

$api = Api::get("https://iconsofslovenia.cdn.prismic.io/api/v2");
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
            <meta property="og:image" content="https://images.prismic.io/iconsofslovenia/a5065a28-c2e5-4e9e-a5b7-a9a95df51194_Marshrutkas.png" />
        <?php endif; ?>
    <?php else: ?>

        <title><?php echo $siteTitle;?></title>
        <meta property="og:title" content="<?php echo $siteTitle;?>" />
        <meta property="og:description" content="<?php echo $siteDescription;?>" />
        <meta property="og:image" content="https://images.prismic.io/iconsofslovenia/a5065a28-c2e5-4e9e-a5b7-a9a95df51194_Marshrutkas.png" />

    <?php endif; ?>

</head>
<body>
<div id="fb-root"></div>
</body>
</html>
