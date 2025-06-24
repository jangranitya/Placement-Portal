<?php

// To Handle Session Variables on This Page
session_start();

if (empty($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

// Including Database Connection
require_once("../db.php");

// If user clicked apply button
if (isset($_GET)) {

    // Validate job post ID
    $jobId = intval($_GET['id']);

    // Get user details
    $sql = "SELECT * FROM users WHERE id_user = '$_SESSION[id_user]'";
    $result1 = $conn->query($sql);

    if ($result1->num_rows > 0) {
        $row1 = $result1->fetch_assoc();

        // Check if all marks are filled
        if ($row1['hsc'] === '' || $row1['ssc'] === '' || $row1['ug'] === '' || $row1['pg'] === '') {
            $_SESSION['status'] = "Please update all your marks (HSC, SSC, UG, PG) in your profile to check eligibility.";
            $_SESSION['status_code'] = "error";
            header("Location: ../view-job-post.php?id=$jobId");
            exit();
        }

        $sum = floatval($row1['hsc']) + floatval($row1['ssc']) + floatval($row1['ug']) + floatval($row1['pg']);
        $total = $sum / 4;
        $course1 = $row1['qualification'];
    }

    // Get job post details
    $sql = "SELECT maximumsalary, qualification FROM job_post WHERE id_jobpost = '$jobId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eligibility = floatval($row['maximumsalary']);
        $course2 = $row['qualification'];

        if ($total >= $eligibility) {
            if ($course1 == $course2) {
                $_SESSION['status'] = "You are eligible for this drive, apply if you are interested.";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "You are not eligible for this drive due to the course criteria. Check out other drives.";
                $_SESSION['status_code'] = "success";
            }
        } else {
            $_SESSION['status'] = "You are not eligible for this drive either due to the overall percentage criteria or course criteria. Update your marks in your profile, if you think you are eligible.";
            $_SESSION['status_code'] = "success";
        }

        header("Location: ../view-job-post.php?id=$jobId");
        exit();
    }
}
