<?php
$files = glob("../files/*.json");
?>

<html lang="en">
<head>
    <title>Octopus</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="style.css" rel="stylesheet" />
    <meta http-equiv="refresh" content="10" />
</head>
<body class="bg-gray-500">
<div class="flex flex-wrap gap-3 p-3">
    <?php foreach($files as $f) {
    $data = json_decode(file_get_contents($f));
    ?>
    <div class='bg-gray-200 p-3 rounded w-64 h-80 relative'>
        <div class='font-semibold text-center uppercase'> <?= $data->name ?></div>
        <div class='flex gap-3 justify-around w-full mt-3'>
            <div class="text-center"><b>CPU</b><br/><?= colorPercent($data->cpu_percent) ?></div>
            <div class="text-center"><b>RAM</b><br/><?= colorPercent($data->ram_percent) ?></div>
            <div class="text-center" title="<?= $data->root_space_left ?>"><b>DSK</b><br/><?= colorPercent($data->root_space_left_percent) ?></div>
            <div class="text-center"><b>SWP</b><br/><?= colorPercent($data->swap_percent) ?></div>
        </div>

        <div class="flex flex-wrap gap-2 justify-center mt-3">
            <?php foreach($data as $key => $val): ?>
                <?php if(!str_contains($key, "service")) {continue;} ?>
                <div class="rounded <?= $val === "active" ? "bg-lime-100" : "bg-red-100" ?> px-2"><?= str_replace(".service", "", $key) ?></div>
            <?php endforeach; ?>
        </div>

        <?php if ($data->reboot_needed): ?>
            <div class="absolute top-0 right-0 p-2 text-center rounded" title="Reboot required">❗</div>
        <?php endif ?>

        <div class="absolute left-0 bottom-0 w-full text-center">
            <?= timeToAgo($data->date) ?> ago
        </div>
    </div>
    <?php
    }
    ?>
</div>
</body>
</html>

<?php

function timeToAgo($date): string
{
    $time = strtotime($date);

    $seconds = time() - $time;

    if ($seconds > 3600) {
        return floor($seconds/3600)."h";
    }

    if ($seconds > 60) {
        return floor($seconds/60)."min";
    }

    return $seconds."s";
}

function colorPercent($value): string
{
    if ($value > 90) {
        return "<span class='text-red-700 animate-pulse font-semibold'>".$value."%</span>";
    }

    return "<span>".$value."%</span>";
}