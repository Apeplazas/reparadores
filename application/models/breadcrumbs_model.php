<?php

class Breadcrumbs_model extends CI_Model{

    var $title   = '';
    var $content = '';
    var $date    = '';
    private $pagesTable = "enlaces";
    
    function __construct(){
    	
        // Call the Model constructor
        parent::__construct();
		
    }
    
    function get_page($id=false,$controller=false,$action=false,$url=false){
    	
    	if ($action=="index")$action=false;
    	if ($id){
        	$query = $this->db->get_where($this->pagesTable, array('enlaceId' => $id));
    	}
    	else{
    		$query = $this->db->get_where($this->pagesTable, array('controller' => $controller,
        		'view'       => $action,
        		'url'		=> $url
        	));
    	}
        return $query->row();
		
    }

}

?>