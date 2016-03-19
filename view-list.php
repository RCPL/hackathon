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
        <?php
        $barcode = 20080104020258;
if (isset($_SESSION['barcode'])) {
  $barcode = $_SESSION['barcode'];
}
        $list_id = $_GET['RecordStoreID'];
        if (is_numeric($barcode)) {
          $lists = PolarisAPI::PatronAccountGetTitleLists($barcode); // Find the patron's list of lists.
          if (!empty($lists->PatronAccountTitleListsRows)) {
            foreach ($lists->PatronAccountTitleListsRows as $list) {
              if ($list->RecordStoreID == $list_id) {
                // Title of this list.
                print '<h1 style="padding-bottom: 15px;" class="pull-left">' . $list->RecordStoreName . '</h1>';
                  // Delete entire list
                  print '<form action="remove-all-from-list.php" method="post" id="remove-all-from-list" class="pull-left">
                    <input type="hidden" name="list_id" value="' . $list->RecordStoreID . '">
                    <div class="form-group">
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i> Delete All Titles</button>
                    </div>
                  </form>
                  <form action="remove-list.php" method="post" id="remove-list" class="pull-left">
                    <input type="hidden" name="list_id" value="' . $list->RecordStoreID . '">
                    <div class="form-group">
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i> Delete List</button>
                    </div>
                  </form>';
                print '<br style="clear: both;">';
                $list_items = PolarisAPI::PatronTitleListGetTitles($barcode, $list->RecordStoreID); // Find the items on each list.
                // For each item in the list, display basic bib info.
                foreach ($list_items->PatronTitleListTitleRows as $key => $list_item) {
                  $bib_record_search = PolarisAPI::searchBibs('q=' . $list_item->LocalControlNumber, 'keyword/cn');
                  //krumo($bib_record_search->BibSearchRows[0]);
                  $bib_record = PolarisAPI::getBib($list_item->LocalControlNumber);
                  //krumo($bib_record);
                  // Get the format/TOM for the item.
                  $format = '';
                  foreach($bib_record->BibGetRows as $row) {
                    switch ($row->ElementID) {
                      case 17:
                        $format = $row->Value;
                        break;
                    }
                  }
                  print '<div class="row" style="padding-bottom: 15px; margin-bottom: 15px; border-bottom: 1px solid #777;">';
                  $image_url = pac_syndetics_image_url($bib_record_search->BibSearchRows[0]->ISBN, $bib_record_search->BibSearchRows[0]->OCLC, $bib_record_search->BibSearchRows[0]->UPC);
                  if (!empty($bib_record_search->BibSearchRows[0]->ISBN) || !empty($bib_record_search->BibSearchRows[0]->OCLC)) {
                    print '<div class="image col-xs-6 col-sm-2"><img src="' . $image_url . '"></div>';
                  }
                  print '<div class="col-xs-6 col-sm-7 vcenter">';
                  print '<h3 class="title">' . $bib_record_search->BibSearchRows[0]->Title . '</h3>';
                  if (!empty($bib_record_search->BibSearchRows[0]->CallNumber)) {
                    print '<div class="call-number"><b>Call Number:</b> ' . $bib_record_search->BibSearchRows[0]->CallNumber . '</div>';
                  }
                  if (!empty($format)) {
                    print '<div class="format"><b>Format:</b> ' . $format . '</div>';
                  }
                  print '<div class="summary" style="padding: 0 0 10px 0;">' . truncate($bib_record_search->BibSearchRows[0]->Summary, 200) . '</div>';
                  print '</div>';
                  print '<div class="col-xs-6 col-sm-3 vcenter">
                  <form action="copy-to-list.php" method="post" class="pull-left">
                   <input type="hidden" name="position" value="' . $list_item->Position . '">
                   <input type="hidden" name="list_id" value="' . $list->RecordStoreID . '">
                   <div class="form-group">
                     <button type="submit" class="btn btn-default"><i class="fa fa-clone fa-lg"></i> Copy</button>
                   </div>
                  </form>
                  <form action="remove-from-list.php" method="post" class="pull-left remove-from-list">
                    <input type="hidden" name="position" value="' . $list_item->Position . '">
                    <input type="hidden" name="list_id" value="' . $list->RecordStoreID . '">
                    <div class="form-group">
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i> Remove</button>
                    </div>
                  </form>
                  </div>';
                  print '</div>';
                }
                if (count($list_items->PatronTitleListTitleRows) < 1) {
                  print '<p class="bg-warning" style="padding: 10px;"><i class="fa fa-info-circle"></i> There are no items in this list. Visit the <a href="/hackathon">home page</a> to start adding!</p>';
                }
              }
            }
          }
        }
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
