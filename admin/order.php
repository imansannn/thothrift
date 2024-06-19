<?php
	include("../function/session.php");
	include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>THO THRIFT</title>
	<link rel = "stylesheet" type = "text/css" href="../css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<script src="../js/bootstrap.js"></script>
	<script src="../js/jquery-1.7.2.min.js"></script>
	<script src="../js/carousel.js"></script>
	<script src="../js/button.js"></script>
	<script src="../js/dropdown.js"></script>
	<script src="../js/tab.js"></script>
	<script src="../js/tooltip.js"></script>
	<script src="../js/popover.js"></script>
	<script src="../js/collapse.js"></script>
	<script src="../js/modal.js"></script>
	<script src="../js/scrollspy.js"></script>
	<script src="../js/alert.js"></script>
	<script src="../js/transition.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../javascripts/filter.js" type="text/javascript" charset="utf-8"></script>
	<script src="../jscript/jquery-1.9.1.js" type="text/javascript"></script>

		<!--Le Facebox-->
		<link href="../facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
		<script src="../facefiles/jquery-1.9.js" type="text/javascript"></script>
		<script src="../facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
		<script src="../facefiles/facebox.js" type="text/javascript"></script>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox()
		})
		</script>

		<script language="javascript" type="text/javascript">
        function printFunc(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;


        }
		</script>
</head>
<body>
	<div id="header" style="position:fixed;">
		<img src="../img/logo.jpg">
		<label>THO THRIFT</label>

			<?php
				$id = (int) $_SESSION['id'];

					$query = $conn->query ("SELECT * FROM admin WHERE adminid = '$id' ") or die (mysqli_error());
					$fetch = $query->fetch_array ();
			?>

			<ul>
				<li><a href="../function/admin_logout.php"><i class="icon-off icon-white"></i>logout</a></li>
				<li>Welcome:&nbsp;&nbsp;&nbsp;<i class="icon-user icon-white"></i><?php echo $fetch['username']; ?></a></li>
			</ul>
	</div>

	<br>

	<div id="leftnav">
		<ul>
			
		<li><a href="#">Products</a>
				<ul>
					<li><a href="admin_feature.php "style="font-size:15px; margin-left:15px;">Featured</a></li>
					<li><a href="admin_top.php "style="font-size:15px; margin-left:15px;">Top</a></li>
					<li><a href="admin_bottom.php "style="font-size:15px; margin-left:15px;">Bottom</a></li>
					<li><a href="admin_cap.php" style="font-size:15px; margin-left:15px;">Cap</a></li>
					
				</ul>
			</li>
			<li><a href="transaction.php">Transactions</a></li>
			<li><a href="customer.php">Customers</a></li>
			<li><a href="message.php">Messages</a></li>
			<li><a href="order.php">Orders</a></li>
		</ul>
	</div>

	<div id="rightcontent" style="position:absolute; top:10%;">
			<div class="alert alert-info"><center><h2>Orders</h2></center></div>
			<br />
			<br />
				<label  style="padding:5px; float:right;"><input type="text" name="filter" placeholder="Search Order Here..." id="filter"></label>
			<br />
				<div style='width:975px;' class="alert alert-info">
					  <table class="table table-hover">
						<thead>
							<th>Items</th>
							<th>Transaction No.</th>
							<th>AMOUNT</th>
						</thead>
						  <tbody>
							<?php
							$Q1 = $conn->query("SELECT * FROM transaction WHERE order_stat = 'Confirmed'");
							while($r1 = $Q1->fetch_array()){

							$tid = $r1['transaction_id'];

							$Q2 = $conn->query("SELECT * FROM transaction_detail LEFT JOIN product ON product.product_id = transaction_detail.product_id WHERE transaction_detail.transaction_id = '$tid' ");
							$r2 = $Q2->fetch_array();

							$pid = $r2['product_id'];
							$o_qty = $r2['order_qty'];

							$p_price = $r2['product_price'];
							$brand = $r2['product_name'];

							echo "<tr>";
							echo "<td>".$brand."</td>";
							echo "<td>".$tid."</td>";
							echo "<td>".formatMoney($p_price)."</td>";
							echo "</tr>";
							}

							$Q3 = $conn->query("SELECT sum(amount) FROM transaction WHERE order_stat = 'Confirmed'");
							while($r3 = $Q3->fetch_array()){

							$amnt = $r3['sum(amount)'];
							echo "<tr><td></td><td>TOTAL : </td> <td><b>RM ".formatMoney($amnt)."</b></td></tr>";
							}
							?>
						  </tbody>
					    </table>
				</div>

				<?php
function formatMoney($number, $fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
}
?>





	</div>
	</form>
			</div>
			</div>



</body>
</html>
