<?php
require_once('serverlayout_constants.php');

$rows = $myJSONconfig["LAYOUT"]["ROWS"];
$columns = $myJSONconfig["LAYOUT"]["COLUMNS"];
$orientation = $myJSONconfig["LAYOUT"]["ORIENTATION"];
?>

<HTML>

<HEAD>
<style>
table.disk_data {overflow: auto;}
table.disk_data td {width:auto; white-space:nowrap;}
table.disk_data thead tr:first-child td{padding-left:5px; padding-right:5px; text-align:center;font-size:13px;background:-webkit-radial-gradient(#E0E0E0,#C0C0C0);background:linear-gradient(#E0E0E0,#C0C0C0);border-right:1px solid #F0F0F0;}
table.disk_data tbody td:first-child {text-align:left;}
table.disk_data tbody td {text-align:center; padding:4px; padding-left:5px; padding-right:5px;}
table.disk_data tbody tr:nth-child(even) {background-color:#F8F8F8;}
table.disk_data tbody tr:hover {background-color:#FDFD96;}
</style>

<script type="text/javascript">
function validateForm() {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for (i = 0; i < elements.length; i++) {
    elements[i].disabled = false;
  }
}

function InitDisabledFields() {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for (i = 0; i < elements.length; i++) {
    elements[i].disabled = true;
  }
}

function DefineColumnsDropDownList() {
  var rows = document.getElementById("ROWS").value;
  var columnsE = document.getElementById("COLUMNS");
  var columns_max = <?php echo $max_trays ?>/rows;
  var columns = <?php echo $columns; ?>;

  var no_selected_option = true;
  columnsE.options.length = 0;

  for (i = 1; i <= columns_max; i++) {
    if (i == columns) {
      columnsE.options[i-1]=new Option(i, i, false, true);  // new Option(text, value, defaultSelected, selected);
      no_selected_option = false;
    } else {
      columnsE.options[i-1]=new Option(i, i, false, false);  // new Option(text, value, defaultSelected, selected);
    }
  }
  if (no_selected_option) {
    columnsE.options[0].selected = true;
  }
}

function EditCheckboxLayout(myCheckbox) {
  var elements = document.getElementsByClassName("LAYOUT_DATA");
  for (i = 0; i < elements.length; i++) {
    if (myCheckbox.checked) {
      elements[i].disabled = false;
    } else {
      elements[i].disabled = true;
    }
  }
}

function UpdateShowCheckboxes(name) {
  switch (name) {
    case "SHOW_DATA_CLR"     : var elements = document.getElementsByClassName("SHOW_DATA_CHECKBOXES"); command = "CLR"; break;
    case "SHOW_DATA_SET"     : var elements = document.getElementsByClassName("SHOW_DATA_CHECKBOXES"); command = "SET"; break;
    case "SHOW_TOOLTIP_CLR"  : var elements = document.getElementsByClassName("SHOW_TOOLTIP_CHECKBOXES"); command = "CLR"; break;
    case "SHOW_TOOLTIP_SET"  : var elements = document.getElementsByClassName("SHOW_TOOLTIP_CHECKBOXES");  command = "SET"; break;
    case "SHOW_COLUMN_I_CLR" : var elements = document.getElementsByClassName("SHOW_COLUMN_I_CHECKBOXES"); command = "CLR"; break;
    case "SHOW_COLUMN_I_SET" : var elements = document.getElementsByClassName("SHOW_COLUMN_I_CHECKBOXES");  command = "SET"; break;
    case "SHOW_COLUMN_H_CLR" : var elements = document.getElementsByClassName("SHOW_COLUMN_H_CHECKBOXES");  command = "CLR"; break;
    case "SHOW_COLUMN_H_SET" : var elements = document.getElementsByClassName("SHOW_COLUMN_H_CHECKBOXES");  command = "SET"; break;
    default:
  }
  for (i = 0; i < elements.length; i++) {
    if (command == "CLR") {
      elements[i].checked = false;
    }
    else if (command == "SET") {
      elements[i].checked = true;
    }
  }
}

function StartUp() {
  DefineColumnsDropDownList();
  InitDisabledFields();
}

</script>
</HEAD>

<BODY>

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="/plugins/serverlayout/php/serverlayout_submit.php" target="progressFrame">

  <div style="width:24%; float:left; border: 0px solid black; overflow: hidden;">
    <div id="title">
      <span class="left">Commands</span>
    </div>
    <div style="text-align:center;"><input type="submit" name="settings" value="Save Layout Configuration"></div>
    <blockquote class='inline_help'>
      <p>Save all data on current tab<p>
    </blockquote>
    <div style="text-align:center;"><button type="button" onClick="done();">Exit ServerLayout</button></div>
    <blockquote class='inline_help'>
      <p>Exit plugin and return to unRAID Settings Page<p>
    </blockquote>

    <div id="title">
      <span class="left">General Settings</span>
    </div>
    <div>
      <table>
        <tr>
          <td>Enable Tray Tooltip:</td>
          <td><input type="checkbox" name="TOOLTIP_ENABLE" id="TOOLTIP_ENABLE" value="YES" <?php if ($myJSONconfig["GENERAL"]["TOOLTIP_ENABLE"] == "YES") { echo "checked"; } ?>></td>
        </tr>
      </table>
      <blockquote class='inline_help'>
        <p>Enable the above checkbox in order to show the Tray Tooltips in the Layout tab<p>
      </blockquote>
    </div>

    <div id="title">
      <span class="left">Layout Settings</span>
    </div>
    <div>
      <table>
        <tr>
          <td>Enable editing:</td>
          <td><input type="checkbox" name="EDIT_LAYOUT" id="EDIT_LAYOUT" onchange="EditCheckboxLayout(this)"></td>
        </tr>
        <tr>
          <td>Rows:</td>
          <td><select class="LAYOUT_DATA" name="ROWS" id="ROWS" size="1" onchange="DefineColumnsDropDownList()">
                  <?php for ($k = 1; $k <= $max_trays; $k++) { ?>
                  <option value="<? echo $k ?>"<? if ($rows == $k) { echo "selected"; } ?>><? echo $k; ?></option>
                  <?php } ?>
                </select></td>
        </tr>
        <tr>
          <td>Columns:</td>
          <td><select class="LAYOUT_DATA" name="COLUMNS" id="COLUMNS" size="1"></select></td>
        </tr>
        <tr>
          <td>Drive Trays Orientation:</td>
          <td><select class="LAYOUT_DATA" name="ORIENTATION" id="ORIENTATION" size="1" >
            <option value="0"<? if ($orientation == "0") { ?> selected<? } ?>>Horizontal</option>
            <option value="90"<? if ($orientation == "90") { ?> selected<? } ?>>Vertical</option>
         </select></td>
        </tr>
      </table>
      <blockquote class='inline_help'>
        <p>Enable the checkbox in order to edit the below options<p>
        <p><strong>Rows</strong> are the number of rows existing in your server<p>
        <p><strong>Columns</strong> are the number of columns existing in your server<p>
        <p><strong>Orientation</strong> is the direction the disk trays are positioned in your servr (horizontal or vertical)</p>
        <p><ins>Note:</ins></p>
        <p><ul><li>If rows or columns parameters are changed (and saved) then all disk tray assignments will be removed and all trays will be visible again</li>
               <li>Enable the checkbox in order to change these settings</li></ul></p>
      </blockquote>
    </div>

    <div id="title">
      <span class="left">Support</span>
    </div>
    <div style="text-align:center;">
      <a href="http://lime-technology.com/forum/index.php?topic=40223.0" target="_blank">Visit Server Layout plugin forum thread</a>
    </div>

    <div id="title">
      <span class="left">Debug</span>
    </div>
    <div>
      <table>
        <tr>
          <td>Enable Time Profiling:</td>
          <td><input type="checkbox" name="TIME_PROFILING" id="TIME_PROFILING" value="YES" <?php if ($myJSONconfig["DEBUG"]["TIME_PROFILING"] == "YES") { echo "checked"; } ?>></td>
        </tr>
      </table>
      <blockquote class='inline_help'>
        <p>Enable time profiling of the data gathering for code inspection and optimization<p>
      </blockquote>
    </div>

    </div>

  <div style="width:74%; float:right; border: 0px solid black; overflow: auto;">
    <div id="title">
      <span class="left">Data View Settings</span>
    </div>
    <div class="margin-left:auto; margin-right:auto;">
      <table class="disk_data">
        <thead>
        <tr>
          <td>Title</td>
          <td>Show in Layout</td>
          <td>Show in Tray Tooltip</td>
          <td>Show in "Installed" Table</td>
          <td>Show in "Historical" Table</td>
        </tr>
        <tr>
          <td>
          </td>
          <td>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_DATA_CLR" onClick="UpdateShowCheckboxes(this.name)">Clear All</button></div>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_DATA_SET" onClick="UpdateShowCheckboxes(this.name)">Set All</button></div>
          </td>
          <td>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_TOOLTIP_CLR" onClick="UpdateShowCheckboxes(this.name)">Clear All</button></div>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_TOOLTIP_SET" onClick="UpdateShowCheckboxes(this.name)">Set All</button></div>
          </td>
          <td>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_COLUMN_I_CLR" onClick="UpdateShowCheckboxes(this.name)">Clear All</button></div>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_COLUMN_I_SET" onClick="UpdateShowCheckboxes(this.name)">Set All</button></div>
          </td>
          <td>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_COLUMN_H_CLR" onClick="UpdateShowCheckboxes(this.name)">Clear All</button></div>
            <div style="width:50%; float:left; text-align:center;"><button type="button" name="SHOW_COLUMN_H_SET" onClick="UpdateShowCheckboxes(this.name)">Set All</button></div>
          </td>
        </tr>
        <tbody>
          <?php foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) { ?>
          <tr>
            <td><?php echo $data_column["TITLE"]; ?></td>
            <td><input type="checkbox" class="SHOW_DATA_CHECKBOXES" name="SHOW_DATA_<?php echo $data_column["NAME"]; ?>" value="YES" <?php if ($data_column["SHOW_DATA"] == "YES") { echo "checked"; } ?>></td>
            <td><input type="checkbox" class="SHOW_TOOLTIP_CHECKBOXES" name="SHOW_TOOLTIP_<?php echo $data_column["NAME"]; ?>" value="YES" <?php if ($data_column["SHOW_TOOLTIP"] == "YES") { echo "checked"; } ?>></td>
            <td><input type="checkbox" class="SHOW_COLUMN_I_CHECKBOXES" name="SHOW_COLUMN_I_<?php echo $data_column["NAME"]; ?>" value="YES" <?php if ($data_column["SHOW_COLUMN_I"] == "YES") { echo "checked"; } ?>></td>
            <td><input type="checkbox" class="SHOW_COLUMN_H_CHECKBOXES" name="SHOW_COLUMN_H_<?php echo $data_column["NAME"]; ?>" value="YES" <?php if ($data_column["SHOW_COLUMN_H"] == "YES") { echo "checked"; } ?>></td>
          </tr>
          <?php } ?>
      </table>
    </div>
  </div>

</form>

<script>StartUp();</script>

</BODY>
</HTML>
