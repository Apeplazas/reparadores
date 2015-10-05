<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "inicio";
$route['404_override'] = '';

$handle = opendir(APPPATH."/modules");
while (false !== ($file = readdir($handle))) {
  if(is_dir(APPPATH."/modules/".$file)){
    $route[$file] = $file;
    $route[$file."/(.*)"] = $file."/$1";
  }
}

$route['aviso-privacidad'] = "avisoprivacidad";
//Route para usuarios
$route['(^((?!(^(reparacion-de-celulares|reparacion-de-tablets|reparacion-de-computadoras|reparacion-de-impresoras|reparacion-de-camaras|reparacion-de-laptops|reparacion-de-videojuegos|reparacion-de-monitores|lista-de-reparadores-en-mexico))).)*)'] = "usuarios/perfiles/$1";
//Route para categorias
$route['(^(reparacion-de-celulares|reparacion-de-tablets|reparacion-de-computadoras|reparacion-de-impresoras|reparacion-de-camaras|reparacion-de-laptops|reparacion-de-videojuegos|reparacion-de-monitores|lista-de-reparadores-en-mexico)$)'] = "reparaciones/subcategoria/$1";
//Route para subcategorias
$route['(^(reparacion-de-celulares|reparacion-de-tablets|reparacion-de-computadoras|reparacion-de-impresoras|reparacion-de-camaras|reparacion-de-laptops|reparacion-de-videojuegos|reparacion-de-monitores)\/.+$)'] = "reparaciones/subcategoria";

/* End of file routes.php */
/* Location: ./application/config/routes.php */