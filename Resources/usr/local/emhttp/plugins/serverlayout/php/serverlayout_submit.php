<?php
include 'serverlayout_constants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // If "Apply" button pressed
  if(isset($_POST['apply'])) {
    // Write new configuration data    
    $arguments = "";
    $rows_new = $_POST['ROWS'];
    $arguments .= "ROWS=\"".$rows_new."\"\n";
    $columns_new = $_POST['COLUMNS'];
    $arguments .= "COLUMNS=\"".$columns_new."\"\n";
    $orientation_new = $_POST['ORIENTATION'];
    $arguments .= "ORIENTATION=\"".$orientation_new."\"\n";
    // Write SHOW/HIDE configuration
    for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) {
      $temp = "SHOW".$i;
      if (isset($_POST[$temp]) and ($_POST[$temp] == "SHOW")) {
        $arguments .= "SHOW".$i."=\"SHOW\"\n";
      } else {
        $arguments .= "SHOW".$i."=\"HIDE\"\n";
      }
    }
    // Get previous ROWS and COLUMNS settings
    $serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
    $rows_old = $serverlayout_cfg['ROWS'];
    $columns_old = $serverlayout_cfg['COLUMNS'];
    // Get number of disks
    if (file_exists($automatic_data)) {
      $serverlayout_auto = parse_ini_file($automatic_data, true);
      $num_disks = count($serverlayout_auto, COUNT_NORMAL);
    } else {
      $num_disks = 0;
    }
    for ($i = 1; $i <= $num_disks; $i++) {
      $temp = $serverlayout_auto[$i]['SN'];
      $arguments .= "[".$temp."]\n";
      // Check if ROWS / COLUMNS / ORIENTATION have changed
      if (($rows_new == $rows_old) and ($columns_new == $columns_old)) {
        // At least one of the following ROWS / COLUMNS has changed - Don't save TRAY_NUM assignments
        if ($serverlayout_auto[$i]['TYPE'] != "USB") {  // Also don't save if device is USB
          $temp = "TRAY_NUM".$i;
          $temp2 = $_POST[$temp];
          $arguments .= "TRAY_NUM=\"".$temp2."\"\n";
        }
      }
      // Save all other disk information
      $temp = "PURCHASE_DATE".$i;
      $temp2 = $_POST[$temp];
      $arguments .= "PURCHASE_DATE=\"".$temp2."\"\n";
    }
    // Save to CONFIG file
    file_put_contents($serverlayout_cfg_file, $arguments);

  // If "Scan Hardware" button pressed
  } elseif (isset($_POST['scan'])) {
      echo $scan_command;
      shell_exec($scan_command);
  }

} else {  // Not POST method

//  $is_post = false;
//  $dataOK = true;
//  $rowsERR = false;
//  $columnsERR = false;

}
?>

<HTML>
<HEAD><SCRIPT>var goback=parent.location;</SCRIPT></HEAD>
<BODY onLoad="parent.location=goback;"</BODY>
</HTML>
