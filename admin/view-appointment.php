<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $cid = $_GET['viewid'];
        $remark = $_POST['remark'];
        $status = $_POST['status'];

        $query = mysqli_query($con, "UPDATE tblbook SET Remark='$remark', Status='$status', RemarkDate=NOW() WHERE ID='$cid'");
        if ($query) {
            echo '<script>alert("Remark and status have been updated.")</script>';
            echo "<script type='text/javascript'> document.location ='all-appointment.php'; </script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>BPMS || View Appointment</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script> new WOW().init(); </script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">View Appointment</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>View Appointment:</h4>

<?php
$cid = $_GET['viewid'];

// Get UserID of the current appointment
$uid_query = mysqli_query($con, "SELECT UserID FROM tblbook WHERE ID='$cid'");
$user_row = mysqli_fetch_array($uid_query);
$user_id = $user_row['UserID'];

// Count how many times this user was rejected
$rejection_query = mysqli_query($con, "SELECT COUNT(*) AS rejected_count FROM tblbook WHERE UserID='$user_id' AND Status='Rejected'");
$rejection_row = mysqli_fetch_array($rejection_query);
$rejected_count = $rejection_row['rejected_count'];

if ($rejected_count >= 2) {
    echo '<div class="alert alert-danger"><strong>Notice:</strong> This customer has been <strong>rejected '.$rejected_count.' times</strong>.</div>';
}

$ret = mysqli_query($con, "SELECT tbluser.FirstName, tbluser.LastName, tbluser.Email, tbluser.MobileNumber, tblbook.ID as bid, tblbook.AptNumber, tblbook.AptDate, tblbook.AptTime, tblbook.Message, tblbook.BookingDate, tblbook.Remark, tblbook.Status, tblbook.RemarkDate FROM tblbook JOIN tbluser ON tbluser.ID=tblbook.UserID WHERE tblbook.ID='$cid'");

while ($row = mysqli_fetch_array($ret)) {
?>
                        <table class="table table-bordered">
                            <tr><th>Appointment Number</th><td><?php echo $row['AptNumber']; ?></td></tr>
                            <tr><th>Name</th><td><?php echo $row['FirstName'] . " " . $row['LastName']; ?></td></tr>
                            <tr><th>Email</th><td><?php echo $row['Email']; ?></td></tr>
                            <tr><th>Mobile Number</th><td><?php echo $row['MobileNumber']; ?></td></tr>
                            <tr><th>Appointment Date</th><td><?php echo $row['AptDate']; ?></td></tr>
                            <tr><th>Appointment Time</th><td><?php echo $row['AptTime']; ?></td></tr>
                            <tr><th>Apply Date</th><td><?php echo $row['BookingDate']; ?></td></tr>
                            <tr><th>Status</th>
                                <td>
                                    <?php
                                    if ($row['Status'] == "") echo "Not Updated Yet";
                                    else echo $row['Status'];
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <table class="table table-bordered">
                            <?php if ($row['Status'] == "") { ?>
                            <form name="submit" method="post" enctype="multipart/form-data"> 
                                <tr>
                                    <th>Remark :</th>
                                    <td><textarea name="remark" rows="6" cols="14" class="form-control" required></textarea></td>
                                </tr>
                                <tr>
                                    <th>Status :</th>
                                    <td>
                                        <select name="status" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Selected">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr align="center">
                                    <td colspan="2"><button type="submit" name="submit" class="btn btn-primary">Submit</button></td>
                                </tr>
                            </form>
                            <?php } else { ?>
                                <tr><th>Remark</th><td><?php echo $row['Remark']; ?></td></tr>
                                <tr><th>Status</th><td><?php echo $row['Status']; ?></td></tr>
                                <tr><th>Remark date</th><td><?php echo $row['RemarkDate']; ?></td></tr>
                            <?php } ?>
                        </table>
<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;
        showLeftPush.onclick = function () {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };
        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
<?php } ?>
