<!-- Example row of columns -->
<div class="row">
  <div class="col-md-4">
    <h2>My Lists</h2>
    <?php
    // Show all of the customer's lists here in a block.
    $barcode = 20080104020258;
if (isset($_SESSION['barcode'])) {
  $barcode = $_SESSION['barcode'];
}

    if (is_numeric($barcode)) {
      $lists = PolarisAPI::PatronAccountGetTitleLists($barcode); // Find the patron's list of lists.
      if (!empty($lists->PatronAccountTitleListsRows)) {
        print '<ul>';
        foreach ($lists->PatronAccountTitleListsRows as $list) {
          //$list_items = PolarisAPI::PatronTitleListGetTitles($barcode, $list->RecordStoreID); // Find the items on each list.
          print '<li><a href="view-list.php?RecordStoreID=' . $list->RecordStoreID . '">' . $list->RecordStoreName . '</a></li>';
        }
        print '</ul>';
      }
    }
    ?>
  </div>
  <div class="col-md-4">
    <h2>Create a New List</h2>
    <form action="add-list.php" method="post">
      <div class="form-group">
        <input type="text" class="form-control" id="list_name" name="list_name" placeholder="Your list name">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-default">Create &raquo;</button>
      </div>
    </form>
  </div>
  <div class="col-md-4">
    <h2>Contributors</h2>
    <p><b>Mark W. Jarrell</b><br>
    Online Applications Developer<br>
    Richland Library</p>
    <p><b>Brian LaRue</b><br>
    Software Support Administrator<br>
    Salt Lake City Public Library</p>
  </div>
</div>

<hr>

<footer>
  <div class="col-xs-12 col-md-2 vcenter">
    &copy; IUG 2016.<br>
    A special hackathon project in cooperation with:
  </div>
  <div class="col-xs-12 col-md-2 col-md-offset-1 vcenter text-center">
    <img class="logo" src="images/iii-logo.png" alt="Innovative Interfaces logo">
  </div>
  <div class="col-xs-12 col-md-2 vcenter text-center">
    <img class="logo" src="images/polaris-logo.png" alt="Polaris logo">
  </div>
  <div class="col-xs-12 col-md-2 vcenter text-center">
    <img class="logo" src="images/logo-print.png" alt="Richland Library logo">
  </div>
  <div class="col-xs-12 col-md-2 vcenter text-center">
    <img class="logo" src="images/slc-logo.png" alt="Salt Lake City logo">
  </div>
</footer>
