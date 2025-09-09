<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>


        body {
            background-color: #f2ece9;
        }

        .navbar {
            background-color: #f2ece9;
        }

        .search-bar {
            border-radius: 20px;
            width: 250px;
            border: 1px solid #ccc;
        }

        .navbar-brand img {
            height: 40px;
        }

        .btn-login {
            background-color: #2d4c48;
            color: white;
            border-radius: 20px;
            padding: 5px 20px;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #1d3331;
            color: white;
        }

        .nav-icons i {
            font-size: 1.2rem;
            color: #2d4c48;
            margin-left: 15px;
        }

        .nav-icons i:hover {
            color: #1d3331;
        }

        .nav-links {
            background-color: white;
            padding: 10px 0;
        }

        .nav-links .nav-link {
            font-weight: bold;
            color: #2d4c48;
            margin: 0 20px;
        }

        .nav-links .nav-link:hover {
            color: #1d3331;
        }

        @media (max-width: 992px) {
            .search-bar {
                width: 100%;
            }

            .navbar .btn-login,
            .nav-icons {
                display: none;
            }

            .navbar-toggler {
                border: none;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            .nav-links .nav-link {
                display: block;
                text-align: center;
            }
        }

        .text-green {
            color: #2d4c48; /* El tono de verde que ya hemos estado utilizando */
        }


        .text-green {
            color: #2d4c48; /* Tono verde que estamos usando */
        }

        hr {
            border: none;
            height: 1px;
            background-color: #2d4c48;
            width: 100%;
        }

        .img-fluid {
            max-height: 500px;
            object-fit: cover;
        }


        .text-green {
            color: #2d4c48; /* Tono de verde usado en el resto del proyecto */
        }

        .img-fluid {
            border-radius: 20px;
            max-height: 400px;
            object-fit: cover;
        }

        .fw-bold {
            font-weight: bold;
        }

    </style>
    <title>Roots</title>
</head>
<body>


<!-- index.php -->
<?php include 'front/general/header.php'; ?>
<?php include 'front/nosotros/body.php'; ?>
<?php include 'front/general/footer.php'; ?>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->
</body>
</html>