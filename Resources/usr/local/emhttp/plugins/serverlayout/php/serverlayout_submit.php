<?php
require_once('serverlayout_constants.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get JSON configuration file
  $myJSONconfig = json_decode(file_get_contents($serverlayout_cfg_file), true);

  // If "Save Settings" button pressed
  if(isset($_POST['settings'])) {

    // Get new ROWS and COLUMNS configuration
    $rows_new = $_POST["ROWS"];
    $columns_new = $_POST["COLUMNS"];
    // Get previous ROWS and COLUMNS configuration
    $rows_old = $myJSONconfig["LAYOUT"]["ROWS"];
    $columns_old = $myJSONconfig["LAYOUT"]["COLUMNS"];

    // Write new layout configuration data
    $myJSONconfig["LAYOUT"]["ROWS"] = $rows_new;
    $myJSONconfig["LAYOUT"]["COLUMNS"] = $columns_new;
    $myJSONconfig["LAYOUT"]["ORIENTATION"] = $_POST["ORIENTATION"];

    // Write DATA_COLUMNS new configuration
    $showdatas = $_POST["SHOW_DATAS"];
    $showcolumndatas_i = $_POST["SHOW_COLUMNS_I"];
    $showcolumndatas_c = $_POST["SHOW_COLUMNS_C"];
    for ($i = 0; $i < count($showdatas); $i++) {
      if (isset($showdatas[$i]) and ($showdatas[i] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_DATA"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_DATA"] = "NO";
      }
      if (isset($showcolumndatas_i[$i]) and ($showcolumndatas_i[i] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_COLUMN_I"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_COLUMN_I"] = "NO";
      }
      if (isset($showcolumndatas_c[$i]) and ($showcolumndatas_c[i] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_COLUMN_C"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$i]["SHOW_COLUMN_C"] = "NO";
      }
    }

    // Clear all TRAY_NUMs if number of rows/columns has changed (can also clear historical because they should already be "")
    if (($rows_new != $rows_old) or ($columns_new != $columns_old)) {
      foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
        $myJSONconfig["DISK_DATA"][$disk_SN]["TRAY_NUM"] = "";
      }
    }
    
    // Save configuration data to JSON configuration file
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));
  }
  
  // If "Save Data" button pressed
  if(isset($_POST['data'])) {
    // Save TRAY_NUMs
    $traynums = $_POST["TRAY_NUMS"];
    $traynums_sn = $_POST["TRAY_NUMS_SN"];
    foreach (array_combine($traynums_sn, $traynums) as $traynum_sn => $traynum) {
      $myJSONconfig["DISK_DATA"][$traynum_sn]["TRAY_NUM"] = $traynum;
    }

    // Write PURCHASE_DATE configuration
    $purchasedates = $_POST["PURCHASE_DATES"];
    $purchasedates_sn = $_POST["PURCHASE_DATES_SN"];
    foreach (array_combine($purchasedates_sn, $purchasedates) as $purchasedate_sn => $purchasedate) {
      $myJSONconfig["DISK_DATA"][$purchasedate_sn]["PURCHASE_DATE"] = $purchasedate;
    }

    // Save configuration data to JSON configuration file
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));
  }
}
?>

<HTML>
<HEAD><SCRIPT>var goback=parent.location;</SCRIPT></HEAD>
<BODY onLoad="parent.location=goback;"</BODY>
</HTML>
