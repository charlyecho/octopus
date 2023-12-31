<?php
$files = glob("../files/*.json");
$short = isset($_GET["short"]);
sort($files);
usort($files, function($a, $b) {
    $_a = json_decode(file_get_contents($a));
    $_b = json_decode(file_get_contents($b));
    return $_a->name > $_b->name;
})
?>
    <html lang="en">
    <head>
        <title>Octopus</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="style.css" rel="stylesheet" />
        <meta http-equiv="refresh" content="10" />
    </head>
    <body class="bg-gray-800">

    <div class="flex justify-between w-full p-3">
        <div class="text-green-400 font-semibold uppercase"><a href="/">Octopus</a></div>
        <div class="text-white font-semibold">
            <a class="px-1" href="?short">S</a>
            <a class="px-1" href="/">F</a>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 px-3">
        <?php foreach($files as $f) {
            $data = json_decode(file_get_contents($f));
            ?>
            <div class='bg-gray-200 dark:bg-gray-700 text-black dark:text-gray-300 p-3 rounded w-64 relative'>
                <div class='font-semibold text-center uppercase'> <?= $data->name ?></div>

                <?php if (!$short): ?>
                    <div class="bg-gray-800 py-1 rounded mt-2 text-xs text-center"><?= $data->uptime ?></div>
                <?php endif ?>

                <?php if ($short): ?>
                <div class="flex flex-wrap justify-center text-white gap-2 mt-2">
                    <?php if ($data->reboot_needed): ?>
                        <div class="rounded bg-red-700 px-2">Reboot needed</div>
                    <?php endif ?>
                    <?php if (percentAlert($data->cpu_percent)): ?>
                        <div class="rounded bg-red-500 px-2">CPU <?= $data->cpu_percent."%" ?></div>
                    <?php endif ?>
                    <?php if (percentAlert($data->ram_percent)): ?>
                        <div class="rounded bg-red-700 px-2">RAM <?= $data->ram_percent."%" ?></div>
                    <?php endif ?>
                    <?php if (percentAlert($data->root_space_left_percent)): ?>
                        <div class="rounded bg-red-700 px-2">DISK <?= $data->root_space_left_percent."%" ?></div>
                    <?php endif ?>
                    <?php if (percentAlert($data->swap_percent)): ?>
                        <div class="rounded bg-red-700 px-2">SWAP <?= $data->swap_percent."%" ?></div>
                    <?php endif ?>

                    <?php foreach($data as $key => $val): ?>
                        <?php if(!str_contains($key, "service") || ($val === "active")) {continue;} ?>
                        <div class="rounded bg-red-700 px-2"><?= str_replace(".service", "", $key) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class='flex gap-3 justify-around w-full mt-3'>
                        <div class="text-center"><b>CPU</b><br/><?= colorPercent($data->cpu_percent) ?></div>
                        <div class="text-center"><b>RAM</b><br/><?= colorPercent($data->ram_percent) ?></div>
                        <div class="text-center" title="<?= $data->root_space_left ?>"><b>DISK</b><br/><?= colorPercent($data->root_space_left_percent) ?></div>
                        <div class="text-center"><b>SWP</b><br/><?= colorPercent($data->swap_percent) ?></div>
                    </div>

                    <div class="min-h-24">
                        <div class="flex flex-wrap gap-2 justify-center mt-3">
                            <?php foreach($data as $key => $val): ?>
                                <?php if(!str_contains($key, "service")) {continue;} ?>
                                <div class="rounded dark:text-black <?= $val === "active" ? "bg-lime-200" : "bg-red-200" ?> px-2"><?= str_replace(".service", "", $key) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($data->reboot_needed): ?>
                        <div class="absolute top-0 right-0 p-2 text-center rounded" title="Reboot required">❗</div>
                    <?php endif ?>

                    <div class=" mt-2 w-full text-center">
                        <span class="bg-gray-800 rounded px-2 text-xs"><?= timeToAgo($data->date) ?> ago</span>
                    </div>
                <?php endif; ?>

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

    if ($seconds > (3600*24)) {
        return floor($seconds/(3600*24))."d";
    }

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
    if (percentAlert($value)) {
        return "<span class='bg-red-700 text-white p-1 rounded font-semibold'>".$value."%</span>";
    }

    return "<span>".$value."%</span>";
}

function percentAlert($value): bool
{
    return $value > 90;
}