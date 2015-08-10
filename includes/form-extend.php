<?php
error_reporting(0);

$_SESSION["this"]["UPLOADBYTE"] = 64000000;
$_SESSION["this"]["UPLOADTEMP"] = "uploads/temp/";

//UPLOAD
		function form_upload ($col, $name, $img) {
			$unlink = $name;
			$upload = $name."1";
			$dir = $_POST[$name."dir"];
			$new = $_FILES[$upload]["name"];
			if ($new) {
				$unlink = $_POST[$unlink];
				$source = $_FILES[$upload]["tmp_name"]; 
				$byte = $_FILES[$upload]["size"];
				if ($byte > $_SESSION["this"]["UPLOADBYTE"]) { $_SESSION["this"]["ALERT"] = "UPLOADBYTE"; }
				else {
					$new = str_replace (" ", "", trim (stripslashes (htmlspecialchars ($new))));
					list ($new, $ext) = explode (".", $new);
					$new = $new.date("_ymj_his").".".$ext;
					$temp = $_SESSION["this"]["UPLOADTEMP"].$new;
					if ($img) {
						move_uploaded_file ($source, $temp);
						list ($width, $height) = getimagesize ($temp);
						if ($img["width"] && $img["height"]) {
							$maxwidth = $img["width"];
							$maxheight = $img["height"];
							$ratio = $maxwidth / $maxheight; 
							if ($width > ($height * $ratio)) { $maxheight = $height / ($width / $maxwidth); }
							else { $maxwidth = $width / ($height / $maxheight); }     } //landscape / portrait
						else { $maxwidth = $width; $maxheight = $height;  }
						$process = imagecreatetruecolor ($maxwidth, $maxheight);
						if ($ext == "jpeg" || $ext == "jpg") { $create = imagecreatefromjpeg ($temp); } elseif ($ext == "png") { $create = imagecreatefrompng ($temp); } elseif ($ext == "gif") { $create = imagecreatefromgif ($temp); }
						imagecopyresampled ($process, $create, 0, 0, 0, 0, $maxwidth, $maxheight, $width, $height);
						imagejpeg ($process, $dir.$new, 80);     }
					else { move_uploaded_file ($source, $dir.$new); }
					if ($col) { form_format ($col, $new, "VAR"); }     
					if ($unlink && file_exists($dir.$unlink)) { unlink ($dir.$unlink); }
					unlink ($temp);     }     }     }
		
		function form_unlink ($col, $name) {
			$unlink = $name;
			$dir = $_POST[$name."dir"];
			if ($unlink && file_exists($dir.$unlink)) { unlink ($dir.$unlink); }
			if ($col) { form_format ($col, "", "VAR"); }     }

//SUB FUNCTIONS	

	function num_array ($total) { $count = 1; while ($count <= $total) { $array[$count] = $count; $count ++; } return $array; }

	function zero_add ($value) { if (strlen($value) == 1) { $value = "0".$value; } return $value; }

	function option_explode ($value) {

		$array = explode ("  ,  ", $value);

		foreach ($array as $pair) { $value = explode ("  =  ", $pair); $return[trim ($value[1])] = trim ($value[0]); }

		return $return;     }

	function concatLabel ($col) {

		if (strstr ($col, "CONCAT_WS")) { 

			$array = explode (" AS ", $col); 

			$return = explode (" ", trim ($array[1]));

			return trim ($return[0]);     }

		else { return $col; }     }	

	function queryENUM ($table, $col) {

		$query = mysql_query ("SHOW COLUMNS FROM ".$table." LIKE '$col'");

		$row = mysql_fetch_array ($query);

		$enum = str_replace ("enum('", "", $row[1]);

		$enum = str_replace ("')", "", $enum);

		$enum = explode ("','", $enum);

		foreach ($enum as $value) { $return[$value] = $value; }

		return $return; }	

	function dateJump ($date, $operand, $type) {

		$date = strtotime ($date);

		if ($type == "DAY") { $return = strtotime ($operand."1 day", $date); }

		elseif ($type == "MONTH") { $return = strtotime ($operand."1 month", $date); }

		elseif ($type == "QTR") { $return = strtotime ($operand."3 month", $date); }

		elseif ($type == "YEAR") { $return = strtotime ($operand."1 year", $date); }

		return date ("Y-m-d" , $return);     }	

	function dateLabel ($date, $type) {

		$date = strtotime ($date);

		$date = date ("Y F" , $date);

		$date = explode (" ", $date);

		if ($type == "MONTH") { $return = $date[1]." ". $date[0]; }

		elseif ($type == "QTR") { 

			if ($date[1] == "January") { $date[1] = "First Qtr"; }

			elseif ($date[1] == "April") { $date[1] = "Second Qtr"; }

			elseif ($date[1] == "July") { $date[1] = "Third Qtr"; }

			elseif ($date[1] == "October") { $date[1] = "Fourth Qtr"; }

			$return = $date[1]." ". $date[0];     }

		elseif ($type == "YEAR") { $return = $date[0]; }

		return $return;     }	

	function dateValid ($date) { if (date("Y-m-d", strtotime ($date)) == $date) { return TRUE; } }

	function stringCalc ($string) {

    	$string = trim($string);

   		$string = ereg_replace ('[^0-9\+-\*\/\(\) ]', '', $string);

		if (strstr ($string, "/ 0")) { return "-"; }

    	$return = create_function ("", "return (" . $string . ");" );

    	return 0 + $return();     }

	function stringReplaceCalc ($string, $array) {

    	$calc = explode (" ", $string);

		foreach ($calc as $key => $value) { if (strpos ($value, "]")) { $value = trim ($value, "[]"); $calc[$key] = $array[$value-1]; } }

		$calc = implode (" ", $calc);

		return stringCalc ($calc);     }

	function numPrint ($num, $dec) {

		if (!is_numeric ($num)) { return $num; }

		if ($num < 0) { $num = $num * -1; $neg = TRUE; }

		$return = number_format ($num, $dec);

		if ($neg) { $return = "(".$return.")"; }

		return $return;     }



//PRINT

	function form_start ($action) {

		if ($action == "SELF") { $action = $_SERVER['PHP_SELF']; }

		echo "<form action=\"".$action."\" class=\"valid\" method=\"post\">\n";     }

		

	function form_end () { echo "</form>\n";     }

	

	function form_print ($name, $type, $label, $value, $option) {

		$return = "";

		$type = explode (" ", $type, 2); //type, class

		$tag = $type[0];

		if ($type[1]) { $class = $type[1]; }

		if (substr ($class, 0, 5) == "FLOAT") { $fieldset = FALSE; } else { $fieldset = TRUE; }

		if ($fieldset) { $return .= "<fieldset class=\"mid\">"; }

		if ($label) { $return .= "<label for=\"".$name."\">".$label."</label>\n"; }

			

		if ($tag == "HIDDEN") { $return = "<input name=\"".$name."\" type=\"hidden\" value=\"".$value."\" />"; }

		elseif ($tag == "TEXT") { $return .= "<input class=\"text ".$class."\" name=\"".$name."\" type=\"text\" value=\"".$value."\" />"; }

		elseif ($tag == "PASSWORD") { $return .= "<input class=\"text ".$class."\" name=\"".$name."\" type=\"password\" value=\"".$value."\" />"; }

		elseif ($tag == "FILE") { $return .= "<input class=\"".$class."\" name=\"".$name."\" type=\"file\" />"; }

		elseif ($tag == "TEXTAREA") { $return .= "<textarea class=\"".$class."\" name=\"".$name."\">".$value."</textarea>"; }

		elseif ($tag == "SUBMIT") { 

			if (!$class) { $class = "submit"; }

			elseif ($class == "FLOAT") { $class .= " submit"; }

			$return .= "<input class=\"".$class."\" name=\"".$name."\" type=\"submit\" value=\"".$value."\" />"; }

		elseif ($tag == "SELECT") { 

			$return .= "<select class=\"".$class."\" name=\"".$name."\">";

			foreach ($option as $option_key => $option_value) { $return .= "<option value=\"".$option_key."\""; if ($option_key == $value) { $return .= " selected=\"yes\""; } $return .= ">".$option_value."</option>"; }

			$return .= "</select>";     }

		elseif ($tag == "CHECKBOX") { 

			if (!is_array ($value)) { $value = explode (",", $value); }

			foreach ($option as $option_key => $option_value) {

				if ($class == "ROW") { $return .= "<div class=\"row\">"; }

				$return .= "<input class=\"checkbox ".$class."\" name=\"".$name."[]\" type=\"checkbox\" value=\"".$option_key."\"";

				if ($value && in_array ($option_key, $value)) { $return .= " checked=\"yes\""; }

				$return .= " />";

				$return .= "<span class=\"checkbox-label\">$option_value</span>";

				if ($class == "ROW") { $return .= "</div>"; }     }     }		

		elseif ($tag == "RADIO") { 

			foreach ($option as $option_key => $option_value) {

				if ($class == "ROW") { $return .= "<div class=\"row\">"; }

				$return .= "<input class=\"radio ".$class."\" name=\"".$name."\" type=\"radio\" value=\"".$option_key."\"";

				if ($value == $option_key) { $return .= " checked=\"yes\""; }

				$return .= " />";

				$return .= "<span class=\"radio-label\">$option_value</span>";

				if ($class == "ROW") { $return .= "</div>"; }     }     }

		elseif ($tag == "DATE" || $tag == "DATEMONTH" || $tag == "DATEQTR" || $tag == "DATEYEAR") { 

			$value = explode("-", $value);

			$value_day = $value[2];

			$value_month = $value[1];

			$value_year = $value[0];

			$day = range (1, 31);

			$month = array (1 => "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

			if ($tag == "DATEQTR") { $month = array (1 => "Q1", 4 => "Q2", 7 => "Q3", 10 => "Q4"); }

			$option = explode("-", $option);

			$year = range ($option[0], $option[1]);

			if ($tag == "DATE") {

				$return .= "<select class=\"date dateday validdate\" name=\"".$name."day\">\n";

				$return .= "<option value=\"\">Day</option>\n";

				foreach ($day as $option_value) { $option_value = zero_add($option_value); $return .= "<option value=\"".$option_value."\""; if ($option_value == $value_day) { $return .= " selected=\"selected\""; } $return .= ">".$option_value."</option>\n"; }

				$return .= "</select>\n";     }

			else { $return .= "<input name=\"".$name."day\" type=\"hidden\" value=\"01\" />\n"; }

			if ($tag == "DATE" ||  $tag == "DATEMONTH" || $tag == "DATEQTR") {

				$return .= "<select class=\"date datemonth\" name=\"".$name."month\">\n";

				if ($tag == "DATEQTR") { $return .= "<option value=\"\">Qtr</option>\n"; }

				else { $return .= "<option value=\"\">Month</option>\n"; }

				foreach ($month as $option_key => $option_value) { $option_key = zero_add($option_key); $return .= "<option value=\"".$option_key."\""; if ($option_key == $value_month) { $return .= " selected=\"selected\""; } $return .= ">".$option_value."</option>\n"; }

				$return .= "</select>\n";     }

			else { $return .= "<input name=\"".$name."month\" type=\"hidden\" value=\"01\" />\n"; }			

			$return .= "<select class=\"date dateyear\" name=\"".$name."year\">\n";

			$return .= "<option value=\"\">Year</option>\n";

			foreach ($year as $option_value) { $return .= "<option value=\"".$option_value."\""; if ($option_value == $value_year) { $return .= " selected=\"selected\""; } $return .= ">".$option_value."</option>\n"; }

			$return .= "</select>\n";     }

		if ($fieldset) { $return .= "</fieldset>\n"; }

		return $return;     }

		

//INSERT

	function form_insert ($table) {

		$count = 0;

		foreach ($_SESSION['this_query'] as $key => $value) {

			if (is_string ($value)) { $value = "'".$value."'"; }

			if ($count == 0) { $insert_key .= $key; $insert_value .= $value; $count ++; }

			else { $insert_key .= ", ".$key; $insert_value .= ", ".$value; } }

		$query = "INSERT INTO ".$table." (".$insert_key.") VALUES (".$insert_value.")";

		unset ($_SESSION['this_query']);

		mysql_query ($query) or die ($query);     }

		

//UPDATE

	function form_update ($table) {

		$count = 0;

		$where = explode(" WHERE ", $table);

		foreach ($_SESSION['this_query'] as $key => $value) {

			if (is_string ($value)) { $value = "'".$value."'"; }

			if ($count == 0) { $update .= $key." = ".$value; $count ++; }

			else { $update .= ", ".$key." = ".$value; } }

		$query = "UPDATE ".$where[0]." SET ".$update." WHERE ".$where[1]."";

		unset ($_SESSION['this_query']);

		mysql_query ($query) or die ($query);     }



//DELETE

	function form_delete ($table) { $query = "DELETE FROM ".$table.""; mysql_query ($query) or die ($query); }	

			

//FORMAT

	function form_format ($column, $name, $type) {
		
		if ($type == "DATE" && !$_POST[$name]) { $value = $_POST[$name."year"]."-".$_POST[$name."month"]."-".$_POST[$name."day"]; }
		
		elseif ($type == "DATEPICKER") { list ($day, $month, $year) = explode("-", $_POST[$name]); $value = $year."-".$month."-".$day; }

		elseif ($type == "VAR") { $value = $name; }

		elseif (!$_POST[$name]) { $value = ""; }

		else { $value = $_POST[$name]; }

		if ($type == "CHECKBOX" && !empty ($value)) { $value = implode (",", $value); }
		
		elseif (strpos ($type, "CHECKBOXBINARY") === 0 && !$value) { list ($type, $value) = explode ("-", $type, 2); }

		elseif ($type == "TEXT") { $value = trim ($value); }

		if ($column) { $_SESSION["this_query"][$column] = $value; }

		return $value;     }



//ENUM		

	function form_option_enum ($table, $column) {

		$return = mysql_fetch_assoc (mysql_query ("DESCRIBE $table $column")); //returns enum('a','b')

		$return = substr ($return["Type"], 6); //removes start enum('

		$return = substr ($return, 0, -2); //removes end ')

		$return = explode ("','", $return);

		foreach ($return as $key => $value) { $return[$value] = $value; unset ($return[$key]); }

		return $return;     }	

	

//LINK CHECKBOX	

	function form_link_checkbox ($param) {

		$parent = $param["parent"];

		$child = $param["child"];

		$link = $param["link"];

		$query = mysql_query ("SELECT * FROM ".$child["table"]." ".$child["condition"]);

		if ($query) { 



			$return .= form_print ($child["table"]."_linktable", "HIDDEN", "", $link["table"], "");

			$return .= form_print ($child["table"]."_linkparentid", "HIDDEN", "", $parent["id"], "");

			$return .= form_print ($child["table"]."_linkparentcol", "HIDDEN", "", $link["parent"], "");

			$return .= form_print ($child["table"]."_linkchildcol", "HIDDEN", "", $link["child"], "");

		

			while ($row = mysql_fetch_array ($query)) { $option[$row[$child["id"]]] = $row[$child["label"]]; }//option

			$query = mysql_query ("SELECT * FROM ".$link["table"]." WHERE ".$link["parent"]." = ".$parent["id"]);

			if ($query) { while ($row = mysql_fetch_array ($query)) { $selected[] = $row[$link["child"]]; } } //selected	

			$return .= form_print ($child["table"]."_link", "CHECKBOX ROW", "", $selected, $option);

			foreach ($option as $key => $value) { $return .= form_print ($child["table"]."_set[]", "HIDDEN", "", $key, ""); }

			return $return;     }     }

			

	function form_insert_link_checkbox ($childtable) { 

		$linktable = form_format ("", $childtable."_linktable", "");

		$linkparentid = form_format ("", $childtable."_linkparentid", "");

		$linkparentcol = form_format ("", $childtable."_linkparentcol", "");

		$linkchildcol = form_format ("", $childtable."_linkchildcol", "");

		$set = form_format ("", $childtable."_set", ""); //column, name, type

		foreach ($set as $value) { form_delete ($linktable." WHERE ".$linkparentcol." = ".$linkparentid." AND ".$linkchildcol." = ".$value); }

		$link = form_format ("", $childtable."_link", ""); //column, name, type

		if ($link) { 

			foreach ($link as $value) {

				form_format ($linkparentcol, $linkparentid, "VAR"); //column, name, type

				form_format ($linkchildcol, $value, "VAR"); //column, name, type

				form_insert ($linktable);     }     }     }



//TREE			

		function form_tree ($param) {

			$filter = $param["filter"];

			$link = $param["link"];

			$tree = $param["tree"];

			$load = $param["load"];

			

			if ($_POST["tree_filter"]) { $_SESSION["tree_filter"] = form_format ("", "tree_filter", ""); } //column, name, type

			elseif ($_GET["filter"]) { 

				$token = $_GET["filter"];

				if ($link["table"] && ($link["table"] != $tree["table"])) { $get["id"] = $link["filter"]; $get["table"] = $filter["table"]; }

				else { $get["id"] = $tree["id"]; $get["table"] = $tree["table"]; }

				$query = mysql_query ("SELECT ".$get["id"]." FROM ".$get["table"]." WHERE token = '$token'");

				while ($row = mysql_fetch_array ($query)) { $_SESSION["tree_filter"] = $row[$get["id"]]; }     }



//filter			

			if ($filter["id"]) { 

				$query = mysql_query ("SELECT ".$filter["id"].", ".$filter["label"]." FROM ".$filter["table"]." ".$filter["condition"]);

				if (mysql_num_rows($query)) { 

					$option[] = "Filter . . .";

					while ($row = mysql_fetch_array ($query)) { $option[$row[$filter["id"]]] = $row[$filter["label"]]; } //option

					echo "<div class=\"row rowfilter\">";

					echo form_start ("SELF");

					echo form_print ("tree_filter", "SELECT FLOAT", "", $_SESSION["tree_filter"], $option); 

					echo form_print ("tree_submit", "SUBMIT FLOAT submit submitmin", "", "Go", "");

					echo form_end();

					echo "</div>";     }     }

//link					

			if ($_SESSION["tree_filter"]) {

				if ($link["table"] && ($link["table"] != $tree["table"])) { 

					$query = mysql_query ("SELECT * FROM ".$link["table"]." WHERE ".$link["filter"]." = ".$_SESSION["tree_filter"]);

					if (mysql_num_rows($query)) { while ($row = mysql_fetch_array ($query)) { $filterLINK[] = $row[$link["tree"]]; } $filterLINK = implode (",", $filterLINK); }

					else { $filterLINK[] = "EMPTY"; }     }

				else { $filterTREE = TRUE; }     }

//tree parent

			if ($filterLINK || $filterTREE || !$filter["id"]) {

				$condition = $tree["condition"];

				if ($filterTREE && !$filter) { $where = "WHERE ".$tree["parent"]." = ".$_SESSION["tree_filter"]; }

				else { $where = "WHERE ".$tree["parent"]." = 0"; }

				if ($filterTREE && $filter) { $where .= " AND ".$link["filter"]." = ".$_SESSION["tree_filter"]; }

				if ($filterLINK) { $where .= " AND ".$link["tree"]." IN (".$filterLINK.")"; }

				if (strstr ($condition, "WHERE ")) { $condition = str_replace ("WHERE ", $where." AND ", $condition); }

				elseif ($condition) { $condition = $where." ".$condition; }

				else { $condition = $where; }

//tree parent
//ECHO SQL						

				//echo "SELECT ".$tree["id"].", ".$tree["label"].", ".$tree["level"]." FROM ".$tree["table"]." ".$condition;

				$query = mysql_query ("SELECT ".$tree["id"].", ".$tree["label"].", ".$tree["level"]." FROM ".$tree["table"]." ".$condition);

				if (mysql_num_rows($query)) { 

					echo "<ul class=\"menu menutree\">";

					while ($row = mysql_fetch_array ($query)) { 

						$id = $row[$tree["id"]];

						$label = $row[$tree["label"]];

						$level = $row[$tree["level"]];

						if ((!$filterLINK && !$filter["id"]) || $filterTREE || $filterLINK) {

							if ($load[$level]) { echo "<li><span class=\"click clicklabel\" onclick=\"$('#tree-content').empty().load('".$load[$level].$id."');\">".$label."</span>"; }

							else { echo "<li><span class=\"click clicknone\">".$label."</span>"; }

							form_tree_loop (array ("parent" => $id, "tree" => $tree, "load" => $load, "filter" => $filterLINK));

							echo "</li>";     }     }

					echo "</ul>";     }

				else { echo "<p>No options available.</p>"; }     }		

//empty

			elseif ($filter["id"]) { echo "<p>Select a 'filter' from the dropdown (above).</p>"; }

			else { echo "<p>No options available for this area.</p>"; }

			unset ($_SESSION["tree_filter"]);     }



//LOOP		

		function form_tree_loop ($param) {

			$parent = $param["parent"];

			$tree = $param["tree"];

			$load = $param["load"];

			if ($param["filter"]) { $filter = explode (",", $param["filter"]); }

//condition			

			$condition = $tree["condition"];

			$where = "WHERE ".$tree["parent"]." = ".$parent;

			if (strstr ($condition, "WHERE ")) { $condition = str_replace ("WHERE ", $where." AND ", $condition); }

			elseif ($condition) { $condition = $where." ".$condition; }

			else { $condition = $where; }

//query

			$query = mysql_query ("SELECT ".$tree["id"].", ".$tree["label"].", ".$tree["level"]."  FROM ".$tree["table"]." ".$condition);

			if (mysql_num_rows($query)) {

				echo "<span class=\"click clickplus\">[+]</span>";

				echo "<ul>";

				while ($row = mysql_fetch_array ($query)) { 

					$id = $row[$tree["id"]];

					$label = $row[$tree["label"]];

					$level = $row[$tree["level"]];

					if ($load[$level] && (!$filter || in_array($id, $filter))) { echo "<li><span class=\"click clicklabel\" onclick=\"$('#tree-content').empty().load('".$load[$level].$id."');\">".$label."</span>"; }

					else { echo "<li><span class=\"click clicknone\">".$label."</span>"; }

					form_tree_loop (array ("parent" => $id, "tree" => $tree, "load" => $load, "filter" => $param["filter"]));	

					echo "</li>";     }

				echo "</ul>";     }     }

				





		function form_Xtab ($param) {

			$filterCOL = $param["filterCOL"];

			$tabROW = $param["tabROW"];

			$tabCOL = $param["tabCOL"];

			$totalROW = $param["totalROW"];

			$totalCOL = $param["totalCOL"];

			$proceed = TRUE;

			if ($tabCOL["option"] == "DATEMONTH") { $tabCOL["date"] = "MONTH"; unset ($proceed); }

			elseif ($tabCOL["option"] == "DATEQTR") { $tabCOL["date"] = "QTR"; unset ($proceed); }

			elseif ($tabCOL["option"] == "DATEYEAR") { $tabCOL["date"] = "YEAR"; unset ($proceed); }

//col header

			if ($tabCOL["option"] == "ENUM") { $tabCOL["option"] = queryENUM ($tabCOL["table"], $tabCOL["col"]); }

//filter			

			if ($filterCOL) { 

				echo form_start ("SELF");

				foreach ($filterCOL as $key => $value) {

					$type = $value["type"];

					$col = $value["col"];

					$label = $value["label"]; 

					unset ($date);

//enum

					if ($type == "ENUM") {

						if ($_POST["filter_submit"]) {

							$enum = form_format ("", "filter_enum".$key, ""); //column, name, type

							if ($enum) {

								if ($col == $tabCOL["col"]) { $tabCOL["option"] = array ($enum); }

								else { $whereCOL[] = $col." = '".$enum."'"; }     }     }

						echo "<fieldset class=\"filter\">";

						if (!$label) { $label = "Select"; }

						$option = queryENUM ($tabCOL["table"], $col); $option = array("" => "Select") + $option; 

						echo form_print ("filter_enum".$key, "SELECT FLOAT", $label, $enum, $option);

						echo form_print ("filter_submit", "SUBMIT buttonimg buttonimgnext", "", "Submit", "");

						echo "</fieldset>";     }						

//date

					elseif ($type == "DATEMONTH" || $type == "DATEQTR" || $type == "DATEYEAR") { $date = "THIS"; }

					elseif ($type == "DATEMONTHRANGE" || $type == "DATEQTRRANGE" || $type == "DATEYEARRANGE") { $date = "RANGE"; }

					if ($date) {

						if ($_POST["filter_submit"]) {

							$from = form_format ("", "filter_datefrom".$key, "DATE"); //column, name, type

							if ($date == "RANGE") { $to = form_format ("", "filter_dateto".$key, "DATE"); }

							elseif ($type == "DATEMONTH") { $to = dateJump ($from, "+", "MONTH"); } 

							elseif ($type == "DATEQTR") { $to = dateJump ($from, "+", "QTR"); } 

							elseif ($type == "DATEYEAR") { $to = dateJump ($from, "+", "YEAR"); }

							if (dateValid ($from) && dateValid ($to)) { 

								if ($tabCOL["date"]) {

									$proceed = TRUE;

									$last = $from;

									while ($last <= $to) {

										$valueCOL = $last." to ";

										if ($tabCOL["option"] == "DATEMONTH") { $last = dateJump ($last, "+", "MONTH"); } 

										elseif ($tabCOL["option"] == "DATEQTR") { $last = dateJump ($last, "+", "QTR"); } 

										elseif ($tabCOL["option"] == "DATEYEAR") { $last = dateJump ($last, "+", "YEAR"); }

										$tabCOL["optionDATE"][] = $valueCOL.dateJump ($last, "-", "DAY");     }

									//foreach ($tabCOL["optionDATE"] as $value) { echo $value." | "; }     

									}

								else { $whereCOL[] = $col." >= '".$from."' AND ".$col." < '".$to."'"; }     }     }

						echo "<fieldset class=\"filter\">";

						$fieldtype = str_replace ("RANGE", "", $type);

						if (!$label) { $label = "Date"; }

						if ($date == "RANGE") { $label .= " from"; }

						echo form_print ("filter_datefrom".$key, $fieldtype." FLOAT", $label, $from, (date("Y") - 2)."-".(date("Y") + 1));

						if ($date == "RANGE") { echo form_print ("filter_dateto".$key, $fieldtype." FLOAT", "to", $to, (date("Y") - 2)."-".(date("Y") + 1)); }

						echo form_print ("filter_submit", "SUBMIT buttonimg buttonimgnext", "", "Submit", "");

						echo "</fieldset>";     }     }

				echo form_end();

				if ($whereCOL) { $whereCOL = " AND ".implode (" AND ", $whereCOL); }     }

//proceed

			if ($proceed) {

//row header		

				$queryROW = mysql_query ("SELECT ".$tabROW["pk"].", ".$tabROW["label"]." FROM ".$tabROW["table"]." WHERE ".$tabROW["condition"]);

				//echo "<p>SELECT ".$tabROW["pk"].", ".$tabROW["label"]." FROM ".$tabROW["table"]." WHERE ".$tabROW["condition"]."</p>";

				if (mysql_num_rows ($queryROW)) { 

//th

					$count = 0;

					echo "<div class=\"row rowscrollx\">";

					echo "<table class=\"row\">";

					echo "<tr>";

					echo "<th>&nbsp;</th>";

					if ($tabCOL["date"]) { $tabCOL["option"] = $tabCOL["optionDATE"]; }

					foreach ($tabCOL["option"] as $valueCOL) { //2010-01-01 to 2011-01-01

						if ($tabCOL["date"]) { $valueCOL = explode(" to ", $valueCOL); echo "<th>".dateLabel ($valueCOL[0], $tabCOL["date"])."</th>"; }

						else { echo "<th>".$valueCOL."</th>"; }     }

					if ($totalROW["type"]) { 

						if ($totalROW["label"]) { $label = $totalROW["label"]; }

						else { $label = "Total"; }

						echo "<th>".$label."</th>";     }

					echo "</tr>";	

//td

					while ($resultROW = mysql_fetch_array ($queryROW)) {

						$count ++;

						$rowPK = $resultROW[$tabROW["pk"]];

						$rowLABEL = $resultROW[concatLabel ($tabROW["label"])];

						echo "<tr>";

						echo "<td>".$rowLABEL."</td>";

						foreach ($tabCOL["option"] as $col) {

							if ($tabCOL["date"]) { 

								$colDATE = explode (" to ", $col);

								$queryCELL = mysql_query ("SELECT ".$tabCOL["value"]." FROM ".$tabCOL["table"]." WHERE ".$tabCOL["fk"]." = ".$rowPK." AND ".$tabCOL["col"]." >= '".$colDATE[0]."' AND ".$tabCOL["col"]." < '".$colDATE[1]."'".$whereCOL);     }

							else { $queryCELL = mysql_query ("SELECT ".$tabCOL["value"]." FROM ".$tabCOL["table"]." WHERE ".$tabCOL["fk"]." = ".$rowPK." AND ".$tabCOL["col"]." = '$col'".$whereCOL); }

							//echo "<p>SELECT ".$tabCOL["value"]." FROM ".$tabCOL["table"]." WHERE ".$tabCOL["fk"]." = ".$rowPK." AND ".$tabCOL["col"]." = '$col'".$whereCOL."</p>";

//cell						

							$sumCELL = 0;

							if (mysql_num_rows ($queryCELL)) {

								while ($resultCELL = mysql_fetch_array ($queryCELL)) { 

									$cellVALUE = $resultCELL[$tabCOL["value"]];

									if (is_numeric ($cellVALUE)) { $sumCELL += $cellVALUE; }     }     }

							echo "<td>".numPrint ($sumCELL, $tabROW["format"])."</td>";

							$sumCOL[$col][] = $sumCELL;

							$sumROW[$count][] = $sumCELL;     }

//rowtotal				

						if ($totalROW["type"]) { 

							$total = 0;

							if ($totalROW["type"] == "SUM") { foreach($sumROW[$count] as $value) { $total += $value; } }

							else { $total = stringReplaceCalc ($totalROW["type"], $sumROW[$count]); }

							echo "<td>".numPrint ($total, $totalROW["format"])."</td>";

							$sumROWEND[] = $total; }

						echo "</tr>";     }

//coltotal					

					if ($totalCOL["type"]) {

						if ($totalCOL["label"]) { $label = $totalCOL["label"]; }

						elseif ($totalCOL["type"] == "SUM") { $label = "Total"; }

						elseif ($totalCOL["type"] == "AVG") { $label = "Average"; }

						elseif ($totalCOL["type"] == "MIN") { $label = "Lowest"; }

						elseif ($totalCOL["type"] == "MAX") { $label = "Highest"; }

						

						foreach ($sumCOL as $col) {

							$total = 0;

							$count = 0;

							foreach ($col as $value) {

								$count ++;

								if ($totalCOL["type"] == "SUM" && is_numeric ($value)) { $total += $value; }

								if ($totalCOL["type"] == "AVG" && is_numeric ($value)) { $total += $value; }

								if ($totalCOL["type"] == "MIN" && is_numeric ($value) && ($value < $total || $count == 1)) { $total = $value; }

								if ($totalCOL["type"] == "MAX" && is_numeric ($value) && ($value > $total || $count == 1)) { $total = $value; }     }

							if ($totalCOL["type"] == "AVG") { $total = $total / $count; }

							$totalTABLE[] = $total;     }



						echo "<tr class=\"total\"><td>".$label."</td>";

						foreach ($totalTABLE as $value) { echo "<td>".numPrint ($value, $totalCOL["format"])."</td>"; }

						if ($totalROW["type"]) { 

							$total = 0;

							$count = 0;

							foreach ($sumROWEND as $value) {

								$count ++;

								if ($totalCOL["type"] == "SUM" && is_numeric ($value)) { $total += $value; }

								if ($totalCOL["type"] == "AVG" && is_numeric ($value)) { $total += $value; }

								if ($totalCOL["type"] == "MIN" && is_numeric ($value) && ($value < $total || $count == 1)) { $total = $value; }

								if ($totalCOL["type"] == "MAX" && is_numeric ($value) && ($value > $total || $count == 1)) { $total = $value; }     }

							if ($totalCOL["type"] == "AVG") { $total = $total / $count; }

							echo "<td>".numPrint ($total, $totalROW["format"])."</td>";     }

						echo "</tr>";     }

//end					

					echo "</table>";

					echo "</div>";     }     }     }

?>