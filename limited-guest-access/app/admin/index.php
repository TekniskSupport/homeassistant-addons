<?php
include 'actions.php';
$actions = new \TekniskSupport\LimitedGuestAccess\Admin\Actions();
?><!DOCTYPE>
<html>
<head>
    <title>Limited User Access Admin</title>
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
        }

        h1 {
            margin: 0 auto;
            text-align: center;
        }

        svg {
            margin-bottom: -7px;
        }

        input, textarea, select {
            width: 70%;
            background:transparent;
            margin: 5px 0px;
            padding: 1%;
            border: 2px solid #1a1a1a;
            transition-duration:0.5s;
        }
        input[type='date'], input[type='time'] {
            width: 30%;
        }
        input[type='date'] + input[type='time'] {
            margin-left: -5px;
        }
        input[type='submit'] {
            color: #fff;
            background-color: #1a1a1a;
            border: 2px solid #1a1a1a;
            transition-duration:0.3s;
        }
        input[type='submit']:hover {
            color: #000;
            background:transparent;
            transition-duration:0.3s;
        }

        input:focus, textarea:focus {
            border-left:10px solid #1a1a1a;
            transition-duration:0.5s;
        }

        input.copylink {
            line-height: 30px;
        }

        .createNewLink {
            font-size: 22px;
            padding: .5rem 1rem;
            border: 1px solid #1a1a1a;
            background-color: whitesmoke;
            min-width: 25%;
            border-radius: 3px;
            display: inline-block;
            color: #1a1a1a;
            text-decoration: none;
            text-align: center;
        }

        a.link,
        a.link:link,
        a.link:visited,
        a.link:active {
            margin: .5rem;
            padding: .5rem 1rem;
            position:relative;
            border: 1px solid #1a1a1a;
            border-radius: 3px;
            color: #1a1a1a;
            text-decoration: none;
        }
        a:hover {
            background-color: white;
        }

        .row:nth-child(odd) {
            background-color: whitesmoke;
        }
        .row {
            padding-left: 2rem;
        }

        .column {
            padding: 1rem 0;
        }
        @media only screen and (min-width: 800px) {
            .row {
                display: flex;
            }

            .column {
                flex: 1;
            }

            a, a:link, a:visited, a:active {
                bottom: -.8rem;
            }
        }

        @media only screen and (max-width: 799px) {
            .row {
                display: block;
            }

            .column {
                display: inline-block
            }
        }

        ul {
            list-style: none;
            text-align: left;
            margin: 0;
            padding: 0;
        }

        a.link.noBorder,
        a.link.noBorder:link,
        a.link.noBorder:visited,
        a.link.noBorder:hover,
        a.link.noBorder:active
        {
            border: none;
            margin: 0;
            padding: 0 0;
            position: initial;
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

      let serviceData = <?= $actions->getServiceData() ?>;
      let states = <?= $actions->getStates() ?>;
      document.addEventListener('DOMContentLoaded', (event) => {
        serviceData.forEach(domain => {
            if (undefined !== domain.services) {
                Object.keys(domain.services).forEach(service => {
                    let option = document.createElement('option');
                    option.value = domain.domain + '.' + service;
                    option.text  = option.value;
                    document.querySelector('#service_call').appendChild(option);
                })
          }
        });

        let sc = document.querySelector('#service_call');
        sc.addEventListener('change', (event) => {
          let value = sc.options[sc.selectedIndex].value;
          let service_call = value.split(".");
          serviceData.forEach(domain => {
            if (domain.domain == service_call[0]) {
              Object.keys(domain.services).forEach(service => {
                if (service == service_call[1]) {
                  document.querySelector('#dynamic_fields').innerHTML = '';
                  Object.keys(domain.services[service].fields).forEach(field => {

                    let label = document.createElement("label");
                    label.setAttribute('for', 'dynamic_field' + field);
                    label.innerHTML = field + '<br/><small><i>' +
                      domain.services[service].fields[field].description  +
                      '</i></small>';
                    document.querySelector('#dynamic_fields').appendChild(label);
                    let br = document.createElement('br');
                    document.querySelector('#dynamic_fields').appendChild(br);
                    let input = document.createElement("input");
                    input.type = 'text';
                    input.placeholder = field;
                    input.name = 'dynamic_field['+ field +']';
                    input.id = 'dynamic_field_' + field;
                    document.querySelector('#dynamic_fields').appendChild(input);
                    document.querySelector('#dynamic_fields').appendChild(br.cloneNode(true));
                  });
                }
              })
            }
          });

          let isReplaced = false;
          states.forEach(state => {
            let entity = state.entity_id.split('.');
            if (service_call[0] == entity[0]) {
              if (typeof document.querySelector("#dynamic_field_entity_id") !== undefined) {
                if (!isReplaced) {
                  let entities = document.querySelector("#dynamic_field_entity_id");
                  let entitiesReplace = document.createElement('select');
                  if (entities.name) entitiesReplace.name = entities.name;
                  if (entities.id) entitiesReplace.id = entities.id;
                  if (entities.className) entitiesReplace.className = entities.className;
                  entities.parentNode.replaceChild(entitiesReplace, entities);
                  isReplaced = true;
                }

                let option = document.createElement('option');
                let newEntities = document.querySelector("#dynamic_field_entity_id");
                option.value = state.entity_id;
                option.text = option.value;
                newEntities.appendChild(option);
              }
            }
          });
        });

        if (document.querySelector('.dateinput').type != "date")
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
<body role="document">
    <a class="createNewLink" href="?action=generateNewLink">Create link</a>
    <br/><br/>
<?php
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addAction') :?>
    <fieldset>
        <legend>Add action to link:</legend>
        <form
            action="?action=addActionToLink&id=<?= $_GET['id'] ?>"
            method="post"
            onsubmit="validateDates();"
        >
            <label for="friendly_name">Friendly name:</label><br/>
            <input id="friendly_name" required name="friendly_name" type="text" />
            <br/>

            <label for="service_call">Service call:</label><br/>
            <select id="service_call" required name="service_call">
            </select>
            <br/>

            <div id="dynamic_fields">

            </div>

            <label for="valid_from_date">Valid from:</label><br/>
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
            />
            <input name="valid_from" type="hidden" id="valid_from" />
            <br/>

            <label for="expiry_time_date">Expiry time:</label><br/>
            <input id="expiry_time_date"
                   name="expiry_time_date"
                   class="dateinput"
                   placeholder="YYYY-MM-DD"
                   type="date"
                   min="<?= date('Y-m-d',time())?>"
                   value="<?= date('Y-m-d',time())?>"
                   onchange="validateDates();"
            />
            <input id="expiry_time_time"
                   name="expiry_time_time"
                   class="timeinput"
                   type="time"
                   placeholder="HH:MM"
                   onchange="validateDates();"
            />
            <input name="expiry_time" type="hidden" id="expiry_time" />
            <br/>

            <label for="one_time_use">One time use?</label><br/>
            <input type="checkbox" value="1" name="one_time_use" id="one_time_use" />
            <br/>

            <input type="submit" />
        </form>
    </fieldset>
    <br/><br/>
<?php endif;
foreach($actions->getAllLinks() as $link) :
    $link = str_replace([$actions::DATA_DIR,'.json'], '', $link);
    $data = json_decode(file_get_contents("/data/links/{$link}.json"),false);
?>
    <div class="row">
        <div class="column">
            Link: <input class="link copylink" id="copyLink<?=$link?>" type="text" value="<?= $actions->externalUrl ?><?= $link ?>/" />
            <span class="copy" style="cursor:pointer;" onclick='(function() {
              var copyText = document.getElementById("copyLink<?=$link?>");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
            })();'>
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" />
                </svg>
            </span>
        </div>
        <div class="column">
            <a class="link" href="?action=addAction&id=<?= $link ?>">
                Add action
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" />
                </svg>
            </a>
        </div>
        <div class="column">
            <a class="link" href="?action=deleteLink&id=<?= $link ?>">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" />
                </svg>
            </a>
        </div>
        <div class="column">
            <ul>
                <?php
                if (!is_null($data)) {
                    foreach($data as $action => $entry):?>
                        <li>
                            <a class="link noBorder" href="?action=removeAction&id=<?= $link ?>&action_id=<?= $action ?>">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" />
                                </svg>
                            </a>
                            <?= $entry->friendly_name ?>
                        </li>
                        <?php
                    endforeach;
                }
                ?>
            </ul>
        </div>
    </div>
<?php endforeach; ?>
</body>
</html>