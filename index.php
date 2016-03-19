<?php
session_start();
// Is Krumo present?
include('Polaris-API-PHP-Class/krumo/class.krumo.php');
// Look for optional local settings file. Helpful for presentation purposes. :)
if (file_exists('Polaris-API-PHP-Class/settings.local.php')) {
  include 'Polaris-API-PHP-Class/settings.local.php';
}
// Include PHP class for use w/ API.
include('Polaris-API-PHP-Class/pac_polaris.inc');
// Reusable functions
include('functions.inc');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>IUG Hackathon - Polaris My Lists Builder</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/hackathon">IUG Hackathon - Polaris My Lists Builder</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php include ('login-form.inc'); ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <p class="lead">Begin by selecting a title to add to your existing lists.
        <?php if (empty($_SESSION['barcode'])) : ?>
          Not logged in yet? Enter your barcode number. Or pretend use sample barcode number <b>20080104020258</b>.
        <?php endif; ?>
        </p>
        <br style="clear:both;">
        <?php
        // Pick a random search term from an array.
        $items = array(
          'Beef',
          'Gone With The Wind',
          'Drama',
          'X-Men',
          'Star Trek',
        );
        $query = $items[array_rand($items)];
        // Try pulling a list of 3 titles.
        $query = 'q=' . urlencode($query) . '&bibsperpage=3'; //&limit=TOM=dvd';
        $result = PolarisAPI::searchBibs($query);
        //krumo($result);

        foreach ($result->BibSearchRows as $key => $row) {
          print '<div class="col-md-4" style="margin-bottom: 15px;">';
          $image_url = pac_syndetics_image_url($row->ISBN, $row->OCLC, $row->UPC);
          if (!empty($row->ISBN) || !empty($row->OCLC) || !empty($row->UPC)) {
            print '<div class="image"><img src="' . $image_url . '"></div>';
          }
          print '<h3 class="title">' . $row->Title . '</h3>';
          print '<div class="summary" style="padding: 0 0 10px 0;">' . truncate($row->Summary, 200) . '</div>';
          // Button to add to My List.
          print '<a class="btn btn-primary btn-lg" href="add-to-list.php?ControlNumber=' . $row->ControlNumber . '"><i class="fa fa-plus"></i> Add to My List</a>';
          print '</div>';
        }
        print '<br style="clear:both;">';
        ?>
      </div>
    </div>

    <div class="container">
      <?php include ('footer.inc'); ?>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
