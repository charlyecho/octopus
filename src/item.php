<?php
$data = json_decode(file_get_contents($f));
echo "<div class='bg-gray-200 p-3 rounded'>";
echo "<div class='font-semibold text-center'>".$data->name."</div>";
echo "</div>";
