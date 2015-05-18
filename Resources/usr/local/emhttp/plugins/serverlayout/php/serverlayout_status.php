<?php
include 'serverlayout_constants.php';

$serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
$rows = $serverlayout_cfg['ROWS'];
$columns = $serverlayout_cfg['COLUMNS'];
$orientation = $serverlayout_cfg['ORIENTATION'];
$num_trays = $num_columns * $num_rows;

if (file_exists($automatic_data)) {
  $serverlayout_auto = parse_ini_file($automatic_data, true);
  $num_disks = count($serverlayout_auto, COUNT_NORMAL);
} else {
  $num_disks = 0;
}
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
              for ($k = 1; $k <= $num_disks; $k++) {
                if ($serverlayout_cfg[$serverlayout_auto[$k]['SN']]['TRAY_NUM'] == $tray_num) {
                  for ($m = 1; $m <= ($num_data_col-$num_data_col_not_show); $m++) {
                    $index = "SHOW".$m;
                    if (($m == 1) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['DEVICE']."</span>"; }
                    if (($m == 2) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['FAMILY']."</span>"; }
                    if (($m == 3) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['MODEL']."</span>"; }
                    if (($m == 4) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['SN']."</span>"; }
                    if (($m == 5) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['FIRMWARE']."</span>"; }
                    if (($m == 6) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_auto[$k]['CAPACITY']."</span>"; }
                    if (($m == 7) and ($serverlayout_cfg[$index] == "SHOW" )) { echo "<span> ".$serverlayout_cfg[$serverlayout_auto[$k]['SN']]['PURCHASE_DATE']."</span>"; }
                  }
                }
              } ?>
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
