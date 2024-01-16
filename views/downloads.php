<?php
session_start();

function downloadCsv($name){
    $out = fopen('php://output', 'w');
    foreach ($_SESSION[$name] as $t){
        fputcsv($out, $t);
    }
    fclose($out);
    unset($_SESSION[$name]);
    header( 'Content-Type: text/csv' );
    header( 'Content-Disposition: attachment;filename='.$name.'-'.date('YmdHis').'.csv' );
}


if(isset($_SESSION['vcards'], $_SESSION['validated']) && $_SESSION['validated']) {
    $out = fopen('php://output', 'w');
    foreach ($_SESSION['vcards'] as $v){
            fwrite($out, "BEGIN:VCARD\n");
            fwrite($out, "VERSION:4.0\n");
            fwrite($out, "FN:".$v['fname']." ".$v['lname']."\n");
            fwrite($out, "N:".$v['lname'].";".$v['fname'].";;;\n");
            fwrite($out, "ORG:Lapland Safaris North\n");
            fwrite($out, "EMAIL:".$v['email']."\n");
            fwrite($out, "TEL;type=CELL:".$v['tel']."\n");
            fwrite($out, "END:VCARD\n");
    }
    unset($_SESSION['vcards']);
    fclose($out);
header( 'Content-Type: text/vcf' );
header( 'Content-Disposition: attachment;filename=sirius_contacts-'.date('YmdHis').'.vcf');
}

if(isset($_SESSION['gig_report'], $_SESSION['validated']) && $_SESSION['validated']) { downloadCsv('gig_report'); }
if(isset($_SESSION['nearmiss_report'], $_SESSION['validated']) && $_SESSION['validated']) { downloadCsv('nearmiss_report'); }
if(isset($_SESSION['feedback_report'], $_SESSION['validated']) && $_SESSION['validated']) { downloadCsv('feedback_report'); }
?>
