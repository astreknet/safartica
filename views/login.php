<section id="login">
<?php
if (isset($_GET['user_lock'])){
    if (isset($_POST['username']) && ($me = new User(filter_var($_POST['username'], FILTER_VALIDATE_EMAIL), $pdo))){
        $me->resetPassword($pdo);
        getout();
    }
    echo '
        <h3>restart pasword</h3>
        <form id="restart_password" method="POST">
            <input type="email" id="username" name="username" required maxlength="45" placeholder="mail" '.value('username').' autocomplete="username"><br>
            <div class="rack">
                <div class="pre_button"><a href="index.php">log in</a></div>
                <input class="button" type="submit" value="restart password">
            </div>
        </form>
    ';
}

else{
    echo '
        <h3>log in</h3>
        <form id="login" method="POST">
            <input type="email" id="username" name="username" required maxlength="45" placeholder="mail" '.value('username').' autocomplete="username"><br>
            <input type="password" id="lpassword" name="lpassword" required maxlength="45" placeholder="password" autocomplete="current-password"><br>
            <div class="rack">
                <div class="pre_button"><a href="?user_lock">forgot pass</a></div>
                <input class="button" type="submit" value="log in">
            </div>
        </form>
    ';  
}
?>
</section>
