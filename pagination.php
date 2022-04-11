<?php
/*

**** ---- Author: http://michalmasarik.cz/ ---- ****

------------------------------------------------------------------------

$_GET['p'] is page number

------------------------------------------------------------------------

**** Example of use ****

$name_table = "table_name";
$select_sql = "*";
$where = "WHERE id='1'"; // Can be empty
$order = "ORDER BY id DESC"; // Can be empty
$records_per_page = 2; // Numeric value

include 'pagination.php'; 

while($order_data = mysqli_fetch_array($select_from_sql)) {
$ip = $order_data['ip'];
$date = $order_data['date'];
$time = $order_data['time'];
$success = $order_data['success'];

echo "$ip<br>$date<br>$time<br>$success<br>";
}

echo "<div class='pager_block'>".$arrows_start.$numbers.$arrows_end."</div>";

------------------------------------------------------------------------
**** CSS Classes ****

.pager_block {
	display:block;
	text-align:center;
}
.pager_block a {
	display:inline-block;
	margin:3px;
	font-weight:normal;
}
.pager_block p {
	display:inline-block;
	font-weight:bold;
	margin:3px;
}
a.page_arrow {

}
a.skip_arrow {
	text-decoration: underline;
}
a.page_numbers {
	text-decoration: underline;
}
------------------------------------------------------------------------
*/

/* Check input variables */
if(!isset($name_table)) {
	echo "Variable with table name is not set.";
} else if(!isset($where)) {
	echo "Variable with WHERE condition is not set.";
} else if(!isset($select_sql)) {
	echo "Variable with select details is not SET.";
} else if(!isset($order)) {
	echo "Variable named ORDER is not set.";
} else if(!isset($records_per_page)) {
	echo "Record per page variable is not set.";
} else if((int)$records_per_page < 1) {
	echo "Number of records per page must be greater than 0.";
} else if($name_table == "") {
	echo "Variable with table name cannot be empty.";
} else if($select_sql == "") {
	echo "Variable with select details cannot be empty.";
} else {
	
	/* Arrows and numbers */
	$arrows_start = "";
	$numbers = "";
	$arrows_end = "";

	/* Check if variable set */
	if(count($_GET) != 0) {
		if(isset($_GET['p']) && (count($_GET) == 1)) {
			$character_get = "?";
		} else {
			$character_get = "&";
		}
	} else {
		$character_get = "?";
	}

	/* Check GET value */
	$get_value = 1;
	if(isset($_GET['p'])) {
		if((int)$_GET['p'] > 0) {  
			$get_value = (int)$_GET['p'];
		}
	}

	/* Count LIMIT */
	if($get_value == 1) {
		$count_start_limit = 0;
	} else {
		$count_limit_1 = $get_value * $records_per_page;
		$count_start_limit = $count_limit_1 - $records_per_page;
	} 
		$limit = "LIMIT $count_start_limit, $records_per_page";

	/* SELECT data by LIMIT */
	if($where != "") {
		$where = $where." ";
	}
	if($order != "") {
		$order = $order." ";
	}
	
	$select_from_sql = mysqli_query($pripoj, "SELECT ".$select_sql." FROM ".$name_table." ".$where.$order.$limit) or die(mysqli_error($pripoj));

	/* Count all pages */
	$select_to_count = mysqli_query($pripoj, "SELECT id FROM ".$name_table." ".$where) or die(mysqli_error($pripoj));
	$count_rows = mysqli_num_rows($select_to_count);
	
	$number_of_pages = ceil($count_rows / $records_per_page);
	
	
	if($number_of_pages > 0) {
		/* URL */	
		if(isset($_GET['p'])) {
			$get_url = $_SERVER["REQUEST_URI"];
			$page_remove = "p";
			$removed_url = preg_replace('/([?&])'.$page_remove.'=[^&]+(&|$)/','$1',$get_url);
	
			if(substr($removed_url, -1) == "&") {
				$removed_url = substr_replace($removed_url ,"",-1);
			}
	
			if(substr($removed_url, -1) == "?") {
				$removed_url = substr_replace($removed_url ,"",-1);
			}
		} else {
			$removed_url = $_SERVER["REQUEST_URI"];
		}
	
	
		/* More pages - dots */
		if(($get_value >= 4) && ($number_of_pages > 5)) {
			$dots_left = " ... ";
		} else {
			$dots_left = "";
		}

		if(($number_of_pages > 5) && ($get_value < ($number_of_pages - 2))) {
			$dots_right = " ... ";
		} else {
			$dots_right = "";
		}
	
		/* Page numbers */
		$first_page = "<a href='$removed_url".$character_get."p=1' class='page_numbers'>1</a>";
		$second_page = "";
		$third_page = "";
		$fourth_page = "";
		$fifth_page = "";
	
		if($number_of_pages >= 2) {
			$second_page = "<a href='".$removed_url.$character_get."p=2' class='page_numbers'>2</a>";
		}
		if($number_of_pages >= 3) {
			$third_page = "<a href='".$removed_url.$character_get."p=3' class='page_numbers'>3</a>";
		}
		if($number_of_pages >= 4) {
			$fourth_page = "<a href='".$removed_url.$character_get."p=4' class='page_numbers'>4</a>";
		}
		if($number_of_pages >= 5) {
			$fifth_page = "<a href='".$removed_url.$character_get."p=5' class='page_numbers'>5</a>";
		}

		if($get_value == 1) {
			$first_page = "<p>1</p>";
		} else if($get_value == 2) {
			$second_page = "<p>2</p>";
		} else if($get_value == 3) {
			$third_page = "<p>3</p>";		
		} else if($get_value == ($number_of_pages - 1)) {
			$count_first = $get_value - 3;
			$count_second = $get_value - 2;
			$count_third = $get_value - 1;
			$count_fifth = $get_value + 1;
			$first_page = "<a href='".$removed_url.$character_get."p=".$count_first."' class='page_numbers'>".$count_first."</a>";
			$second_page = "<a href='".$removed_url.$character_get."p=".$count_second."' class='page_numbers'>".$count_second."</a>";
			$third_page = "<a href='".$removed_url.$character_get."p=".$count_third."' class='page_numbers'>".$count_third."</a>";
			$fourth_page = "<p>".$get_value."</p>";
			$fifth_page = "<a href='".$removed_url.$character_get."p=".$count_fifth."' class='page_numbers'>".$count_fifth."</a>";
		} else if($get_value == $number_of_pages) {
			$count_first = $get_value - 4;
			$count_second = $get_value - 3;
			$count_third = $get_value - 2;
			$count_fourth = $get_value - 1;
			if($count_first == 0) {
				$first_page = "";
			} else {
				$first_page = "<a href='".$removed_url.$character_get."p=".$count_first."' class='page_numbers'>".$count_first."</a>";
			}
			$second_page = "<a href='".$removed_url.$character_get."p=".$count_second."' class='page_numbers'>".$count_second."</a>";
			$third_page = "<a href='".$removed_url.$character_get."p=".$count_third."' class='page_numbers'>".$count_third."</a>";
			$fourth_page = "<a href='".$removed_url.$character_get."p=".$count_fourth."' class='page_numbers'>".$count_fourth."</a>";
			$fifth_page = "<p>".$get_value."</p>";			
		} else {
			$count_first = $get_value - 2;
			$count_second = $get_value - 1;
			$count_fourth = $get_value + 1;
			$count_fifth = $get_value + 2;
			$first_page = "<a href='".$removed_url.$character_get."p=".$count_first."' class='page_numbers'>".$count_first."</a>";
			$second_page = "<a href='".$removed_url.$character_get."p=".$count_second."' class='page_numbers'>".$count_second."</a>";
			$third_page = "<p>".$get_value."</p>";
			$fourth_page = "<a href='".$removed_url.$character_get."p=".$count_fourth."' class='page_numbers'>".$count_fourth."</a>";
			$fifth_page = "<a href='".$removed_url.$character_get."p=".$count_fifth."' class='page_numbers'>".$count_fifth."</a>";	
		}

		$numbers = $dots_left.$first_page.$second_page.$third_page.$fourth_page.$fifth_page.$dots_right;


		/* Back arrows */
		$back_to_first_page = "";
		$previous_page = "";
	
		$count_back_page = $get_value - 1;
		if($get_value == 1) {
			$back_to_first_page = "";
			$previous_page = "";
		} else if($get_value == 2) {
			$previous_page = "<a href='".$removed_url.$character_get."p=".$count_back_page."' class='page_arrow'>&lt;</a> ";
		} else if(($get_value >= 4) && ($number_of_pages > 5)){
			$back_to_first_page = "<a href='".$removed_url."' class='skip_arrow'>1</a> ";
			$previous_page = "<a href='".$removed_url.$character_get."p=".$count_back_page."' class='page_arrow'>&lt;</a> ";	
		} else {
			$previous_page = "<a href='".$removed_url.$character_get."p=".$count_back_page."' class='page_arrow'>&lt;</a> ";
		}

		$arrows_start = $back_to_first_page." ".$previous_page;


		/* Forward arrows */
		$last_page = "";
		$next_page = "";

		$count_next_page = $get_value + 1;

		if($get_value == $number_of_pages) {
			$last_page = "";
			$next_page = "";
		} else if($get_value == ($number_of_pages - 1)) {
			$next_page = " <a href='".$removed_url.$character_get."p=".$count_next_page."' class='page_arrow'>&gt;</a>";
		} else if(($get_value <= ($number_of_pages - 3)) && ($number_of_pages > 5)) {
			$next_page = " <a href='".$removed_url.$character_get."p=".$count_next_page."' class='page_arrow'>&gt;</a>";
			$last_page = " <a href='".$removed_url.$character_get."p=".$number_of_pages."' class='skip_arrow'>".$number_of_pages."</a> ";
		} else {
			$next_page = " <a href='".$removed_url.$character_get."p=".$count_next_page."' class='page_arrow'>&gt;</a>";
		}

		$arrows_end = $next_page." ".$last_page;
	}
}
?>
