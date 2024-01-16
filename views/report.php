<?php

if (isset($_GET['gig'])) {
    $sql = "SELECT  gig.datetime,
                    safari.name,
                    concat(user.fname, ' ', user.lname),
                    gig.travius,
                    gig.start,
                    gig.end,
                    gig.route,
                    gig.weather,
                    gig.temp,
                    gig.remarks,
                    gig.accident,
                    gig.updated 
            FROM gig LEFT JOIN safari ON gig.safari_id = safari.id LEFT JOIN user ON gig.user_id = user.id";
    $csvheader = array('date', 'safari', 'guide', 'travius', 'start', 'end', 'route', 'weather', 'temp', 'remarks', 'accident', 'updated');
    prepareReport('gig_report', $sql, $csvheader, $pdo);
}

if (isset($_GET['nearmiss'])) {
    $sql = "SELECT  nearmiss.nm_datetime,
                    gig.start,
                    safari.name,
                    concat(user.fname, ' ', user.lname),
                    gig.travius,
                    gig.route,
                    gig.remarks,
                    nearmiss.nm_place,
                    nearmiss.nm_description,
                    nearmiss.guide,
                    nearmiss.customer,
                    nearmiss.third,
                    nearmiss.updated 
            FROM nearmiss LEFT JOIN gig ON nearmiss.gig_id = gig.id LEFT JOIN user ON nearmiss.user_id = user.id LEFT JOIN safari ON gig.safari_id = safari.id";
    $csvheader = array('nm date', 'safari started', 'safari', 'guide', 'travius', 'route', 'gig remarks', 'place', 'description', 'guide involved', 'customer involved', 'third involved', 'updated');
    prepareReport('nearmiss_report', $sql, $csvheader, $pdo);
}


if (isset($_GET['feedback'])) {
    $sql = "SELECT feedback.datetime, feedback.description FROM feedback";
    $csvheader = array('date', 'description');
    prepareReport('feedback_report', $sql, $csvheader, $pdo);
}

?>

<section id="reports">
    <h3>Reports</h3>
        <a href="./?reports&gig" ><div>gig</div></a>
        <a href="./?reports&nearmiss" ><div>near miss</div></a>
        <a href="./?reports&feedback" ><div>feedback</div></a>
</section>
