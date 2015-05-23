<?php
include 'serverlayout_constants.php';

$myJSONconfig = Get_JSON_Config_File();  // Get or create JSON configuration file
$myJSONconfig = Scan_Installed_Devices_Data($myJSONconfig);  // Scan all installed devices
file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));  // Save configuration data to JSON configuration file

$rows = $myJSONconfig["LAYOUT"]['ROWS'];
$columns = $myJSONconfig["LAYOUT"]['COLUMNS'];
$orientation = $myJSONconfig["LAYOUT"]['ORIENTATION'];
$num_trays = $num_columns * $num_rows;
?>

<HTML>
<HEAD>
<style>
.container {
  width: <? echo ($width * $columns); ?>px;
  height: <? echo ($width * $rows); ?>px;
  margin-left: auto;
  margin-right: auto;
  background: rgba(54, 25, 25, 0);  /* Transparent Background */
  overflow: hidden;
  box-sizing: border-box;
}

.row_container {
  width: <? echo ($width * $columns); ?>px;
  height: <? if ($orientation == 0) { echo $height; } else { echo $width; } ?>px;
  box-sizing: border-box;
  overflow: hidden;
}

.cell_container {
  width: <? echo $width; ?>px;
  height: <? echo $height; ?>px;
  box-sizing: border-box;
  float:left;
  overflow: hidden;
}

.cell_background {
  width: <? echo ($width-$background_padding); ?>px;
  height: <? echo ($height-$background_padding); ?>px;
  box-sizing: border-box;
  float:left;
  background-image: url(<?php echo $frontpanel_imgfile; ?>);
  border-radius: <?php echo $border_radius; ?>px;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  overflow: hidden;
}  

.cell_text {
  text-align: center;
  position: relative;           /* Vertical Center */
  top: 50%;                     /* Vertical Center */
  transform: translateY(-50%);  /* Vertical Center */
  padding-left: 5px;
  padding-right: 5px;
  box-sizing: border-box;
  overflow: hidden;
  color: white;
}

.cell_text span:nth-child(even) {
  color: black;
}

.cell_text span:nth-child(odd) {
  color: white;
}
</style>

<script type="text/javascript">
function UpdateDIVSizes() {
  var orientation = <?php echo $orientation; ?>;
  var element = document.getElementById("container");
  if (orientation == 0) {
    element.style.height = "<?php echo ($height * $rows); ?>px";
  } else {
    element.style.width = "<?php echo ($height * $columns); ?>px";
  }
}
</script>
</HEAD>
<BODY>

<div class="container" id="container">
<?php for ($i = 1; $i <= $rows; $i++) { ?>
  <div class="row_container">
  <?php for ($j = 1; $j <= $columns; $j++) {
      $x_translate = $orientation/90*(-$width/2 + $height/2 - ($j-1)*($width-$height));
      $y_translate = $orientation/90*(-$width/2 + $height/2); ?>
    <div class="cell_container" <?php if ($orientation == 90) { echo "style=\"transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);\""; } ?>>
      <div class="cell_background">
        <div class="cell_text">
        <?php $tray_num = (($i-1) * $columns) + $j;
              echo "<span>".$tray_num."</span>";
              foreach (myJSONconfig["DISK_DATA"] as $disk) {
                if (($disk["STATUS"]=="INSTALLED") and ($disk['TRAY_NUM'] == $tray_num)) {
                  foreach (myJSONconfig["DATA_COLUMNS"] as $data_col) {
                    if ($data_col["SHOW_DATA"] == "YES") {
                      echo "<span>".$disk[$data_col["NAME"]]." </span>";
                    }
                  }
                }
              }
        </div>
      </div>
    </div>
  <?php } ?>
  </div>
<?php } ?>
</div>

<script>UpdateDIVSizes();</script>

</BODY>
</HTML>
