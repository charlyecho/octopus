<?php
$files = glob("../files/*.json");
?>

<html lang="en">
<head>
    <title>Octopus</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="style.css" rel="stylesheet" />
</head>
<body class="bg-gray-500">
<div class="flex flex-wrap gap-3 p-3">
    <?php foreach($files as $f) {
        require( "../src/item.php" );
    }
    ?>
</div>
</body>
</html>
