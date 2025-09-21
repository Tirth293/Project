<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {	
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $adminid = $_SESSION['alogin'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        // Validate user input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format');</script>";
        } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
            echo "<script>alert('Invalid mobile number');</script>";
        } else {
            $sql = "UPDATE admin SET Name=:name, EmailId=:email, MobileNumber=:mobile WHERE UserName=:adminid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
            
            if ($query->execute()) {
                echo "<script>alert('Profile has been updated.');</script>";
                echo "<script>window.location.href = 'profile.php';</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Tours and Travels Website | Admin Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/morris.css" type="text/css"/>
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-2.1.4.min.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <!-- Header -->
                <?php include('includes/header.php'); ?>
                <div class="clearfix"> </div>
            </div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Admin Profile</li>
            </ol>
            <div class="grid-form">
                <div class="grid-form1">
                    <div class="panel-body">
                        <form name="chngpwd" method="post" class="form-horizontal">
                            <?php 
                            $adminid = $_SESSION['alogin'];
                            $sql = "SELECT * FROM admin WHERE UserName=:adminid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) {
                            ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">User Name</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control1" type="text" value="<?php echo htmlentities($result->UserName); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Name</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control1" type="text" name="name" value="<?php echo htmlentities($result->Name); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        <input class="form-control1" type="email" name="email" value="<?php echo htmlentities($result->EmailId); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Mobile No</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                        <input class="form-control1" type="text" name="mobile" value="<?php echo htmlentities($result->MobileNumber); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <?php }} ?>
                            <div class="col-sm-8 col-sm-offset-2">
                                <button type="submit" name="submit" class="btn-primary btn">Submit</button>
                                <button type="reset" class="btn-inverse btn">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <?php include('includes/footer.php'); ?>
    </div>
    <!-- Sidebar -->
    <?php include('includes/sidebarmenu.php'); ?>
    <script>
        var toggle = true;
        $(".sidebar-icon").click(function() {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({ "position": "absolute" });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function() {
                    $("#menu span").css({ "position": "relative" });
                }, 400);
            }
            toggle = !toggle;
        });
    </script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
