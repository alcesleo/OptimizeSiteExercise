<?php
    require_once( "get.php");
    require_once( "sec.php");
    checkUser();
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <title>Messy Labbage</title>

    <!-- CSS libs -->
    <link href="http://fonts.googleapis.com/css?family=Karla:400,700" rel="stylesheet" />
    <link href="http://fonts.googleapis.com/css?family=Wellfleet" rel="stylesheet" />
    <link href="css/screen.css" rel="stylesheet" media="screen" />
    <link href="css/lightbox.css" rel="stylesheet" media="screen" />

    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet" />

    <!-- Bootstrap CSS (for some reason needs to be after custom css) -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

    <!-- External CSS -->
    <link href="http://vhost3.lnu.se:20080/~1dv449/scrape/css.php?css=grid1" rel="stylesheet">
    <link href="http://vhost3.lnu.se:20080/~1dv449/scrape/css.php?css=grid2" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                <li>
                    <button class="btn" id="logout">Logga ut</button>
                </li>
            </ul>
            <h3 class="text-muted">Messy Labbage</h3>
        </div>

        <!-- page header -->
        <div class="jumbotron">

            <h1>Messy Labbage</h1>
            <p class="lead">Software developed by the MakeMyPageBetterPlease Company</p>

        </div>

        <div class="image-row">

            <!-- This holds all the links -->
            <div class="row">
                <div class="col-md-6">
                    <?php
                        /* Produces all the links to the producers */
                        require_once( "get.php");
                        $ps=getProducers();
                        foreach($ps as $p) {
                            echo( '<a onclick="changeProducer(' .$p[ "producerID"] . ');" href="#mess_anchor">' .$p[ "name"] . '</a><br />');
                        }
                    ?>
                </div>
                <div class="col-md-6">
                    <img src="img/pics/food.jpg" height="220px" />
                </div>
            </div>
            <div style="clear: both;"></div>

            <!-- This is the part that will be populated with data from AJAX -->
            <div id="mess_anchor"></div>
            <div id="mess_container">
                <div class="row">
                    <!-- Headline will be updated here -->
                    <div class="col-md-6" height="250px">
                        <h1 id="mess_p_headline"></h1>
                        <p id="mess_p_kontakt"></p>
                        <a id="p_img_link" class="example-image-link" href="" data-lightbox="example-set" title="">
                            <img id="p_img" class="example-image" src="" alt="" width="100" height="100" />
                        </a>
                    </div>

                    <div class="col-md-6">
                        <p>Skriv ditt meddelande så dyker det upp i listan</p>
                        <input id="mess_inputs" type="hidden" value="" />Namn:
                        <br />
                        <input id="name_txt" type="text" name="name" value="<?php echo $_SESSION['user']; ?>" />
                        <br />Meddelande:
                        <br />
                        <textarea id="message_ta" cols="50" rows="5" name="message"></textarea>
                        <br />
                        <br />
                        <button id="add_btn" class="btn btn-primary">Skicka ditt meddelande</button>
                    </div>
                    <div class="col-md-6">
                        <strong>Meddelanden:</strong>
                        <br />
                        <div id="mess_p_mess">
                            <!-- Här populeras meddelandena -->
                        </div>
                    </div>
                </div>
            </div> <!-- mess_container -->

        </div> <!-- image-row -->
    </div> <!-- container -->

    <!-- Scripts
    <script src="js/jquery.js"></script>
    <script src="js/lightbox.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/main.js"></script>
    -->
    <script src="js/dist/build.min.js"></script>
</body>

</html>
