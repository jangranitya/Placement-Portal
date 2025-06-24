<?php
session_start();
require_once("db.php");

// Check login
if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}

$id_user = $_SESSION['id_user'];
$id_jobpost = isset($_GET['id_jobpost']) ? $_GET['id_jobpost'] : 0;

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $cgpa = $_POST['cgpa'];
  $skills = $_POST['skills'];
  $message = $_POST['message'];

  // Resume upload
  $resume = '';
  if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $resume = 'uploads/resume_' . time() . '_' . basename($_FILES['resume']['name']);
    move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
  }

  // Insert into apply_job_post
  $stmt = $conn->prepare("INSERT INTO apply_job_post (id_user, id_jobpost, cgpa, skills, message, resume) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iidsss", $id_user, $id_jobpost, $cgpa, $skills, $message, $resume);
  if ($stmt->execute()) {
    $_SESSION['message'] = "Application submitted!";
  } else {
    $_SESSION['message'] = "Error submitting application.";
  }

  header("Location: jobs.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Apply to Job</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
  <h2>Apply for Job</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="resume">Upload Resume (PDF/DOC):</label>
      <input type="file" name="resume" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="cgpa">Current CGPA:</label>
      <input type="number" step="0.01" name="cgpa" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="skills">Skills:</label>
      <textarea name="skills" class="form-control" required></textarea>
    </div>
    <div class="form-group">
      <label for="message">Message (optional):</label>
      <textarea name="message" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Submit Application</button>
  </form>
</div>
</body>
</html>
