<?php
session_start();

include('redirect.inc');

if (!$_SESSION["userId"]) {
    Redirect('/poke/login_form.html', false);
}

if (!$_SESSION["userLoginId"]) {
    Redirect('/poke/login_form.html', false);
}

$modeName = 'small';
if ($_GET["mode"]) {
    $tmp_modeName = $_GET["mode"];
    if ($tmp_modeName == 'normal') {
        $modeName = 'normal';
    } else if ($tmp_modeName == 'noimage') {
        $modeName = 'noimage';
    }
}

function createCard($id, $modeName) {
    $colStyle = 'col-4 col-lg-2';
    echo "<div id=\"monster-root-panel-$id\" data-monster-number='$id' class=\"$colStyle monster-root-panel\" style=\"display:none;\">";
    echo "  <div class=\"card\">";
    echo "  <div class=\"monster-detail-header clickable card-header\">";
    echo "    <div>";
    echo "    <span class=\"monster-number clickable\"></span>";
    echo "    <span class=\"monster-name clickable\"></span>";
    echo "    </div>";
    echo "    <div style=\"height:20px;\">";
    echo "    <span class=\"monster-owner-badge badge\" style=\"display:inline;\"></span>";
    echo "    <span class=\"monster-owner-my-badge badge badge-warning\" style=\"display:none;\">나</span>";
    echo "    <span class=\"monster-owner-new-badge badge badge-success\" style=\"display:none;\">N</span>";
    echo "    <span class=\"badge badge-success\" style=\"visibility:hidden;\">나</span>";
    echo "    </div>";
    echo "  </div>";
    echo "  <div class=\"card-block monster-detail-panel clickable\">";
    echo "    <p class=\"card-text monster-face-panel \" style=\"text-align:center;\">";
    echo "    <a class=\"owner-button fa fa-fw fa-user\"></a>";
    if ($modeName != 'noimage') {
        echo "      <img class=\"monster-face-image $modeName\">";
    }
    echo "    </p>";
    echo "    <button id=\"have-button\" class=\"have-button btn btn-primary form-control\"></button>";
    echo "  </div>";
    echo "  </div>";
    echo "</div>";
    echo "<!-- #monster-root-panel -->";
}

echo "<html>";
echo "<head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">";

echo "";
echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css\" integrity=\"sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ\" crossorigin=\"anonymous\">";
echo "<link href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css\" rel=\"stylesheet\" integrity=\"sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN\" crossorigin=\"anonymous\">";
echo "";

echo "<script src=\"https://code.jquery.com/jquery-3.1.1.min.js\" integrity=\"sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=\" crossorigin=\"anonymous\"></script>";
echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js\" integrity=\"sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb\" crossorigin=\"anonymous\"></script>";
echo "<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js\" integrity=\"sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn\" crossorigin=\"anonymous\"></script>";
echo "";

echo "<link rel=\"stylesheet\" href=\"/poke/include/poke/css/monster-list.css\">";
echo "<script src=\"/poke/include/poke/js/monster-list.js\"></script>";
echo "";
echo "</head>";
echo "<body>";
echo "    <input type=\"hidden\" id=\"image-postfix\" value=\"_n\" />";
echo "    <div class=\"container-fluid\" style=\"margin-top:10px;\">";
echo "            <a class=\"btn btn-primary logout-button\" href=\"/poke/logout.php\" role=\"button\" style=\"position:absolute;right:4;top:0;\">LOGOUT</a>";
echo "    <span>";
echo "        <ul class=\"nav nav-tabs\">";
echo "          <li class=\"nav-item\">";
echo "            <a id=\"owner-tab-all\" class=\"owner-tab-item nav-link active\" href=\"#\">전체</a>";
echo "          </li>";
echo "          <li class=\"nav-item\">";
echo "            <a id=\"owner-tab-my\" class=\"owner-tab-item nav-link\" href=\"#\">나</a>";
echo "          </li>";
echo "          <li class=\"nav-item\">";
echo "            <a id=\"owner-tab-other\" class=\"owner-tab-item nav-link\" href=\"#\">남</a>";
echo "          </li>";
echo "        </ul>";
echo "    </span>";
echo "        <div class=\"row\" style=\"display:block;\" id=\"filter-type-panel\">";
echo "            <div class=\"col-12\">";
echo "                <span id=\"rare-count\" class=\"monster-count-label rare\"></span> <span id=\"normal-count\" class=\"monster-count-label normal\"></span>  <span id=\"total-catch-count\" class=\"monster-count-label small\"></span> <span id=\"unfound-count\" class=\"monster-count-label unfound\"></span>";
echo "            </div>";
echo "            <div class=\"col-12\">";
echo "                <span>";
echo "                    <label class=\"filter-check custom-control custom-checkbox\">";
echo "                        <input id=\"filter-rare-checkbox\" type=\"checkbox\" class=\"filter-category-checkbox custom-control-input\" checked>";
echo "                        <span class=\"custom-control-indicator\"></span>";
echo "                        <span class=\"custom-control-description\">레어</span>";
echo "                    </label>";
echo "                    <label class=\"filter-check custom-control custom-checkbox\">";
echo "                        <input id=\"filter-normal-checkbox\" type=\"checkbox\" class=\"filter-category-checkbox custom-control-input\" checked>";
echo "                        <span class=\"custom-control-indicator\"></span>";
echo "                        <span class=\"custom-control-description\">잡</span>";
echo "                    </label>";
echo "                    <label class=\"filter-check custom-control custom-checkbox\">";
echo "                        <input id=\"filter-unfound-checkbox\" type=\"checkbox\" class=\"filter-category-checkbox custom-control-input\" checked>";
echo "                        <span class=\"custom-control-indicator\"></span>";
echo "                        <span class=\"custom-control-description\">미발견</span>";
echo "                    </label>";
echo "                </span>";
echo "            </div>";
echo "        </div>";
echo "";
echo "        <div class=\"row\">";

for ($id = 1; $id <= 250; $id++) {
    createCard($id, $modeName);
}

echo "        </div>";

echo "        <div id=\"ownerModal\" class=\"modal fade\" role=\"dialog\">";
echo "            <div class=\"modal-dialog\">";
echo "                <div class=\"modal-content\">";
echo "                    <div class=\"modal-header\">";
echo "                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>";
echo "                        <h4 class=\"modal-title\">가진 사람</h4>";
echo "                    </div>";
echo "                    <div class=\"modal-body\">";
echo "                        <p id=\"ownerListText\" class=\"monster-owner-text small\">Some text in the modal.</p>";
echo "                    </div>";
echo "                    <div class=\"modal-footer\">";
echo "			      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>";
echo "                    </div>";
echo "                </div>";
echo "            </div>";
echo "        </div>";
echo "    </div>";
echo "</body>";
echo "</html>";
?>
