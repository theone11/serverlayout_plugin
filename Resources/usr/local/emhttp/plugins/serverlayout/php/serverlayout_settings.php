<?php
include 'serverlayout_constants.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['apply'])) {
    // Write new configuration data    
    $arguments = "";
    $num_rows_new = $_POST['ROWS'];
    $arguments .= "ROWS=\"".$num_rows_new."\"\n";
    $num_columns_new = $_POST['COLUMNS'];
    $arguments .= "COLUMNS=\"".$num_columns_new."\"\n";
    $orientation_new = $_POST['ORIENTATION'];
    $arguments .= "ORIENTATION=\"".$orientation_new."\"\n";
      
    for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) {  // Write SHOW/HIDE configuration
      $temp = "SHOW".$i;
      echo $_POST[$temp].", ";
      if (isset($_POST[$temp])) {
        echo "isset, ";
      } else {
        echo "not isset, ";
      }
      if (isset($_POST[$temp]) and ($_POST[$temp] == "SHOW")) {
        $arguments .= "SHOW".$i."=\"SHOW\"\n";
      } else {
        $arguments .= "SHOW".$i."=\"HIDE\"\n";
      }
    }

    // Get previous ROWS and COLUMNS settings
    $serverlayout_cfg = parse_ini_file($serverlayout_cfg_file, true);
    $num_rows_old = $serverlayout_cfg['ROWS'];
    $num_columns_old = $serverlayout_cfg['COLUMNS'];

    // Check if ROWS or COLUMNS have changed
    if (($num_rows_new == $num_rows_old) and ($num_columns_new == $num_columns_old)) {
      // ROWS or COLUMNS have NOT changed - Prepare disks' data
      if (file_exists($automatic_data)) {
        $serverlayout_auto = parse_ini_file($automatic_data, true);
        $num_disks = count($serverlayout_auto, COUNT_NORMAL);
      } else {
        $num_disks = 0;
      }
      for ($i = 1; $i <= $num_disks; $i++) {
        $temp = $serverlayout_auto[$i]['SN'];
        $arguments .= "[".$temp."]\n";
        if ($serverlayout_auto[$i]['TYPE'] != "USB") {
          $temp = "TRAY_NUM".$i;
          $temp2 = $_POST[$temp];
          $arguments .= "TRAY_NUM=\"".$temp2."\"\n";
        }
        $temp = "PURCHASE_DATE".$i;
        $temp2 = $_POST[$temp];
        $arguments .= "PURCHASE_DATE=\"".$temp2."\"\n";
      }
    } else {
      // ROWS or COLUMNS HAVE changed - Do NOT prepare disks' data - Do nothing
    }
    file_put_contents($serverlayout_cfg_file, $arguments);

  } elseif (isset($_POST['scan'])) {
      shell_exec($scan_command);
  }

//  }
} else {
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
$num_rows = $serverlayout_cfg['ROWS'];
$num_columns = $serverlayout_cfg['COLUMNS'];
$orientation = $serverlayout_cfg['ORIENTATION'];
$num_trays = $num_columns * $num_rows;

?>

<HTML>

<HEAD>
<style> </style>

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
    
//  alert("TRAY_NUM"+current_tray_num+" changed to "+value+".\nFor all other "+(num_disks-1)+" disks, "+value+" will be removed and "+oldvalue+" will be returned");
  
  for (i = 1; i <= num_disks; i++) {
    var index = "TRAY_NUM"+i;
    var tray_num = document.getElementById(index);

    if (tray_num != null) {  // If no Element exists (USB) then nothing to do
      
      if (i != parseInt(current_tray_num)) {  // Only manipulate other TRAY_NUMs
//        alert("Processing "+index+" - current_tray_num="+current_tray_num+"\nNew value = "+value+", oldvalue = "+oldvalue);
        // Start - Remove option from all other TRAY_NUMs
        if (value != "") {  // If new value is not "Unassigned" then remove it from other TRAYS
          var num_options = tray_num.options.length;
          for (j = 0; j < num_options; j++) {  // Go over all options
            if (tray_num.options[j].value == value) {  // Find option with same value
  // cannot happen because            if (tray_num.value == value) {  // If current selected value is to be removed then change new value to "Unassigned" which is "" 
  // "value" would not have             tray_num.value = "";
  // been available to choose           }
              tray_num.options.remove(j);  // Remove option
//              alert("Removed "+value+" from TRAY_NUM"+i);
              break;  // Need to break because option found and length is now 1 option less
            }
          }
        }
        // Start - Add previous option back to all other TRAY_NUMs
        if (oldvalue != "") {
//          alert("Adding "+parseInt(oldvalue));
          var num_options = tray_num.options.length;  // Get number of options again because changed - option might have been removed
          var not_found = true;
          for (j = 1; j < num_options; j++) {  // Go over all options except EMPTY
            if (parseInt(oldvalue) <= parseInt(tray_num.options[j].value)) {
              not_found = false;
              var new_option = document.createElement("option");
              new_option.value = oldvalue;
              new_option.text = oldvalue;
              tray_num.options.add(new_option, j);
//              alert("Added "+oldvalue+" to TRAY_NUM"+i+" at position "+j);
              break;
            }
          }
          if (not_found) {
            var new_option = document.createElement("option");
            new_option.value = oldvalue;
            new_option.text = oldvalue;
            tray_num.options.add(new_option, num_options);
//            alert("Added "+oldvalue+" to TRAY_NUM"+i+" at position "+j);
          }
        }
      }
    }
  }
//  alert("Completed Processing");
//}

//function UpdatePreviewTable() {
//  var num_disks = <?php echo $num_disks ?>;


  // Create device_list array for all disks found
  var device_list = [<?for ($i = 1; $i <= $num_disks; $i++) {
                           if ($i == $num_disks) {
                             echo "\""; echo $serverlayout_auto[$i]['DEVICE']; echo "\"";
                           } else {
                             echo "\""; echo $serverlayout_auto[$i]['DEVICE']; echo "\", ";
                           }
                        } ?>];
  var myTable = document.getElementById("PREVIEWTABLE");  // Get table element
  if (value != "") {
    var temp = value % <?php echo $num_columns; ?>;
    if (temp == 0) {
      var row = (value / <?php echo $num_columns; ?>) - 1;  // Calculate ROW
      var column = <?php echo $num_columns; ?> - 1;  // Calculate COLUMN
    } else {
      var row = Math.floor(value / <?php echo $num_columns; ?>);  // Calculate ROW
      var column = (value % <?php echo $num_columns; ?>) - 1;  // Calculate COLUMN
    }
//    alert("Updating TRAY_NUM"+current_tray_num+" device_id \""+device_list[current_tray_num-1]+" to "+value+" ("+row+", "+column+")");
    myTable.rows[row].cells[column].innerHTML = value+" - "+device_list[current_tray_num-1];  // Update table cell data
  }

  if (oldvalue != "") {
    var temp = oldvalue % <?php echo $num_columns; ?>;
    if (temp == 0) {
      var oldrow = (oldvalue / <?php echo $num_columns; ?>) - 1;  // Calculate ROW
      var oldcolumn = <?php echo $num_columns; ?> - 1;  // Calculate COLUMN
    } else {
      var oldrow = Math.floor(oldvalue / <?php echo $num_columns; ?>);  // Calculate ROW
      var oldcolumn = (oldvalue % <?php echo $num_columns; ?>) - 1;  // Calculate COLUMN
    }
//    alert("Updating TRAY_NUM"+current_tray_num+" device_id \""+device_list[current_tray_num-1]+"\" from "+oldvalue+" ("+oldrow+", "+oldcolumn+")");
    myTable.rows[oldrow].cells[oldcolumn].innerHTML = oldvalue+" - ";  // Update table cell data
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
</script>
</HEAD>

<BODY onload="StartUp()">

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div>
    <h2>Set server layout</h2>
    <p><u>Notes:</u><p>
    <ul>
      <li>Changing the layout (Rows/Columns) will result in resetting all manually entered data (Trays #, Purchase Date, etc...)</li>
      <li>Maximum number of drive trays allowed is <?php echo $max_trays ?></li>
      <li>First choose the number of rows and then choose the number of columns</li>
    </ul>
    <p>
      Rows: <select name="ROWS" id="ROWS" size="1" onchange="DefineColumnsDropDownList()">
              <option value="" disabled></option>
              <?php for ($k = 1; $k <= $max_trays; $k++) { ?>
              <option value="<? echo $k ?>"<? if ($serverlayout_cfg['ROWS'] == $k) { ?> selected<? } ?>><? echo $k ?></option>
              <?php } ?>
            </select>
    </p>
    <p>
      Columns: <select name="COLUMNS" id="COLUMNS" size="1">
               </select>
    </p>
    <p>
      Drive Trays Orientation:
        <select name="ORIENTATION" id="ORIENTATION" size="1" >
          <option value="0"<? if ($orientation == "0") { ?> selected<? } ?>>Horizontal</option>
          <option value="90"<? if ($orientation == "90") { ?> selected<? } ?>>Vertical</option>
        </select>
    </p>
    Enable editing: <input type="checkbox" name="EDIT_LAYOUT" id="EDIT_LAYOUT" onchange="EditLayoutCheckbox(this)">
  </div>

  <br>
  <br>

  <div>
    <h2>List of devices found</h2>
    <table>
      <tr>
        <td></td>
        <?php for ($i = 1; $i <= ($num_data_col-$num_data_col_not_show); $i++) { ?>
        <td align="center"><input type="checkbox" name="SHOW<?php echo $i ?>" id="SHOW<?php echo $i ?>" value="SHOW"></td>
        <?php } ?>
        <td></td>
      </tr>
      <tr>
        <th>Type</th> <th>Device</th> <th>Model</th> <th>Serial Number</th> <th>Firmware</th> <th>Capacity</th> <th>Purchase Date</th> <th>Tray #</th>
      </tr>
      <?php if ($num_disks > 0) { ?>
      <?php for ($i = 1; $i <= $num_disks; $i++) { ?>
      <tr>
        <td><?php switch ($serverlayout_auto[$i]['TYPE']) {
                    case "SATA": ?> <img src="SATA_Logo.jpg" style="width:30px;height:20px"> <? break;
                    case "USB": ?> <img src="USB_Logo.jpg" style="width:30px;height:20px"> <? break;
                    default: echo $serverlayout_auto[$i]['TYPE']; break; } ?>
        </td>
        <td><?php echo $serverlayout_auto[$i]['DEVICE']; ?></td>
        <td><?php echo $serverlayout_auto[$i]['MODEL']; ?></td>
        <td><?php echo $serverlayout_auto[$i]['SN']; ?></td>
        <td><?php echo $serverlayout_auto[$i]['FIRMWARE']; ?></td>
        <td><?php echo $serverlayout_auto[$i]['CAPACITY']; ?></td>
        <td><input type="text" name="PURCHASE_DATE<?php echo $i ?>" id="PURCHASE_DATE<?php echo $i ?>" style="width: 6em;" maxlength="10" value="<?=$serverlayout_cfg[$serverlayout_auto[$i]['SN']]['PURCHASE_DATE'];?>"></td>
        <td>
          <?php if ($serverlayout_auto[$i]['TYPE'] != "USB") { ?>
          <select name="TRAY_NUM<?php echo $i ?>" id="TRAY_NUM<?php echo $i ?>" size="1" onfocus="this.oldvalue = this.value;" onchange="UpdateTrayOptions(<?php echo $i ?>, this); this.oldvalue = this.value;">
          </select>
          <?php } else { ?>
          <?php echo $serverlayout_cfg[$serverlayout_auto[$i]['SN']]['TRAY_NUM']; ?>
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <tr>
        <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td colspan="2" align="center">Enable editing: <input type="checkbox" name="EDIT_TABLE" id="EDIT_TABLE" onchange="EditTableCheckbox(this)"></td>      
      </tr>
      <?php } else { ?>
      <tr>
        <td align="center" colspan="<? echo $num_data_col; ?>">No disks found - Scan Hardware</td>
      </tr>
      <?php } ?>
      <tr>
        <td align="center" colspan="<? echo $num_data_col; ?>"><input type="submit" name="scan" value="Scan Hardware"></td>
      </tr>
    </table>
  </div>

  <br>
  <br>

  <div>
    <h2>Preview of server layout</h2>
    <table id="PREVIEWTABLE">
      <?php for ($i = 1; $i <= $num_rows; $i++) { ?>
      <tr>
        <?php for ($j = 1; $j <= $num_columns; $j++) { ?>
        <td>
          <?php $tray_num = (($i-1) * $num_columns) + $j;
                echo "$tray_num - ";
                for ($k = 1; $k <= $num_disks; $k++) {
                  if ($serverlayout_cfg[$serverlayout_auto[$k]['SN']]['TRAY_NUM'] == $tray_num) { echo $serverlayout_auto[$k]['DEVICE']; }
                } ?>
        </td>
        <?php } ?>
      </tr>
      <?php } ?>
    </table>
  </div>
  
  <br>
  <br>

  <div>
    <h2>Press "Apply" button to save data</h2>
    <input type="submit" name="apply" value="Apply">
  </div>
</form>

</BODY>
</HTML>
