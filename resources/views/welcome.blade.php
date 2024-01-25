<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dictionary API</title>

    <!-- Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="app-container">
        <div class="center mt-5">
            <div class="card m-5">
                <div class="card body">
                    <div class="text-center">
                        <img src="{{ asset('images/logo.png') }}" width="200" alt="logo" />
                    </div>
                    <h2 class="mt-3">Dictionary APP For IT API</h2>
                    <div class="text-center">
                        <span class="badge badge-info">Laravel 10</span>
                    </div>
                    <hr>
                    <p>API Documentation</p>
                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>User Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>Login API with Token</li>
                                    <li>Authenticated User Profile</li>
                                    <li>Refresh Data</li>
                                    <li>get user by id</li>
                                    <li>Logout</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Suggest Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>get suggest</li>
                                    <li>get suggest all</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>History Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>check if exist</li>
                                    <li>get word lookup history by user</li>
                                    <li>get translate history by user</li>
                                    <li>display By Time Word Lookup History</li>
                                    <li>display By Time Translate History</li>
                                    <li>delete-by-id-word-lookup-history</li>
                                    <li>delete-word-lookup-history</li>
                                    <li>delete-translate-by-id</li>
                                    <li>delete-translate-history</li>
                                    <li>delete-all-history</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Search Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>search word</li>
                                    <li>search by specialty</li>
                                    <li>search word lookup history</li>
                                    <li>search translate history</li>
                                    <li>search love vocabulary by word</li>
                                    <li>search love text by word</li>
                                    <li>find love by word and english</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Specialization Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>get all specializations</li>
                                    <li>display by specializations</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Words Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>random word</li>
                                    <li>get-all-word</li>
                                    <li>get-unapproved</li>
                                    <li>store word</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Word Type Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>get-all-word-type</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Means Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>store mean</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Favorite Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>total love item</li>
                                    <li>show love vocabulary</li>
                                    <li>delete love vocabulary</li>
                                    <li>show love text</li>
                                    <li>delete love text</li>
                                    <li>delete all favorite</li>
                                    <li>sort by favorite word lookup</li>
                                    <li>sort by favorite text</li>
                                    <li>update vocabulary</li>
                                    <li>update text</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Hot Vocabulary Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>get-hot-vocabulary</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Reviews Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>reviews</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-body m-2">
                        <div class="row">
                            <div class="col-5">
                                <h4>Mini Game Module</h4>
                            </div>
                            <div class="col-7">
                                <ul class="feature-list">
                                    <li>get-questions</li>
                                    <li>get-more-questions-mini-game</li>
                                    <li>get-random-wrong-answers</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <p class="text-center">
                        <a href="{{ route('l5-swagger.default.api') }}" class="btn btn-primary">
                            <i class="fa fa-book"></i>API Documentation
                        </a>
                        <a href="https://github.com/quanghuybest2k2/dictionary-server" target="_blank"
                            class="btn btn-info">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
