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
            background: #111;
        }

        h1 {
            color: #efefef;
            margin: 0 auto;
            text-align: center;
        }

        a:hover {
            left: 4px;
            background: black;
        }

        a, a:link, a:visited, a:active {
            font-size: 22px;
            padding: 2rem 4rem;
            border: 1px solid #888;
            background-color: #1a1a1a;
            border-radius: 3px;
            width: 75%;
            display: block;
            margin: 1rem auto;
            color: ghostwhite;
            text-decoration: none;
            text-align: center;
            transition: background .3s;
            position: relative;
        }

        a:before {
            right: 2rem;
            content: ">";
            color: white;
            position: absolute;
            font-size: 36px;
            font-weight: bold;
            top: 25%;
            bottom: 50%;
        }
    </style>
</head>
<body role="document">
<?php
$availableActions = $actions->getFilteredActions();

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
