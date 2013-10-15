<html>
<body>

<head>
    <title><?=$title?></title>
    <?=$map->getHeaderJS();?>
    <?=$map->getMapJS();?>
</head>

<body>
<?=$map->printOnLoad();?> 
<?=$map->printMap();?>

</body>
</html>