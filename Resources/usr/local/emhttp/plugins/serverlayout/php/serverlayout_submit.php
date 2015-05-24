<?php
include 'serverlayout_constants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // If "Apply" button pressed
  if(isset($_POST['apply'])) {

    // Get or create JSON configuration file
    $myJSONconfig = Get_JSON_Config_File();

    // Get new configuration data    
    $rows_new = $_POST["ROWS"];
    $columns_new = $_POST["COLUMNS"];
    // Get previous ROWS and COLUMNS settings
    $rows_old = $myJSONconfig["LAYOUT"]["ROWS"];
    $columns_old = $myJSONconfig["LAYOUT"]["COLUMNS"];

    // Write new layout configuration data    
    $myJSONconfig["LAYOUT"]["ROWS"] = $rows_new;
    $myJSONconfig["LAYOUT"]["COLUMNS"] = $columns_new;
    $myJSONconfig["LAYOUT"]["ORIENTATION"] = $_POST["ORIENTATION"];

    // Write SHOW configuration
    $showcheckboxes = $_POST["SHOW_CHECKBOXES"];
    $showcheckboxes_name = $_POST["SHOW_CHECKBOXES_NAME"];
    foreach (array_combine($showcheckboxes_name, $showcheckboxes) as $showcheckbox_name => $showcheckbox) {
      if (isset($showcheckbox) and ($showcheckbox == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$showcheckbox_name]["SHOW_DATA"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$showcheckbox_name]["SHOW_DATA"] = "NO";
      }
    }

    // Write TRAY_NUM configuration
    $traynums = $_POST["TRAY_NUMS"];
    $traynums_sn = $_POST["TRAY_NUMS_SN"];
    foreach (array_combine($traynums_sn, $traynums) as $traynum_sn => $traynum) {
      if (($rows_new == $rows_old) and ($columns_new == $columns_old)) {
        $myJSONconfig["DISK_DATA"][$traynum_sn]["TRAY_NUM"] = $traynum;
      } else {
        $myJSONconfig["DISK_DATA"][$traynum_sn]["TRAY_NUM"] = "";
    }

    // Write PURCHASE_DATE configuration
    $purchasedates = $_POST["PURCHASE_DATE"];
    $purchasedates_sn = $_POST["PURCHASE_DATE_SN"];
    foreach (array_combine($purchasedates_sn, $purchasedates) as $purchasedate_sn => $purchasedate) {
      $myJSONconfig["DISK_DATA"][$purchasedate_sn]["TRAY_NUM"] = $purchasedate;
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
