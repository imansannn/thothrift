<?php
    include("../function/session.php");
    include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>THO THRIFT</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
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
    <link href="../facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="../facefiles/jquery-1.9.js" type="text/javascript"></script>
    <script src="../facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
    <script src="../facefiles/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox()
        })
    </script>
</head>
<body>
    <div id="header" style="position:fixed;">
        <img src="../img/logo.jpg">
        <label>THO THRIFT</label>

        <?php
            $id = (int) $_SESSION['id'];

            $stmt = $conn->prepare("SELECT * FROM admin WHERE adminid = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $fetch = $result->fetch_array();
        ?>

        <ul>
            <li><a href="../function/admin_logout.php"><i class="icon-off icon-white"></i>logout</a></li>
            <li>Welcome:&nbsp;&nbsp;&nbsp;<i class="icon-user icon-white"></i><?php echo htmlspecialchars($fetch['username'], ENT_QUOTES, 'UTF-8'); ?></li>
        </ul>
    </div>

    <br>

    <div id="leftnav">
        <ul>
            <li><a href="#">Products</a>
                <ul>
                    <li><a href="admin_feature.php" style="font-size:15px; margin-left:15px;">Featured</a></li>
                    <li><a href="admin_top.php" style="font-size:15px; margin-left:15px;">Top</a></li>
                    <li><a href="admin_bottom.php" style="font-size:15px; margin-left:15px;">Bottom</a></li>
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
        <div class="alert alert-info"><center><h2>Transactions</h2></center></div>
        <br />
        <label style="padding:5px; float:right;"><input type="text" name="filter" placeholder="Search Transactions here..." id="filter"></label>
        <br />

        <div class="alert alert-info">
            <table class="table table-hover" style="background-color:;">
                <thead>
                    <tr style="font-size:16px;">
                        <th>ID</th>
                        <th>DATE</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = $conn->query("SELECT * FROM transaction LEFT JOIN customer ON customer.customerid = transaction.customerid") or die(mysqli_error());
                        while ($fetch = $query->fetch_array()) {
                            $id = $fetch['transaction_id'];
                            $amnt = $fetch['amount'];
                            $o_stat = $fetch['order_stat'];
                            $o_date = $fetch['order_date'];
                            $name = htmlspecialchars($fetch['firstname'].' '.$fetch['lastname'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $o_date; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $amnt; ?></td>
                        <td><?php echo $o_stat; ?></td>
                        <td> 
                            <?php if ($o_stat == 'Confirmed' || $o_stat == 'Cancelled'){
                                 ?> <a href="receipt.php?tid=<?php echo $id; ?>">View</a>
                            <?php } else { ?> 
                            <?php } ?>
                            <?php if ($o_stat == 'Confirmed' || $o_stat == 'Cancelled') { ?>
                            <?php } else { ?>
                                <a class="btn btn-mini btn-info" href="confirm.php?id=<?php echo $id; ?>">Confirm</a>
                                | <a class="btn btn-mini btn-danger" href="cancel.php?id=<?php echo $id; ?>">Cancel</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
        /* Stock in */
        if (isset($_POST['stockin'])) {
            $pid = $_POST['pid'];

            $stmt = $conn->prepare("SELECT * FROM stock WHERE product_id = ?");
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array();

            $old_stck = $row['qty'];
            $new_stck = $_POST['new_stck'];
            $total = $old_stck + $new_stck;

            $stmt = $conn->prepare("UPDATE stock SET qty = ? WHERE product_id = ?");
            $stmt->bind_param("is", $total, $pid);
            $stmt->execute();

            header("Location: admin_bottom.php");
        }

        /* Stock out */
        if (isset($_POST['stockout'])) {
            $pid = $_POST['pid'];

            $stmt = $conn->prepare("SELECT * FROM stock WHERE product_id = ?");
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array();

            $old_stck = $row['qty'];
            $new_stck = $_POST['new_stck'];
            $total = $old_stck - $new_stck;

            $stmt = $conn->prepare("UPDATE stock SET qty = ? WHERE product_id = ?");
            $stmt->bind_param("is", $total, $pid);
            $stmt->execute();

            header("Location: admin_bottom.php");
        }
    ?>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('.remove').click(function() {
            var id = $(this).attr("id");

            if (confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    type: "POST",
                    url: "../function/remove.php",
                    data: ({ id: id }),
                    cache: false,
                    success: function(html) {
                        $(".del" + id).fadeOut(2000, function() { $(this).remove(); });
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>
