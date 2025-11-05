<?php
include_once 'actions.php';
$actions = new \TekniskSupport\LimitedGuestAccess\Admin\Actions();
?><!DOCTYPE html>
<html>
<head>
    <title>Limited User Access Admin</title>
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

        /* Form Elements */
        label {
            display: block;
            margin-bottom: 8px;
            color: #e1e1e1;
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
            border: 1px solid #7d7d7d;
            border-radius: 4px;
            background-color: #101010;
            color: #e1e1e1;
            box-sizing: border-box;
            -webkit-appearance: none; /* Remove default browser styling for select */
            -moz-appearance: none;
            appearance: none;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #009ac7; /* HA Blue */
            box-shadow: 0 0 0 1px #009ac7;
        }
        
        select {
            background-image: url('data:image/svg+xml;utf8,<svg fill="e1e1e1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 30px;
        }

        /* Buttons */
        input[type='submit'],
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
        
        .ha-button-blue {
            background-color: #009ac7; /* Action Blue */
        }
        
        .ha-button-green { /* Defined as a new class based on user request */
            background-color: #d2e7b9; 
            color: #101010;
        }

        .ha-button-red {
            background-color: #db4437; /* HA Red */
        }

        input[type='submit']:hover,
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

        .link-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #7d7d7d;
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
            color: #e1e1e1;
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
            border-top: 1px solid #7d7d7d;
            padding-top: 10px;
        }
        
        .action-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            background-color: #101010;
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
            border-top: 1px solid #7d7d7d;
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
            background-color: #d2e7b9;
            color: #101010;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Icons */
        .icon {
            width: 20px;
            height: 20px;
            fill: currentColor;
            vertical-align: middle;
            margin-left: 5px;
            margin-right: 5px;
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
    <script type="text/javascript">
      function validateDates() {
        let v  = document.querySelector('#valid_from');
        let e  = document.querySelector('#expiry_time');
        let vd = document.querySelector('#valid_from_date').value;
        let vt = document.querySelector('#valid_from_time').value;
        let ed = document.querySelector('#expiry_time_date').value;
        let et = document.querySelector('#expiry_time_time').value;

        if (vd) {
          v.value = vd;
        }
        if (vt) {
          v.value = v.value + ' ' + vt
        }
        if (ed) {
          e.value = ed;
        }
        if (et) {
          e.value = e.value + ' ' + et
        }
      }

      function validateLink() {
        let e  = document.querySelector('#linkPath');
        e.value = e.value.replace(/[^a-z0-9]/ig,'');
      }

      let serviceData = <?= $actions->getServiceData() ?>;
      let states = <?= $actions->getStates() ?>;
      document.addEventListener('DOMContentLoaded', (event) => {
        if (document.querySelector('#service_call')) {
          serviceData.sort((a, b) => a.domain > b.domain ? 1 : -1);
          serviceData.forEach(domain => {
            if (undefined !== domain.services) {
              let services = Object.keys(domain.services);
              services.sort((a, b) => a.services > b.services ? 1 : -1);
              services.forEach(service => {
                let option = document.createElement('option');
                option.value = domain.domain + '.' + service;
                option.text = option.value;
                document.querySelector('#service_call').appendChild(option);
              })
            }
          });

          let sc = document.querySelector('#service_call');
          function populateForm() {
            let value = sc.options[sc.selectedIndex].value;
            let service_call = value.split(".");
            serviceData.forEach(domain => {
              if (domain.domain == service_call[0]) {
                Object.keys(domain.services).forEach(service => {
                  if (service == service_call[1]) {
                    document.querySelector('#dynamic_fields').innerHTML = '';
                    domain.services[service].fields = Object.assign({ "entity_id": {
                        "description": "entity_id",
                        "example": "switch.your_switch"
                      }
                    });
                    Object.keys(domain.services[service].fields).forEach(field => {
                      let label = document.createElement("label");
                      label.setAttribute('for', 'dynamic_field' + field);
                      label.innerHTML = field + '<br/><small><i>' +
                        domain.services[service].fields[field].description +
                        '</i></small>';
                      document.querySelector('#dynamic_fields').appendChild(label);
                      let br = document.createElement('br');
                      document.querySelector('#dynamic_fields').appendChild(br);
                      let input = document.createElement("input");
                      input.type = 'text';
                      input.placeholder = field;
                      input.name = 'dynamic_field[' + field + ']';
                      input.id = 'dynamic_field_' + field;
                      document.querySelector('#dynamic_fields').appendChild(input);
                      document.querySelector('#dynamic_fields').appendChild(br.cloneNode(true));
                    });
                  }
                })
              }
            });

            let isReplaced = false;
            states.sort((a, b) => (a.entity_id > b.entity_id) ? 1 : -1);
            states.forEach(state => {
              let entity = state.entity_id.split('.');
              if (service_call[0] == entity[0]) {
                if (document.querySelector("#dynamic_field_entity_id") !== null) {
                  if (!isReplaced) {
                    let entities = document.querySelector("#dynamic_field_entity_id");
                    let entitiesReplace = document.createElement('select');
                    if ("name" in entities) entitiesReplace.name = entities.name;
                    if ("id" in entities) entitiesReplace.id = entities.id;
                    if ("className" in entities) entitiesReplace.className = entities.className;
                    entities.parentNode.replaceChild(entitiesReplace, entities);
                    isReplaced = true;
                  }

                  let option = document.createElement('option');
                  let newEntities = document.querySelector("#dynamic_field_entity_id");
                  option.value = state.entity_id;
                  if (typeof state.attributes.friendly_name !== 'undefined') {
                    option.text = state.attributes.friendly_name + ' (' + option.value + ')';
                  } else{
                    option.text = option.value;
                  }
                  newEntities.appendChild(option);
                }
              }
            });
          }

          sc.addEventListener('change', (event) => {
            populateForm();
          });
          populateForm();

          const builtForm = new Event('builtForm');
          document.dispatchEvent(builtForm);
        }

        if (document.querySelector('.dateinput') && document.querySelector('.dateinput').type != "date")
        {
          let jqcss = document.createElement("link");
          jqcss.setAttribute('href', window.location.protocol + "//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css");
          jqcss.setAttribute('rel',  "stylesheet");

          let jqjs  = document.createElement('script');
          jqjs.src  = window.location.protocol + "//code.jquery.com/jquery-1.12.4.js";
          jqjs.id   = 'jquery';
          jqjs.type ='text/javascript';

          let jqui  = document.createElement('script');
          jqui.src  = window.location.protocol + "//code.jquery.com/ui/1.12.1/jquery-ui.js";
          jqui.type ='text/javascript';

          document.querySelector('head').appendChild(jqcss);
          document.querySelector('head').appendChild(jqjs);
          document.querySelector('head').appendChild(jqui);
          document.getElementById('jquery').addEventListener('load', function () {
            console.log('loaded!!');
            setTimeout(
              function(){
                $(function () {
                  $('.dateinput').datepicker();
                });
              },
              1000);

          });
        }
      });
    </script>
</head>
<body>
<?php
if (isset($_GET['page']) && $_GET['page'] === 'style'):
    include_once 'style.php';
elseif (isset($_GET['page']) && $_GET['page'] === 'logs'):
    include_once 'logs.php';
elseif (isset($_GET['page']) && $_GET['page'] === 'add_link'):
?>
<div class="back-link">
    <a class="ha-button ha-button-blue" href="?">&larr; Back to Main Admin</a>
</div>
<h1>Create New Link</h1>
<div class="card mb-4">
    <form action="?action=createNamedLink" method="post">
        <label for="linkPath">Optional Custom Path:</label>
        <input onblur="validateLink();" onchange="validateLink()" id="linkPath"
                type="text" name="linkPath" placeholder="e.g., my_custom_link" />

        <label for="theme">Theme:</label>
        <select name="theme" id="theme">
            <option selected="selected" value="default">Default - Dark</option>
            <option value="light-grey">Light - Grey</option>
            <option value="dark-blue">Dark - Blue</option>
            <option value="light-blue">Light - Blue</option>
        </select>

        <label for="password">Optional Password:</label>
        <input type="password" name="password" id="password" placeholder="Max 72 characters" maxlength="72" />

        <input type="submit" value="Create Link" class="ha-button ha-button-blue" />
    </form>
</div>
<?php else: ?>
<h1>Limited User Access Admin</h1>

<?php if (isset($_GET['saved']) && $_GET['saved'] === 'true'): ?>
    <div class="success-message">
        Configuration has been saved successfully!
    </div>
<?php endif; ?>

<div class="card mb-4 text-center">
    <a class="ha-button ha-button-blue" href="?page=add_link">
        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" /></svg>
        Add New Link
    </a>
    <a class="ha-button ha-button-blue" href="?page=style">
        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M12,18.5C12.97,18.5 13.75,19.28 13.75,20.25C13.75,21.22 12.97,22 12,22C11.03,22 10.25,21.22 10.25,20.25C10.25,19.28 11.03,18.5 12,18.5M12,2C11.03,2 10.25,2.78 10.25,3.75C10.25,4.72 11.03,5.5 12,5.5C12.97,5.5 13.75,4.72 13.75,3.75C13.75,2.78 12.97,2 12,2M12,10.25C11.03,10.25 10.25,11.03 10.25,12C10.25,12.97 11.03,13.75 12,13.75C12.97,13.75 13.75,12.97 13.75,12C13.75,11.03 12.97,10.25 12,10.25M17.85,7L21.75,8.15L20.6,12L17.85,7M3,12L4.15,8.15L8.05,7L3,12M12,15.25C11.66,15.25 11.33,15.19 11,15.15V16L12,16.5L13,16V15.15C12.67,15.19 12.34,15.25 12,15.25M12,8.75C12.34,8.75 12.66,8.81 13,8.85V8L12,7.5L11,8V8.85C11.33,8.81 11.66,8.75 12,8.75Z" /></svg>
        Manage Customization
    </a>
    <a class="ha-button ha-button-blue" href="?page=logs">
        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" /></svg>
        View Login Attempts
    </a>
</div>


<?php
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'addAction' || $_REQUEST['action'] == 'adjustAction')) :
    $queryParams =  ($_REQUEST['action'] == 'adjustAction')
        ? '?action=editAction&id='. $_REQUEST['id'] .'&action_id=' . $_REQUEST['action_id']
        : '?action=addActionToLink&id='. $_REQUEST['id'];
    if ($_REQUEST['action'] == 'adjustAction') {
        $data = json_decode(file_get_contents("/data/links/{$_REQUEST['id']}.json"));
    }
?>
    <div class="card mb-4">
        <h2 class="card-header"><?= ($_REQUEST['action'] == 'adjustAction') ? 'Edit Action' : 'Add Action to Link' ?></h2>
        <form action="<?= $queryParams ?>" method="post" onsubmit="validateDates();">
            <label for="friendly_name">Friendly name:</label>
            <input id="friendly_name" required name="friendly_name" type="text" />

            <label for="service_call">Service call:</label>
            <select id="service_call" required name="service_call"></select>

            <div id="dynamic_fields"></div>

            <label for="valid_from_date">Valid from:</label>
            <input id="valid_from_date"
                   placeholder="YYYY-MM-DD"
                   class="dateinput"
                   name="valid_from_date"
                   type="date"
                   min="<?= date('Y-m-d',time())?>"
                   value="<?= date('Y-m-d',time())?>"
                   onchange="validateDates();(function(){
                       document.querySelector('#expiry_time_date').min = document.querySelector('#valid_from_date').value;
                   })()"
            />
            <input id="valid_from_time"
                   onchange="validateDates();"
                   name="valid_from_time"
                   class="timeinput"
                   placeholder="HH:MM"
                   type="time"
                   value="00:00"
            />
            <input name="valid_from" type="hidden" id="valid_from" />

            <label for="expiry_time_date">Expiry time:</label>
            <input id="expiry_time_date"
                   name="expiry_time_date"
                   class="dateinput"
                   placeholder="YYYY-MM-DD"
                   type="date"
                   min="<?= date('Y-m-d',time())?>"
                   value="<?= date('Y-m-d',time());?>"
                   onchange="validateDates();"
            />
            <input id="expiry_time_time"
                   name="expiry_time_time"
                   class="timeinput"
                   type="time"
                   placeholder="HH:MM"
                   onchange="validateDates();"
                   value="23:59"
            />
            <input name="expiry_time" type="hidden" id="expiry_time" />

            <label for="one_time_use">One time use?</label>
            <input type="checkbox" value="1" name="one_time_use" id="one_time_use" />
            <span class="ha-checkbox-label"></span><br/>

            <input type="submit" value="Save Action" class="ha-button ha-button-blue" />
        </form>
    </div>
    <script type="text/javascript">
      let data = <?= (isset($data) && isset($data->{$_GET['action_id']})) ? json_encode($data->{$_GET['action_id']}) : '[]' ?>;
      function fillData() {
        Object.keys(data).forEach(function (key) {
          if (document.querySelector('[name=' + key + ']')) {
            if (key == 'valid_from') {
              let dateParts = data[key].split(' ');
              let date = dateParts[0];
              let time = dateParts[1];
              if (date)
                document.querySelector('#valid_from_date').value = date;
              if (time)
                document.querySelector('#valid_from_time').value = time;
            }
            if (key == 'expiry_time') {
              let dateParts = data[key].split(' ');
              let date = dateParts[0];
              let time = dateParts[1];
              if (date)
                document.querySelector('#expiry_time_date').value = date;
              if (time)
                document.querySelector('#expiry_time_time').value = time;
            }
            if (document.querySelector('[name=' + key + ']').type == 'checkbox') {
              if (data[key] == 1) {
                document.querySelector('[name=' + key + ']').checked = 'checked';
              }
            } else {
                document.querySelector('[name=' + key + ']').value = data[key];
            }
            let change = new Event("change");
            document.querySelector('[name=' + key + ']').dispatchEvent(change);
            if ('service_call_data' in data) {
              Object.keys(data['service_call_data']).forEach(function (serviceCallData) {
                if (document.querySelector('#dynamic_field_' + serviceCallData)) {
                  document.querySelector('#dynamic_field_' + serviceCallData).value = data['service_call_data'][serviceCallData];
                  let change = new Event("change");
                  document.querySelector('#dynamic_field_' + serviceCallData).dispatchEvent(change);
                }
              })
            }
          }
        })
      }
      fillData();
      document.addEventListener('builtForm', function (e) { fillData(); }, false);
    </script>
<?php endif; ?>

<div class="card">
    <h2 class="card-header">Existing Links</h2>
    <?php if (empty($actions->getAllLinks())): ?>
        <p>No links created yet.</p>
    <?php else: ?>
        <?php foreach($actions->getAllLinks() as $link) :
            $link = str_replace([$actions::DATA_DIR,'.json'], '', $link);
            $data = json_decode(file_get_contents("/data/links/{$link}.json"),false);
            unset($data->linkData);
        ?>
            <div class="link-item">
                <div class="link-item-section">
                    Link:
                    <input class="copy-link-input" id="copyLink<?=$link?>" type="text"
                           value="<?= $actions->externalUrl ?><?= $link ?>/" readonly/>
                    <button type="button" class="ha-button" onclick='(function() {
                        var copyText = document.getElementById("copyLink<?=$link?>");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                        alert("Link copied to clipboard!");
                    })();'>
                        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" /></svg>
                        Copy
                    </button>
                </div>
                <div class="link-item-section link-actions">
                    <a class="ha-button ha-button-blue" href="?action=addAction&id=<?= $link ?>">
                        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" /></svg>
                        Add Action
                    </a>
                    <a class="ha-button ha-button-red" href="?action=deleteLink&id=<?= $link ?>" onclick="return confirm('Are you sure you want to delete this link?');">
                        <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" /></svg>
                        Delete Link
                    </a>
                </div>
                <div class="link-item-section link-item-details">
                    <?php if (!is_null($data) && !empty((array)$data)): ?>
                        <ul class="action-list">
                            <?php foreach($data as $action => $entry):
                                $lastUsed = $entry->last_used ?? [];
                            ?>
                                <li class="action-item">
                                    <div class="action-item-details">
                                        <?= htmlspecialchars($entry->friendly_name) ?><br/>
                                        <small>Times used: <?= count($lastUsed) ?></small><br/>
                                        <?php if (!empty($lastUsed)): ?>
                                            <small>Last used: <?= date('Y-m-d H:i:s', end($lastUsed)) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="action-item-buttons">
                                        <a class="ha-button ha-button-blue" href="?action=adjustAction&id=<?= $link ?>&action_id=<?= $action ?>">
                                            <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" /></svg>
                                            Edit
                                        </a>
                                        <a class="ha-button ha-button-red" href="?action=removeAction&id=<?= $link ?>&action_id=<?= $action ?>" onclick="return confirm('Are you sure you want to remove this action?');">
                                            <svg class="icon" viewBox="0 0 24 24"><path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" /></svg>
                                            Remove
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No actions defined for this link.</p>
                    <?php endif; ?>

                    <div class="modify-password-form">
                        <form action="?action=modifyPassword&id=<?= $link ?>" method="post">
                            <input type="password" name="new_password" placeholder="New password" maxlength="72" required />
                            <input type="submit" value="Change Password" class="ha-button ha-button-blue" />
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php endif; ?>
</body>
</html>
