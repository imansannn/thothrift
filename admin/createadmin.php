<!DOCTYPE html>
<html>
<head>
    <title>Create Admin - THO THRIFT</title>
    <link rel="icon" href="img/logo.jpg" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/carousel.js"></script>
    <script src="js/button.js"></script>
    <script src="js/dropdown.js"></script>
    <script src="js/tab.js"></script>
    <script src="js/tooltip.js"></script>
    <script src="js/popover.js"></script>
    <script src="js/collapse.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/scrollspy.js"></script>
    <script src="js/alert.js"></script>
    <script src="js/transition.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            var email = document.forms["adminForm"]["email"].value;
            var password = document.forms["adminForm"]["password"].value;
            var robotCheck = document.forms["adminForm"]["robotCheck"].checked;

            if (email == "" || password == "") {
                alert("Email and Password must be filled out");
                return false;
            }

            if (!robotCheck) {
                alert("Please verify you are not a robot");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div id="header">
        <img src="../img/logo.jpg">
        <label>THO THRIFT</label>
    </div>

    <div id="create-admin" style="max-width: 300px; margin: auto; padding-top: 50px;">
        <form name="adminForm" method="post" class="well" style="padding: 20px;" onsubmit="return validateForm()">
            <center>
                <legend>Create Admin</legend>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="robotCheck" required> I am not a robot
                </div>
                <button type="submit" name="create" class="btn btn-primary" style="width: 100%;">Create</button>
            </center>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
        // Database connection
        $host = 'localhost'; // Change as necessary
        $db = 'alphaware'; // Change as necessary
        $user = 'root'; // Change as necessary
        $pass = ''; // Change as necessary
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Insert admin into the database
        $stmt = $pdo->prepare('INSERT INTO admin (username, password) VALUES (?, ?)');
        $stmt->execute([$email, $password]);

        echo "<center><div class='alert alert-success' role='alert'>Admin account created successfully!</div></center>";
    }
    ?>
</body>
</html>
