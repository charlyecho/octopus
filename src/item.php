<?php
$data = json_decode(file_get_contents($f));
?>

<div class='bg-gray-200 p-3 rounded w-64 h-80 relative'>
    <div class='font-semibold text-center uppercase'> <?= $data->name ?></div>
    <div class='flex gap-3 justify-around w-full mt-3'>
        <div class="text-center"><b>CPU</b><br/><?= $data->cpu_percent ?>%</div>
        <div class="text-center"><b>RAM</b><br/><?= $data->ram_percent ?>%</div>
        <div class="text-center"><b>DSK</b><br/><?= $data->root_space_left ?></div>
        <div class="text-center"><b>SWP</b><br/><?= $data->swap_percent ?>%</div>
    </div>

    <div class="absolute left-0 bottom-0 w-full text-center">
        <?= time()-strtotime($data->date) ?>s ago
    </div>
</div>
