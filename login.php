<?php

// Save the barcode into a session.
session_start();
if (!empty($_POST['barcode']) && is_numeric($_POST['barcode'])) {
  $_SESSION['barcode'] = $_POST['barcode'];
}
// Redirect to homepage.
header("Location: /hackathon");
