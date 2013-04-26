<?php

?>
<h1>Train Times:</h1>
<?php
   foreach ($times as $station => $time) {
      printf('<h2>%s: %s</h2>', $station, htmlspecialchars($time));
   }

