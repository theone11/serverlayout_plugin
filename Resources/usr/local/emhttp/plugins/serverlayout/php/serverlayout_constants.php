<?php
$max_trays = 26;

// Constants - Data file locations
$automatic_data = "/boot/config/plugins/serverlayout/AutomaticData.cfg";
$serverlayout_cfg_file = "/boot/config/plugins/serverlayout/serverlayout.json";

// Constants - Image file locations
$frontpanel_imgfile = "/plugins/serverlayout/images/frontpanel.jpg";
$sata_imgfile = "/plugins/serverlayout/images/SATA_Logo.png";
$usb_imgfile = "/plugins/serverlayout/images/USB_Logo.png";
$optical_imgfile = "/plugins/serverlayout/images/opticalmedia_Logo.png";

// Constants - Drives' background
$factor= 4;  // Status table vs. Preview table size
$border_radius = 8;
$background_padding = 4;
$width = 320;
$height = 80;

// Constants - JSON configuration file
$default_layout = array("LAYOUT" => array("ROWS" => "6", "COLUMNS" => "4", "ORIENTATION" => "0"));

$default_col_data = array("DATA_COLUMNS" => array (
                      "TRAY_NUM"            => array("NAME" => "TRAY_NUM",            "TITLE" => "Tray #",         "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "1",  "TEXT_ALIGN" => "center"),
                      "TYPE"                => array("NAME" => "TYPE",                "TITLE" => "Type",           "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "2",  "TEXT_ALIGN" => "center"),
                      "DEVICE"              => array("NAME" => "DEVICE",              "TITLE" => "Device",         "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "3",  "TEXT_ALIGN" => "center"),
                      "PATH"                => array("NAME" => "PATH",                "TITLE" => "Path",           "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "NO",  "ORDER" => "4",  "TEXT_ALIGN" => "left"  ),
                      "MANUFACTURER"        => array("NAME" => "MANUFACTURER",        "TITLE" => "Manufacturer",   "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "5",  "TEXT_ALIGN" => "left"  ),
                      "MODEL"               => array("NAME" => "MODEL",               "TITLE" => "Model",          "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "6",  "TEXT_ALIGN" => "left"  ),
                      "SN"                  => array("NAME" => "SN",                  "TITLE" => "Serial Number",  "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "7",  "TEXT_ALIGN" => "right" ),
                      "FW"                  => array("NAME" => "FW",                  "TITLE" => "Firmware",       "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "8",  "TEXT_ALIGN" => "right" ),
                      "CAPACITY"            => array("NAME" => "CAPACITY",            "TITLE" => "Capacity",       "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "9",  "TEXT_ALIGN" => "right" ),
                      "FIRST_INSTALL_DATE"  => array("NAME" => "FIRST_INSTALL_DATE",  "TITLE" => "First Install",  "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "10", "TEXT_ALIGN" => "center"),
                      "RECENT_INSTALL_DATE" => array("NAME" => "RECENT_INSTALL_DATE", "TITLE" => "Recent Install", "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "11", "TEXT_ALIGN" => "center"),
                      "LAST_SEEN_DATE"      => array("NAME" => "LAST_SEEN_DATE",      "TITLE" => "Last Seen",      "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "12", "TEXT_ALIGN" => "center"),
                      "PURCHASE_DATE"       => array("NAME" => "PURCHASE_DATE",       "TITLE" => "Purchase Date",  "SHOW_DATA" => "YES", "SHOW_COLUMN_I" => "YES", "SHOW_COLUMN_H" => "YES", "ORDER" => "13", "TEXT_ALIGN" => "center")
                      ));

$default_disk = array("TRAY_NUM"            => "",
                      "TYPE"                => "",
                      "DEVICE"              => "",
                      "PATH"                => "",
                      "MANUFACTURER"        => "",
                      "MODEL"               => "",
                      "SN"                  => "",
                      "FW"                  => "",
                      "CAPACITY"            => "",
                      "FIRST_INSTALL_DATE"  => "",
                      "RECENT_INSTALL_DATE" => "",
                      "LAST_SEEN_DATE"      => "",
                      "PURCHASE_DATE"       => "",
                      "STATUS"              => "",
                      "FOUND"               => ""
                      );


$myJSONconfig = Get_JSON_Config_File();  // Get or create JSON configuration file
$myJSONconfig = Scan_Installed_Devices_Data($myJSONconfig);  // Scan all installed devices
file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));  // Save configuration data to JSON configuration file


// *****************************
// Function Get_JSON_Config_File
// *****************************
// The function creates an default configuration file if none exists, otherwise it creates a default configuration
// BUT also copies over all user defined configuration data from existing configuration file

function Get_JSON_Config_File() {
  // Constants - GLOBAL constants
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $default_layout = $GLOBALS["default_layout"];
  $default_col_data = $GLOBALS["default_col_data"];
  $default_disk = $GLOBALS["default_disk"];
  // Local Constants
  $default_disk_data = array("DISK_DATA" => "");

  // Define new configuration file based on default values
  $myJSONconfig_new = array_merge($default_layout, $default_col_data, $default_disk_data);

  if (file_exists($serverlayout_cfg_file)) {  // Import JSON file if exists

    $myJSONconfig_old = json_decode(file_get_contents($serverlayout_cfg_file), true);

    foreach (array_keys($myJSONconfig_new["LAYOUT"]) as $layout_key) {
      if (array_key_exists($layout_key, $myJSONconfig_old["LAYOUT"])) {  // If Layout Key exists then copy it over - All new Keys are inherited from default
        $myJSONconfig_new["LAYOUT"][$layout_key] = $myJSONconfig_old["LAYOUT"][$layout_key];
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
  $default_disk = $GLOBALS["default_disk"];

  if ($myJSONconfig["DISK_DATA"] != "") {
    if (array_key_exists($disk["SN"], $myJSONconfig["DISK_DATA"])) {  // Disk already exists in DISK_DATA array
      foreach (array_keys($default_disk) as $key) {
        switch ($key) {
          case "TYPE"         :                                                  // Use new data - Do not overwrite with old data
          case "DEVICE"       :                                                  //
          case "PATH"         :                                                  //
          case "MANUFACTURER" :                                                  //
          case "SN"           :                                                  //
          case "FW"           :                                                  //
          case "CAPACITY"     : break;                                           //
          default: $disk[$key] = $myJSONconfig["DISK_DATA"][$disk["SN"]][$key];  // Get all other existing data (Manual data, Dates, etc...)
        }
      }
      if ($disk["PATH"] != $myJSONconfig["DISK_DATA"][$disk["SN"]]["PATH"]) {
        $disk["TRAY_NUM"] = "";                                                 // Reset TRAY_NUM if device PATH has changed
      }
      $disk["LAST_SEEN_DATE"] = date("Y/m/d");                                  // Update current date for previously out-of-array devices
      $disk["FOUND"] = "YES";                                                   // Change FOUND to YES for later scanning
      if ($myJSONconfig["DISK_DATA"][$disk["SN"]]["STATUS"] == "HISTORICAL") {  // Disk is HISTORICAL
        $disk["RECENT_INSTALL_DATE"] = date("Y/m/d");                           // Update current date for previously out-of-array devices
        $disk["STATUS"] = "INSTALLED";                                          // Change STATUS to INSTALLED
      }

      // Update disk to JSON array
      unset($myJSONconfig["DISK_DATA"][$disk["SN"]]);  // array_replace doesn't seem to work
      $myJSONconfig["DISK_DATA"][$disk["SN"]] = $disk;
    } else {                                           // Disk new to server (also new to HISTORICAL)
      $myJSONconfig = Add_New_Disk($myJSONconfig, $disk);
    }
  } else {                                             // Server Empty
    $myJSONconfig = Add_New_Disk($myJSONconfig, $disk);
  }

  return $myJSONconfig;
}

// ************************************
// Function Scan_Installed_Devices_Data
// ************************************
function Scan_Installed_Devices_Data($myJSONconfig) {
  // Constants - GLOBAL constants
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $default_disk = $GLOBALS["default_disk"];

  // Change for all disks (if exists any) FOUND to "NO" for later scanning
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "NO";
    }
  }

  // Find HDD and CD/DVD devices

  $data = explode("\n", shell_exec("ls -las /dev/disk/by-id 2>/dev/null"));
  foreach ($data as $line) {
    if ((strstr($line, "ata-")) and (!strstr($line, "-part"))) {  // Look for SATA devices (HDD and CD/DVD ROMs) AND not partitions
      $disk = $default_disk;  // Create a new disk array from template
      $disk["DEVICE"] = trim(substr($line, strpos($line, "../../")+strlen("../../")));  // Update device id in any case
      $lsscsi_data = explode("\n", shell_exec("lsscsi 2>/dev/null"));
      foreach ($lsscsi_data as $data_line) {
        if (strstr($data_line, "/dev/".$disk["DEVICE"])) {
          $disk["PATH"] = trim(substr($data_line, strpos($data_line, "[")+1, strpos($data_line, "]")-strpos($data_line, "[")-1)); break;
        } else {
          $disk["PATH"] = "";
        }
      }
      if (substr($disk["DEVICE"],0,2) == "sd") {  // Get HDD data
        $disk["TYPE"] = "SATA";
        $device_data = explode("\n", shell_exec("smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null"));
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch ($parameter) {
              case "Model Family:"     : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "Device Model:"     : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "Serial Number:"    : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)))); break;
              case "Firmware Version:" : $disk["FW"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "User Capacity:"    : $disk["CAPACITY"] = trim(substr($data_line, strpos($data_line, "[")+1, strpos($data_line, "]")-strpos($data_line, "[")-1)); break;
              default :
            }
          }
        }
       
      } elseif (substr($disk["DEVICE"],0,2) == "sr") {  // Get CD/DVD data
        $disk["TYPE"] = "CD/DVD";
        $disk["CAPACITY"] = "CD/DVD";
        $device_data = explode("\n", shell_exec("hdparm -I /dev/".$disk["DEVICE"]." 2>/dev/null"));
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch ($parameter) {
              case "Serial Number:"    : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)))); break;
              default :
            }
          }
        }
        $device_data = explode("\n", shell_exec("smartctl -i /dev/".$disk["DEVICE"]." 2>/dev/null"));
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch ($parameter) {
              case "Vendor:"        : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "Product:"       : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              case "Revision:"      : $disk["FW"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter))); break;
              default :
            }
          }
        }
      }

      $myJSONconfig = Check_Add_Update_Disk($myJSONconfig, $disk);

    }
  }

  // Find USB devices

  $data = explode("\n", shell_exec("lsusb 2>/dev/null"));

  foreach ($data as $line) {
    if (strstr($line, "Bus ") and strstr($line, "Device ")) {
      $disk = $default_disk;  // Create a new disk array from template

      $bus = trim(substr($line, strpos($line, "Bus ")+strlen("Bus "), 3));
      $device = trim(substr($line, strpos($line, "Device ")+strlen("Device "), 3));
      $disk["PATH"] = "/".$bus."/".$device;

      $device_data = explode("\n", shell_exec("lsusb -D /dev/bus/usb/".$bus."/".$device." 2>/dev/null"));
      $is_USB = "";

      foreach ($device_data as $data_line) {
        $parameter = trim(substr(trim($data_line), 0, strpos(trim($data_line), " ")));
        switch ($parameter) {
          case "iManufacturer"   : $disk["MANUFACTURER"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter)+13)); break;
          case "iProduct"        : $disk["MODEL"] = trim(substr($data_line, strpos($data_line, $parameter)+strlen($parameter)+18)); break;
          case "iSerial"         : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)+19))); break;
          case "bInterfaceClass" : $is_USB = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter))+10)); break;
          default :
        }
      }

      if ($is_USB == "Mass Storage") {
        $disk["TYPE"] = "USB";

        $device_data = explode("\n", shell_exec("ls -las /dev/disk/by-id/*".$disk["SN"]."* 2>/dev/null"));
        if (!strstr($device_data[0], "No such file or directory")) {
          $disk["DEVICE"] = trim(substr($device_data[0], strpos($device_data[0], "../../")+strlen("../../")));
        }

        $device_data = explode("\n", shell_exec("sgdisk -p /dev/".$disk["DEVICE"]." 2>/dev/null"));
        foreach ($device_data as $data_line) {
          if (strpos($data_line, "sectors, ")) {
            $disk["CAPACITY"] = trim(substr($data_line, strpos($data_line, "sectors, ")+strlen("sectors, ")));
          }
        }

        $myJSONconfig = Check_Add_Update_Disk($myJSONconfig, $disk);

      }
    }
  }

  // Change all remaining disks (if exist any disks) with FOUND="NO" to STATUS="HISTORICAL"
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      if ($myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] == "NO") {
        $myJSONconfig["DISK_DATA"][$disk_SN]["STATUS"] = "HISTORICAL";
        $myJSONconfig["DISK_DATA"][$disk_SN]["DEVICE"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["PATH"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["TRAY_NUM"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "YES";
      }
    }
  }
  return $myJSONconfig;
}
?>
