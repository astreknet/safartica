<section id="my_gigs">
    <h3>My Trips</h3>
<?php
$me = new Guide($_SESSION['usermail'], $pdo);
if (isset($_GET['tid']) && $me->userlevel > 0 ) {
    $gig = selectAllFromWhere('gig', 'id', $_GET['tid'], $pdo);
    $safari = selectAllFromWhere('safari', 'id', $gig[0]['safari_id'], $pdo);
    $mytime = new DateTime($gig[0]['datetime']);
    $diff15Min = new DateInterval('PT15M');
    $nearmiss = selectAllFromWhere('nearmiss', 'gig_id', $_GET['tid'], $pdo);
    #$accident = selectAllFromWhere('accident', 'gig_id', $_GET['tid'], $pdo);
    #$h4class = (count($accident) > 0 ? 'class_red' : (count($nearmiss) > 0 ? 'class_orange' : (is_null($gig[0]['remarks']) ? 'class_pale' : 'class_green')));
    $h4class = (count($nearmiss) > 0 ? 'class_orange' : (is_null($gig[0]['remarks']) ? 'class_pale' : 'class_green'));
    
    if (!empty($gig[0]['travius'])) { 
        echo '<h4 class="'.$h4class.'"><a href="'.$gig[0]['travius'].'" target="_blank">'.$safari[0]['name'].', '.date("j M Y G:i", strtotime($gig[0]['datetime'])).'</a></h4>';
    }
    else {
        echo '<h4 class="'.$h4class.'">'.$safari[0]['name'].', '.date("j M Y G:i", strtotime($gig[0]['datetime'])).'</h4>';
    }

    #echo var_dump($_SESSION).'<br>';
    #echo var_dump($acc).'<br>';
    #echo var_dump($me->accident).'<br>';


### UPDATE TRIP ######################################
    if (isset($_POST['start'], $_POST['end'], $_POST['route'], $_POST['weather'], $_POST['temp'], $_POST['remarks']) && $me->userlevel > 0) {
        $inputs = array('start', 'end', 'route', 'weather', 'temp', 'remarks');
        $checks = array('accident');
        $me->updateTable('gig', $_GET['tid'], $inputs, $checks, $pdo);
        header( "refresh:0;url=./" );
    }
    sessionForm($gig[0], TRUE);
    $accident = ($_SESSION['accident'] ? 'checked' : '');
    echo '
        <div id="update_gig"> 
        <form action="" method="POST">
            Start Time: <input type="time" id="start" name="start" required value="'.$gig[0]['start'].'"><br>
            End Time: <input type="time" id="end" name="end" required value="'.$gig[0]['end'].'"><br>
            Route: <input type="text" id="route" name="route" required maxlength="150" placeholder="route" value="'.$gig[0]['route'].'"><br>
            Weather: <input type="text" id="weather" name="weather" required maxlength="150" value="'.$gig[0]['weather'].'"><br>
            Temperature: <input type="number" id="temp" placeholder="-5" step="0.5" min="-45" max="30" name="temp" required value="'.$gig[0]['temp'].'"> C<br> 
            <textarea id="remarks" name="remarks" maxlength="410" placeholder="Anything remarkable?">'.$gig[0]['remarks'].'</textarea>
            Accident: <input type="checkbox" id="accident" name="accident" '.$accident.'><br>
            <input type="submit" class="button" value="update gig">
        </form>
        </div>';
    
### NEAR MISS ########################################        
    $form_miss = '';
    $submit = "add near miss";
    if (isset($_POST['nm_datetime'], $_POST['nm_place'], $_POST['nm_description'])) {
        $nearmissId = (isset($_GET['miss']) ? $_GET['miss'] : insertInto('nearmiss', 'user_id', $me->id, $pdo));
        $nearmissId = (is_array($nearmissId) ? $nearmissId['id'] : $nearmissId);
        updateTableItemWhere('nearmiss', 'gig_id', $_GET['tid'], 'id', $nearmissId, $pdo);
        $inputs = array('nm_datetime', 'nm_place', 'nm_description');
        $checks = array('guide', 'customer', 'third');
        $me->updateTable('nearmiss', $nearmissId, $inputs, $checks, $pdo);
        header( "refresh:0;url=./?tid=".$_GET['tid'] );
    }
    echo '  
        <div id="near_miss">
            <h5 id="nearmiss_report">Near Miss</h5>';
            
    ### UPDATE NEAR MISS ############################
    if (isset($_GET['tid'],$_GET['miss'])) {
        $miss = selectAllFromWhere('nearmiss', 'id', $_GET['miss'], $pdo);
        sessionForm($miss[0], TRUE);
        $form_miss = '?tid='.$_GET['tid'].'&miss='.$_GET['miss'];
        $submit = "update near miss";
        $customer = ($_SESSION['customer'] ? 'checked' : '');
        $guide = ($_SESSION['guide'] ? 'checked' : '');
        $third = ($_SESSION['third'] ? 'checked' : '');
    }
    
    echo '  <form action="'.$form_miss.'" method="POST">
                <select name="nm_datetime" required>
                    <option value="" selected disabled hidden>Time</option>';
        for ($i = 0; $i < ($safari[0]['length']/15)+6; $i++){
            $sel = (((value('nm_datetime') == $mytime->format("Y-m-d H:i:s"))) ? 'selected' : '');
            echo '  <option value="'.$mytime->format("Y-m-d H:i").'" '.$sel.'>'.$mytime->format('H:i').'</option>';
            $mytime->add($diff15Min);
        }
    echo '      </select>
                <input type="text" id="nm_place" name="nm_place" required maxlength="150" placeholder="place" value="'.value('nm_place').'">
                <textarea id="nm_description" name="nm_description" required maxlength="270" placeholder="description">'.value('nm_description').'</textarea><br>
                <input type="checkbox" id="guide" name="guide" '.$guide.'> guide<br>
                <input type="checkbox" id="customer" name="customer" '.$customer.'> customer<br>
                <input type="checkbox" id="third" name="third" '.$third.'> other<br>
                <input type="submit" class="button" value="'.$submit.'"><br>
            </form> ';
    if (isset($miss)){
        sessionForm($miss[0], FALSE);
    }

    if (count($nearmiss) > 0) {
        echo '<ul>';
        foreach ($nearmiss as $n){
            #$saf = selectAllFromWhere('safari', 'id', $gig['safari_id'], $pdo);
            $involved = ($n['guide'] && $n['customer'] ? 'guide and customer' : ($n['guide'] ? 'guide' : 'customer'));
            echo '  <li><a href="?tid='.$_GET['tid'].'&miss='.$n['id'].'#nearmiss_report">'.date("G:i", strtotime($n['nm_datetime'])).' - '.$n['nm_place'].' - '.$n['nm_description'].' - '.$involved.'</a></li>';
        }
        echo '</ul>';
    }
    else {
        echo "<p>You don't have any near miss in this gig. Yay!</p>";
    }
    echo '</div>';        
}

else {
    $nowtime = new DateTime('NOW');
    $maxtime = new DateTime('NOW');
    $min = (($nowtime->format("i") > 29) ? 60 : 30);
    $diffMin = new DateInterval('PT'.($min - $nowtime->format('i')).'M');
    $nowtime = $nowtime->add($diffMin);
    $maxtime = $maxtime->add($diffMin);
    $diff15Min = new DateInterval('PT15M');
    $diff30Min = new DateInterval('PT30M');
    $diff6H = new DateInterval('PT6H');
    $maxtime = $maxtime->add($diff6H);

    if (isset($_POST['safari_id'], $_POST['datetime'], $_POST['route']) && $me->userlevel > 0 && !(selectAllFromWhere('gig', 'datetime', $_POST['datetime'], $pdo) && selectAllFromWhere('gig', 'user_id', $me->id, $pdo))) {
        $travius = (isset($_POST['travius']) ? $_POST['travius'] : NULL);
        $gigId = insertInto('gig', 'user_id', $me->id, $pdo);
        $inputs = array('safari_id', 'travius', 'datetime', 'route', 'remarks');
        $checks = array();  
        $me->updateTable('gig', $gigId['id'], $inputs, $checks, $pdo);
        header( "refresh:0;url=./" );
    }

    echo '
        <form method="POST">
            <select id="safari" name="safari_id" required>
                <option value="" selected disabled hidden>Choose a safari</option>
    ';
    $safari = selectAllFromWhere('safari', 'active', 1, $pdo);
    foreach($safari as $s){
        $sel = ((isset($_POST['safari_id']) && ($_POST['safari_id'] == $s['id'])) ? 'selected' : '');
        if ($s['active']) {
            echo '<option value="'.$s['id'].'" '.$sel.'>'.$s['name'].'</option>';
        }
    }
    echo '  </select><br>';

    #if ($me->userlevel > 1) {
    #    echo '<a href="./?safaris">or... add a safari!</a><br>';
    #}

    echo '  
            Date, Time: <input type="datetime-local" id="datetime" name="datetime" min="'.$nowtime->format("Y-m-d H:i").'" max="'.$maxtime->format("Y-m-d H:i").'" required value="'.$nowtime->format("Y-m-d H:i").'"><br>
            Travius: <input type="text" id="travius" name="travius" maxlength="6" pattern="[0-9]{6}" value="'.value('travius').'" ><br>
            Route: <input type="text" id="route" name="route" required maxlength="150" placeholder="route" value="'.value('route').'" ><br>
            <input type="submit" class="button" value="add gig">
        </form>
    ';
    if ($me->gig) {
        echo '<ol>';
        #array_multisort(array_column( $gigs, 'datetime' ), SORT_DESC, $gigs); // reverse order
        foreach ($me->gig as $gig){
            $nm = in_array($gig['id'], array_column($me->nearmiss, 'gig_id'));
            $saf = selectAllFromWhere('safari', 'id', $gig['safari_id'], $pdo);
            #$gigclass = (($ac) ? 'class_red' : (($nm) ? 'class_orange' : (is_null($gig['remarks']) ? 'class_pale' : 'class_green')));
            $gigclass = (($nm) ? 'class_orange' : (is_null($gig['remarks']) ? 'class_pale' : 'class_green')); 
            echo '  <li class="'.$gigclass.'"><a href="?tid='.$gig['id'].'">'.date("d M Y H:i", strtotime($gig['datetime'])).' - '.$saf[0]['name'].'</a></li>';
        }
        echo '</ol>';
    }
    else {
        echo "<p>You don't have any gigs... yet</p>";
    }
}
?>
</section>
