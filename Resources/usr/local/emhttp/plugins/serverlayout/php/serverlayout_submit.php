<?php
//require_once('serverlayout_constants.php');
$serverlayout_cfg_file = "/boot/config/plugins/serverlayout/serverlayout.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // If "Save Settings" button pressed
  if(isset($_POST['settings'])) {

    // Get JSON configuration file
    $myJSONconfig = json_decode(file_get_contents($serverlayout_cfg_file), true);

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

    // Write General settings
    if (isset($_POST["TOOLTIP_ENABLE"]) and ($_POST["TOOLTIP_ENABLE"] == "YES")) {
      $myJSONconfig["GENERAL"]["TOOLTIP_ENABLE"] = "YES";
    } else {
      $myJSONconfig["GENERAL"]["TOOLTIP_ENABLE"] = "NO";
    }
    
    // Write DATA_COLUMNS new configuration
    foreach (array_keys($myJSONconfig["DATA_COLUMNS"]) as $data_column_name) {
      if (isset($_POST["SHOW_DATA_".$data_column_name]) and ($_POST["SHOW_DATA_".$data_column_name] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_DATA"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_DATA"] = "NO";
      }
      if (isset($_POST["SHOW_TOOLTIP_".$data_column_name]) and ($_POST["SHOW_TOOLTIP_".$data_column_name] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_TOOLTIP"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_TOOLTIP"] = "NO";
      }
      if (isset($_POST["SHOW_COLUMN_I_".$data_column_name]) and ($_POST["SHOW_COLUMN_I_".$data_column_name] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_COLUMN_I"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_COLUMN_I"] = "NO";
      }
      if (isset($_POST["SHOW_COLUMN_H_".$data_column_name]) and ($_POST["SHOW_COLUMN_H_".$data_column_name] == "YES")) {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_COLUMN_H"] = "YES";
      } else {
        $myJSONconfig["DATA_COLUMNS"][$data_column_name]["SHOW_COLUMN_H"] = "NO";
      }
    }

    // Clear all TRAY_NUMs if number of rows/columns has changed (can also clear historical because they should already be "")
    // Also recreate TRAY_SHOWs array according to the new ROWS*COLUMNS and set all to "YES"
    if (($rows_new != $rows_old) or ($columns_new != $columns_old)) {
      foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
        $myJSONconfig["DISK_DATA"][$disk_SN]["TRAY_NUM"] = "";
      }
      
      // Change all new TRAY_SHOWs to "YES"
      for ($i = 1; $i <= ($rows_new*$columns_new); $i++) {
        $myJSONconfig["TRAY_SHOW"][$i] = "YES";  // Recreate TRAY_SHOW array of new size with all "YES" values
      }
      // Clear/Destroy unused TRAY_SHOWs if there are now less trays
      if (($rows_old*$columns_old) > ($rows_new*$columns_new)) {
        for ($i = ($rows_new*$columns_new + 1); $i <= ($rows_old*$columns_old); $i++) {
          unset($myJSONconfig["TRAY_SHOW"][$i]);
        }
      }
    }

    // Save configuration data to JSON configuration file
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));
  }

  
  // If "Save Data" button pressed
  else if(isset($_POST['data'])) {

    // Get JSON configuration file
    $myJSONconfig = json_decode(file_get_contents($serverlayout_cfg_file), true);

    // Save TRAY_SHOWs
    foreach (array_keys($myJSONconfig["TRAY_SHOW"]) as $key) {
      $myJSONconfig["TRAY_SHOW"][$key] = $_POST["TRAY_SHOW_".$key];
    }

    // // Save TRAY_NUMs
    // $datas = $_POST["TRAY_NUMS"];
    // $keys = $_POST["TRAY_NUMS_SN"];
    // foreach (array_combine($keys, $datas) as $key => $data) {
    //   $myJSONconfig["DISK_DATA"][$key]["TRAY_NUM"] = $data;
    // }

    // Save TRAY_NUMs
    $datas = $_POST["PATH_FULL"];
    $keys = $_POST["TRAY_NUMS"];
    for ($i=0; $i < count($datas); $i++) { 
      $key = $keys[$i];
      $data = $datas[$i];
      $myJSONconfig["PATH_DATA"] = preg_grep("#{$data}#i", $myJSONconfig["PATH_DATA"], PREG_GREP_INVERT);
      if ($key) {
        $myJSONconfig["PATH_DATA"][$key] = $data;
      }
    }

    // Write PURCHASE_DATE configuration
    $datas = $_POST["PURCHASE_DATES"];
    $keys = $_POST["PURCHASE_DATES_SN"];
    foreach (array_combine($keys, $datas) as $key => $data) {
      $myJSONconfig["DISK_DATA"][$key]["PURCHASE_DATE"] = $data;
    }

    // Write FIRST_INSTALL_DATE configuration
    $datas = $_POST["FIRST_INSTALL_DATES"];
    $keys = $_POST["FIRST_INSTALL_DATES_SN"];
    foreach (array_combine($keys, $datas) as $key => $data) {
      $myJSONconfig["DISK_DATA"][$key]["FIRST_INSTALL_DATE"] = $data;
    }

    // Write NOTES configuration
    $datas = $_POST["NOTESS"];
    $keys = $_POST["NOTESS_SN"];
    foreach (array_combine($keys, $datas) as $key => $data) {
      $myJSONconfig["DISK_DATA"][$key]["NOTES"] = $data;
    }

    // Save configuration data to JSON configuration file
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));
  }
  
  // If "Delete" button pressed
  else if(isset($_POST['delete_historical'])) {

    // Get JSON configuration file
    $myJSONconfig = json_decode(file_get_contents($serverlayout_cfg_file), true);
    
    // Delete relevant historical devices as defined
    if(isset($_POST["DELETE_DISK"])) {
      if (is_array($_POST["DELETE_DISK"])) {
        foreach ($_POST["DELETE_DISK"] as $data) {
          unset($myJSONconfig["DISK_DATA"][$data]);
        }
      } else {
        unset($myJSONconfig["DISK_DATA"][$_POST["DELETE_DISK"]]);
      }
    }

    // Save configuration data to JSON configuration file
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));
  }


  else if(isset($_POST['update_smartmontools_database'])) {
    // Update Smartmontools database
    shell_exec("/usr/sbin/update-smart-drivedb");
  }
}
?>

<HTML>
<HEAD><SCRIPT>var goback=parent.location;</SCRIPT></HEAD>
<BODY onLoad="parent.location=goback;"></BODY>
</HTML>
