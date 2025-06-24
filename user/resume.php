<?php
session_start();
if (empty($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}
require_once("../db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 30px;
        }

        .navbar-brand {
            margin-left: 300px;
        }

        .navbar-nav li {
            margin-left: 30px;
        }

        .nav-link {
            color: #b3c6e0 !important;
        }

        .nav-link:hover {
            color: #482ff7 !important;
        }

        #cv-form, #cv-template {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-small {
            font-size: 0.8rem;
        }

        .my-img {
            border-radius: 50%;
            max-width: 150px;
            margin-bottom: 15px;
        }

        .background {
            background-color: #e7eaf6;
        }

        .card {
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #343a40;
            color: white;
        }

        .container h1, h3 {
            color: #343a40;
        }

        #img8 {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Placement Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mx-8" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="resume.php">Create Resume</a></li>
                    <li class="nav-item"><a class="nav-link" href="notice.php">Notice</a></li>
                    <li class="nav-item"><a class="nav-link" href="../jobs.php">Active Drives</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" id="cv-form">
        <h1 class="text-center">Resume Generator</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Personal Information</h3>
                <div class="form-group mb-3">
                    <label>Your Name</label>
                    <input type="text" id="nameField" placeholder="Enter here" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Your Contact</label>
                    <input type="text" id="contactField" placeholder="Enter here" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Your Address</label>
                    <textarea id="addressField" rows="3" placeholder="Enter here" class="form-control"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Select Your Photo</label>
                    <input type="file" onchange="previewFile()" id="imageField" class="form-control">
                    <img id="img8" src="" height="200" alt="Image preview...">
                </div>
                <p class="text-secondary">Important Links</p>
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="text" id="emailField" placeholder="Enter here" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label>Facebook</label>
                    <input type="text" id="fbField" placeholder="Enter here" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label>Instagram</label>
                    <input type="text" id="instaField" placeholder="Enter here" class="form-control">
                </div>
                <div class="form-group mb-2">
                    <label>LinkedIn</label>
                    <input type="text" id="linkedField" placeholder="Enter here" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <h3>Professional Information</h3>
                <div class="form-group mb-3">
                    <label>Objective</label>
                    <textarea id="objectiveField" rows="2" placeholder="Enter here" class="form-control"></textarea>
                </div>
                <div class="form-group mb-3" id="sF">
                    <label>Skills</label>
                    <textarea rows="2" placeholder="Enter here" class="form-control sField"></textarea>
                    <div class="text-center mt-2" id="sAddButton">
                        <button onclick="addNewSfield()" class="btn btn-primary btn-small">Add</button>
                    </div>
                </div>
                <div class="form-group mb-3" id="we">
                    <label>Work Experience</label>
                    <textarea rows="2" placeholder="Enter here" class="form-control weField"></textarea>
                    <div class="text-center mt-2" id="weaddButton">
                        <button onclick="addNewWEfield()" class="btn btn-primary btn-small">Add</button>
                    </div>
                </div>
                <div class="form-group mb-3" id="eq">
                    <label>Academic Qualification</label>
                    <textarea rows="2" placeholder="Enter here" class="form-control eqfield"></textarea>
                    <div class="text-center mt-2" id="eqaddButton">
                        <button onclick="addNewEQfield()" class="btn btn-primary btn-small">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button onclick="generateCV()" class="btn btn-primary btn-lg">Generate CV</button>
        </div>
    </div>

    <div class="container" id="cv-template" style="display: none;">
        <div class="row">
            <div class="col-md-4 text-center py-5 background">
                <img id="imgT" src="https://via.placeholder.com/150" class="img-fluid my-img" alt="">
                <div>
                    <p id="nameT1"><strong>Name</strong></p>
                    <p id="contactT">Contact</p>
                    <p id="addressT">Address</p>
                    <hr />
                    <p><a id="emailT" href="#">Email</a></p>
                    <p><a id="fbT" href="#">Facebook</a></p>
                    <p><a id="instaT" href="#">Instagram</a></p>
                    <p><a id="linkedT" href="#">LinkedIn</a></p>
                </div>
            </div>
            <div class="col-md-8 py-5">
                <h1 id="nameT2">Name</h1>
                <div class="card">
                    <div class="card-header background">
                        <h4>Objective</h4>
                    </div>
                    <div class="card-body">
                        <p id="objectiveT"></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header background">
                        <h4>Skills</h4>
                    </div>
                    <div class="card-body">
                        <ul id="sT"></ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header background">
                        <h4>Work Experience</h4>
                    </div>
                    <div class="card-body">
                        <ul id="weT"></ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header background">
                        <h4>Education Qualification</h4>
                    </div>
                    <div class="card-body">
                        <ul id="eqT"></ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <button onclick="printCV()" class="btn btn-primary">Print CV</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>
