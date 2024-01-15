<section id="safaris">
<?php
if (isset($_GET['safaris'], $_GET['id'])) {
    if (isset($_POST['name'], $_POST['length']) && $me->userlevel > 1) { 
        $active = (isset($_POST['active']) ? 1 : 0);
        updateTableItemWhere('safari', 'name', $_POST['name'], 'id', $_GET['id'], $pdo);
        updateTableItemWhere('safari', 'length', $_POST['length'], 'id', $_GET['id'], $pdo);
        updateTableItemWhere('safari', 'active', $active, 'id', $_GET['id'], $pdo);
        header( "refresh:0;url=?safaris" );
    }
    $safari = selectAllFromWhere('safari', 'id', $_GET['id'], $pdo);
    $check = ($safari[0]['active'] ? 'checked' : '');        
    echo '<h3>update '.$safari[0]['name'].'</h3>
            <form action="" method="POST">
                <input type="text" id="name" name="name" required maxlength="60" placeholder="safari name" value="'.$safari[0]['name'].'"><br>
                <select id="length" name="length" required>
                    <option value="" selected disabled hidden>length:</option>';
    for ($i=60; $i<=300; $i=$i+30) {
        $selected = ($safari[0]['length'] == $i ? 'selected' : '');
        echo '      <option value="'.$i.'" '.$selected.'>'.($i/60).' h</option>';
        }
    echo '      </select><br>
                <input type="checkbox" id="active" name="active" '.$check.'> active<br>
                <input type="submit" value="update safari">
            </form>'; 
}

else {
    if (isset($_POST['name'], $_POST['length']) && !(selectAllFromWhere('safari', 'name', $_POST['name'], $pdo)) && $me->userlevel > 1) {
        insertInto('safari', 'name', $_POST['name'], $pdo);
        updateTableItemWhere('safari', 'length', $_POST['length'], 'name', $_POST['name'], $pdo);
    }
    foreach (selectAllFrom('safari', $pdo) as $s){
        $safari[] = new Safari($s['id'], $s['name'], $s['length'], $s['weekday'], $s['description'], $s['time'], $s['active']);
    }
    echo '<h3>Safaris</h3>
            <form method="POST">
                <input type="text" id="name" name="name" required maxlength="60" placeholder="safari name"><br>
                <select id="length" name="length" required>
                    <option value="" selected disabled hidden>length:</option>';
    for ($i=60; $i<=360; $i=$i+30) {
        echo '      <option value="'.$i.'">'.($i/60).' h</option>';
        }  
        echo '  </select><br>
                <input type="submit" class="button" value="add safari">
            </form>
    
            <ul>';
    foreach ($safari as $s){
        echo '  <li class="safari'.$s->active.'"><a href="?safaris&id='.$s->id.'">'.$s->name.'</a></li>';
    } 
    echo '  </ul>
            <div class="button_svg"><a href="./">'.file_get_contents('img/back.svg').'</a></div>';
}
?>
</section>
