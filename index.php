<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <title>Login to File Browser</title>
</head>

<body>
    <h1 class="h1 text-center mt-5">Log In</h1>

    <div class="container d-flex justify-content-center mt-5">
        <form action="" method="POST" class="col-lg-4 col-sm-8 col-12">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="mantas">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" name="password" placeholder="test123">
            </div>
            <?php
            // login
            session_start();
            $msg = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['login']) && !empty($_POST['username'])  && !empty($_POST['password'])) {
                    if ($_POST['username'] == "mantas" && $_POST['password'] == "test123") {
                        $_SESSION['logged_in'] = true;
                        $_SESSION['timeout'] = time();
                        $_SESSION['username'] = "mantas";
                        header("Location: code.php");
                    } else {
                        $msg = 'Neteisingi prisijungimo duomenys';
                    }
                }
            }

            echo  "<p class=\"text-danger display-5 \">$msg</p>";
            ?>
            <input class="btn btn-primary mt-2 w-100" type="submit" value="Prisijungti" name="login">
           
        </form>


    </div>
</body>

</html>