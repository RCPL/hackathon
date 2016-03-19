<?php if (empty($_SESSION['barcode'])) : ?>
  <form class="navbar-form navbar-right" id="login" action="login.php" method="post">
    <div class="form-group">
      <label for="exampleInputName2">Library Card Number</label>
      <input type="text" class="form-control" name="barcode" id="barcode" placeholder="20080104020258">
    </div>
    <button type="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> Sign in</button>
  </form>
<?php else : ?>
  <form class="navbar-form navbar-right" id="login" action="logout.php" method="post">
    <div class="form-group">
      <label>Welcome <b><?php print $_SESSION['barcode']; ?></b>!</label>
      <button type="submit" class="btn btn-default" style="margin-left: 10px;"><i class="fa fa-sign-out"></i> Sign Out</button>
  </form>
<?php endif; ?>
