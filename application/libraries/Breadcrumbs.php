<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Breadcrumbs
* 
* Author:  Benjamin Carrera
* Created:  9.24.2014 
* 
* Description:  Class to create dynamic breadcrumbs according to the page setup in the database
* 
*/
class breadcrumbs{
	
	/**
	 * CodeIgniter global
	 *
	 **/
	protected $ci;


	/**
	 * __construct
	 *
	 * @return void
	 * @author Benjamin Carrera
	 **/
	public function __construct(){
		
		//Get an Instance of CodeIgniter
		$this->ci =& get_instance();
		//load model
		$this->ci->load->model('breadcrumbs_model');
		
	}
	
	/**
	 * Breadcrumbs
	 *
	 * @return void
	 * @author Benjamin Carrera
	 **/
	public function generate_breadcrumb(){
		
		//define the controller and action
		$controller = $this->ci->router->fetch_class();
		$view 		= $this->ci->router->fetch_method();
		$url		= end($this->ci->uri->segments);
		//define the breadcrumb variable
		$breadcrumb = '';
 
		//pull the current page from the model
		$page = $this->ci->breadcrumbs_model->get_page(false,$controller,$view,$url);

		if(is_object($page) && $page->parent_id != ''){ //if the page is a child object
		
			$parent[0] = $this->ci->breadcrumbs_model->get_page($page->parent_id);
		
			if (isset($parent[0]->parent_id) && $parent[0]->parent_id != ''){ //if the parent is also a child object start the loop to find all the child objects
			
				$i = 1; //set the counter
				$final = false; //switch to turn off the loop
				
				while (!$final){
					
					$parent[$i] = $this->ci->breadcrumbs_model->get_page($parent[$i-1]->parent_id); //get the parent from the model
					if ($parent[$i]->parent_id == ''){$final=true;} //if there are no more parents stop the loop
					$i++;
					
				}
			}
			
			krsort($parent); //reverse the array
			$completeUrl = '';
			foreach ($parent as $breadcrumbItem){
	
					$breadcrumb		.= '<li><em> > </em>' . anchor($completeUrl.$breadcrumbItem->url,$breadcrumbItem->nombre) . "</li>";
					$completeUrl	.= $breadcrumbItem->url . '/';

			}		
				
			$breadcrumb .= '<li><em> > </em>' . anchor($completeUrl.$page->url,$page->nombre) . "</li>"; //attach the page title					
			
		}elseif($url && $page){
			
			$breadcrumb .= '<li><em> > </em>' . anchor($page->url,$page->nombre) . '</li>'; //attach the page title
			
		}

		return $breadcrumb;
		
	}

}
