<?php use rave\core\Config; ?>
<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="utf-8"/>
    <title></title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>/css/style.css">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
          crossorigin="anonymous">
    <!-- Bootstrap end-->
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/select2/4.0.2/css/select2.min.css"
          integrity="sha256-fCvsF0xsnCxll1wsahPQTSOuvghR/s3EUivgvueC+iE="
          crossorigin="anonymous">
    <!-- Jquery 2.2.2 -->
    <script src="https://code.jquery.com/jquery-2.2.2.min.js"
            integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI=" crossorigin="anonymous"></script>
</head>

<body role="document">

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?= Config::get('app')['name'] ?> Admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <?php if (isset($logged)): ?>
                <div class="navbar-left">
                    <ul class="nav navbar-nav">
                        <li><a href="<?= WEB_ROOT ?>/"><i class="glyphicon glyphicon-open"></i>
                                Site</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs<span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= WEB_ROOT ?>/admin/users"><i class="glyphicon glyphicon-list"></i>
                                        Lister</a>
                                </li>
                                <li><a href="<?= WEB_ROOT ?>/admin/user/create"><i class="glyphicon glyphicon-plus"></i>
                                        Creer</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Technotes<span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= WEB_ROOT ?>/admin/technotes"><i class="glyphicon glyphicon-list"></i>
                                        Lister</a>
                                </li>
                                <li><a href="<?= WEB_ROOT ?>/admin/technote/create"><i
                                            class="glyphicon glyphicon-plus"></i>
                                        Creer</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Questions<span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= WEB_ROOT ?>/admin/questions"><i class="glyphicon glyphicon-list"></i>
                                        Lister</a>
                                </li>
                                <li><a href="<?= WEB_ROOT ?>/admin/question/create"><i
                                            class="glyphicon glyphicon-plus"></i>
                                        Creer</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Tags<span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= WEB_ROOT ?>/admin/tags"><i class="glyphicon glyphicon-list"></i>
                                        Lister</a>
                                </li>
                                <li><a href="<?= WEB_ROOT ?>/admin/tags/r"><i class="glyphicon glyphicon-list"></i>
                                        Lister refusés</a>
                                </li>
                                <li><a href="<?= WEB_ROOT ?>/admin/tags/p"><i class="glyphicon glyphicon-list"></i>
                                        Lister proposées</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= WEB_ROOT ?>/admin/tag/create"><i class="glyphicon glyphicon-plus"></i>
                                        Creer</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="navbar-right">
                <?php if (isset($logged)): ?>
                    <p class="navbar-text">
                        Bienvenue <a class="navbar-link"
                                     href="<?= WEB_ROOT ?>/admin/user/<?= $logged->id ?>"><?= $logged->name ?> <?= $logged->firstname ?></a>
                    </p>
                    <a class="navbar-text navbar-link" href="<?= WEB_ROOT ?>/admin/logout?csrf=<?= $csrf ?>">Se
                        deconnecter</a>

                <?php else: ?>
                    <form class="navbar-form" method="post" action="<?= WEB_ROOT ?>/admin/login">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">
                        <div class="form-group">
                            <input type="text" placeholder="Email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control" name="password">
                        </div>
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form>
                <?php endif; ?>
            </div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>
<div class="container">
    <?php if (isset($info)): ?>
        <div class="alert alert-info alert-dismissible fade in" role=alert>
            <button type=button class=close data-dismiss=alert aria-label=Close><span aria-hidden=true>&times;</span>
            </button>
            <?= $info ?>
        </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade in" role=alert>
            <button type=button class=close data-dismiss=alert aria-label=Close><span aria-hidden=true>&times;</span>
            </button>
            <?= $success ?>
        </div>
    <?php endif; ?>
    <?php if (isset($warning)): ?>
        <div class="alert alert-danger alert-dismissible fade in" role=alert>
            <button type=button class=close data-dismiss=alert aria-label=Close><span aria-hidden=true>&times;</span>
            </button>
            <?= $warning ?>
        </div>
    <?php endif; ?>
    <?= /** @var string $content */
    $content ?>
</div>

<!-- Bootstrap.js 3.3.6 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<!-- Select 2 -->
<script src="https://cdn.jsdelivr.net/select2/4.0.2/js/select2.min.js"
        integrity="sha256-04G2Dnj+apKwEmFACpe+2vz/yh4YM6+FDQ2qhLyQX/s="
        crossorigin="anonymous"></script>
<script src="<?= WEB_ROOT ?>/js/js.cookie-2.1.1.min.js"></script>
<script src="<?= WEB_ROOT ?>/js/main.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</body>
</html>