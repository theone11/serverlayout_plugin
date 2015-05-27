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
table.disk_data td:first-child {padding-left:5px; padding-right:5px; text-align:center;font-size:13px;background:-webkit-radial-gradient(#E0E0E0,#C0C0C0);background:linear-gradient(#E0E0E0,#C0C0C0);border-right:1px solid #F0F0F0;}
table.disk_data thead tr:first-child td{padding-left:5px; padding-right:5px; text-align:center;font-size:13px;background:-webkit-radial-gradient(#E0E0E0,#C0C0C0);background:linear-gradient(#E0E0E0,#C0C0C0);border-right:1px solid #F0F0F0;}
table.disk_data tbody td {padding-left:5px; padding-right:5px;}
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

/*
function InitMultipleSelect() {
  var data =  [<?php $first = true;
                             foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) {
                               if ($first) {
                                 $first = false;
                                 echo "[\"".$data_column["TITLE"]."\", \"".$data_column["SHOW_DATA"]."\", \"".$data_column["SHOW_COLUMN_I"]."\" ,\"".$data_column["SHOW_COLUMN_H"]."\"]";
                               } else {
                                 echo ", [\"".$data_column["TITLE"]."\", \"".$data_column["SHOW_DATA"]."\", \"".$data_column["SHOW_COLUMN_I"]."\" ,\"".$data_column["SHOW_COLUMN_H"]."\"]";
                               }
                             } ?>];

  var Eshow_data = document.getElementById("SHOW_DATAS");
  var Eshow_column_i = document.getElementById("SHOW_COLUMNS_I");
  var Eshow_column_h = document.getElementById("SHOW_COLUMNS_H");
  Eshow_data.options.length = 0;
  Eshow_column_i.options.length = 0;
  Eshow_column_h.options.length = 0;
  
  for (i = 0; i < Eshow_data.length; i++) {
    if (data[i][1] = "YES") {
      Eshow_data.options[i]=new Option(data[i][1], i, false, true);  // new Option(text, value, defaultSelected, selected);
    } else {
      Eshow_data.options[i]=new Option(data[i][1], i, false, false);
    }
    
    if (data[i][2] = "YES") {
      Eshow_column_i.options[i]=new Option(data[i][1], i, false, true);
    } else {
      Eshow_column_i.options[i]=new Option(data[i][1], i, false, false);
    }
    
    if (data[i][3] = "YES") {
      Eshow_column_h.options[i]=new Option(data[i][1], i, false, true);
    } else {
      Eshow_column_h.options[i]=new Option(data[i][1], i, false, false);
    }
  }
}
*/

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
      columnsE.options[i]=new Option(i, i, false, true);  // new Option(text, value, defaultSelected, selected);
    } else {
      columnsE.options[i]=new Option(i, i, false, false);
    }
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

function StartUp() {
  DefineColumnsDropDownList();
  InitDisabledFields();
  InitMultipleSelect();
  UpdateDIVSizes();
}

</script>
</HEAD>

<BODY>

<form name="serverlayout_settings" method="post" onsubmit="validateForm()" action="/plugins/serverlayout/php/serverlayout_submit.php" target="progressFrame">

  <div style="width:100%; ?>%; float:left; border: 0px solid black; overflow: hidden;">
    <table>
      <tr>
        <td><input type="submit" name="settings" value="Save Layout Configuration"></td>
        <td><button type="button" onClick="done();">Exit ServerLayout</button></td>
      </tr>
    </table>
  </div>
    
  <div style="width:100%; ?>%; float:left; border: 0px solid black; overflow: hidden;">
    <div style="width:24%; float:left; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Layout Settings</span>
      </div>
      <div class="margin-left:auto; margin-right:auto;">
        <table>
          <tr>
            <td>Enable editing:</td>
            <td><input type="checkbox" name="EDIT_LAYOUT" id="EDIT_LAYOUT" onchange="EditCheckboxLayout(this)"></td>
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

    <div style="width:74%; float:right; border: 0px solid black; overflow: hidden;">
      <div id="title">
        <span class="left">Data View Settings</span>
      </div>
      <div class="margin-left:auto; margin-right:auto;">
        <table class="disk_data">
          <thead>
          <tr>
            <td>Title</td>
            <td>Show in Layout</td>
            <td>Show in "Installed" Table</td>
            <td>Show in "Historical" Table</td>
          <tr>
          <tbody>
            <?php foreach ($myJSONconfig["DATA_COLUMNS"] as $data_column) { ?>
            <tr>
              <td><?php echo $data_column["TITLE"]; ?></td>
              <td><input type="checkbox" name="SHOW_DATAS[]" value="YES" <?php if ($data_column["SHOW_DATA"] == "YES") { echo "checked"; } ?> ></input></td>
              <td><input type="checkbox" name="SHOW_COLUMNS_I[] value="YES" <?php if ($data_column["SHOW_COLUMN_I"] == "YES") { echo "checked"; } ?>></input></td>
              <td><input type="checkbox" name="SHOW_COLUMNS_H[] value="YES" <?php if ($data_column["SHOW_COLUMN_H"] == "YES") { echo "checked"; } ?>></input></td>
            </tr>
        </table>
      </div>
    </div>
  </div>

</form>

<script>StartUp();</script>

</BODY>
</HTML>
