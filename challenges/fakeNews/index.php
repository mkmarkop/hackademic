<?php
include_once dirname(__FILE__) . '/../../init.php';
session_start();
require_once(HACKADEMIC_PATH . "pages/challenge_monitor.php");
$monitor->update(CHALLENGE_INIT, $_GET);
$_SESSION['init'] = true;

define('PORTAL_DB_NAME', 'hack_fakeNews');
define('PORTAL_DB_USER', 'hack_yeah');
define('PORTAL_DB_PASSWORD', 'topsecret123');
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

<?php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$db_selected = $conn->select_db(PORTAL_DB_NAME);

if (!$db_selected) {
    $conn->query("CREATE DATABASE " . PORTAL_DB_NAME);
    $conn->query("CREATE USER '" . PORTAL_DB_USER
        . "'@'" . DB_HOST . "' IDENTIFIED BY '" . PORTAL_DB_PASSWORD . "'");

    $conn->query("REVOKE ALL PRIVILEGES, GRANT OPTION FROM '"
        . PORTAL_DB_USER . "'@'" . DB_HOST . "'");

    $conn->query("GRANT SELECT ON " . PORTAL_DB_NAME . ".* TO '"
        . PORTAL_DB_USER . "'@'" . DB_HOST);
    $conn->query("FLUSH PRIVILEGES");
}

$conn->select_db(PORTAL_DB_NAME);

$query = $conn->query("SELECT email, first_name, last_name FROM authors");
if (!$query) {
    $table = "CREATE TABLE IF NOT EXISTS `authors` (
    `aid` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
    PRIMARY KEY (`aid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
    $conn->query($table);

    $sql = "INSERT INTO `authors` (`aid`, `email`, `first_name`, `last_name`) VALUES (NULL, 'xlee89@kong.com', 'Xin', 'Lee')";
    $conn->query($sql);

    $sql = "INSERT INTO `authors` (`aid`, `email`, `first_name`, `last_name`) VALUES (NULL, 'ilikemybike@yuhuu.com', 'Matthew', 'Potato')";
    $conn->query($sql);

    $sql = "INSERT INTO `authors` (`aid`, `email`, `first_name`, `last_name`) VALUES (NULL, 'arriva@sangria.com', 'Carlos', 'Fontana')";
    $conn->query($sql);
}

$query = $conn->query("SELECT * FROM news");
if (!$query) {
    $table = "CREATE TABLE IF NOT EXISTS news (
    `nid` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(140) NOT NULL,
    `content` varchar(1491) NOT NULL,
    `author` int(11) NOT NULL,
    PRIMARY KEY (`nid`),
    FOREIGN KEY (`author`) REFERENCES authors(`aid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
    $conn->query($table);

    $sql = "INSERT INTO `news` VALUES (
    NULL,
    'Cat videos increase happiness',
    'Watching cute cat videos and looking at their online pictures may not be a waste of time."
    ." It actually might increase your overall happiness, questionable research says.',
    1
    );";
    $conn->query($sql);

    $sql = "INSERT INTO `news` VALUES (
    NULL,
    'Business in ownership of Mr. X involved in drug selling',
    'MedPex, a company which is just one of many that Mr. X owns is being prosecuted for illegal drug smuggling."
    ." This business in his ownership is allegedly involved in a massive drug selling deal.',
    2
    );";
    $conn->query($sql);

    $sql = "INSERT INTO `news` VALUES (
    NULL,
    'Pain East has announced a new album',
    'A new album has just been announced by Pain East, a country star with rising popularity in whole country.',
    3
    );";
    $conn->query($sql);
}
?>

<div class="container">
    <div class="row">
        <div class="col s12" style="padding: 0;">
            <div class="collection">
                <?php
                $queryString = "SELECT news.title as title, news.content as content," .
                    " LEFT(authors.first_name, 1) as first_initial, LEFT(authors.last_name, 1) as second_initial" .
                    " FROM news INNER JOIN authors ON news.author=authors.aid";
                if (isset($_POST['wantToFind'])) {
                    $queryString = $queryString . " WHERE content LIKE '%" . $_POST['wantToFind'] . "%';";
                };
                $query = $conn->query($queryString);

                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        echo "<div class='collection-item'>";
                        echo "<h4>" . $row["title"] . "</h4>";
                        echo "<p class='grey-text lighten-1-text'>" . $row["first_initial"] . ". " . $row["second_initial"] . '.' . "</p>";
                        echo "<h6>" . $row["content"] . "</h6>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='collection-item'>";
                    echo "<h5>No results found for <i>" . $_POST['wantToFind'] . "</i></h5>";
                    echo "</div>";
                }

                $conn->close();
                ?>
            </div>
        </div>
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
