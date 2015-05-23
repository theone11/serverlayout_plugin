<?php
$myJSONconfig = Get_JSON_Config_File();  // Get or create JSON configuration file
$myJSONconfig = Scan_Installed_Devices_Data($myJSONconfig);  // Scan all installed devices
file_put_contents($serverlayout_cfg_file, json_encode($myJSONconfig));  // Save configuration data to JSON configuration file

$rows = $myJSONconfig["LAYOUT"]['ROWS'];
$columns = $myJSONconfig["LAYOUT"]['COLUMNS'];
$orientation = $myJSONconfig["LAYOUT"]['ORIENTATION'];
$num_trays = $columns * $rows;

$num_disks = 0;
foreach ($myJSONconfig as $disk) {
  if ($disk["STATUS"] == "INSTALLED") {
    $num_disks = $num_disks + 1;
  }
}

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
  margin-left: auto;
  margin-right: auto;
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
  font-weight: bold;
}

.cell_text_preview span:nth-child(even) {
  color: black;
}

.cell_text_preview span:nth-child(odd) {
  color: white;
}

table.disk_data {overflow: auto;}
table.disk_data td {width:auto; white-space:nowrap;}
table.disk_data thead tr:first-child td{text-align:center;font-size:13px;background:-webkit-radial-gradient(#E0E0E0,#C0C0C0);background:linear-gradient(#E0E0E0,#C0C0C0);border-right:1px solid #F0F0F0;}
table.disk_data tbody td {padding-left:5px; padding-right:5px;}
table.disk_data tbody td:nth-child(-n+2) {text-align:center;}
table.disk_data tbody td:nth-child(n+3):nth-child(-n+5) {text-align:left;}
table.disk_data tbody td:nth-child(n+6):nth-child(-n+7) {text-align:right;}
table.disk_data tbody td:nth-child(n+8):nth-child(-n+8) {text-align:center;}
table.disk_data tbody td:nth-child(n+9):nth-child(-n+9) {text-align:right;}
table.disk_data tbody tr:nth-child(even) {background-color:#F8F8F8;}
table.disk_data tbody tr:hover {background-color:#FDFD96;}

</style>

<script type="text/javascript">
function validateForm() {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for each (element in elements) {
    element.disabled = false;
  }

  var elements = document.getElementsByClassName("MANUAL_DATA");
  for each (element in elements) {
    element.disabled = false;
  }
}

function InitDisabledFields() {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for each (element in elements) {
    element.disabled = true;
  }

  var elements = document.getElementsByClassName("MANUAL_DATA");
  for each (element in elements) {
    element.disabled = true;
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
  var initial_shows = {<?$first = true;
                         foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                           if ($first) {
                             $first = false;
                             echo "SHOW_".$data_column["NAME"].":\"".$data_column["SHOW"]."\"";
                           } else {
                             echo ", SHOW_".$data_column["NAME"].":\"".$data_column["SHOW"]."\"";
                           }
                         } ?>];

  var elements = document.getElementsByClassName("CHECK_SHOW_DATA");
  for each (element in elements) {
    if (initial_shows[element.name] == "YES") {
      element.checked = true;
    } else {
      element.checked = false;
    }
  }
}

function InitDisabledFields() {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for each (element in elements) {
    element.disabled = true;
  }

  var elements = document.getElementsByClassName("MANUAL_DATA");
  for each (element in elements) {
    element.disabled = true;
  }
}
  
function DefineColumnsDropDownList() {
  var rows = document.getElementById("ROWS").value;
  var columnsE = document.getElementById("COLUMNS");
  columnsE.options.length = 0;
  columnsE.options[0] = new Option("", "", false, false);
  columnsE.options[0].disabled = true;
  var columns_max = <?php echo $max_trays ?>/rows;
  var columns = <?php echo $columns; ?>;

  for (i = 1; i <= columns_max; i++) {
    if (i == columns) {
      columnsE.options[i]=new Option(i, i, false, true);
    } else {
      columnsE.options[i]=new Option(i, i, false, false);
    }
  }
}

function TrayOptionsStartup() {
  var num_trays = <?php echo $num_trays ?>;
  // Create initial_trays array for all disks found
  var initial_trays = [<?$first = true;
                         foreach ($myJSONconfig["DISK_DATA"] as $disk) {
                           if (($disk["STATUS"] == "INSTALLED") and ($disk["TYPE"] != USB)) {
                             if ($first) {
                               $first = false;
                               echo "\"".$disk["TRAY_NUM"]."\"";
                             } else {
                               echo ", \"".disk["TRAY_NUM"]."\"";
                             }
                           }
                         } ?>];

  var elements = document.getElementsByClassName("TRAY_NUM_CLASS");

  for each (element in elements) {
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
    if (element.options[element.selectedIndex].text == "Unassigned") {
      element.style.backgroundColor = "#ffb2ae";
    }
  }
}
  
function UpdateTrayOptions(current_tray_name, device, this_element) {
  var value = this_element.value;
  var oldvalue = this_element.oldvalue;

  if (value == "") {
     this_element.style.backgroundColor = "#ffb2ae";  // Red
  }
  else {
    this_element.style.backgroundColor = "#aeffb2";  // Green
  }

  var elements = document.getElementsByClassName("TRAY_NUM_CLASS");
  
  for each (element in elements) {
    if (element.name != this_element.name) {
      // Start - Remove option from all other TRAY_NUMs
      if (value != "") {  // If new value is not "Unassigned" then remove it from other TRAYS
        var num_options = element.options.length;
        for (j = 0; j < num_options; j++) {  // Go over all options
          if (element.options[j].value == value) {  // Find option with same value
            element.options.remove(j);  // Remove option
            break;  // Need to break because option found and length is now 1 option less
          }
        }
      }
      // Start - Add previous option back to all other TRAY_NUMs
      if (oldvalue != "") {
        var num_options = element.options.length;  // Get number of options again because changed - option might have been removed
        var not_found = true;
        for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY
          if (parseInt(oldvalue) <= parseInt(element.options[j].value)) {
            not_found = false;
            var new_option = document.createElement("option");
            new_option.value = oldvalue;
            new_option.text = oldvalue;
            element.options.add(new_option, j);
            break;  // Found and added option in correct (sorted) place
          }
        }
        if (not_found) {
          var new_option = document.createElement("option");
          new_option.value = oldvalue;
          new_option.text = oldvalue;
          element.options.add(new_option, num_options);
        }
      }
    }
  }

//  alert("Moving device"+device_list[current_tray_num-1]+" from "+oldvalue+" to "+value);
  if (value != "") {
    document.getElementById("TRAY_TEXT"+value).innerHTML = value+" - "+device; // Change DIV HTML content for new tray if it is assigned
    document.getElementById("TRAY_TEXT"+value).style.color = "#aeffb2";
  }
  if (oldvalue != "") {
    document.getElementById("TRAY_TEXT"+oldvalue).innerHTML = oldvalue; // Change DIV HTML content for previous tray if it was assigned
    document.getElementById("TRAY_TEXT"+oldvalue).style.color = "#aeffb2";
  }
}

function EditCheckbox(myCheckbox, group) {
  var elements = document.getElementsByClassName(group);
  for each (element in elements) {
    if (myCheckbox.checked) {
      element.disabled = false;
    } else {
      element.disabled = true;
    }
  }
}

function UpdateDIVSizes() {
  var orientation = <?php echo $orientation; ?>;
  var element = document.getElementById("container_preview");
  if (orientation == 0) {
    element.style.height = "<?php echo ($height_preview * $rows); ?>px";
  } else {
    element.style.width = "<?php echo ($height_preview * $columns); ?>px";
  }
}

</script>
</HEAD>

<BODY>

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="/plugins/serverlayout/php/serverlayout_submit.php" target="progressFrame">

  <?php if ($layout_orientation == 0) { $level_1_div_width = 100; } else { $level_1_div_width = 28; } ?>
  <div style="width: <?php echo $level_1_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
    <?php if ($layout_orientation == 0) { $level_2_div_width = 33; } else { $level_2_div_width = 100; } ?>
    <div style="width: <?php echo $level_2_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Controls</span>
      </div>
      <?php if ($layout_orientation == 0) { $level_3_div_width = 100; } else { $level_3_div_width = 100; } ?>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <input type="submit" name="apply" value="Save Data & Apply Configuration"></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <input type="submit" name="scan" value="Scan Hardware"></div>
      <div style="width: <?php echo $level_3_div_width; ?>%; float:left; border: 0px solid black;">
        <button type="button" onClick="done();">Exit ServerLayout</button></div>
    </div>

    <div style="width: <?php echo $level_2_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Layout Settings</span>
      </div>
      <div class="margin-left:auto; margin-right:auto;">
        <table>
          <tr>
            <td>Enable editing:</td>
            <td><input type="checkbox" name="EDIT_LAYOUT" id="EDIT_LAYOUT" onchange="EditCheckbox(this, "LAYOUT_DATA")"></td>
          </tr>
          <tr>
            <td>Rows:</td>
            <td><select class="LAYOUT_DATA" name="ROWS" id="ROWS" size="1" onchange="DefineColumnsDropDownList()">
                    <option value="" disabled></option>
                    <?php for ($k = 1; $k <= $max_trays; $k++) { ?>
                    <option value="<? echo $k ?>"<? if ($rows == $k) { ?> selected<? } ?>><? echo $k ?></option>
                    <?php } ?>
                  </select></td>
          </tr>
          <tr>
            <td>Columns:</td>
            <td><select class="LAYOUT_DATA" name="COLUMNS" id="COLUMNS" size="1">
                     </select></td>
          </tr>
          <tr>
            <td>Drive Trays Orientation:</td>
            <td><select class="LAYOUT_DATA" name="ORIENTATION" id="ORIENTATION" size="1" >
              <option value="0"<? if ($orientation == "0") { ?> selected<? } ?>>Horizontal</option>
              <option value="90"<? if ($orientation == "90") { ?> selected<? } ?>>Vertical</option>
           </select></td>
          </tr>
        </table>
      </div>
    </div>

    <div style="width: <?php echo $level_2_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Preview Server Layout</span>
      </div>
      <div class="container_preview" id="container_preview">
      <?php for ($i = 1; $i <= $rows; $i++) { ?>
        <div class="row_container_preview">
        <?php for ($j = 1; $j <= $columns; $j++) {
            $x_translate = $orientation/90*(-$width_preview/2 + $height_preview/2 - ($j-1)*($width_preview-$height_preview));
            $y_translate = $orientation/90*(-$width_preview/2 + $height_preview/2); ?>
          <div class="cell_container_preview" <?php if ($orientation == 90) { echo "style=\"transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);\""; } ?>>
            <div class="cell_background_preview">
              <?php $tray_num = (($i-1) * $columns) + $j; ?>
              <div id="TRAY_TEXT<?php echo $tray_num; ?>" class="cell_text_preview">
              <?php echo "<span>".$tray_num."</span>";
                    foreach (myJSONconfig["DISK_DATA"] as $disk) {
                      if (($disk["STATUS"]=="INSTALLED") and ($disk["TRAY_NUM"] == $tray_num)) {
                        echo "<span> ".$disk["DEVICE"]."</span>";
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

  <div style="width: 100%; float:left; border: 0px solid black;">
  <?php } else { ?>
  <div style="width: 70%; float:right; border: 0px solid black;">
  <?php } ?>
    <div id="title">
      <span class="left">Device List and Data Entry - Enable editing <input type="checkbox" name="EDIT_TABLE" id="EDIT_TABLE" onchange="EditCheckbox(this, "MANUAL_DATA")"></span>
    </div>
    <div>
      <table class="disk_data">
        <thead>
        <tr>
          <?php foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) { ?>
          <td>
          <?php echo $data_column["Title"]; ?>
            <input class="CHECK_SHOW_DATA" type="checkbox" name="SHOW_<?php echo $data_column["NAME"]; ?>" id="SHOW_<?php echo $data_column["NAME"]; ?>" value="YES">
          </td>
          <?php } ?>
        </tr>
        <?php if ($num_disks > 0) {
                foreach ($myJSONconfig["DISK_DATA"] as $disk { 
                  if ($disk["STATUS"] == "INSTALLED") {
                    echo "<tbody><tr>"
                    foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                      echo "<td>";
                      switch($data_column["NAME"]) {
                        case "TRAY_NUM"            : if ($disk["TYPE"] != "USB") { ?>
                                                     <select class="MANUAL_DATA TRAY_NUM_CLASS" name=<?php echo "\"TRAY_NUM_".$disk["SN"]."\""; ?> id=<?php echo "\"TRAY_NUM_".$disk["SN"]."\""; ?> size="1" onfocus="this.oldvalue = this.value;" onchange="UpdateTrayOptions(this.name, <?php echo $disk["DEVICE"]; ?>, this); this.oldvalue = this.value;">
                                                     </select>
                                                     <?php } else {
                                                             echo $disk["TRAY_NUM"];
                                                           };
                                                     break;
                        case "TYPE"                : switch ($disk["TYPE"]) {
                                                       case "SATA": ?> <img src=<?php echo $sata_imgfile; ?> style="width:auto;height:20px"> <? break;
                                                       case "USB": ?> <img src=<?php echo $usb_imgfile; ?> style="width:auto;height:20px"> <? break;
                                                       case "CD/DVD": ?> <img src=<?php echo $optical_imgfile; ?> style="width:auto;height:20px"> <? break;
                                                       default: echo $disk["TYPE"];
                                                     }
                                                     break;
                      
                        case "DEVICE"              : echo $disk["DEVICE"]; break;
                        case "MANUFACTURER"        : echo $disk["MANUFACTURER"]; break;
                        case "MODEL"               : echo $disk["MODEL"]; break;
                        case "SN"                  : echo $disk["SN"]; break;
                        case "FW"                  : echo $disk["FW"]; break;
                        case "CAPACITY"            : echo $disk["CAPACITY"]; break;
                        case "FIRST_INSTALL_DATE"  : echo $disk["FIRST_INSTALL_DATE"]; break;
                        case "RECENT_INSTALL_DATE" : echo $disk["RECENT_INSTALL_DATE"]; break;
                        case "LAST_SEEN_DATE"      : echo $disk["LAST_SEEN_DATE"]; break;
                        case "PURCHASE_DATE"       : echo "<span class=\"MANUAL_DATA\">".$disk["PURCHASE_DATE"]."</span>"; break;
                        default                    :
                      }
                      echo "</td>";
                    }
                    echo "</tr>";
                  }
                }
              } else {
                echo "<tr>";
                echo "<td align="center" colspan=\"".count($myJSONconfig["DATA_COLUMNS"])."\">No disks found - Scan Hardware</td>"
                echo "</tr>";
              } ?>
      </table>
    </div>
  </div>

</form>

<script>StartUp();</script>

</BODY>
</HTML>
