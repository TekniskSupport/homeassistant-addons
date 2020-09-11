<?php
include('actions.php');
$actions = new \TekniskSupport\LimitedGuestAccess\User\Actions;
switch ($actions->theme) {
    case 'default':
        $primaryColor   = 'rgba(11,11,11,1);';
        $secondaryColor = 'rgba(40,40,40,.5);';
        break;
    case 'light-grey':
        $primaryColor   = 'rgba(211,211,211,1);';
        $secondaryColor = 'rgba(11,11,11,.5);';
        break;
    case 'dark-blue':
        $primaryColor   = 'rgba(11,11,11,1);';
        $secondaryColor = 'rgba(11,11,221,.5);';
        break;
    case 'light-blue':
        $primaryColor   = 'rgba(221,221,221,1);';
        $secondaryColor = 'rgba(11,11,221,.5);';
        break;
    default:
        $primaryColor   = 'rgba(11,11,11,1);';
        $secondaryColor = 'rgba(40,40,40,.5);';
        break;
}
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
            background: <?= $primaryColor ?>;
        }

        h1 {
            color: ghostwhite;
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
            background-color: <?= $secondaryColor ?>;
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
            color: ghostwhite;
            position: absolute;
            font-size: 36px;
            font-weight: bold;
            top: 25%;
            bottom: 50%;
        }

        a.state-off:after {
            right: 8rem;
            content: "\2022";
            color: ghostwhite;
            position: absolute;
            font-size: 36px;
            font-weight: bold;
            top: 25%;
            bottom: 50%;
        }

        a.state-on:after {
            right: 8rem;
            content: "\2022";
            color: gold;
            position: absolute;
            font-size: 36px;
            font-weight: bold;
            top: 25%;
            bottom: 50%;
        }

        input, textarea, select {
            width: 70%;
            background:transparent;
            color: ghostwhite;
            margin: 5px 0;
            padding: 1%;
            border: 2px solid ghostwhite;
            transition-duration:0.5s;
        }
        input:focus, textarea:focus {
            border-left:10px solid ghostwhite;
            transition-duration:0.5s;
        }
        input[type='submit'] {
            color: #1a1a1a;
            background-color: ghostwhite;
            border: 2px solid ghostwhite;
            transition-duration:0.3s;
        }
        input[type='submit']:hover {
            color: ghostwhite;
            background:transparent;
            transition-duration:0.3s;
        }
    </style>
    <?=  $actions->inject('style.css') ; ?>
    <?=  $actions->inject('script.js') ; ?>
</head>
<body role="document">
<?=  $actions->inject('header.htm') ; ?>
<?php
if ($actions->passwordProtected && !$actions->authenticated) :?>
    <div style="text-align: center; margin-top: 20%">
        <form action='?auth' method="post">
            <input name='password' type="password" placeholder="password">
            <input type="submit" />
        </form>
    </div>
<?php
else:
    $availableActions = $actions->getFilteredActions();

    if (isset($_GET['performedAction']) && !is_null($_GET['performedAction'])) {
        echo '<h1>Performing: ' . urldecode($_GET['performedAction']) . "</h1>";
    }

    if (!$actions) {
        throw new \Exception('Could not get actions');
    }

    foreach ($availableActions as $id => $data) :
            $state = (isset($data->service_call_data->entity_id)
                      && !empty($data->service_call_data->entity_id))
                   ? json_decode($actions->getState($data->service_call_data->entity_id))
                   : false;
        ?>
        <a <?php if(isset($state->state)): echo 'class="state-'. $state->state . '"'; endif;?>"
            href="?action=<?= $id ?>"><?= $data->friendly_name ?></a>
        <?php
    endforeach;
endif;
?>
<?=  $actions->inject('footer.htm') ; ?>
</body>
</html>
