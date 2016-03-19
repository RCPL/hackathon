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
        <h1>Copy to My Lists</h1>
        <?php
        $success = FALSE;
        if (!empty($_GET['success'])) {
          $success = $_GET['success'];
        }
        ?>
        <?php if ($success != TRUE) : ?>
          <form action="copy-to-list.php?success=true" method="post">
            <input type="hidden" id="from_list_id" name="from_list_id" value="<?php print $_POST['list_id']; ?>">
            <input type="hidden" id="position" name="position" value="<?php print $_POST['position']; ?>">
            <div class="form-group">
              <!-- Show them a dropdown that lists their lists. -->
              <select name="to_list_id" id="to_list_id" class="form-control">
                <?php
                $barcode = 20080104020258;
                if (isset($_SESSION['barcode'])) {
                  $barcode = $_SESSION['barcode'];
                }
                $lists = PolarisAPI::PatronAccountGetTitleLists($barcode); // Find the patron's list of lists.
                if (!empty($lists->PatronAccountTitleListsRows)) {
                  foreach ($lists->PatronAccountTitleListsRows as $list) {
                    if ($list->RecordStoreID != $_POST['list_id']) { // Don't let them copy to the from list.
                      print '<option value="' . $list->RecordStoreID . '">' . $list->RecordStoreName . '</option>';
                    }
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Copy</button>
            </div>
          </form>
        <?php else : ?>
          <!-- If they submit the form, copy the title to that list. -->
          <?php
          $barcode = 20080104020258;
          if (isset($_SESSION['barcode'])) {
            $barcode = $_SESSION['barcode'];
          }
          $result = PolarisAPI::PatronTitleListCopyTitle($barcode, $_POST['from_list_id'], $_POST['position'], $_POST['to_list_id']);
          ?>
          <!-- Show a success message. -->
          <p class="bg-success" style="padding: 10px;"><i class="fa fa-thumbs-o-up"></i> Your item was successfully copied to the list!</p>
        <?php endif; ?>
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
