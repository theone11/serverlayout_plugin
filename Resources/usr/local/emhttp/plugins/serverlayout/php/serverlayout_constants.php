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
                      "TRAY_NUM"            => array("NAME" => "TRAY_NUM",            "TITLE" => "Tray #",              "SHOW_DATA" => "NO",  "ORDER" => "1"),
                      "TYPE"                => array("NAME" => "TYPE",                "TITLE" => "Type",                "SHOW_DATA" => "NO",  "ORDER" => "2"),
                      "DEVICE"              => array("NAME" => "DEVICE",              "TITLE" => "Device",              "SHOW_DATA" => "YES", "ORDER" => "3"),
                      "MANUFACTURER"        => array("NAME" => "MANUFACTURER",        "TITLE" => "Manufacturer",        "SHOW_DATA" => "YES", "ORDER" => "4"),
                      "MODEL"               => array("NAME" => "MODEL",               "TITLE" => "Model",               "SHOW_DATA" => "YES", "ORDER" => "5"),
                      "SN"                  => array("NAME" => "SN",                  "TITLE" => "Serial Number",       "SHOW_DATA" => "YES", "ORDER" => "6"),
                      "FW"                  => array("NAME" => "FW",                  "TITLE" => "Firmware",            "SHOW_DATA" => "YES", "ORDER" => "7"),
                      "CAPACITY"            => array("NAME" => "CAPACITY",            "TITLE" => "Capacity",            "SHOW_DATA" => "YES", "ORDER" => "8"),
                      "FIRST_INSTALL_DATE"  => array("NAME" => "FIRST_INSTALL_DATE",  "TITLE" => "First Install Date",  "SHOW_DATA" => "YES", "ORDER" => "9"),
                      "RECENT_INSTALL_DATE" => array("NAME" => "RECENT_INSTALL_DATE", "TITLE" => "Recent Install Date", "SHOW_DATA" => "YES", "ORDER" => "10"),
                      "LAST_SEEN_DATE"      => array("NAME" => "LAST_SEEN_DATE",      "TITLE" => "Last Seen Date",      "SHOW_DATA" => "YES", "ORDER" => "11"),
                      "PURCHASE_DATE"       => array("NAME" => "PURCHASE_DATE",       "TITLE" => "Purchase Date",       "SHOW_DATA" => "YES", "ORDER" => "12"),
                      ));

$template_disk = array("TRAY_NUM"            => "",
                       "TYPE"                => "",
                       "DEVICE"              => "",
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
                       "FOUND"               => "");

// *****************************
// Function Get_JSON_Config_File
// *****************************
function Get_JSON_Config_File() {
  // Constants - GLOBAL constants
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $default_layout = $GLOBALS["default_layout"];
  $default_col_data = $GLOBALS["default_col_data"];
  // Local Constants
  $default_disk_data = array("DISK_DATA" => "");

  if (file_exists($serverlayout_cfg_file)) {  // Import JSON file if exists
    $myJSONconfig = json_decode(file_get_contents($serverlayout_cfg_file), true);
    $myJSONconfig = array_replace_recursive(array_merge($default_layout, $default_col_data, $default_disk_data), $myJSONconfig);
  #  $myJSONconfig = array_diff($myJSONconfig, array_merge($default_layout, $default_col_data, $default_disk_data)); For removing unused fields - Function does not exist

  } else {  // Else create new JSON file
    $myJSONconfig = array_merge($default_layout, $default_col_data, $default_disk_data);
    file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));  // Save configuration data to JSON configuration file
  }
  return $myJSONconfig;
}

// ******************************
// Function Check_Add_Update_Disk
// ******************************
function Check_Add_Update_Disk($myJSONconfig, $disk) {
  // Constants - GLOBAL constants
  $template_disk = $GLOBALS["template_disk"];

  if ((count.$myJSONconfig["DISK_DATA"] > 0) and (array_key_exists($disk["SN"], $myJSONconfig["DISK_DATA"]))) {  // Disk already exists in DISK_DATA array
    $device_save = $disk["DEVICE"];                                 // Save new DEVICE
    foreach (array_keys($template_disk) as $key) {
      $disk[$key] = $myJSONconfig["DISK_DATA"][$disk["SN"]][$key];  // Get all existing data - for array_replace_recursive later on
    }
    $disk["DEVICE"] = $device_save;                                 // KEEP new DEVICE
    $disk["LAST_SEEN_DATE"] = date("Y/m/d");          // Update current date for previously out-of-array devices
    $disk["FOUND"] = "YES";                           // Change FOUND to YES for later scanning
    if ($myJSONconfig["DISK_DATA"][$disk["SN"]]["STATUS"] == "HISTORICAL") {  // Disk is HISTORICAL
      $disk["RECENT_INSTALL_DATE"] = date("Y/m/d");   // Update current date for previously out-of-array devices
      $disk["STATUS"] = "INSTALLED";                  // Change STATUS to INSTALLED
    }

    // Update disk to JSON array
    unset($myJSONconfig["DISK_DATA"][$disk["SN"]]);  // array_replace doesn't seem to work
    $myJSONconfig["DISK_DATA"][$disk["SN"]] = $disk;      


  } else {  // Disk new to server (also new to HISTORICAL)          
    $disk["FIRST_INSTALL_DATE"] = date("Y/m/d");    // New disk to server
    $disk["LAST_SEEN_DATE"] = date("Y/m/d");        // New disk to server
    $disk["RECENT_INSTALL_DATE"] = date("Y/m/d");   // New disk to server
    $disk["STATUS"] = "INSTALLED";                  // Change STATUS to INSTALLED
    $disk["FOUND"] = "YES";                         // Change FOUND to YES for later scanning
    // Add disk to JSON array
    $myJSONconfig["DISK_DATA"][$disk["SN"]] = $disk;
  }

  return $myJSONconfig;
}

// ************************************
// Function Scan_Installed_Devices_Data
// ************************************
function Scan_Installed_Devices_Data($myJSONconfig) {
  // Constants - GLOBAL constants
  $serverlayout_cfg_file = $GLOBALS["serverlayout_cfg_file"];
  $template_disk = $GLOBALS["template_disk"];

  // Change for all disks (if exists any) FOUND to "NO" for later scanning
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "NO";
    }
  }

  // Find HDD and CD/DVD devices

  $data = explode("\n", shell_exec("ls -las /dev/disk/by-id"));

  foreach ($data as $line) {
    if ((strstr($line, "ata-")) and (!strstr($line, "-part"))) {  // Look for SATA devices (HDD and CD/DVD ROMs) AND not partitions
      $disk = $template_disk;  // Create a new disk array from template
      $disk["DEVICE"] = trim(substr($line, strpos($line, "../../")+strlen("../../")));  // Update device id in any case
      if (substr($disk["DEVICE"],0,2) == "sd") {  // Get HDD data
        $disk["TYPE"] = "SATA";
        $device_data = explode("\n", shell_exec("smartctl -i /dev/".$disk["DEVICE"]));
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
        $device_data = explode("\n", shell_exec("hdparm -I /dev/".$disk["DEVICE"]));
        foreach ($device_data as $data_line) {
          if (strpos($data_line, ":")) {
            $parameter = trim(substr($data_line, 0, strpos($data_line, ":")+strlen(":")));
            switch ($parameter) {
              case "Serial Number:"    : $disk["SN"] = trim(substr($data_line, (strpos($data_line, $parameter)+strlen($parameter)))); break;
              default :
            }
          }
        }
        $device_data = explode("\n", shell_exec("smartctl -i /dev/".$disk["DEVICE"]));
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

  $data = explode("\n", shell_exec("lsusb"));

  foreach ($data as $line) {
    $bus = trim(substr($line, strpos($line, "Bus ")+strlen("Bus "), 3));
    $device = trim(substr($line, strpos($line, "Device ")+strlen("Device "), 3));

    $device_data = explode("\n", shell_exec("lsusb -D /dev/bus/usb/".$bus."/".$device));

    $disk = $template_disk;  // Create a new disk array from template
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
    
      $device_data = explode("\n", shell_exec("ls -las /dev/disk/by-id/*".$disk["SN"]."*"));
      if (!strstr($device_data[0], "No such file or directory")) {
        $disk["DEVICE"] = trim(substr($device_data[0], strpos($device_data[0], "../../")+strlen("../../")));
      }

      $device_data = explode("\n", shell_exec("sgdisk -p /dev/".$disk["DEVICE"]));
      foreach ($device_data as $data_line) {
        if (strpos($data_line, "sectors, ")) {
          $disk["CAPACITY"] = trim(substr($data_line, strpos($data_line, "sectors, ")+strlen("sectors, ")));
        }
      }

      $myJSONconfig = Check_Add_Update_Disk($myJSONconfig, $disk);

    }
  }

  // Change all remaining disks (if exist any disks) with FOUND="NO" to STATUS="HISTORICAL"
  if ($myJSONconfig["DISK_DATA"] != "") {
    foreach (array_keys($myJSONconfig["DISK_DATA"]) as $disk_SN) {
      if ($myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] == "NO") {
        $myJSONconfig["DISK_DATA"][$disk_SN]["STATUS"] = "HISTORICAL";
        $myJSONconfig["DISK_DATA"][$disk_SN]["DEVICE"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["TRAY_NUM"] = "";
        $myJSONconfig["DISK_DATA"][$disk_SN]["FOUND"] = "YES";
      }
    }
  }
  return $myJSONconfig;
}
?>
