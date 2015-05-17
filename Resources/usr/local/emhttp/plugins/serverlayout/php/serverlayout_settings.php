<?php
include 'serverlayout_constants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['apply'])) {
    // Write new configuration data    
    $arguments = "";
    $rows_new = $_POST['ROWS'];
    $arguments .= "ROWS=\"".$rows_new."\"\n";
    $columns_new = $_POST['COLUMNS'];
    $arguments .= "COLUMNS=\"".$columns_new."\"\n";
    $orientation_new = $_POST['ORIENTATION'];
    $arguments .= "ORIENTATION=\"".$orientation_new."\"\n";
    // Write SHOW/HIDE configuration
    for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) {
      $temp = "SHOW".$i;
      if (isset($_POST[$temp]) and ($_POST[$temp] == "SHOW")) {
        $arguments .= "SHOW".$i."=\"SHOW\"\n";
      } else {
        $arguments .= "SHOW".$i."=\"HIDE\"\n";
      }
    }
    // Get previous ROWS and COLUMNS settings
    $serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
    $rows_old = $serverlayout_cfg['ROWS'];
    $columns_old = $serverlayout_cfg['COLUMNS'];
    // Get number of disks
    if (file_exists($automatic_data)) {
      $serverlayout_auto = parse_ini_file($automatic_data, true);
      $num_disks = count($serverlayout_auto, COUNT_NORMAL);
    } else {
      $num_disks = 0;
    }
    for ($i = 1; $i <= $num_disks; $i++) {
      $temp = $serverlayout_auto[$i]['SN'];
      $arguments .= "[".$temp."]\n";
      // Check if ROWS / COLUMNS / ORIENTATION have changed
      if (($rows_new == $rows_old) and ($columns_new == $columns_old)) {
        // At least one of the following ROWS / COLUMNS has changed - Don't save TRAY_NUM assignments
        if ($serverlayout_auto[$i]['TYPE'] != "USB") {  // Also don't save if device is USB
          $temp = "TRAY_NUM".$i;
          $temp2 = $_POST[$temp];
          $arguments .= "TRAY_NUM=\"".$temp2."\"\n";
        }
      }
      // Save all other disk information
      $temp = "PURCHASE_DATE".$i;
      $temp2 = $_POST[$temp];
      $arguments .= "PURCHASE_DATE=\"".$temp2."\"\n";
    }
    // Save to CONFIG file
    file_put_contents($serverlayout_cfg_file, $arguments);

  } elseif (isset($_POST['scan'])) {
      shell_exec($scan_command);
  }

} else {  // Not POST method

//  $is_post = false;
//  $dataOK = true;
//  $rowsERR = false;
//  $columnsERR = false;

}

if (file_exists($automatic_data)) {
  $serverlayout_auto = parse_ini_file($automatic_data, true);
  $num_disks = count($serverlayout_auto, COUNT_NORMAL);
} else {
  $num_disks = 0;
}

$serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
$rows = $serverlayout_cfg['ROWS'];
$columns = $serverlayout_cfg['COLUMNS'];
$orientation = $serverlayout_cfg['ORIENTATION'];
$num_trays = $columns * $rows;

// Variables and Calculations for layout styles

$width_preview = $width/$factor;
$height_preview = $height/$factor;
$border_radius_preview = $border_radius/$factor;
$background_padding_preview = $background_padding;

if ($orientation == 0) {
  if (($columns * $width) > ($rows * $height)) {
    $layout_orientation = 0;
  } else {
    $layout_orientation = 90;
  }
} else {
  if (($columns * $height) > ($rows * $width)) {
    $layout_orientation = 0;
  } else {
    $layout_orientation = 90;
  }
}  
?>

<HTML>

<HEAD>
<style>
.container_preview {
  width: <? echo ($width_preview * $columns); ?>px;
  height: <? echo ($width_preview * $rows); ?>px;
  margin: 0px;
  background: rgba(54, 25, 25, 0);  /* Transparent Background */
  overflow: hidden;
  box-sizing: border-box;
}

.row_container_preview {
  width: <? echo ($width_preview * $columns); ?>px;
  height: <? if ($orientation == 0) { echo $height_preview; } else { echo $width_preview; } ?>px;
  box-sizing: border-box;
  overflow: hidden;
}

.cell_container_preview {
  width: <? echo $width_preview; ?>px;
  height: <? echo $height_preview; ?>px;
  box-sizing: border-box;
  float:left;
  overflow: hidden;
}

.cell_background_preview {
  width: <? echo ($width_preview-$background_padding_preview); ?>px;
  height: <? echo ($height_preview-$background_padding_preview); ?>px;
  box-sizing: border-box;
  float:left;
  background-image: url(<?php echo $frontpanel_imgfile; ?>);
  border-radius: <?php echo $border_radius_preview; ?>px;
	background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  overflow: hidden;
}  

.cell_text_preview {
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
</style>

<script type="text/javascript">
function validateForm() {
  var num_disks = <?php echo $num_disks ?>;

  document.getElementById('ROWS').disabled = false;
  document.getElementById('COLUMNS').disabled = false;
  document.getElementById('ORIENTATION').disabled = false;
  for (i = 1; i <= num_disks; i++) {
    index = "TRAY_NUM"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - NOT created in HTML for USB
      document.getElementById(index).disabled = false;
    }
    index = "PURCHASE_DATE"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - NOT created in HTML for USB
      document.getElementById(index).disabled = false;
    }
  }
}

function StartUp() {
  DefineColumnsDropDownList();
  TrayOptionsStartup();
  InitDisabledFields();
  InitShowCheckboxed();
  UpdateDIVSizes();
}

function InitShowCheckboxed() {
  var num_data_col = <?php echo $num_data_col; ?>;
  var num_data_col_not_show = <?php echo $num_data_col_not_show; ?>;
  var initial_shows = [<?for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) {
                           $temp = "SHOW".$i;
                           if ($i == ($num_data_col-$num_data_col_not_show)) {
                             echo "\""; echo $serverlayout_cfg[$temp]; echo "\"";
                           } else {
                             echo "\""; echo $serverlayout_cfg[$temp]; echo "\", ";
                           }
                        } ?>];

  for (i = 1; i <= (num_data_col-num_data_col_not_show); i++) {  // Read SHOW/HIDE configuration
    var index = "SHOW"+i;
    var element = document.getElementById(index);
    if (element != null) {
      if (initial_shows[i-1] == "SHOW") {
        element.checked = true;
      } else {
        element.checked = false;
      }
    }
  }
}

function InitDisabledFields() {
  var num_disks = <?php echo $num_disks ?>;

  document.getElementById('ROWS').disabled = true;
  document.getElementById('COLUMNS').disabled = true;
  document.getElementById('ORIENTATION').disabled = true;
  for (i = 1; i <= num_disks; i++) {
    index = "TRAY_NUM"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - is not created in HTML for USB
      document.getElementById(index).disabled = true;
    }
    index = "PURCHASE_DATE"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - is not created in HTML for USB
      document.getElementById(index).disabled = true;
    }
  }
}
  
function DefineColumnsDropDownList() {
  var rows = document.getElementById("ROWS").value;
  var columnsE = document.getElementById("COLUMNS");
  columnsE.options.length = 0;
  columnsE.options[0] = new Option("", "", false, false);
  columnsE.options[0].disabled = true;
  var columns_max = <?php echo $max_trays ?>/rows;
  var columns = <?php echo $serverlayout_cfg['COLUMNS']; ?>;

  for (i = 1; i <= columns_max; i++) {
    if (i == columns) {
      columnsE.options[i]=new Option(i, i, false, true);
    } else {
      columnsE.options[i]=new Option(i, i, false, false);
    }
  }
}

function TrayOptionsStartup() {
  var num_disks = <?php echo $num_disks ?>;
  var num_trays = <?php echo $num_trays ?>;
  // Create initial_trays array for all disks found
  var initial_trays = [<?for ($i = 1; $i <= $num_disks; $i++) {
                           if ($i == $num_disks) {
                             echo "\""; echo $serverlayout_cfg[$serverlayout_auto[$i]['SN']]['TRAY_NUM']; echo "\"";
                           } else {
                             echo "\""; echo $serverlayout_cfg[$serverlayout_auto[$i]['SN']]['TRAY_NUM']; echo "\", ";
                           }
                        } ?>];

  for (i = 1; i <= num_disks; i++) {
    var index = "TRAY_NUM"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - is not created in HTML for USB
      element.options.length = 0;
      element.options[0] = new Option("Unassigned", "", false, true);  // Add EMPTY option and define as selected
      var count = 1;  // Start from 2nd option (1st option is EMPTY)
      for (j = 1; j <= num_trays; j++) {  // Scan all options
        if (parseInt(j) == parseInt(initial_trays[i-1])) {  // If option is the saved option
          element.options[count] = new Option(j, j, false, true);  // Create new option and define as selected
          element.options[0].selected = false;  // Remove selected from EMPTY option
          count++;
        } else {
          if (initial_trays.indexOf(j.toString()) == -1) {
            element.options[count] = new Option(j, j, false, false);  // Option does not exists in this and other TRAY_NUMs. Create new option and define as not selected
            count++;
          }
        }
      }
    }
  }
}

function UpdateTrayOptions(current_tray_num, element) {
  var num_disks = <?php echo $num_disks ?>;
  var num_trays = <?php echo $num_trays ?>;
  var value = element.value;
  var oldvalue = element.oldvalue;
  
  for (i = 1; i <= num_disks; i++) {
    var index = "TRAY_NUM"+i;
    var tray_num = document.getElementById(index);

    if (tray_num != null) {  // If no Element exists (USB) then nothing to do
      
      if (i != parseInt(current_tray_num)) {  // Only manipulate other TRAY_NUMs
        // Start - Remove option from all other TRAY_NUMs
        if (value != "") {  // If new value is not "Unassigned" then remove it from other TRAYS
          var num_options = tray_num.options.length;
          for (j = 0; j < num_options; j++) {  // Go over all options
            if (tray_num.options[j].value == value) {  // Find option with same value
              tray_num.options.remove(j);  // Remove option
              break;  // Need to break because option found and length is now 1 option less
            }
          }
        }
        // Start - Add previous option back to all other TRAY_NUMs
        if (oldvalue != "") {
          var num_options = tray_num.options.length;  // Get number of options again because changed - option might have been removed
          var not_found = true;
          for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY
            if (parseInt(oldvalue) <= parseInt(tray_num.options[j].value)) {
              not_found = false;
              var new_option = document.createElement("option");
              new_option.value = oldvalue;
              new_option.text = oldvalue;
              tray_num.options.add(new_option, j);
              break;  // Found and added option in correct (sorted) place
            }
          }
          if (not_found) {
            var new_option = document.createElement("option");
            new_option.value = oldvalue;
            new_option.text = oldvalue;
            tray_num.options.add(new_option, num_options);
          }
        }
      }
    }
  }

  // Create device_list array for all disks found
  var device_list = [<?for ($i = 1; $i <= $num_disks; $i++) {
                           if ($i == $num_disks) {
                             echo "\""; echo $serverlayout_auto[$i]['DEVICE']; echo "\"";
                           } else {
                             echo "\""; echo $serverlayout_auto[$i]['DEVICE']; echo "\", ";
                           }
                        } ?>];
  
//  alert("Moving device"+device_list[current_tray_num-1]+" from "+oldvalue+" to "+value);
  if (value != "") {
    document.getElementById("TRAY_TEXT"+value).innerHTML = value+" - "+device_list[current_tray_num-1]; // Change DIV HTML content for new tray if it is assigned
  }
  if (oldvalue != "") {
    document.getElementById("TRAY_TEXT"+oldvalue).innerHTML = oldvalue+" - "; // Change DIV HTML content for previous tray if it was assigned
  }
}

function EditLayoutCheckbox(myCheckbox) {
  var rows = document.getElementById("ROWS");
  var columns = document.getElementById("COLUMNS");
  var orientation = document.getElementById("ORIENTATION");
  if (myCheckbox.checked) {
    rows.disabled = false;
    columns.disabled = false;
    orientation.disabled = false;
  } else {
    rows.disabled = true;
    columns.disabled = true;
    orientation.disabled = true;
  }    
}

function EditTableCheckbox(myCheckbox) {
  var num_disks = <?php echo $num_disks ?>;

  for (i = 1; i <= num_disks; i++) {
    var index = "TRAY_NUM"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - Not created in HTML for USB
      if (myCheckbox.checked) {
        element.disabled = false;
      } else {
        element.disabled = true;
      }
    }
    var index = "PURCHASE_DATE"+i;
    var element = document.getElementById(index);
    if (element != null) {  // Check if element exists - Not created in HTML for USB
      if (myCheckbox.checked) {
        element.disabled = false;
      } else {
        element.disabled = true;
      }
    }
  }
}

function UpdateDIVSizes() {
  var orientation = <?php echo $orientation; ?>;
  var element = document.getElementsByClassName("container_preview");
  for (i = 0; i < element.length; i++) {
    if (orientation == 0) {
      element[i].style.height = <?php echo ($height_preview * $rows); ?>;
    } else {
      element[i].style.width = <?php echo ($height_preview * $columns); ?>;
    }
  }
}

</script>
</HEAD>

<BODY onload="StartUp()">

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

  <?php if ($layout_orientation == 0) { $level_1_div_width = 100; } else { $level_1_div_width = 24; } ?>
  <div style="width: <?php echo $level_1_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden">
    <?php if ($layout_orientation == 0) { $level_2_div_width = 49; } else { $level_2_div_width = 100; } ?>
    <div style="width: <?php echo $level_2_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden">
      <div id="title">
        <span class="left">Controls</span>
      </div>
      <?php if ($layout_orientation == 0) { $level_3_div_width = 33; } else { $level_3_div_width = 100; } ?>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <input type="submit" name="apply" value="Save & Apply"></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <input type="submit" name="scan" value="Scan Hardware"></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <button type="button" onClick="done();">Exit ServerLayout</button></div>

      <div id="title">
        <span class="left">Layout Settings - Enable editing <input type="checkbox" name="EDIT_LAYOUT" id="EDIT_LAYOUT" onchange="EditLayoutCheckbox(this)"></span>
      </div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        Rows: <select name="ROWS" id="ROWS" size="1" onchange="DefineColumnsDropDownList()">
                  <option value="" disabled></option>
                  <?php for ($k = 1; $k <= $max_trays; $k++) { ?>
                  <option value="<? echo $k ?>"<? if ($serverlayout_cfg['ROWS'] == $k) { ?> selected<? } ?>><? echo $k ?></option>
                  <?php } ?>
                </select></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        Columns: <select name="COLUMNS" id="COLUMNS" size="1">
                   </select></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        Drive Trays Orientation:
          <select name="ORIENTATION" id="ORIENTATION" size="1" >
            <option value="0"<? if ($orientation == "0") { ?> selected<? } ?>>Horizontal</option>
            <option value="90"<? if ($orientation == "90") { ?> selected<? } ?>>Vertical</option>
          </select></div>
    <?php if ($layout_orientation == 0) { ?>
    </div>

    <div style="width: 49%; float:right; border: 0px solid black; overflow: hidden">
    <?php } ?>
      <div id="title">
        <span class="left">Preview Server Layout</span>
      </div>
      <div class="container_preview">
      <?php for ($i = 1; $i <= $rows; $i++) { ?>
        <div class="row_container_preview">
        <?php for ($j = 1; $j <= $columns; $j++) {
            $x_translate = $orientation/90*(-$width_preview/2 + $height_preview/2 - ($j-1)*($width_preview-$height_preview));
            $y_translate = $orientation/90*(-$width_preview/2 + $height_preview/2); ?>
          <div class="cell_container_preview" <?php if ($orientation == 90) { echo "style=\"transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);\""; } ?>>
            <div class="cell_background_preview">
              <?php $tray_num = (($i-1) * $columns) + $j; ?>
              <div id="TRAY_TEXT<?php echo $tray_num; ?>" class="cell_text_preview">
              <?php echo $tray_num." - ";
                    for ($k = 1; $k <= $num_disks; $k++) {
                      if ($serverlayout_cfg[$serverlayout_auto[$k]['SN']]['TRAY_NUM'] == $tray_num) {
                        echo $serverlayout_auto[$k]['DEVICE'];
                      }
                    } ?>
              </div>
            </div>
          </div>
        <?php } ?>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>

  <?php if ($layout_orientation == 0) { ?>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>

  <div style="width: 100%; float:left; border: 0px solid black;">
  <?php } else { ?>
  <div style="width: 74%; float:right; border: 0px solid black;">
  <?php } ?>
    <div id="title">
      <span class="left">Device List and Data Entry - Enable editing <input type="checkbox" name="EDIT_TABLE" id="EDIT_TABLE" onchange="EditTableCheckbox(this)"></span>
    </div>
    <div>
      <table>
        <tr>
          <td></td>
          <?php for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) { ?>
          <td align="center"><input type="checkbox" name="SHOW<?php echo $i ?>" id="SHOW<?php echo $i ?>" value="SHOW"></td>
          <?php } ?>
          <td></td>
        </tr>
        <tr>
          <th style="white-space:nowrap">Type</th>
          <th style="white-space:nowrap">Device</th> 
          <th style="white-space:nowrap">Family</th> 
          <th style="white-space:nowrap">Model</th> 
          <th style="white-space:nowrap">Serial Number</th> 
          <th style="white-space:nowrap">Firmware</th> 
          <th style="white-space:nowrap">Capacity</th> 
          <th style="white-space:nowrap">Purchase Date</th> 
          <th style="white-space:nowrap">Tray #</th>
        </tr>
        <?php if ($num_disks > 0) { ?>
          <?php for ($i = 1; $i <= $num_disks; $i++) { ?>
        <tr>
          <td style="white-space:nowrap"><?php switch ($serverlayout_auto[$i]['TYPE']) {
                      case "SATA": ?> <img src=<?php echo $sata_imgfile; ?> style="width:auto;height:20px"> <? break;
                      case "USB": ?> <img src=<?php echo $usb_imgfile; ?> style="width:auto;height:20px"> <? break;
                      case "CD/DVD": ?> <img src=<?php echo $optical_imgfile; ?> style="width:auto;height:20px"> <? break;
                      default: echo $serverlayout_auto[$i]['TYPE']; break; } ?>
          </td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['DEVICE']; ?></td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['FAMILY']; ?></td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['MODEL']; ?></td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['SN']; ?></td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['FIRMWARE']; ?></td>
          <td style="white-space:nowrap"><?php echo $serverlayout_auto[$i]['CAPACITY']; ?></td>
          <td style="white-space:nowrap"><input type="text" name="PURCHASE_DATE<?php echo $i ?>" id="PURCHASE_DATE<?php echo $i ?>" style="width: 6em;" maxlength="10" value="<?=$serverlayout_cfg[$serverlayout_auto[$i]['SN']]['PURCHASE_DATE'];?>"></td>
          <td style="white-space:nowrap">
            <?php if ($serverlayout_auto[$i]['TYPE'] != "USB") { ?>
            <select name="TRAY_NUM<?php echo $i ?>" id="TRAY_NUM<?php echo $i ?>" size="1" onfocus="this.oldvalue = this.value;" onchange="UpdateTrayOptions(<?php echo $i ?>, this); this.oldvalue = this.value;">
            </select>
            <?php } else { ?>
            <?php echo $serverlayout_cfg[$serverlayout_auto[$i]['SN']]['TRAY_NUM']; ?>
            <?php } ?>
          </td>
        </tr>
          <?php } ?>
        <?php } else { ?>
        <tr>
          <td style="white-space:nowrap" align="center" colspan="<? echo $num_data_col; ?>">No disks found - Scan Hardware</td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</form>

</BODY>
</HTML>
