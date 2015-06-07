<?php
require_once('serverlayout_constants.php');

$rows = $myJSONconfig["LAYOUT"]["ROWS"];
$columns = $myJSONconfig["LAYOUT"]["COLUMNS"];
$orientation = $myJSONconfig["LAYOUT"]["ORIENTATION"];
$num_trays = $columns * $rows;

// Variables and Calculations for layout styles

$width_preview = $width/$factor;
$height_preview = $height/$factor;
$border_radius_preview = $border_radius/$factor;
$background_padding_preview = $background_padding/$factor;

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

if ($layout_orientation == 0) { $level_1_div_width = 100; } else { $level_1_div_width = 24; }  // Width of preview table
if ($layout_orientation == 0) { $level_2_div_width = 49; } else { $level_2_div_width = 100; }  // Width of preview table
if ($layout_orientation == 0) { $data_div_width = 100; } else { $data_div_width = 74; }        // Width of data tables
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

.cell_background_preview, .cell_background_preview_hide {
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

.cell_background_preview_hide {
 opacity: 0.2;
}

.cell_text_preview {
  text-align: center;
  position: relative;           /* Vertical Center */
  top: 50%;                     /* Vertical Center */
  -webkit-transform: translateY(-50%);  /* Vertical Center */
      -ms-transform: translateY(-50%);  /* Vertical Center */
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
table.disk_data thead tr:first-child td{padding-left:5px; padding-right:5px; text-align:center;font-size:13px;background:-webkit-radial-gradient(#E0E0E0,#C0C0C0);background:linear-gradient(#E0E0E0,#C0C0C0);border-right:1px solid #F0F0F0;}
table.disk_data tbody td {padding:4px; padding-left:5px; padding-right:5px;}
table.disk_data tbody tr:nth-child(even) {background-color:#F8F8F8;}
table.disk_data tbody tr:hover {background-color:#FDFD96;}

</style>

<script type="text/javascript">
function validateForm() {
  var elements = document.getElementsByClassName("MANUAL_DATA");
  for (i = 0; i < elements.length; i++) {
    elements[i].disabled = false;
  }
}

function InitDisabledFields() {
  var elements = document.getElementsByClassName("MANUAL_DATA");
  for (i = 0; i < elements.length; i++) {
    elements[i].disabled = true;
  }
}

function TrayOptionsStartup() {
  var num_trays = <?php echo $num_trays ?>;
  // Create initial_trays array for all disks found
  var initial_trays = [<?php $first = true;
                             foreach ($myJSONconfig["DISK_DATA"] as $disk) {
                               if (($disk["STATUS"] == "INSTALLED") and ($disk["TYPE"] != USB)) {
                                 if ($first) {
                                   $first = false;
                                   echo "\"".$disk["TRAY_NUM"]."\"";
                                 } else {
                                   echo ", \"".$disk["TRAY_NUM"]."\"";
                                 }
                               }
                             } ?>];

  var tray_shows = [<?php $first = true;
                          foreach ($myJSONconfig["TRAY_SHOW"] as $tray_show) {
                            if ($first) {
                              $first = false;
                              echo "\"".$tray_show."\"";
                            } else {
                              echo ", \"".$tray_show."\"";
                            }
                          } ?>];

  var elements = document.getElementsByClassName("TRAY_NUM_CLASS");

  for (i = 0; i < elements.length; i++) {
    elements[i].options.length = 0;
    elements[i].options[0] = new Option("Unassigned", "", false, true);  // Add EMPTY option and define as selected
    var count = 1;  // Start from 2nd option (1st option is EMPTY)
    for (j = 1; j <= num_trays; j++) {  // Scan all options except EMPTY option (j = 0)
      if (tray_shows[j-1] == "YES") {
        if (parseInt(j) == parseInt(initial_trays[i])) {  // If option is the saved option
          elements[i].options[count] = new Option(j, j, false, true);  // Create new option and define as selected
          elements[i].options[0].selected = false;  // Remove selected from EMPTY option
          count++;
        } else {
          if (initial_trays.indexOf(j.toString()) == -1) {
            elements[i].options[count] = new Option(j, j, false, false);  // Option does not exists in this and other TRAY_NUMs. Create new option and define as not selected
            count++;
          }
        }
      }
    }
    if (elements[i].options[elements[i].selectedIndex].text == "Unassigned") {
      elements[i].style.backgroundColor = "#ffb2ae";
    }
  }
}
  
function UpdateTrayOptions(device, this_element) {
  var value = this_element.value;
  var oldvalue = this_element.oldvalue;

  if (value == "") {
     this_element.style.backgroundColor = "#ffb2ae";  // Red
  }
  else {
    this_element.style.backgroundColor = "#aeffb2";  // Green
  }

  var elements = document.getElementsByClassName("TRAY_NUM_CLASS");
  for (i = 0; i < elements.length; i++) {
    if (elements[i].getAttribute("id") != this_element.getAttribute("id")) {  // Manipulate all trays that are not the one that has changed
      // Start - Remove option from all other TRAY_NUMs
      if (value != "") {  // If new value is not "Unassigned" then remove it from other TRAYS
        var num_options = elements[i].options.length;
        for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY option (j = 0)
          if (elements[i].options[j].value == value) {  // Find option with same value
            elements[i].options.remove(j);  // Remove option
            break;  // Need to break because option found and length is now 1 option less
          }
        }
      }
      // Start - Add previous option back to all other TRAY_NUMs
      if (oldvalue != "") {
        var num_options = elements[i].options.length;  // Get number of options again because changed - option might have been removed
        var not_found = true;
        for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY option (j = 0)
          if (parseInt(oldvalue) <= parseInt(elements[i].options[j].value)) {
            not_found = false;
            var new_option = document.createElement("option");
            new_option.value = oldvalue;
            new_option.text = oldvalue;
            elements[i].options.add(new_option, j);
            break;  // Found and added option in correct (sorted) place
          }
        }
        if (not_found) {
          var new_option = document.createElement("option");
          new_option.value = oldvalue;
          new_option.text = oldvalue;
          elements[i].options.add(new_option, num_options);
        }
      }
    }
  }

  if (value != "") {
    document.getElementById("TRAY_TEXT"+value).innerHTML = value+" - "+device; // Change DIV HTML content for new tray if it is assigned
    document.getElementById("TRAY_TEXT"+value).style.color = "#aeffb2";
  }
  if (oldvalue != "") {
    document.getElementById("TRAY_TEXT"+oldvalue).innerHTML = oldvalue; // Change DIV HTML content for previous tray if it was assigned
    document.getElementById("TRAY_TEXT"+oldvalue).style.color = "#aeffb2";
  }
}

function EditCheckboxData(myCheckbox) {
  var elements = document.getElementsByClassName("MANUAL_DATA");
  for (i = 0; i < elements.length; i++) {
    if (myCheckbox.checked) {
      elements[i].disabled = false;
    } else {
      elements[i].disabled = true;
    }
  }
}

function TrayShowUpdate(element, tray_num) {
  if (element.className == "cell_background_preview_hide") {
  // If element class is "cell_background_preview_hide" then change the class type to "cell_background_preview"
  // and add tray_num option to all TRAY_NUM_CLASS elements !!! sorted !!!
    element.className = "cell_background_preview";
    var tray_showE = document.getElementsByName("TRAY_SHOW_"+tray_num)[0];
    tray_showE.value = "YES";
    var elements = document.getElementsByClassName("TRAY_NUM_CLASS");
    for (i = 0; i < elements.length; i++) {
      var num_options = elements[i].options.length;
      var not_found = true;
      for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY option (j = 0)
        if (tray_num < elements[i].options[j].value) {
          not_found = false;
          var new_option = document.createElement("option");
          new_option.value = tray_num;
          new_option.text = tray_num;
          elements[i].options.add(new_option, j);
          break;  // Found and added option in correct (sorted) place
        }
      }
      if (not_found) {
        var new_option = document.createElement("option");
        new_option.value = tray_num;
        new_option.text = tray_num;
        elements[i].options.add(new_option, num_options);
      }
    }
  } else if (element.className == "cell_background_preview") {
  // If element class is "cell_background_preview" then change the class type to "cell_background_preview_hide"
  // and remove tray_num option from all TRAY_NUM_CLASS elements, if it is a "selected" option then change and set unassigned (0) as the new "selected" option
      element.className = "cell_background_preview_hide";
      var tray_showE = document.getElementsByName("TRAY_SHOW_"+tray_num)[0];
      tray_showE.value = "NO";
      var elements = document.getElementsByClassName("TRAY_NUM_CLASS");
      for (i = 0; i < elements.length; i++) {
        var num_options = elements[i].options.length;
        for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY option (j = 0)
//          alert(elements[i].getAttribute("id")+" "+elements[i].options.length+" "+tray_num+" "+elements[i].options[j].value+" "+elements[i].options[j].selected);
          if (elements[i].options[j].value > tray_num) {  // Because options are sorted then skip larger options than tray_num
            break;
          }
          else if (elements[i].options[j].value == tray_num) {  // Find option with same value
            if (elements[i].options[j].selected == true) {  // If tray number that is going to be hidden is selected then change selection to unassigned.
              elements[i].options[0].selected = true;
              elements[i].style.backgroundColor = "#ffb2ae";
              document.getElementById("TRAY_TEXT"+tray_num).innerHTML = tray_num; // Remove device name from preview text
            }
            elements[i].options.remove(j);  // Remove option
            break;  // Need to break because option found and length is now 1 option less
          }
        }
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

function StartUp() {
  TrayOptionsStartup();
  InitDisabledFields();
  UpdateDIVSizes();
}

</script>
</HEAD>

<BODY>

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="/plugins/serverlayout/php/serverlayout_submit.php" target="progressFrame">

  <div style="width: <?php echo $level_1_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
    <div style="width: <?php echo $level_2_div_width; ?>%; float:left; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Commands</span>
      </div>
      <div style="text-align:center;"><input type="submit" name="update_smartmontools_database" value="Update Smartmontools Database"></div>
      <div style="text-align:center;"><input type="submit" name="data" value="Save Data"></div>
      <div style="text-align:center;"><button type="button" onClick="done();">Exit ServerLayout</button></div>
    </div>

    <div style="width: <?php echo $level_2_div_width; ?>%; float:right; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Preview Server Layout</span>
      </div>
      <div class="container_preview" id="container_preview">
      <?php for ($i = 1; $i <= $rows; $i++) { ?>
        <div class="row_container_preview">
        <?php for ($j = 1; $j <= $columns; $j++) {
            $x_translate = $orientation/90*(-$width_preview/2 + $height_preview/2 - ($j-1)*($width_preview-$height_preview));
            $y_translate = $orientation/90*(-$width_preview/2 + $height_preview/2);
            $tray_num = (($i-1) * $columns) + $j; ?>
          <div class="cell_container_preview"
               <?php if ($orientation == 90) { echo "style=\"transform: -webkit-transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);
                                                                        -ms-transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);
                                                                        transform: rotate(-90deg) translate(".$y_translate."px, ".$x_translate."px);\""; } ?>>
            <?php if ($myJSONconfig["TRAY_SHOW"][$tray_num] == "YES") { ?>
            <div class="cell_background_preview" onclick="TrayShowUpdate(this, <?php echo $tray_num; ?>)">
              <input type="hidden" name="TRAY_SHOW_<?php echo $tray_num; ?>" value="<?php echo $myJSONconfig["TRAY_SHOW"][$tray_num]; ?>">
              <div id="TRAY_TEXT<?php echo $tray_num; ?>" class="cell_text_preview">
              <?php echo "<span>".$tray_num."</span>";
                    foreach ($myJSONconfig["DISK_DATA"] as $disk) {
                      if (($disk["STATUS"]=="INSTALLED") and ($disk["TRAY_NUM"] == $tray_num)) {
                        echo "<span> ".$disk["DEVICE"]."</span>";
                      }
                    } ?>
              </div>
            </div>
            <?php } else if ($myJSONconfig["TRAY_SHOW"][$tray_num] == "NO") { ?>
            <div class="cell_background_preview_hide" onclick="TrayShowUpdate(this, <?php echo $tray_num; ?>)">
              <input type="hidden" name="TRAY_SHOW_<?php echo $tray_num; ?>" value="<?php echo $myJSONconfig["TRAY_SHOW"][$tray_num]; ?>">
            </div>
            <?php } ?>
          </div>
        <?php } ?>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>

  <div style="width: <?php echo $data_div_width; ?>%; float:right; border: 0px solid black; overflow: auto;">
    <div id="title">
      <span class="left">Installed Devices and Data Entry - Enable editing <input type="checkbox" name="EDIT_TABLE" id="EDIT_TABLE" onchange="EditCheckboxData(this)"></span>
    </div>
    <div>
      <table class="disk_data">
        <thead>
        <tr>
          <?php $no_show_column_i = true;
                foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                  if ($data_column["SHOW_COLUMN_I"] == "YES") {
                    $no_show_column_i = false;
                    echo "<td>".$data_column["TITLE"]."</td>";
                  }
                }
                if ($no_show_column_i) {
                  echo "<tr>";
                  echo "<td style=\"text-align:center;\" colspan=\"".count($myJSONconfig["DATA_COLUMNS"])."\">No data columns selected in Settings tab</td>";
                  echo "</tr>";
                } ?>
        </tr>
        <tbody>
        <?php $no_installed_disk = true;
              foreach ($myJSONconfig["DISK_DATA"] as $disk) {
                if ($disk["STATUS"] == "INSTALLED") {
                  $no_installed_disk = false;
                  echo "<tr>";
                  foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                    if ($data_column["SHOW_COLUMN_I"] == "YES") {
                      echo "<td style=\"text-align:".$data_column["TEXT_ALIGN"].";\">";
                      switch($data_column["NAME"]) {
                        case "TRAY_NUM"            : if ($disk["TYPE"] != "USB") { ?>
                                                     <select class="MANUAL_DATA TRAY_NUM_CLASS" name="TRAY_NUMS[]" id="TRAY_NUM_<?php echo $disk["SN"]; ?>" size="1" onfocus="this.oldvalue = this.value;" onchange="UpdateTrayOptions('<?php echo $disk["DEVICE"]; ?>', this); this.oldvalue = this.value;">
                                                     </select>
                                                     <input type="hidden" name="TRAY_NUMS_SN[]" value="<?php echo $disk["SN"]; ?>">
                                                     <?php }
                                                     break;
                        case "TYPE"                : switch ($disk["TYPE"]) {
                                                       case "SATA": echo "<img src=\"".$sata_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       case "USB": echo "<img src=\"".$usb_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       case "CD/DVD": echo "<img src=\"".$optical_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       default: echo $disk["TYPE"];
                                                     }
                                                     break;
                        case "FIRST_INSTALL_DATE"  : ?> <input class="MANUAL_DATA" type="text" name="FIRST_INSTALL_DATES[]" style="width: 6em;" maxlength="10" value="<?php echo $disk["FIRST_INSTALL_DATE"]; ?>">
                                                        <input type="hidden" name="FIRST_INSTALL_DATES_SN[]" value="<?php echo $disk["SN"]; ?>">
                                                     <?php break;
                        case "PURCHASE_DATE"       : ?> <input class="MANUAL_DATA" type="text" name="PURCHASE_DATES[]" style="width: 6em;" maxlength="10" value="<?php echo $disk["PURCHASE_DATE"]; ?>">
                                                        <input type="hidden" name="PURCHASE_DATES_SN[]" value="<?php echo $disk["SN"]; ?>">
                                                     <?php break;
                        case "NOTES"               : ?> <input class="MANUAL_DATA" type="text" name="NOTESS[]" style="width: 15em;" maxlength="255" value="<?php echo $disk["NOTES"]; ?>">
                                                        <input type="hidden" name="NOTESS_SN[]" value="<?php echo $disk["SN"]; ?>">
                                                     <?php break;
                        default                    : echo $disk[$data_column["NAME"]];
                      }
                      echo "</td>";
                    }
                  }
                  echo "</tr>";
                }
              }
              if ($no_installed_disk) {
                echo "<tr>";
                echo "<td style=\"text-align:center;\" colspan=\"".count($myJSONconfig["DATA_COLUMNS"])."\">No installed disks found</td>";
                echo "</tr>";
              } ?>
      </table>
    </div>

    <div id="title">
      <span class="left">Historical Devices</span>
    </div>
    <div>
      <table class="disk_data">
        <thead>
        <tr>
          <?php $no_show_column_h = true;
                foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                  if ($data_column["SHOW_COLUMN_H"] == "YES") {
                    $no_show_column_h = false;
                    echo "<td>".$data_column["TITLE"]."</td>";
                  }
                }
                if ($no_show_column_h) {
                  echo "<tr>";
                  echo "<td style=\"text-align:center;\" colspan=\"".count($myJSONconfig["DATA_COLUMNS"])."\">No data columns selected in Settings tab</td>";
                  echo "</tr>";
                } ?>
        </tr>
        <tbody>
        <?php $no_historical_disk = true;
              foreach ($myJSONconfig["DISK_DATA"] as $disk) { 
                if ($disk["STATUS"] == "HISTORICAL") {
                  $no_historical_disk = false;
                  echo "<tr>";
                  foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                    if ($data_column["SHOW_COLUMN_H"] == "YES") {
                      echo "<td style=\"text-align:".$data_column["TEXT_ALIGN"].";\">";
                      switch($data_column["NAME"]) {
                        case "TYPE"                : switch ($disk["TYPE"]) {
                                                       case "SATA": echo "<img src=\"".$sata_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       case "USB": echo "<img src=\"".$usb_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       case "CD/DVD": echo "<img src=\"".$optical_imgfile."\" style=\"width:auto;height:20px\">"; break;
                                                       default: echo $disk["TYPE"];
                                                     }
                                                     break;
                        default                    : echo $disk[$data_column["NAME"]];
                      }
                      echo "</td>";
                    }
                  }
                  echo "</tr>";
                }
              }
              if ($no_historical_disk) {
                echo "<tr>";
                echo "<td style=\"text-align:center;\" colspan=\"".count($myJSONconfig["DATA_COLUMNS"])."\">No historical disks found</td>";
                echo "</tr>";
              } ?>
      </table>
    </div>
  </div>

</form>

<script>StartUp();</script>

</BODY>
</HTML>
