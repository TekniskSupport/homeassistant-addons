<?php
include 'actions.php';
$actions = new \TekniskSupport\LimitedGuestAccess\Admin\Actions();

// Load existing CSS if it exists
$cssContent = '';
$cssFile = '/data/style.css';
if (file_exists($cssFile)) {
    $cssContent = file_get_contents($cssFile);
}
?><!DOCTYPE>
<html>
<head>
    <title>Limited User Access Admin - Custom CSS</title>
    <meta charset="utf-8"/>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8;'/>
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

        * {
            font-family: "Open Sans", verdana, arial, sans-serif;
        }

        body {
            height: 100%;
            background-color: white;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            margin: 0 auto 20px;
            text-align: center;
            color: #1a1a1a;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #1a1a1a;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .form-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 400px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            padding: 10px;
            border: 1px solid #1a1a1a;
            border-radius: 3px;
            background-color: #fff;
            resize: vertical;
        }

        input[type='submit'] {
            background-color: #1a1a1a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type='submit']:hover {
            background-color: #333;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .instructions {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .instructions h3 {
            margin-top: 0;
            color: #1a1a1a;
        }

        .instructions ul {
            margin-bottom: 0;
        }

        .instructions li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body role="document">
    <a class="back-link" href="?">&larr; Back to Main Admin</a>
    
    <h1>Custom CSS Management</h1>
    
    <?php if (isset($_GET['saved']) && $_GET['saved'] === 'true'): ?>
        <div class="success-message">
            Custom CSS has been saved successfully!
        </div>
    <?php endif; ?>
    
    <div class="instructions">
        <h3>Instructions:</h3>
        <ul>
            <li>Enter your custom CSS styles in the text area below</li>
            <li>Your styles will be applied to all user-facing pages</li>
            <li>Use standard CSS syntax</li>
            <li>Click "Save CSS" when you're done</li>
        </ul>
    </div>
    
    <div class="form-container">
        <form action="?action=saveStyle" method="post">
            <textarea name="custom_css" placeholder="/* Enter your custom CSS here */
body {
    background-color: #f0f0f0;
}

a {
    color: #007bff;
}"><?= htmlspecialchars($cssContent) ?></textarea>
            <br/>
            <input type="submit" value="Save CSS" />
        </form>
    </div>
</body>
</html>
