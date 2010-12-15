<?php

foreach ($_GET['listItem'] as $position => $item) : 
  $sql[] = "UPDATE `table` SET `position` = $position WHERE `id` = $item"; 
endforeach; 
print_r ($sql);

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

?>