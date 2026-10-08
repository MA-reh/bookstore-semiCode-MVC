<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>

    <?php include __DIR__ . "/../shared/metaTagsAndCssLinks.php" ?>

    <link rel="stylesheet" href="<?= asset("css/home/home.css") ?>">
    <link rel="stylesheet" href="<?= asset("css/home/home.responsive.css") ?>">
</head>

<body>

    <header id="Home">
        <?php include __DIR__ . "/../shared/navbar.php" ?>


        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row">
                            <div class="part col-lg-6">
                                <div class="item">
                                    <div class="caption">
                                        <h5>First slide</h5>
                                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="part col-lg-6">
                                <div class="item">
                                    <img src="<?= asset("images/slide_1.png") ?>" class="d-block w-100" alt="slide_1.png">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="carousel-item">
                    <div class="container">
                        <div class="row">
                            <div class="part col-lg-6">
                                <div class="item">
                                    <div class="caption">
                                        <h5>Second slide</h5>
                                        <p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on th</p>
                                    </div>
                                </div>
                            </div>
                            <div class="part col-lg-6">
                                <div class="item">
                                    <img src="<?= asset("images/slide_2.png") ?>" class="d-block w-100" alt="slide_2.png">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="carousel-item">
                    <div class="container">
                        <div class="row">
                            <div class="part col-lg-6">
                                <div class="item">
                                    <div class="caption">
                                        <h5>Third slide</h5>
                                        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="part col-lg-6">
                                <div class="item">
                                    <img src="<?= asset("images/slide_3.png") ?>" class="d-block w-100" alt="slide_3.png">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>


    <?php include __DIR__ . "/../shared/globalScriptsJS.php" ?>
    <script src="<?= asset("js/home/home.js") ?>"></script>

</body>

</html>