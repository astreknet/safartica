<section id="navbar">
<?php
$menu = array('?exit' => '⍈');
if (!isset($_SESSION['register']) || !$_SESSION['register']) {
    $menu_admin =  array(
        '?reports' => 'reports', 
        '?users' => 'users', 
        './' => 'gigs', 
        '?account' => strtolower($me->fname)
    );
    $menu = ($me->userlevel > 1 ? array_merge($menu_admin, $menu) : array_merge(array_slice($menu_admin, 3), $menu));
}

foreach($menu as $k => $v) {
    echo '<a href="./'.$k.'"><div>'.$v.'</div></a>';
}
?>
</section>
