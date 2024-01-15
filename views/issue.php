<section id="issues">
    <h3>Work Issues</h3>
<?php
$me = new Guide($_SESSION['usermail'], $pdo);
$mytime = new DateTime('NOW');
$form_action = '?issues';
$submit = "add issue";
$first_aid = $hospital_visit = '';
if (isset($_POST['datetime'], $_POST['place'], $_POST['description'])) {
    $issueId = (isset($_GET['id']) ? $_GET['id'] : insertInto('issue', 'user_id', $me->id, $pdo));
    $issueId = (is_array($issueId) ? $issueId['id'] : $issueId);
    $inputs = array('datetime', 'place', 'description', 'injury');
    $checks = array('first_aid', 'hospital_visit');
    $me->updateTable('issue', $issueId, $inputs, $checks, $pdo);
    header( "refresh:0;url=./?issues" );
}

if (isset($_GET['id'])) {
    $iss = selectAllFromWhere('issue', 'id', $_GET['id'], $pdo);
    sessionForm($iss[0], TRUE);
    $form_action = '?issues&id='.$_GET['id'];
    $submit = "update issue";
    $first_aid = ($_SESSION['first_aid'] ? 'checked' : '');
    $hospital_visit = ($_SESSION['hospital_visit'] ? 'checked' : '');
}

echo '  
    <form action="'.$form_action.'" method="POST">
        <input type="datetime-local" id="datetime" name="datetime" required max="'.$mytime->format("Y-m-d H:i").'" value="'.value('datetime').'">
        <input type="text" id="place" name="place" required maxlength="150" placeholder="place" value="'.value('place').'">
        <textarea id="description" name="description" required maxlength="270" placeholder="description">'.value('description').'</textarea><br>
        <textarea id="injury" name="injury" maxlength="270" placeholder="injury">'.value('injury').'</textarea><br>
        <input type="checkbox" id="first_aid" name="first_aid" '.$first_aid.'> first aid<br>
        <input type="checkbox" id="hospital_visit" name="hospital visit" '.$hospital_visit.'> hospital visit<br>
        <input type="submit" class="button" value="'.$submit.'"><br>
    </form> ';

if (isset($iss)){
    sessionForm($iss[0], FALSE);
}

if (count($me->issue) > 0) {
    echo '<ol>';
    foreach ($me->issue as $i){
        $issueclass = (empty($i['injury']) ? 'class_orange' : 'class_red'); 
        echo '  <li class="'.$issueclass.'"><a href="?issues&id='.$i['id'].'">'.date("d-m-Y G:i", strtotime($i['datetime'])).' - '.$i['place'].' - '.$i['description'].'</a>';
        if (!empty($i['injury']))
            echo ' - '.$i['injury'];
        if ($i['first_aid'])
            echo ' -  first aid';
        if ($i['hospital_visit'])
            echo ' -  hospital visit';
        echo '</li>';
    }
    echo '</ol>';
}
else {
    echo "<p>You don't have any issues. Yay!</p>";
    }

?>
</section>
