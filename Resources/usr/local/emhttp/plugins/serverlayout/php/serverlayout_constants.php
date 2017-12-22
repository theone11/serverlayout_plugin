<?php
// Constants - Profiler file locations
$serverlayout_tprf_file = "/boot/config/plugins/serverlayout/serverlayout.tprofiler";
$profiler = "";
$profiler .= microtime(true) . ",Started serverlayout_constants.php" . "\n";

// Constants - Maximum number of trays in array
$max_trays = 99;

// Constants - Data file locations
$serverlayout_cfg_file = "/boot/config/plugins/serverlayout/serverlayout.json";

// Constants - Image file locations
$frontpanel_imgfile = "/plugins/serverlayout/images/frontpanel.jpg";
$frontpanelusb_imgfile = "/plugins/serverlayout/images/frontpanelusb.png";
$sata_imgfile = "/plugins/serverlayout/images/SATA_Logo.png";
$sas_imgfile = "/plugins/serverlayout/images/SAS_Logo.png";
$usb_imgfile = "/plugins/serverlayout/images/USB_Logo.png";
$optical_imgfile = "/plugins/serverlayout/images/opticalmedia_Logo.png";

// Constants - Drives' background
$factor= 4;  // Status table vs. Preview table size
$border_radius = 8;
$background_padding = 4;
$width = 320;
$height = 80;
$status_width = $width/12;

$width_usb = 874/3;
$height_usb = 229/3;

// Constants - JSON configuration file
$default_layout = array("GENERAL" => array("TOOLTIP_ENABLE" => "YES"),
                        "LAYOUT" => array("ROWS" => "6", "COLUMNS" => "4", "ORIENTATION" => "0"),
                        "DEBUG" => array("TIME_PROFILING" => "NO"),
                        "TRAY_SHOW" => array( "1" => "YES",  "2" => "YES",  "3" => "YES",  "4" => "YES",  "5" => "YES",  "6" => "YES",  "7" => "YES",  "8" => "YES",
                                              "9" => "YES", "10" => "YES", "11" => "YES", "12" => "YES", "13" => "YES", "14" => "YES", "15" => "YES", "16" => "YES",
                                             "17" => "YES", "18" => "YES", "19" => "YES", "20" => "YES", "21" => "YES", "22" => "YES", "23" => "YES", "24" => "YES")
                        );

$default_col_data = array("DATA_COLUMNS" => array (
                      "TRAY_NUM"            => array("NAME" => "TRAY_NUM",            "TITLE" => "Tray #",           "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "1",  "TEXT_ALIGN" => "center"),
                      "TYPE"                => array("NAME" => "TYPE",                "TITLE" => "Type",             "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "2",  "TEXT_ALIGN" => "center"),
                      "DEVICE"              => array("NAME" => "DEVICE",              "TITLE" => "Device",           "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "3",  "TEXT_ALIGN" => "center"),
                      "PATH"                => array("NAME" => "PATH",                "TITLE" => "Path",             "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "4",  "TEXT_ALIGN" => "left"  ),
                      "UNRAID"              => array("NAME" => "UNRAID",              "TITLE" => "unRAID",           "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "5",  "TEXT_ALIGN" => "left"  ),
                      "MANUFACTURER"        => array("NAME" => "MANUFACTURER",        "TITLE" => "Manufacturer",     "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "6",  "TEXT_ALIGN" => "left"  ),
                      "MODEL"               => array("NAME" => "MODEL",               "TITLE" => "Model",            "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "7",  "TEXT_ALIGN" => "left"  ),
                      "SN"                  => array("NAME" => "SN",                  "TITLE" => "Serial Number",    "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "8",  "TEXT_ALIGN" => "right" ),
                      "FW"                  => array("NAME" => "FW",                  "TITLE" => "Firmware",         "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "9",  "TEXT_ALIGN" => "right" ),
                      "CAPACITY"            => array("NAME" => "CAPACITY",            "TITLE" => "Capacity",         "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "10", "TEXT_ALIGN" => "right" ),
                      "POWER_ON_HOURS"      => array("NAME" => "POWER_ON_HOURS",      "TITLE" => "Power On Hours",   "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "11", "TEXT_ALIGN" => "right" ),
                      "LOAD_CYCLE_COUNT"    => array("NAME" => "LOAD_CYCLE_COUNT",    "TITLE" => "Load Cycle Count", "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "12", "TEXT_ALIGN" => "right" ),
                      "FIRST_INSTALL_DATE"  => array("NAME" => "FIRST_INSTALL_DATE",  "TITLE" => "First Install",    "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "13", "TEXT_ALIGN" => "right" ),
                      "RECENT_INSTALL_DATE" => array("NAME" => "RECENT_INSTALL_DATE", "TITLE" => "Recent Install",   "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "14", "TEXT_ALIGN" => "center"),
                      "LAST_SEEN_DATE"      => array("NAME" => "LAST_SEEN_DATE",      "TITLE" => "Last Seen",        "SHOW_DATA" => "NO",  "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "NO",  "SHOW_COLUMN_H" => "YES", "ORDER" => "15", "TEXT_ALIGN" => "center"),
                      "PURCHASE_DATE"       => array("NAME" => "PURCHASE_DATE",       "TITLE" => "Purchase Date",    "SHOW_DATA" => "YES", "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "16", "TEXT_ALIGN" => "center"),
                      "NOTES"               => array("NAME" => "NOTES",               "TITLE" => "Notes",            "SHOW_DATA" => "NO",  "SHOW_TOOLTIP" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "17", "TEXT_ALIGN" => "left"  )
                      ));

$default_disk = array("TRAY_NUM"            => "",
                      "TYPE"                => "",
                      "DEVICE"              => "",
                      "PATH"                => "",
                      "UNRAID"              => "",
                      "MANUFACTURER"        => "",
                      "MODEL"               => "",
                      "SN"                  => "",
                      "FW"                  => "",
                      "CAPACITY"            => "",
                      "POWER_ON_HOURS"      => "",
                      "LOAD_CYCLE_COUNT"    => "",
                      "FIRST_INSTALL_DATE"  => "",
                      "RECENT_INSTALL_DATE" => "",
                      "LAST_SEEN_DATE"      => "",
                      "PURCHASE_DATE"       => "",
                      "NOTES"               => "",
                      "STATUS"              => "",
                      "FOUND"               => "",
                      "COLOR"               => "",
                      );

$default_disk_data = array("DISK_DATA" => array());

$profiler .= microtime(true) . ",Get_JSON_Config_File()" . "\n";
$myJSONconfig = Get_JSON_Config_File();  // Get or create JSON configuration file
$profiler_on = $myJSONconfig["DEBUG"]["TIME_PROFILING"];
if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Scan_Installed_Devices_Data(myJSONconfig)" . "\n"; }
$myJSONconfig = Scan_Installed_Devices_Data($myJSONconfig);  // Scan all installed devices
if ($profiler_on == "YES") { $profiler .= microtime(true) . ",file_put_contents(serverlayout_cfg_file, json_encode(myJSONconfig))" . "\n"; }
file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));  // Save configuration data to JSON configuration file
if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Save Profiling - file_put_contents(serverlayout_tprf_file, profiler)" . "\n"; }
if ($profiler_on == "YES") { file_put_contents($serverlayout_tprf_file, $profiler); }

// *****************************
// Function Get_JSON_Config_File
// *****************************
// The function creates a default configuration file if none exists, otherwise it creates a default configuration
// BUT also copies over all user defined configuration data from existing configuration file

function Get_JSON_Config_File() {
  // Constants - GLOBAL constants
  $profiler = $GLOBALS["profiler"];
  $profiler_on = $GLOBALS["profiler_on"];
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $default_layout = $GLOBALS["default_layout"];
  $default_col_data = $GLOBALS["default_col_data"];
  $default_disk = $GLOBALS["default_disk"];
  $default_disk_data = $GLOBALS["default_disk_data"];
  // Local Constants
//  $default_disk_data = array("DISK_DATA" => "");

  // Define new configuration file based on default values
  $myJSONconfig_new = array_merge($default_layout, $default_col_data, $default_disk_data);

  if (file_exists($serverlayout_cfg_file)) {  // Import JSON file if exists

    $myJSONconfig_old = json_decode(file_get_contents($serverlayout_cfg_file), true);

    $rows_old = $myJSONconfig_old["LAYOUT"]["ROWS"];
    $columns_old = $myJSONconfig_old["LAYOUT"]["COLUMNS"];
    $rows_new = $myJSONconfig_new["LAYOUT"]["ROWS"];
    $columns_new = $myJSONconfig_new["LAYOUT"]["COLUMNS"];

    foreach (array_keys($myJSONconfig_new["LAYOUT"]) as $key) {
      if (array_key_exists($key, $myJSONconfig_old["LAYOUT"])) {  // If Layout Key exists then copy it over - All new Keys are inherited from default
        $myJSONconfig_new["LAYOUT"][$key] = $myJSONconfig_old["LAYOUT"][$key];
      }
    }

    foreach (array_keys($myJSONconfig_new["GENERAL"]) as $key) {
      if ($myJSONconfig_old["GENERAL"] != "") {
        if (array_key_exists($key, $myJSONconfig_old["GENERAL"])) {  // If General Key exists then copy it over - All new Keys are inherited from default
          $myJSONconfig_new["GENERAL"][$key] = $myJSONconfig_old["GENERAL"][$key];
        }
      }
    }
    
    foreach (array_keys($myJSONconfig_new["DEBUG"]) as $key) {
      if ($myJSONconfig_old["DEBUG"] != "") {
        if (array_key_exists($key, $myJSONconfig_old["DEBUG"])) {  // If Debug Key exists then copy it over - All new Keys are inherited from default
          $myJSONconfig_new["DEBUG"][$key] = $myJSONconfig_old["DEBUG"][$key];
        }
      }
    }
    
    // Copy to the new array only the values that exist in the old array
    if ($myJSONconfig_old["TRAY_SHOW"] != "") {
      for ($i = 1; $i <= ($rows_old*$columns_old); $i++) {
        $myJSONconfig_new["TRAY_SHOW"][$i] = $myJSONconfig_old["TRAY_SHOW"][$i];
      }
      // Clear/Destroy unused TRAY_SHOWs if there are now less trays than default number
      if (($rows_new*$columns_new) > ($rows_old*$columns_old)) {
        for ($i = ($rows_old*$columns_old + 1); $i <= ($rows_new*$columns_new); $i++) {
          unset($myJSONconfig_new["TRAY_SHOW"][$i]);
        }
      }
    } else {
      for ($i = 1; $i <= ($rows_old*$columns_old); $i++) {
        $myJSONconfig_new["TRAY_SHOW"][$i] = "YES";
      }
    }
    
    foreach ($myJSONconfig_new["DATA_COLUMNS"] as $data_column_K => $data_column) {
      if (array_key_exists($data_column_K, $myJSONconfig_old["DATA_COLUMNS"])) {  // If Data Column exists then check each key
                                                                                  // All new Data Columns are inherited from default including their keys
        foreach (array_keys($data_column) as $data_column_key) {
          if (array_key_exists($data_column_key, $myJSONconfig_old["DATA_COLUMNS"][$data_column_K])) {  // If Data Column Key exists then update user defined keys only
                                                                                                        // All new Data Columns Keys are inherited from default
            switch ($data_column_key) {
              case "SHOW_DATA"     :
              case "SHOW_TOOLTIP"  :
              case "SHOW_COLUMN_I" :
              case "SHOW_COLUMN_H" :
              case "ORDER"         : $myJSONconfig_new["DATA_COLUMNS"][$data_column_K][$data_column_key] = $myJSONconfig_old["DATA_COLUMNS"][$data_column_K][$data_column_key]; break;
              default :
            }
          }
        }
      }
    }
    
    if ($myJSONconfig_old["DISK_DATA"] != "") {             // If at least one disk exists
      foreach (array_keys($myJSONconfig_old["DISK_DATA"]) as $disk_SN) {      // For each existing Disk (by key=SN)
        foreach (array_keys($default_disk) as $disk_key) {  // For all keys in new Disk default template
          if (array_key_exists($disk_key, $myJSONconfig_old["DISK_DATA"][$disk_SN])) {  // If key exists in old disk then copy it over
            $myJSONconfig_new["DISK_DATA"][$disk_SN][$disk_key] = $myJSONconfig_old["DISK_DATA"][$disk_SN][$disk_key];
          } else {                                                                  // Else it does not exist --> create new one
            $myJSONconfig_new["DISK_DATA"][$disk_SN][$disk_key] = "";
          }
        }
      }
    }

  }
  $GLOBALS["profiler"] = $profiler;
  $GLOBALS["profiler_on"] = $profiler_on;
  // Save new configuration (default or modified) to JSON file
  file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig_new));  // Save configuration data to JSON configuration file
  return $myJSONconfig_new;
}

// *********************
// Function Add_New_Disk
// *********************
function Add_New_Disk($myJSONconfig, $disk) {
  $disk["FIRST_INSTALL_DATE"] = date("Y/m/d");    // New disk to server
  $disk["LAST_SEEN_DATE"] = date("Y/m/d");        // New disk to server
  $disk["RECENT_INSTALL_DATE"] = date("Y/m/d");   // New disk to server
  $disk["STATUS"] = "INSTALLED";                  // Change STATUS to INSTALLED
  $disk["FOUND"] = "YES";                         // Change FOUND to YES for later scanning
  // Add disk to JSON array
  $myJSONconfig["DISK_DATA"][$disk["SN"]] = $disk;
  return $myJSONconfig;
}

// ******************************
// Function Check_Add_Update_Disk
// ******************************
function Check_Add_Update_Disk($myJSONconfig, $disk) {
  // Constants - GLOBAL constants
  $profiler = $GLOBALS["profiler"];
  $profiler_on = $GLOBALS["profiler_on"];
  $default_disk = $GLOBALS["default_disk"];

  if ($myJSONconfig["DISK_DATA"] != "") {
    if (array_key_exists($disk["SN"], $myJSONconfig["DISK_DATA"])) {  // Disk already exists in DISK_DATA array
      foreach (array_keys($default_disk) as $key) {
        switch ($key) {
          case "TYPE"             :                                                  // Use new data in $disk - Do not overwrite with old data
          case "DEVICE"           :                                                  //
          case "PATH"             :                                                  //
          case "UNRAID"           :                                                  //
          case "MANUFACTURER"     :                                                  //
          case "SN"               :                                                  //
          case "FW"               :                                                  //
          case "CAPACITY"         :                                                  //
          case "POWER_ON_HOURS"   :                                                  //
          case "LOAD_CYCLE_COUNT" :                                                  //
          case "COLOR"            : break;                                           //
          default: $disk[$key] = $myJSONconfig["DISK_DATA"][$disk["SN"]][$key];  // Get all other existing data (Manual data, Dates, etc...)
        }
      }
//      if ($disk["PATH"] != $myJSONconfig["DISK_DATA"][$disk["SN"]]["PATH"]) {
//        $disk["TRAY_NUM"] = "";                                                 // Reset TRAY_NUM if device PATH has changed
//      }
      $disk["LAST_SEEN_DATE"] = date("Y/m/d");                                  // Update current date for previously out-of-array devices
      $disk["FOUND"] = "YES";                                                   // Change FOUND to YES for later scanning
      if ($myJSONconfig["DISK_DATA"][$disk["SN"]]["STATUS"] == "HISTORICAL") {  // Disk is HISTORICAL
        $disk["RECENT_INSTALL_DATE"] = date("Y/m/d");                           // Update current date for previously out-of-array devices
        $disk["STATUS"] = "INSTALLED";                                          // Change STATUS to INSTALLED
        $disk["TRAY_NUM"] = "";                                                 // Clear TRAY_NUM history for new assignment
      }

      // Update disk to JSON array
      unset($myJSONconfig["DISK_DATA"][$disk["SN"]]);  // array_replace doesn't seem to work so I remove the original disk and add the new one
      $myJSONconfig["DISK_DATA"][$disk["SN"]] = $disk;
    } else {                                           // Disk new to server (also new to HISTORICAL)
      $myJSONconfig = Add_New_Disk($myJSONconfig, $disk);
    }
  } else {                                             // Server Empty
    $myJSONconfig = Add_New_Disk($myJSONconfig, $disk);
  }

  $GLOBALS["profiler"] = $profiler;
  $GLOBALS["profiler_on"] = $profiler_on;
  return $myJSONconfig;
}

// ************************************
// Function Scan_Installed_Devices_Data
// ************************************
function Scan_Installed_Devices_Data($myJSONconfig) {
  // Constants - GLOBAL constants
  $profiler = $GLOBALS["profiler"];
  $profiler_on = $GLOBALS["profiler_on"];
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $default_disk = $GLOBALS["default_disk"];
  $unraid_disks = $GLOBALS["disks"];

  // Change for all disks (if exists any) FOUND to "NO" for later scanning
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - Reset FOUND to NO" . "\n"; }
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "NO";
    }
  }
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - Reset FOUND to NO". "\n"; }

  // Go over all SATA, SAS and USB devices in /dev/disk/by-id
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - ls -las /dev/disk/by-id 2>/dev/null". "\n"; }
  $data = explode("\n", shell_exec("ls -las /dev/disk/by-id 2>/dev/null"));
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - ls -las /dev/disk/by-id 2>/dev/null" . "\n"; }
  foreach ($data as $line) {
    if ((strstr($line, "ata-") or strstr($line, "usb-") or strstr($line, "scsi-")) and (!strstr($line, "-part"))) {  // Look for SATA, SAS and USB devices AND not partitions
      $disk = $default_disk;  // Create a new disk array from template
      // Check if device is SATA, SAS or USB for future testing
      if (strstr($line, "ata-")) {
        $device_type = "SATA";
      } else if (strstr($line, "scsi-")) {
        $device_type = "SAS";
      } else if (strstr($line, "usb-")) {
        $device_type = "USB";
      }
      // Find DEVICE
      $disk["DEVICE"] = trim(substr($line, strpos($line, "../../")+strlen("../../")));  // Update device id in any case
      // Find PATH
      if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - lsscsi -t 2>/dev/null" . "\n"; }
      $lsscsi_data = explode("\n", shell_exec("lsscsi -t 2>/dev/null"));
      if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - lsscsi -t 2>/dev/null" . "\n"; }
      foreach ($lsscsi_data as $data_line) {
        if (strstr($data_line, "/dev/".$disk["DEVICE"])) {
          if (($device_type == "SATA") or ($device_type == "SAS")) {
            $disk["PATH"] = trim(substr($data_line, strpos($data_line, "[")+1, strpos($data_line, "]")-strpos($data_line, "[")-1));
          } else if ($device_type == "USB") {
            $disk["PATH"] = trim(substr($data_line, strpos($data_line, "usb:")+4, strpos($data_line, "/dev/")-strpos($data_line, "usb:")-4-1));
          }
          break;
        }
      }
      if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - lsscsi -t 2>/dev/null" . "\n"; }

      // Find UNRAID disk functionality
      foreach ($unraid_disks as $unraid_disk) {
        if ($unraid_disk["device"] == $disk["DEVICE"]) {
          $disk["UNRAID"] = $unraid_disk["name"];
          $disk["COLOR"] =  $unraid_disk["color"];
          break;
        }
      }
      if ($disk["COLOR"] == "") {
        $disk["COLOR"] = "blue-on";
      }
      
      // Find all other disk information
      if (substr($disk["DEVICE"],0,2) == "sd") {  // Get HDD data
        // For HDD devices
        if (($device_type == "SATA") or ($device_type == "SAS")) {
          $disk["TYPE"] = $device_type;
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - smartctl --all /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
          $device_data = explode("\n", shell_exec("smartctl --all /dev/".$disk["DEVICE"]." 2>/dev/null"));
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - smartctl --all /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
          foreach ($device_data as $data_line) {
            if (strpos($data_line, ":")) {
              $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
              switch (strtolower($parameter)) {
                case "vendor:"           : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break; // Added for ARECA support
                case "model family:"     : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
                case "product:"          : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break; // Added for ARECA support
                case "device model:"     : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
                case "serial number:"    : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)))); break;
                case "revision:"         : $disk["FW"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break; // Added for ARECA support
                case "firmware version:" : $disk["FW"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
                case "user capacity:"    : $disk["CAPACITY"] = trim(substr($data_line, strpos($data_line, "[")+1, strpos($data_line, "]")-strpos($data_line, "[")-1)); break;
                default :
              }
            }
            else if (strpos($data_line, "Power_On_Hours")) {
              $disk["POWER_ON_HOURS"] = trim(substr(trim($data_line), strrpos(trim($data_line), " ")));
              if (strpos($disk["POWER_ON_HOURS"], "h")) {  // If "h" for hours exists then truncate to hours only
                $disk["POWER_ON_HOURS"] = trim(substr($disk["POWER_ON_HOURS"], 0, strpos($disk["POWER_ON_HOURS"], "h")));
              }
            }
            else if (strpos($data_line, "Load_Cycle_Count")) {
              $disk["LOAD_CYCLE_COUNT"] = trim(substr(trim($data_line), strrpos(trim($data_line), " ")));
            }
          }
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - smartctl --all /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }

        } else if ($device_type == "USB") {
          // For USB devices
          $disk["TYPE"] = "USB";
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - udevadm info --query=property --name=/dev/".$disk["DEVICE"]." --attribute-walk 2>/dev/null" . "\n"; }
          $device_data = explode("\n", shell_exec("udevadm info --query=property --name=/dev/".$disk["DEVICE"]." --attribute-walk 2>/dev/null"));
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - udevadm info --query=property --name=/dev/".$disk["DEVICE"]." --attribute-walk 2>/dev/null" . "\n"; }
          foreach ($device_data as $data_line) {
            if (strpos($data_line, "ATTR")) {
              $parameter = trim(substr($data_line, strpos($data_line, "{")+1, strpos($data_line, "}")-strpos($data_line, "{")-1));
              switch (strtolower($parameter)) {
                case "serial"       : $first_quote_pos = strpos($data_line, "\"");
                                      $disk["SN"] = trim(substr($data_line, ($first_quote_pos+1), strpos($data_line, "\"", $first_quote_pos+1) - $first_quote_pos - 1)); break;
                case "manufacturer" : $first_quote_pos = strpos($data_line, "\"");
                                      $disk["MANUFACTURER"] = trim(substr($data_line, ($first_quote_pos+1), strpos($data_line, "\"", $first_quote_pos+1) - $first_quote_pos - 1)); break;
                case "product"      : $first_quote_pos = strpos($data_line, "\"");
                                      $disk["MODEL"] = trim(substr($data_line, ($first_quote_pos+1), strpos($data_line, "\"", $first_quote_pos+1) - $first_quote_pos - 1));
                                      break 2;
                default:
              }
            }
          }
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - udevadm info --query=property --name=/dev/".$disk["DEVICE"]." --attribute-walk 2>/dev/null" . "\n"; }

          // Find USB capacity
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - sgdisk -p /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
          $device_data = explode("\n", shell_exec("sgdisk -p /dev/".$disk["DEVICE"]." 2>/dev/null"));
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - sgdisk -p /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
          foreach ($device_data as $data_line) {
            if (strpos($data_line, "sectors, ")) {
              $disk["CAPACITY"] = trim(substr($data_line, strpos($data_line, "sectors, ")+strlen("sectors, ")));
            }
          }
          if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - sgdisk -p /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
       }
 
      } elseif (substr($disk["DEVICE"],0,2) == "sr") {  // Get CD/DVD data
        // For CD/DVD devices
        $disk["TYPE"] = "CD/DVD";
        $disk["CAPACITY"] = "CD/DVD";
        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - hdparm -I /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
        $device_data = explode("\n", shell_exec("hdparm -I /dev/".$disk["DEVICE"]." 2>/dev/null"));
        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - hdparm -I /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch (strtolower($parameter)) {
              case "serial number:"    : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)))); break;
              default :
            }
          }
        }
        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - hdparm -I /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }

        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
        $device_data = explode("\n", shell_exec("smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null"));
        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Analyze - smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch (strtolower($parameter)) {
              case "vendor:"        : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "product:"       : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "revision:"      : $disk["FW"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              default :
            }
          }
        }
        if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null" . "\n"; }
      }

      if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - Check_Add_Update_Disk($myJSONconfig, $disk)" . "\n"; }
      $myJSONconfig = Check_Add_Update_Disk($myJSONconfig, $disk);
      if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - Check_Add_Update_Disk($myJSONconfig, $disk)" . "\n"; }

    }
  }
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",End - ls -las /dev/disk/by-id 2>/dev/null" . "\n"; }

  // Change all remaining disks (if exist any disks) with FOUND="NO" to STATUS="HISTORICAL"
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",Start - Update not FOUND to HISTORICAL" . "\n"; }
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      if ($myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] == "NO") {
        $myJSONconfig["DISK_DATA"][$disk_SN]["STATUS"] = "HISTORICAL";
        $myJSONconfig["DISK_DATA"][$disk_SN]["DEVICE"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["PATH"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["UNRAID"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["COLOR"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "YES";
      }
    }
  }
  if ($profiler_on == "YES") { $profiler .= microtime(true) . ",END - Update not FOUND to HISTORICAL" . "\n"; }
  $GLOBALS["profiler"] = $profiler;
  $GLOBALS["profiler_on"] = $profiler_on;
  return $myJSONconfig;
}
?>
