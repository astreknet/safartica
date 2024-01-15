<?php
(isset($me) ?: $me = new User($_SESSION['usermail'], $pdo));
(empty($me->fname) ?: $_SESSION['fname'] = $me->fname);
(empty($me->lname) ?: $_SESSION['lname'] = $me->lname);
(empty($me->tel) ?: $_SESSION['tel'] = $me->tel);

if (
//    isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
    isset($_POST['fname'], $_POST['lname'], $_POST['tel'], $_POST['new'], $_POST['password']) && 
    !empty(trim($_POST['fname'])) && !empty(trim($_POST['lname'])) &&
    !empty(trim($_POST['tel'])) && !empty($_POST['new']) &&
    ($_POST['new'] === $_POST['password'])
    ){
//        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); 
        $password = hash('sha256', $_POST['password']);
        $fname = htmlspecialchars(trim($_POST['fname']));
        $lname = htmlspecialchars(trim($_POST['lname']));
        $tel = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
        updateTableItemWhere('user', 'fname', $fname, 'id', $me->id, $pdo);
        updateTableItemWhere('user', 'lname', $lname, 'id', $me->id, $pdo);
        updateTableItemWhere('user', 'tel', $tel, 'id', $me->id, $pdo);
        updateTableItemWhere('user', 'activation', NULL, 'id', $me->id, $pdo);
        updateTableItemWhere('user', 'password', $password, 'id', $me->id, $pdo);
        getout();
}

if (
    isset($_POST['description']) && !empty(trim($_POST['description']))
    ){
        $description = htmlspecialchars(trim($_POST['description']));
        insertInto('feedback', 'description', $description, $pdo);
    }


?>
<section id="register">
    <h3>Account</h3>
    <form action method="POST">
        <input type="text" name="fname" required maxlength="18" placeholder="first name" value="<?php echo value('fname'); ?>" autocomplete="first-name"><br>
        <input type="text" name="lname" required maxlength="18" placeholder="last name" value="<?php echo value('lname'); ?>" autocomplete="last-name"><br>
        <input type="tel" name="tel" required maxlength="18" placeholder="tel" value="<?php echo value('tel'); ?>" pattern="[+][0-9].{5,}" title="phone with country code ex: +1234567890" oninvalid="setCustomValidity('phone with country code ex: +1234567890')" onchange="try{setCustomValidity('')}catch(e){}" autocomplete="tel"><br>

        <input type="password" id="new" name="new" required maxlength="45" minlength="8" placeholder="new password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) form.password.pattern = this.value;" autocomplete="new-password"><br>
        <input type="password" id="password" name="password" required maxlength="45" minlength="8" placeholder="repeat new password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Please enter the same password as above' : '');" autocomplete="new-password"><br>
        
        <input type="submit" class="button" value="update">
    </form>
</section>

<section id="feedback">
    <h3>Anonymous Feedback</h3>
    <form action method="POST">
        <textarea id="description" name="description" maxlength="270" placeholder="feedback"></textarea><br>
            
        <input type="submit" class="button" value="send">
    </form>
</section>
