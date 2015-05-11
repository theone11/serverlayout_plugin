<?php
include 'serverlayout_constants.php';

$serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
$rows = $serverlayout_cfg['ROWS'];
$columns = $serverlayout_cfg['COLUMNS'];
$orientation = $serverlayout_cfg['ORIENTATION'];
$num_trays = $num_columns * $num_rows;

$width = 293;
$height = 80;

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

#container {
  overflow: hidden;
  min-width: <? echo ($width * $columns); ?>px;
  max-width: <? echo ($width * $columns); ?>px;
  min-height: <? echo ($height * $rows); ?>px;
  max-height: <? echo ($height * $rows); ?>px;
  transform: rotate(-<? echo $orientation ?>deg)
             translate(-<? echo $orientation/90*($columns/2*$width - $rows/2*$height) ?>px,
                        <? echo $orientation/90*($rows/2*$height - $columns/2*$width) ?>px);
  border-spacing: 0px;
  border-width: 0px;
  margin: 50px;
  padding: 0px;
  borders: 0px;
  background: rgba(54, 25, 25, 0);
}

#tray {
  border-color: black;
  line-height:20px;
  background-image: url("frontpanel.jpg");
  background-size: 306px 80px;
  background-repeat: no-repeat;

  max-height: <? echo $height; ?>px;
  max-width: <? echo $width; ?>px;
  height: <? echo $height; ?>px;
  width: <? echo $width; ?>px;
  float:left;
  border-spacing: 0px;
  border-width: 0px;
  margin: 0px;
  padding:0px;
  borders: 0px;
  color: black;
  display: table;
}

#tray_text {
  text-align: center;
  vertical-align: middle;
  border-spacing: 0px;
  border-width: 0px;
  margin: 0px;
  padding:0px;
  padding-left: 65px;
  padding-right: 5px;
  borders: 0px;
  display: table-cell;
  vertical-align: middle;
}
;
</style>

</HEAD>
<BODY>

<div id="container">
<?php for ($i = 1; $i <= $rows; $i++) { ?>
  <?php for ($j = 1; $j <= $columns; $j++) { ?>
      <div id="tray">
      <div id="tray_text">
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
<?php } ?>
</div>

</BODY>
</HTML>
