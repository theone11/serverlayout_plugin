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

          $x_translate = $rows;
          $y_translate = $columns;
         
?>

<HTML>
<HEAD>
<style>
.container {
  width: <? echo ($width * $columns); ?>px;
  height: <? echo ($width * $rows); ?>px;
  margin: 50px;
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
  background-image: url(<?php echo $frontpanel_imgfile; ?>);
  background-size: cover;
  overflow: hidden;
}

.cell_text {
  text-align: center;
  position: relative;           /* Vertical Center */
  top: 50%;                     /* Vertical Center */
  transform: translateY(-50%);  /* Vertical Center */
  padding-left: 65px;
  padding-right: 5px;
  box-sizing: border-box;
  overflow: hidden;
}
</style>

<script type="text/javascript">
function UpdateDIVSizes() {
  var orientation = <?php echo $orientation; ?>;
  var element = document.getElementsByClassName("container");
  for (i = 0; i < element.length; i++) {
    if (orientation == 0) {
      element[i].style.height = <?php echo ($height * $rows); ?>;
    } else {
      element[i].style.width = <?php echo ($height * $columns); ?>;
    }
  }
}
</script>
</HEAD>
<BODY onload="UpdateDIVSizes()">

<div class="container">
<?php for ($i = 1; $i <= $rows; $i++) { ?>
  <div class="row_container">
  <?php for ($j = 1; $j <= $columns; $j++) {
      $x_translate = $orientation/90*(-$width/2 + $height/2 - ($j-1)*($width-$height));
      $y_translate = $orientation/90*(-$width/2 + $height/2); ?>
    <div class="cell_container" <?php if ($orientation == 90) { echo "style=\"transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);\""; } ?>>
      <div class="cell_text">
      <?php $tray_num = (($i-1) * $columns) + $j;
            echo $tray_num." / ";
            for ($k = 1; $k <= $num_disks; $k++) {
              if ($serverlayout_cfg[$serverlayout_auto[$k]['SN']]['TRAY_NUM'] == $tray_num) {
                for ($m = 1; $m <= ($num_data_col-$num_data_col_not_show); $m++) {
                  $index = "SHOW".$m;
                  if (($m == 1) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_auto[$k]['DEVICE']." / "; }
                  if (($m == 2) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_auto[$k]['MODEL']." / "; }
                  if (($m == 3) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_auto[$k]['SN']." / "; }
                  if (($m == 4) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_auto[$k]['FIRMWARE']." / "; }
                  if (($m == 5) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_auto[$k]['CAPACITY']." / "; }
                  if (($m == 6) and ($serverlayout_cfg[$index] == "SHOW" )) { echo $serverlayout_cfg[$serverlayout_auto[$k]['SN']]['PURCHASE_DATE']." / "; }
                }
              }
            } ?>
      </div>
    </div>
  <?php } ?>
  </div>
<?php } ?>
</div>

</BODY>
</HTML>
