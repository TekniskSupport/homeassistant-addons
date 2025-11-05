<?php
include_once 'actions.php';
$actions = new \TekniskSupport\LimitedGuestAccess\Admin\Actions();

// Load login attempts log
$loginAttempts = [];
$logFile = '/data/login_attempts.json';
if (file_exists($logFile)) {
    $loginAttempts = json_decode(file_get_contents($logFile), true) ?? [];
}
?><!DOCTYPE html>
<html>
<head>
    <title>Limited User Access Admin - Login Attempts</title>
    <meta charset="utf-8"/>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8;'/>
    <style type="text/css">
        /* Basic Reset & Body Styling for HA consistency */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background-color: #1c1c1c;
            color: #e1e1e1;
            padding: 20px;
            box-sizing: border-box;
        }

        /* General Typography */
        h1, h2, h3, h4, h5, h6 {
            color: #e1e1e1;
            text-align: center;
            margin-top: 0;
            margin-bottom: 24px;
        }

        /* Card Styling to mimic HA UI */
        .card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-header {
            font-size: 1.25em;
            font-weight: 500;
            margin-bottom: 16px;
            color: #e1e1e1;
            border-bottom: 1px solid #7d7d7d;
            padding-bottom: 12px;
        }

        /* Buttons */
        .ha-button {
            background-color: #009ac7; /* Action Blue */
            color: #e1e1e1;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s ease;
            text-decoration: none;
            display: inline-block;
            margin-right: 8px;
        }

        .ha-button:hover {
            filter: brightness(1.1);
        }

        /* Link Styling */
        a {
            color: #009ac7; /* Action Blue */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Specific Admin UI Elements */
        .back-link {
            display: block;
            margin-bottom: 24px;
            text-align: center;
        }

        /* Table Styling */
        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .log-table th,
        .log-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #7d7d7d;
        }

        .log-table th {
            background-color: #101010;
            color: #e1e1e1;
            font-weight: 500;
        }

        .log-table tr:hover {
            background-color: #101010;
        }

        .log-table .success {
            color: #d2e7b9;
        }

        .log-table .failure {
            color: #db4437;
        }

        .no-attempts {
            text-align: center;
            color: #7d7d7d;
            font-style: italic;
            padding: 40px;
        }

        /* Utility */
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body role="document">
    <div class="back-link">
        <a class="ha-button" href="?">&larr; Back to Main Admin</a>
    </div>
    <h1>Login Attempts Log</h1>

    <div class="card">
        <h2 class="card-header">Recent Login Attempts</h2>
        <?php if (empty($loginAttempts)): ?>
            <div class="no-attempts">
                No login attempts recorded yet.
            </div>
        <?php else: ?>
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Link ID</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sort attempts by timestamp (most recent first)
                    krsort($loginAttempts);
                    foreach ($loginAttempts as $attempt):
                    ?>
                        <tr>
                            <td><?= date('Y-m-d H:i:s', $attempt['timestamp']) ?></td>
                            <td><?= htmlspecialchars($attempt['ip_address']) ?></td>
                            <td title="<?= htmlspecialchars($attempt['user_agent']) ?>">
                                <?= htmlspecialchars(substr($attempt['user_agent'], 0, 50)) ?><?php if (strlen($attempt['user_agent']) > 50): ?>...<?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($attempt['link_id']) ?></td>
                            <td class="<?= $attempt['success'] ? 'success' : 'failure' ?>">
                                <?= $attempt['success'] ? 'Success' : 'Failed' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
