<?php
include('actions.php');
$actions = new \TekniskSupport\LimitedGuestAccess\User\Actions;
?><!DOCTYPE>
<html>
<head>
    <title>Remote control</title>
    <meta charset="utf-8"/>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8;'/>
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

        * {
            font-family: "Open Sans", verdana, arial, sans-serif;
        }

        body {
            height: 100%;
        }

        h1 {
            margin: 0 auto;
            text-align: center;
        }

        a, a:link, a:visited, a:active {
            font-size: 22px;
            padding: 2rem 4rem;
            border: 1px solid #1a1a1a;
            background-color: #2E86C1;
            border-radius: 3px;
            width: 75%;
            display: block;
            margin: 1rem auto;
            color: #1a1a1a;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>
<body role="document">
<?php
$availableActions = $actions->getAllActions();

if (isset($_GET['performedAction']) && !is_null($_GET['performedAction'])) {
    echo '<h1>Performing: ' . urldecode($_GET['performedAction']) . "</h1>";
}

if (!$actions) {
    throw new \Exception('Could not get actions');
}

foreach ($availableActions as $id => $data) :?>
    <a href="?action=<?= $id ?>"><?= $data->friendly_name ?></a>
    <?php
endforeach;
?>
</body>
</html>
