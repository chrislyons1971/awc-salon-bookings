<!--Generate Calendar-->
<br>

<?php

if ($year && $month) echo $this->calendar->generate($year, $month, $calendar_data);
else echo $this->calendar->generate();

?>

<br><br><br>