<?php

// all DB connections funnel through here

class DBController
{ 
    private $db;

    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $database = "documentaries";

    function __construct(){
        $this->db = new mysqli($this->servername, $this->username, $this->password);
        $this->db->select_db($this->database);
    }

    function db(){
        return $this->db;
    }
    
    private function query($sql){
        $sql = $this->db->prepare($sql);
        $sql->execute();
        return $sql->get_result();
    }
    
    function close(){
        $this->db->close();
    }
    
    /** data retrieval functions **/
    
    function getFeaturedMovies(){
        return $this->query("SELECT * FROM docs WHERE featured=1 AND visible=1 AND cover<>'' ORDER BY createdOn DESC");
    }
    
    // get all categories
    function getCategories(){
        return $this->query("SELECT * FROM categories ORDER BY category ASC");
    }
    
    // counts number of movies per category
    function getCategoriesCount(){
        return $this->query("SELECT GROUP_CONCAT(category) FROM docs WHERE visible=1");
    }
    
    // get total doc count
    function getMovieCount(){
        return $this->query("SELECT COUNT(id) as number FROM docs WHERE visible=1");
    }
    
    function getContentByCategory($category, $sortBy, $sortDir){
        // ensure no empty docs are selected
        $categoryFilter = "WHERE (title<>'' AND description<>'' AND cover<>'' AND visible=1)";
        if($category != "all"){
            $categoryFilter .= " AND (find_in_set('". $category ."',category) <> 0)";
        }

        $this->db->query("SET NAMES utf8");

        // get all documentaries in table and sorted
        return $this->query("SELECT * FROM docs ". $categoryFilter ." ORDER BY ". $sortBy ." ". $sortDir);
    }
    
    // returns embed code for given video id and type of video
    // video type can be trailer or watch
    function getEmbedCodeByID($videoID, $videoType){
        $result = $this->query("SELECT ". $videoType ." FROM docs WHERE id = '". $videoID ."'");
        $embedCode = $result->fetch_row();
        return $embedCode[0];
    }
    
    function getRandomQuote(){
        return $this->query("SELECT * FROM quotes ORDER BY RAND() LIMIT 1");
    }
    
    /** Edit area functions **/
    
    
    function getAllMovies(){
        return $this->query("SELECT * FROM docs ORDER BY id DESC");
    }
    
    
    function getMovieByID($id){
        $result = $this->query("SELECT * FROM docs WHERE id = '". $id ."'");
        return $result->fetch_array();
    }
    
    
    function dbErrorMessage(){
        print json_encode(array('error' => '<div class="docRow">Oops... Lucid Tree doesn&apos;t understand your request.<br/><br/><a href="/">Return to the main page</a> or click the menu on the left to try again. <p>'.mysqli_error().'</p></div>'));
        exit;
    }
}

?>