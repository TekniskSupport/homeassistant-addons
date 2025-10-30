<?php
include_once 'actions.php';
$actions = new \TekniskSupport\LimitedGuestAccess\Admin\Actions();

// Load existing CSS if it exists
$cssContent = '';
$cssFile = '/data/style.css';
if (file_exists($cssFile)) {
    $cssContent = file_get_contents($cssFile);
}

$footerContent = '';
$footerFile = '/data/footer.htm';
if (file_exists($footerFile)) {
    $footerContent = file_get_contents($footerFile);
}
?><!DOCTYPE html>
<html>
<head>
    <title>Limited User Access Admin - Custom CSS & Footer</title>
    <meta charset="utf-8"/>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8;'/>
    <style type="text/css">
        /* Basic Reset & Body Styling for HA consistency */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background-color: rgb(28, 28, 28);
            color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            box-sizing: border-box;
        }

        /* General Typography */
        h1, h2, h3, h4, h5, h6 {
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            margin-top: 0;
            margin-bottom: 24px;
        }

        /* Card Styling to mimic HA UI */
        .card {
            background-color: rgb(48, 48, 48);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-header {
            font-size: 1.25em;
            font-weight: 500;
            margin-bottom: 16px;
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 12px;
        }

        /* Form Elements */
        label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9em;
        }

        input[type='text'],
        input[type='password'],
        input[type='date'],
        input[type='time'],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 1px solid rgb(58, 58, 58);
            border-radius: 4px;
            background-color: rgb(60, 60, 60);
            color: rgba(255, 255, 255, 0.9);
            box-sizing: border-box;
            -webkit-appearance: none; /* Remove default browser styling for select */
            -moz-appearance: none;
            appearance: none;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2196f3; /* HA Blue */
            box-shadow: 0 0 0 1px #2196f3;
        }
        
        select {
            background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 30px;
        }

        /* Buttons */
        input[type='submit'],
        .ha-button {
            background-color: #4CAF50; /* HA Green */
            color: white;
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
        
        .ha-button-blue {
            background-color: #2196f3; /* HA Blue */
        }

        .ha-button-red {
            background-color: #f44336; /* HA Red */
        }

        input[type='submit']:hover,
        .ha-button:hover {
            filter: brightness(1.1);
        }

        /* Link Styling */
        a {
            color: #2196f3; /* HA Blue */
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

        .link-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .link-item:last-child {
            border-bottom: none;
        }

        .link-item-section {
            flex: 1;
            min-width: 200px;
            margin-bottom: 8px;
        }

        .link-actions button {
            margin-right: 8px;
        }
        
        .link-item-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em;
        }

        .link-item-details li {
            margin-bottom: 4px;
        }
        
        .copy-link-input {
            width: calc(100% - 40px); /* Adjust for copy button */
            display: inline-block;
            vertical-align: middle;
        }
        .copy-button {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
            vertical-align: middle;
            margin-left: 8px;
        }
        .copy-button svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
            margin-bottom: 0;
        }
        
        .action-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 10px;
        }
        
        .action-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            background-color: rgb(55, 55, 55);
            padding: 8px;
            border-radius: 4px;
        }
        
        .action-item-details {
            flex-grow: 1;
            margin-left: 8px;
        }
        
        .action-item-buttons {
            display: flex;
            gap: 4px;
        }
        
        .action-item-buttons .ha-button {
            padding: 4px 8px;
            font-size: 0.8em;
        }

        .modify-password-form {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modify-password-form input[type="password"] {
            width: auto;
            min-width: 150px;
            margin-right: 8px;
        }
        .modify-password-form input[type="submit"] {
            margin-top: 0;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Instructions Specific */
        .instructions {
            background-color: rgb(40, 40, 40);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .instructions h3 {
            color: rgba(255, 255, 255, 0.9);
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.1em;
            text-align: left;
        }

        .instructions ul {
            list-style: disc;
            margin-left: 20px;
            padding: 0;
            color: rgba(255, 255, 255, 0.7);
        }

        .instructions li {
            margin-bottom: 5px;
        }
        
        /* Utility */
        .text-center {
            text-align: center;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .mt-4 {
            margin-top: 16px;
        }
    </style>
</head>
<body role="document">
    <a class="back-link" href="?">&larr; Back to Main Admin</a>
    
    <h1>Custom CSS & Footer Management</h1>
    
    <?php if (isset($_GET['saved']) && $_GET['saved'] === 'true'): ?>
        <div class="success-message">
            Configuration has been saved successfully!
        </div>
    <?php endif; ?>
    
    <div class="instructions card">
        <h3>Instructions:</h3>
        <ul>
            <li>Enter your custom CSS styles in the text area below. These styles will be applied to all user-facing pages.</li>
            <li>Enter custom HTML for your page footer in the footer text area below.</li>
            <li>Use standard CSS/HTML syntax respectively.</li>
            <li>Click "Save Configuration" when you're done.</li>
        </ul
>    </div>
    
    <div class="form-container card">
        <form action="?action=saveStyle" method="post">
            <label for="custom_css">Custom CSS</label>
            <textarea id="custom_css" name="custom_css" placeholder="/* Enter your custom CSS here */
body {
    background-color: #f0f0f0;
}

a {
    color: #007bff;
}"><?= htmlspecialchars($cssContent) ?></textarea>
            <br/>
            <label for="custom_footer">Custom Footer HTML</label>
            <textarea id="custom_footer" name="custom_footer" placeholder="<!-- Enter your custom footer HTML here -->"><?= htmlspecialchars($footerContent) ?></textarea>
            <br/>
            <input type="submit" value="Save Configuration" />
        </form>
    </div>
</body>
</html>
