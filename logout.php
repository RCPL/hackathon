<?php

// Kill the session.
session_start();
session_destroy();
// Redirect to homepage.
header("Location: /hackathon");
