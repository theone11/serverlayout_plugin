<?php
$max_trays = 26;
$num_data_col = 8;
$num_data_col_not_show = 2;  // TYPE (Must be 1st), TRAY_NUM (Must be last)
$automatic_data = "../AutomaticData.cfg";  // "/boot/config/plugins/serverlayout/AutomaticData.cfg";
$serverlayout_cfg_file = "../serverlayout.cfg";  // "/boot/config/plugins/serverlayout/serverlayout.cfg";
$scan_command = "../getdiskinfo_smartctl.sh";  // "/etc/rc.d/rc.serverlayout getdiskinfo";

$frontpanel_imgfile = "../images/frontpanel.jpg";
$sata_imgfile = "../images/SATA_Logo.png";
$usb_imgfile = "../images/USB_Logo.png";

$factor= 4;
$border_radius = 8;
$background_padding = 4;

$width = 320;
$height = 80;

// Constants for serverlayout layout styles
$control_width = 180;
$control_height = 40;
$control_margin = 10;
$heading_height = 40;
$heading_margin = 10;
?>
