<?php

function Club_addusernum()
{
    $clubdb = System::load_app_model("club_db", "common");
    $uid = intval(_getcookie("uid"));
    return $num = $clubdb->GetUserClubCont($uid);
}

function Club_userpostnum($lex)
{
    $clubdb = System::load_app_model("club_db", "common");
    $uid = intval(_getcookie("uid"));

    if ($lex == "tiezi") {
        $num = $clubdb->GetUserClubPostNum($uid, 1);
    }

    if ($lex == "huifu") {
        $num = $clubdb->GetUserClubPostNum($uid, 2);
    }

    return $num;
}

function Club_postnum($cid, $tid = "0")
{
    $clubdb = System::load_app_model("club_db", "common");
    return $clubdb->GetClubPostCont($cid, $tid);
}

function Club_title($cid)
{
    $clubdb = System::load_app_model("club_db", "common");
    $title = $clubdb->GetClubOne($cid);
    return $title["title"];
}

function Club_img($cid)
{
    $clubdb = System::load_app_model("club_db", "common");
    $title = $clubdb->GetClubOne($cid);
    return $title["img"];
}

function Club_posttitle($id)
{
    $clubdb = System::load_app_model("club_db", "common");
    $title = $clubdb->GetClubPostOne($id, 3);
    $title = $clubdb->GetClubPostOne($title["huifu_id"], 3);
    return $title["title"];
}


?>
