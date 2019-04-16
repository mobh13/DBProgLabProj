<?php

require_once  "bundle.php";
class Search
{
    private $db_table_videos = "labProj_videos";

    function searchVideos($paraphrase){
        $db = DB::getInstance();

        $query = "select * from $this->db_table_videos where match(descreption, title) against ('$paraphrase') order by views desc ";
        $data = $db->multiFetch($query);
        return $data;

    }


}