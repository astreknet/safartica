<?php
ini_set('session.gc_maxlifetime', 300);  #  
ini_set('session.gc_probability', 1);    #
ini_set('session.gc_divisor', 1);        # Kicks you out in 5 min if inactive
session_start();
date_default_timezone_set('Europe/Helsinki');

### MYSQL ################################################
$pdo = new PDO('mysql:host=127.0.0.1;dbname=safartica;charset=utf8mb4', 'safartica', 'S1f1rt3c1');
$sql = '
        CREATE TABLE IF NOT EXISTS safari (
            id INT2 unsigned NOT NULL AUTO_INCREMENT,
            name varchar(60) NOT NULL unique,
            length INT2 unsigned DEFAULT 60,
            weekday INT3 DEFAULT 1111111,
            description LONG,
            time time DEFAULT "09:00:00",
            price_adult decimal(8,2) DEFAULT 0.00,
            price_solo decimal(8,2) DEFAULT 0.00,
            price_child decimal(8,2) DEFAULT 0.00,
            active bool DEFAULT TRUE,
            PRIMARY KEY (id)
        ); 

        INSERT IGNORE INTO safari (id, name, length, description) VALUES
            (1, "scenic snowmobile safari", 120, "Brilliant opportunity to master the art of driving a snowmobile and marvel the beautiful northern scenery. After driving instructions, we will head towards the nature and drive over the hills and fells of Saariselkä area. In the middle we stop for a break to enjoy hot drinks and the silent nature surrounding us."),
            (2, "snowmobile adventure for adults", 180, "When you want a taste of more snowmobiling and enjoy the ride all to yourself, choose this adventure! Follow our guide to some less-used tracks that lead us a bit deeper into the stunning landscapes of Saariselkä. We will have short breaks for photographing and hot drinks, otherwise the focus is purely on driving. Great chance to learn some snowmobiling tips!"),
            (3, "artic paws – 5 km husky safari", 120, "Come to explore the magical winter wonderland from a husky sleigh! After getting instructed by the musher himself, we head towards the trails going in the forest and over the hills. Take a moment to marvel the nature while the huskies pull you deeper to the nature – have you noticed yet how silent the nature is? After the ride we return to the kennel where we have a chance to get to know more about the friendly huskies and enjoy hot drinks."),
            (4, "husky trail adventure - 10 km husky safari", 180, "Experience the joy and excitement of mushing your own team of huskies! Upon arrival to the kennel, our husky musher instructs how to steer the husky sleigh before we get to the nature. These husky trails meander in the snowy forest and over the hills, which gives some challenge to the drivers. Enjoy the speed and the snow-covered nature around. After the ride we return to the kennel where we hear stories about the huskies and their lives at the kennel."),
            (5, "traditional reindeer safari", 150, "Come and experience the white nature from comfort of a reindeer sleigh! We get seated in a sleigh pulled by reindeer and start heading towards the snowy forest – what a wonderful opportunity to relax, take photos and listen the silence of nature. After the sleigh ride we return to the reindeer farm to hear stories of reindeer herding and enjoy hot drinks."),
            (6, "full day adventure with snowmobiles", 360, "Unleash your inner explorer and join the snowmobile safari which will be one of the highlights of your holiday in Finnish Lapland! Our guide will lead us to the wilderness by snowmobiles, to the tracks that lead you to impressive natural sights which cannot be reached with shorter safaris. In the middle we will have a break to enjoy delicious lunch before continuing our adventure."),
            (7, "artic ice fishing experience", 150, "Welcome to follow how locals spend their free time! After a short car transfer, we arrive to a local lake where our guide will show the tools and tricks for ice fishing; how to get through the ice and how to prepare the ice fishing rod and baits. After everything is set, you can relax, listen to the silence and experience the tranquillity of the nature. How many fish will you catch?"),
            (8, "scenic snowshoe safari", 150, "Join us for daylight snowshoe experience in beautiful landscapes of Northern Finland. Our guide will instruct how to use the snowshoes and lead us to the nature walking paths. Test your skills and step into the deep snow! Upon taking a break, we will enjoy hot chocolate and marshmallows served by our guide."),
            (9, "evening snowmobile safari with campfire", 180, "Snowmobiling in dark wintery nature is once in a lifetime experience! Snowmobile headlights are illuminating our way through nature while we drive through forest, swamps and hills. In the middle we take a break and our guide will make a fire where we get to grill sausages and warm up a little. If we are lucky enough we get to marvel the northern lights above us!"),
            (10, "reindeer ride through a polar night", 150, "Sit in a reindeer sleigh and let the reindeer pull you calmly to snowy nature. Even in darkness the wintery forest is never fully dark because of the white snow. Relax and keep your eyes towards the sky, if we are lucky we get to marvel the magnificent northern lights above us. After the ride we return to the reindeer farm where we get to enjoy hot drinks and know more about the life of reindeer."),
            (11, "aurora photo tour by car", 240, "Are you dreaming of catching Aurora Borealis? It’s time to hop on a bus and head towards the areas where the chances of seeing the northern lights are at the highest. With clear sky and little luck, we may get to see them dancing in the sky! On our way the guide will take photos of us which we get as a memory, and he can give night-time photographing tips for those travelling with own cameras. In between we enjoy hot drinks and gingerbread cookies while observing the sky."),
            (12, "northern lights hunting by snowshoes", 150, "Capture the feeling of night-time wilderness and quietness at its best! Our guide will show how to use the snowshoes and lead us to the dark nature – yet snow-covered nature is never purely dark. Breath the pure air, marvel the starry sky and be brave to test the snowshoeing in deep snow. With little luck we may see the beautiful aurora borealis above us, don’t forget your camera!"),
            (13, "custom safari", 0, "Open safari");

        CREATE TABLE IF NOT EXISTS user (
            id INT2 unsigned NOT NULL AUTO_INCREMENT,
            email varchar(45) NOT NULL unique,
            password char(64),
            fname varchar(18),
            lname varchar(18),
            tel varchar(18),
            userlevel INT1 unsigned DEFAULT 1,
            activation char(32), 
            updated timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (id)
        );

        
        INSERT IGNORE INTO user (id, email, password, fname, lname, tel, userlevel) VALUES 
            (1, "hugo@astrek.net", "6b6bf838063e554f031da357336f5be30a04f8786926be4e4a3e0c417215abf2", "Hugo", "Sastre", "+358440270975", 3),
            (2, "petteri.hyvari@safartica.com", "48cfd637bcb06290036407c55c15210b3c9cac9045f5ff48c4f4136dc65fc3ab", "Petteri", "Hyväri", "+358406654912", 2);

        CREATE TABLE IF NOT EXISTS gig (
            id INT2 unsigned NOT NULL AUTO_INCREMENT,
            user_id INT2 unsigned NOT NULL,
            safari_id INT2 unsigned DEFAULT 1,
            travius char(6),
            datetime datetime DEFAULT current_timestamp(),
            start time,
            end time,
            route varchar(150),
            weather varchar(90),
            temp DOUBLE(2,1),
            remarks varchar(450),
            accident bool DEFAULT FALSE,
            updated datetime ON UPDATE current_timestamp(),
            PRIMARY KEY (id),
            KEY fk_gig_user (user_id),
            KEY fk_gig_safari (safari_id),
            CONSTRAINT fk_gig_safari FOREIGN KEY (safari_id) REFERENCES safari (id) ON DELETE CASCADE,
            CONSTRAINT fk_gig_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS nearmiss (
            id INT2 unsigned NOT NULL AUTO_INCREMENT,
            user_id INT2 unsigned NOT NULL,
            gig_id INT2 unsigned DEFAULT 1,
            nm_datetime datetime DEFAULT current_timestamp(),
            nm_place varchar(150),
            point point,
            nm_description varchar(300),
            guide bool DEFAULT FALSE,
            customer bool DEFAULT FALSE,
            third bool DEFAULT FALSE,
            updated timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (id),
            KEY fk_nearmiss_user (user_id),
            KEY fk_nearmiss_gig (gig_id),
            CONSTRAINT fk_nearmiss_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE,
            CONSTRAINT fk_nearmiss_gig FOREIGN KEY (gig_id) REFERENCES gig (id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS feedback (
            id INT2 unsigned NOT NULL AUTO_INCREMENT,
            datetime datetime DEFAULT current_timestamp(),
            description varchar(300),
            PRIMARY KEY (id)
        );
    ';
    
$stmt = $pdo->prepare($sql);
$stmt->execute();
$stmt->closeCursor();

function selectAllFrom($table, $pdo){
    try {
        $sql = 'SELECT * FROM '.$table;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }
        $stmt->closeCursor();
        return $result;
    }
    catch (PDOException $e) {
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() ;
    }
    include  __DIR__ . '/../views/output.php';
}

function selectAllFromWhere($table, $item, $i, $pdo){
    try {
        $sql = 'SELECT * FROM '.$table.' WHERE '.$item.' = :'.$item;
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':'.$item, $i);
        $stmt->execute();
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }
        $stmt->closeCursor();
        return $result;
    }
    catch (PDOException $e) {
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() ;
    }
    include  __DIR__ . '/views/output.php';
}

function updateTableItemWhere($table, $item, $i, $where, $w, $pdo){
    $sql = 'UPDATE '.$table.' SET '.$item.' = :'.$item.' WHERE '.$where.' = :'.$where;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':'.$item, $i);
    $stmt->bindValue(':'.$where, $w);
    $stmt->execute();
    $stmt->closeCursor();
}

function insertInto($table, $item, $i, $pdo){
    $sql = 'INSERT INTO '.$table.' ('.$item.') values (:'.$item.')';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':'.$item, $i);
    $stmt->execute();
    $sql = 'SELECT LAST_INSERT_ID() as id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result;
}

function deleteOneDayOldNonRegisteredUsers($pdo){
    $sql = 'DELETE FROM user where DATEDIFF(current_timestamp(), updated) > 0 AND password is NULL AND fname is NULL AND lname is NULL AND tel is NULL AND userlevel = 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();
}

### FUNCTIONS #############################################
function value($post){
    if (isset($_POST[$post]))
        return $_POST[$post];
    else
        if (isset($_SESSION[$post]))
            return $_SESSION[$post];
}

function getout(){
    header( "refresh:0;url=index.php" ); 
    session_unset();
    session_destroy();
    die();
}

function formatdate($date) {
    $myunixdate = strtotime($date);
    if (date("Y-m-d") == date("Y-m-d", $myunixdate))
        return "today ".date("G:i", $myunixdate);
    else
        return date("D M j G:i", $myunixdate);
}

function sessionForm($val, $bool) {
    foreach ($val as $k => $v) {
        if ($bool == TRUE) {
            $_SESSION[$k] = $v;
        } 
        else {
            unset($_SESSION[$k]);
        }
    }
}

function prepareReport($name, $sql, $csvheader, $pdo) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $_SESSION[$name] = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $_SESSION[$name][] = $row;
    }
    array_unshift($_SESSION[$name], $csvheader);
    header( "refresh:0;url=views/downloads.php" );
}

### CLASS #################################################
class User{
    public $id, $email, $password, $fname, $lname, $tel, $userlevel, $activation, $updated; 

    public function __construct($pMail, $pdo){
        if ($row = selectAllFromWhere('user', 'email', filter_var($pMail, FILTER_VALIDATE_EMAIL), $pdo)) {
            foreach ($row[0] as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    public function validate($pPassword){
        return ($pPassword === $this->password ? true : false);
    }

    public function resetPassword($pdo){
        if ($this->userlevel){
            $activation = bin2hex(random_bytes(16));
            $url = 'https://'.$_SERVER['HTTP_HOST'].'?account&username='.$this->email.'&activation='.$activation;
            updateTableItemWhere('user', 'activation', $activation, 'email', $this->email, $pdo);
            mail($this->email, 'sirius recover', $url);
        }
    }

    public function createUser($userMail, $pdo){
        if (filter_var($userMail, FILTER_VALIDATE_EMAIL)  && !(selectAllFromWhere('user', 'email', $userMail, $pdo)) && ($this->userlevel > 1)) {
            insertInto('user', 'email', $userMail, $pdo);
            $activation = bin2hex(random_bytes(16));
            $url = 'https://'.$_SERVER['HTTP_HOST'].'?account&username='.$userMail.'&activation='.$activation;
            updateTableItemWhere('user', 'activation', $activation, 'email', $userMail, $pdo);
            #$headers = array('From' => 'hugo@astrek.net', 'Reply-To' => 'sirius@astrek.net');
            mail($userMail, 'sirius acivation', $url); 
        }    
    }

    public function updateUserlevel($userId, $userLevel, $pdo){
        if (($row = selectAllFromWhere('user', 'id', $userId, $pdo)) &&  $this->userlevel > 1 && $row[0]['userlevel'] < $this->userlevel && $userLevel < $this->userlevel)
            updateTableItemWhere('user', 'userlevel', $userLevel, 'id', $userId, $pdo);
    }
}

class Guide extends User{
    public $gig, $nearmiss;

    public function __construct($pMail, $pdo){
        parent::__construct($pMail, $pdo);
        $this->gig = selectAllFromWhere('gig', 'user_id', $this->id, $pdo);
        $this->nearmiss = selectAllFromWhere('nearmiss', 'user_id', $this->id, $pdo);
    }

    public function updateTable($table, $tableId, $inputs, $checks, $pdo){
        foreach ($inputs as $in) {
            (!isset($_POST[$in]) && empty($_POST[$in]) ?: updateTableItemWhere($table, $in, $_POST[$in], 'id', $tableId, $pdo));
        }
        foreach($checks as $c) {
            (isset($_POST[$c]) ? updateTableItemWhere($table, $c, 1, 'id', $tableId, $pdo) : updateTableItemWhere($table, $c, 0, 'id', $tableId, $pdo));
        }
    }
}

class Admin extends Guide{
    public $allgig, $allincident, $alluser;

    public function __construct($pMail, $pdo){
        parent::__construct($pMail, $pdo);
        $this->allgig = selectAllFrom('gig', $pdo);
        $this->allincident = selectAllFrom('nearmiss', $pdo);
        $this->alluser = selectAllFrom('user', $pdo);
    }

}


class Safari{
    public $id, $name, $length, $weekday, $description, $time, $active;

    public function __construct($id, $name, $length, $weekday, $description, $time, $active){
        $this->id = $id;        
        $this->name = $name;        
        $this->length = $length;        
        $this->weekday = $weekday;        
        $this->description = $description;        
        $this->time = $time;        
        $this->active = $active;        
    }
}

class Trip{
    public $id, $user_id, $safari_id, $travius, $datetime, $route, $remarks, $done;

    public function __construct($pId, $pUser_id, $pSafari_id, $pTravius, $pDatetime, $pRoute, $pRemarks, $pDone){
        $this->id = $pId;
        $this->user_id = $pUser_id;
        $this->safari_id = $pSafari_id;
        $this->travius = $pTravius;
        $this->datetime = $pDatetime;
        $this->route = $pRoute;
        $this->remarks = $pRemarks;
        $this->done = $pDone;
    }
}

### VALIDATION ############################################
if (isset($_POST['username'], $_POST['lpassword']) && ($me = new User($_POST['username'], $pdo)) && ($me->userlevel) && ($me->validate(hash('sha256', $_POST['lpassword'])))) { 
    $_SESSION = array('usermail' => $me->email, 'validated' => TRUE);
}

if (isset($_GET['username'], $_GET['activation'], $_GET['account']) && ($me = new User($_GET['username'], $pdo)) && ($me->userlevel) && ($me->activation === $_GET['activation'])){
    $_SESSION = array('usermail' => $me->email, 'validated' => TRUE, 'register' => TRUE);
} 

(!isset($_GET['exit']) ?: getout());

### ROUTING ###############################################
require_once 'views/header.php';
if (isset($_SESSION['usermail'], $_SESSION['validated']) && ($me = new User($_SESSION['usermail'], $pdo)) && ($me->userlevel) && ($_SESSION['validated'])) {
    require_once 'views/navbar.php';
    if (isset($_GET['reports']) && $me->userlevel > 1) {
        include_once 'views/report.php';
    }
    elseif (isset($_GET['safaris']) && $me->userlevel > 1) {
        include_once 'views/safari.php';
    }
    elseif (isset($_GET['users']) && $me->userlevel > 1) {
        include_once 'views/user.php';
    }
    elseif (isset($_GET['account'])) {
        include_once 'views/account.php';
    }
    elseif (isset($_GET['feedback'])) {
        include_once 'views/feedback.php';
    }
    else {
        include_once 'views/gig.php';
    }
}
else {
    include_once 'views/login.php';
}

require_once 'views/footer.php';
?>
