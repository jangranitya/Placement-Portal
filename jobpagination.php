<?php

session_start();

require_once("db.php");

$limit = 4;

if (isset($_GET["page"])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$start_from = ($page - 1) * $limit;

$sql = "SELECT * FROM job_post ORDER BY id_jobpost DESC LIMIT $start_from, $limit";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $sql1 = "SELECT * FROM company WHERE id_company='$row[id_company]'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
      while ($row1 = $result1->fetch_assoc()) {
?>

        <div class="attachment-block clearfix">
          <img class="attachment-img" src="uploads/logo/<?php echo $row1['logo']; ?>" alt="Attachment Image">

          <div class="attachment-pushed">
            <h4 class="attachment-heading">
              <a href="view-job-post.php?id=<?php echo $row['id_jobpost']; ?>">
                <?php echo $row['jobtitle']; ?>
              </a>
              <span class="attachment-heading pull-right">₹<?php echo $row['minimumsalary']; ?>/Year</span>
            </h4>
            <div class="attachment-text">
              <div>
                <strong><?php echo $row1['companyname']; ?> | <?php echo $row1['city']; ?> | Role <?php echo $row['experience']; ?></strong>
              </div>
            </div>

            <div class="text-right" style="margin-top: 10px;">
              <a href="apply.php?id_jobpost=<?php echo $row['id_jobpost']; ?>" class="btn btn-success">Apply</a>
            </div>
          </div>
        </div>

<?php
      }
    }
  }
}

$conn->close();
?>
