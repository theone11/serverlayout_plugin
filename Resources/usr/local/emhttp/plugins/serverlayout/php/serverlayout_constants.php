<?php
$max_trays = 26;
$num_data_col = 9;
$num_data_col_not_show = 2;  // TYPE (Must be 1st), TRAY_NUM (Must be last)
$automatic_data = "/boot/config/plugins/serverlayout/AutomaticData.cfg";
$serverlayout_cfg_file = "/boot/config/plugins/serverlayout/serverlayout.cfg";
$scan_command = "/usr/local/emhttp/plugins/serverlayout/shell_scripts/getdiskdata.sh ".$automatic_data;

$frontpanel_imgfile = "/plugins/serverlayout/images/frontpanel.jpg";
$sata_imgfile = "/plugins/serverlayout/images/SATA_Logo.png";
$usb_imgfile = "/plugins/serverlayout/images/USB_Logo.png";
$optical_imgfile = "/plugins/serverlayout/images/opticalmedia_Logo.png";

// Drives' background constants
$factor= 4;  // Status table vs. Preview table size
$border_radius = 8;
$background_padding = 4;
$width = 320;
$height = 80;
?>
