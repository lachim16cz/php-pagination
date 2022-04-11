# PHP pagination
## Description
it is a simple paging script that splits a large number of records into individual pages.

## Result
![Application](../../img_description/pager_1.png)
![Connection](../../img_description/pager_2.png)

## Example of use
```
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
```

## CSS Example
```
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
```
