<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-XSS-Protection: 1; mode=block");
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="front/css/brand.css">
    <!-- Inline styles removed to favor front/css/brand.css -->
    <title>Roots</title>
</head>

<body>


    <!-- index.php -->
    <?php include 'front/general/header.php'; ?>
    <?php include 'front/tienda/body.php'; ?>
    <?php include 'front/general/footer.php'; ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script>
        // Inicializar el carrusel
        var myCarousel = document.querySelector('#productCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 3000,
            wrap: true
        });

        // Funcionalidad del contador
        document.addEventListener('DOMContentLoaded', function () {
            // Seleccionar todos los botones de decremento y los inputs
            const decrementButtons = document.querySelectorAll('.btn-outline-secondary.btn-sm:nth-child(1)');
            const incrementButtons = document.querySelectorAll('.btn-outline-secondary.btn-sm:nth-child(3)');

            decrementButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    const input = button.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });
            });

            incrementButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    const input = button.previousElementSibling;
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });
        });
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->
</body>

</html>