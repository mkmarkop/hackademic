<?php
include_once dirname(__FILE__) . '/../../init.php';
require_once(HACKADEMIC_PATH . "pages/challenge_monitor.php");
define('AUTHOR_EMAIL', 'ilikemybike@yuhuu.com');
define('AUTHOR_NAME', 'Matthew Potato');
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FakeNews Challenge</title>
    <meta name="author" content="Marko Pranjic">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<nav class="light-blue lighten-1">
    <div class="nav-wrapper container">
        <a href="index.php" class="brand-logo">FakeNews</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li>
                <form method="post" action="index.php">
                    <div class="input-field">
                        <input id="search" placeholder="Search..." type="search" name="wantToFind" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </li>
            <li><a href="index.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <ul id="nav-mobile" class="sidenav">
            <li><a href="index.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li>
                <form method="post" action="index.php">
                    <div class="input-field">
                        <input id="search" placeholder="Search..." type="search" name="wantToFind" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </li>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
</nav>


<div class="container">
    <div class="row">
        <?php
        $won = false;
        if (isset($_POST['email']) && isset($_POST['message'])) {
            $email = trim($_POST['email']);
            $message = trim($_POST['message']);

            if ($email === AUTHOR_EMAIL && $message === AUTHOR_NAME) {
                $monitor->update(CHALLENGE_SUCCESS, $_GET);
                $won = true;
                ?>
                <div class="col s12" style="padding: 0;">
                    <div class="collection">
                        <div class='collection-item'>
                            <h4>Congratulations</h4>
                            <p class='grey-text lighten-1-text'>Mr. X</p>
                            <h6>Your reward has been sent to your bank account.</h6>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                $monitor->update(CHALLENGE_FAILURE, $_GET);
            }
        }

        if (!$won) {
            ?>
            <form class="col s12" method="post" style="padding: 0;">
                <div class="row">
                    <div class="input-field col s12">
                        <input type="email" name="email" id="email">
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" name="message" id="message">
                        <label for="message">Message</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 center">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </div>
            </form>
            <?php
        } ?>
    </div>
</div>

<footer class="page-footer transparent">
    <div class="footer-copyright grey darken-1">
        <div class="container">
            Made with <a href="http://materializecss.com" class="grey-text lighten-4-text">Materialize</a>
            <span class="right">BadSoftware Corp.</span>
        </div>
    </div>
</footer>
<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
</html>
