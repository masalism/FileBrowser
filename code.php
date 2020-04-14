<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>File Browser</title>
</head>

<body>
    <div class="container">
        <?php
        session_start();

        if (!$_SESSION['logged_in']) {
            header("Location: index.php");
            exit;
        }

        // log out
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['logout'])) {
                session_start();
                unset($_SESSION['username']);
                unset($_SESSION['password']);
                unset($_SESSION['logged_in']);
                header("Location: index.php");
            }
        }

        // Foto ikelimas
        $errorMessage = '';

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            if ($file['size'] > 10 * 1024 * 1024) {
                $errorMessage = "You can not upload more than 10 mb files";
            } else if (!in_array($ext, ['png', 'jpg', 'svg', 'jpeg'])) {
                $errorMessage = "You can only upload images";
            } else {
                move_uploaded_file($_FILES['file']['tmp_name'], "./" . $_GET['path'] . $_FILES['file']['name']);
            }
        }

        // Failo trynimas
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['delete'])) {
                $deleteFile = './' . $_GET['path'] . $_POST['delete'];
                if (is_file($deleteFile)) {
                    unlink($deleteFile);
                }
            }
        }

        // dir kurimas
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['create']) && $_POST['create'] != "") {
                $createDirectory = './' . $_GET['path'] . $_POST['create'];
                if (!is_dir($createDirectory))
                    mkdir($createDirectory, 0777, true);
            }
        }

        // failo parsisiuntimas
        if (isset($_POST['download'])) {
            print('Path to download: ' . './' . $_GET["path"] . $_POST['download']);
            $file = './' . $_GET["path"] . $_POST['download'];
            $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileToDownloadEscaped));
            flush();
            readfile($fileToDownloadEscaped);
            exit;
        }

        // Direktorijos spausdinimas
        $path = './' . $_GET["path"];
        $data = scandir($path);

        echo '<h2 class="mt-3">Directory: ' . str_replace('?path=', '/', $_SERVER['REQUEST_URI']) . "</h2>";

        ?>

    </div>
    <div class="container">
        <?php

        echo "<table class=\"table table-striped mt-5\"><thead class=\"thead-dark\"><tr><th>Type</th><th>Name<th>Action</th></th></thead>";
        echo "<tbody>";

        // $backButton;

        foreach ($data as $element) {
            $value = (is_dir($path . $element)
                ? '<a href="' . (isset($_GET['path'])
                    ? $_SERVER['REQUEST_URI'] . $element . '/'
                    : $_SERVER['REQUEST_URI'] . '?path=' . $element . '/') . '">' . $element . '</a>'
                : $element);
            $type = is_dir($path . $element) ? "Directory" : "File";
            $options = is_dir($path . $element) ? null :
                '<form action=" " method="POST">
                <input type="hidden" name="delete" value=' . $element . '>
                <button type="submit" class="btn btn-danger btn-sm mr-2">Delete</button>
            </form>
            <form action="" method="POST">
                <input type="hidden" name="download" value=' . $element . '>
                <button type="submit" class="btn btn-primary btn-sm">Download</button>
            </form>';

            // if ($element == "..") {
            //     $backButton = $element;
            // }
            
            if ($element != ".." && $element != ".") {
                echo "<tr>";
                echo "<td>$type</td>";
                echo "<td>$value</td>";
                echo "<td class=\"d-flex\">$options</td>";
                echo "</tr>";
            }
        }

        echo "</tbody>";
        echo "</table>";

        ?>

        <?php
        // Gristi atgal
        $query = explode("/", $_SERVER['QUERY_STRING']);
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        array_pop($query);
        if (count($query) == 1) {
            $url = explode("?", $_SERVER['REQUEST_URI']);
            // echo "<pre>";
            // var_dump($url);
            // echo "</pre>";
            array_pop($url);
            $newURL = implode("/", $url);
            // echo "<pre>";
            // var_dump($newURL);
            // echo "</pre>";
        } else {
            $url = explode("/", $_SERVER['REQUEST_URI']);
            array_pop($url);
            array_pop($url);
            $newURL = implode("/", $url) . "/";
            // echo "<pre>";
            // var_dump($newURL);
            // echo "</pre>";
        }
        ?>

        <a class="btn btn-success" href="<?php print($newURL) ?>">Grįžti</a>

        <form class="form-group mt-3 border rounded w-50 bg-light" action="" method="post" enctype="multipart/form-data">
            <input class="form-control-file" type="file" name="file"><br>
            <button class="btn btn-warning">Įkelti</button>
            <h3><?php echo $errorMessage ?></h3>
        </form>

        <form class="w-50 my-3" action=" " method="POST">
            <input class="form-control mb-2" name="create" placeholder="Direktorijos pavadinimas">
            <button type="submit" class="btn btn-secondary">Sukurti direktoriją</button>
        </form>

        <form class="logout" action="code.php" method="POST">
            <input type="hidden" name="logout">
            <button type="submit" class="btn btn-danger btn-lg mt-2">Atsijungti</button>
        </form>
    </div>



</body>

</html>