<section id="users">
<?php
if (isset($_GET['users'], $_GET['id']) && $me->userlevel > 1 && $_GET['id'] != 1 && $_GET['id'] != $me->id) {
    $user = selectAllFromWhere('user', 'id', $_GET['id'], $pdo); 
    $userlevels = array('inactive', 'guide', 'admin');
    if ($me->userlevel < 3)
        array_pop($userlevels);
    if (is_null($user[0]['password']) && is_null($user[0]['fname']) && is_null($user[0]['lname']) && is_null($user[0]['tel'])) {
        echo "
            <h3>".$user[0]['email']."</h3>
            <p>If this user doesn't complete the registration soon will be deleted!</p>
            <div class='button_svg'><a href='?users'>".file_get_contents('img/back.svg')."</a></div>
        "; 
    }
    else {
        echo '
            <h3 class="userlevel'.$user[0]['userlevel'].'">'.$user[0]['fname'].' '.$user[0]['lname'].'</h3>
            <p><address>
                <a href="tel:'.$user[0]['tel'].'">'.$user[0]['tel'].'</a><br>
                <a href="mailto:'.$user[0]['email'].'">'.$user[0]['email'].'</a>
            </address></p>
            <form action="?users" method="POST">
                <select id="userlevel" name="userlevel" required>
                    <option value="" selected disabled hidden>userlevel:</option>';
                for ($i = 0; $i < count($userlevels); $i++) {
                    $sel = ($user[0]['userlevel'] == $i ? 'selected' : '');
                    $dis = ($me->id == $user['id'] && $me->userlevel != $i ? 'disabled' : '');
                    echo '<option value="'.$i.'" '.$sel.' '.$dis.'>'.$userlevels[$i].'</option>';
                    }
        echo '  
                </select><br>
                <input type="hidden" id="userID" name="userId" value="'.$_GET['id'].'">
                <input type="submit" value="update user">
            </form>
        ';
    }
}
else {
    deleteOneDayOldNonRegisteredUsers($pdo);
    if (isset($_POST['userlevel'], $_POST['userId'])) {
            $me->updateUserlevel($_POST['userId'], $_POST['userlevel'], $pdo);
    }

    if(isset($_POST['email'])) { 
        $me->createUser($_POST['email'], $pdo);
    }
    foreach (selectAllFrom('user', $pdo) as $u){
        $user[] = new User($u['email'], $pdo);
    }
    echo '
        <h3>Users</h3>
        <form action="" method="POST">
            <input type="text" id="email" name="email" required maxlength="45" placeholder="email" autocomplete="email"><br>
            <input type="submit" class="button" value="add user" >
        </form>
                    
        <ol>';
    
    foreach ($user as $s){
        echo '<li class="userlevel'.$s->userlevel.'"><a href="?users&id='.$s->id.'">';
        echo (!is_null($s->fname) && !is_null($s->lname) ? $s->fname.' '.$s->lname : $s->email);
        echo '</a></li>';
    } 

    echo '
        </ol>
        <div class="button_svg"><a href="?users&vcard">'.file_get_contents("img/vcard.svg").'</a></div>';

    if (isset($_GET['vcard'])){
        $sql = 'select fname, lname, tel, email from user where userlevel > 0 and userlevel < 3 and tel is not NULL';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $_SESSION['vcards'] = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $_SESSION['vcards'][] = $row;
        }
        header( "refresh:0;url=views/downloads.php" );
    }
}
?>
</section>
