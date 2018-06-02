<?php
if(!empty($_POST['text1'])) {
    // D'abord, je me connecte à la base de données.
    mysql_connect("localhost", "root", "");
    mysql_select_db("demo");

    // Je mets aussi certaines sécurités ici…
    $passe = mysql_real_escape_string(htmlspecialchars($_POST['number']));
    $passe2 = mysql_real_escape_string(htmlspecialchars($_POST['password']));
    if($passe == $passe2) {
        $pseudo = mysql_real_escape_string(htmlspecialchars($_POST['text1']));
        $email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
        // Je vais crypter le mot de passe.
        $passe = sha1($passe);

        mysql_query("INSERT INTO validation VALUES('', '$pseudo', '$passe', '$email')");
    }
    
    else {
        echo 'Les deux mots de passe que vous avez rentrés ne correspondent pas…';
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">
<!-- Head -->

<head>
    <title>Border Register Form Flat Responsive Widget Template :: w3layouts</title>
    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="Border Register Form a Responsive Web Template, Bootstrap Web Templates, Flat Web Templates, Android Compatible Web Template, Smartphone Compatible Web Template, Free Webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design">
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta-Tags -->
    <!-- Index-Page-CSS -->
    <link rel="stylesheet" href="csss/style.css" type="text/css" media="all">
    <!-- //Custom-Stylesheet-Links -->
    <!--fonts -->
    <!-- //fonts -->
    <link rel="stylesheet" href="csss/font-awesome.css" type="text/css" media="all">
    <!-- //Font-Awesome-File-Links -->
    
    <!-- Google fonts -->
    <link href="//fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;subset=latin-ext,vietnamese" rel="stylesheet">

    <!-- Google fonts -->

</head>
<!-- //Head -->
<!-- Body -->

<body>
    <h1 class="title-agile text-center">Veuillez Inscrivez vous!!</h1>
    <div class="content-w3ls">
        <div class="content-bottom">
            <h2>Register Here <i class="fa fa-hand-o-down" aria-hidden="true"></i></h2>
            <form action="#" method="post">
                <div class="field-group">
                    <span class="fa fa-user" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="text1" id="text1" type="text" value="" placeholder="Username" required>
                    </div>
                </div>
                <div class="field-group">
                    <span class="fa fa-envelope" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="email" id="email" type="email" value="" placeholder="Email" required>
                    </div>
                </div>
                <div class="field-group">
                    <span class="fa fa-lock" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="number" id="number" type="Password" value="" placeholder="password" required>
                    </div>
                </div>
                <div class="field-group">
                    <span class="fa fa-lock" aria-hidden="true"></span>
                    <div class="wthree-field">
                        <input name="password" id="myInput" type="text" placeholder="Confirm Password">
                    </div>
                </div>
                <div class="wthree-field">
                    <input id="saveForm" name="saveForm" type="submit" value="Register" />
                </div>
                <div class="account">
                    <p class="text-center">Already have an account ? <a href="#">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <div class="copyright text-center">
        <p>Â© 2018 Border Register Form. All rights reserved | Design by
            <a href="http://w3layouts.com">W3layouts</a>
        </p>
    </div>
</body>
<!-- //Body -->
</html>

