<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compartir extends MX_Controller {
			
	function compartir()
	{
		parent::__construct();
	}
	
	function index()
	{
		//Genera metatags
        $uno = $this->uri->segment(1);
        $op['tags'] = $this->data_model->cargarOptimizacion($uno);
		
        $op['menu'] = $this->data_model->cargarMenuHeader();
        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
        
		//Vista//
		$this->load->view('inicio-view', $op);
	}
	
	function enviarCompartido()
	{	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email');
		$this->form_validation->set_rules('amigoEmail', 'Email', 'trim|required|valid_email|callback_email');
		
		$amigo    = $this->input->post('amigoEmail');
		$email    = $this->input->post('email');

		
		if ($this->form_validation->run($this) == FALSE)
		{
			//Genera metatags
	        $uno = $this->uri->segment(1);
	        $op['tags'] = $this->data_model->cargarOptimizacion($uno);
			
	        $op['menu'] = $this->data_model->cargarMenuHeader();
	        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
			
			$this->load->view('inicio-view', $op);
			
		}
		else
		{
			
			//Valida la informacion del contacto
			$cadena = $email." ".$amigo;
			preg_match("/\b(href|declare|select|insert|somebody|OR|or|\=|sleep|http|www)\b/",$cadena,$contacto);
				
			if(count($contacto) == 0){
			$info = array(
							  'invito'       => $email,
							  'amigo'     => $amigo,
							  );
							  
			$this->db->insert('invitaciones', $info);
			
			$this->load->library('email');
			$this->email->set_newline("\r\n");
			$this->email->from($email, 'Reparadores - Comunidad de Reparadores en Mexico');
			$this->email->to($amigo);
			$this->email->bcc('mdiaz@apeplazas.com');
			$this->email->subject('Invitación a la Comunidad de Reparadores.mx ');
			$this->email->message('
<html>
<head>
<meta charset="utf-8">
<title>Busca y encuentra los mejores técnicos de reparación en México</title>
<style>
	h1, h2{font-size:20px; font-weight:700; margin:25px 0 3px}
	body{font-family:helvetica,arial,sans-serif; background-color:#f4f5f7; font-size:.8em; line-height:20px; color:#555}
	.wrap{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #c30}
	.wrapTwo{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #c30}
	a{color:#c30}
	#regEmail{display:inline; width:200px; margin:40px auto; height:28px; text-align:center; background-color: #B81C2D; color:#fff; text-transform:uppercase; font-size:16px; padding:12px 30px 9px; border-radius:6px; text-decoration:none;}
	li{list-style:none}
	ul{margin:0; padding:0}
	#foot{color:#777; font-size:10px;b order-top:1px solid #ccc; padding:10px 0}
	em{color:#c30; font-style: normal}
</style>
</head>
<body style="background-color:#eee;">
<table cellpadding="40" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px font-family:helvetica,arial,sans-serif; border:1px solid #ccc; color:#555; margin-top:30px;">
  <tr>
    <td>
    <div class="wrap">
    <h1>Hola </h1>
    <p>Te recomiendo esta pagina web en la cual encontraras un directorio de los mejores técnicos de reparación en México.<br> <a href="http://www.reparadores.mx" title="Directorio Reparadores México" >www.reparadores.mx</a><br>Saludos</p>
    </div>
    </td>
  </tr>
</table>

</body>
</html>
			 ');
				if($this->email->send())
					{
						redirect('compartir/gracias');
					}
				else{
						//Genera metatags
				        $uno = $this->uri->segment(1);
				        $op['tags'] = $this->data_model->cargarOptimizacion($uno);
						
				        $op['menu'] = $this->data_model->cargarMenuHeader();
				        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
						
						$this->load->view('gracias-view', $op);
					}
			// Si estan tratando de agregar codigo o spameando el formulario les pinto .|.
			}
			else
			{ 
				echo '.|.  - Looser';
			}	

		}
	}


	function enviarGoogle(){
		
		@session_start();
		// Authenticate if we're not already
		if (!isset($_SESSION['access_token'])){
			redirect('oauth2callback');
			exit;
		}
		
		
		$google			= $this->load->library("google");
		$op['contacts'] = $google->printAllContacts();

		$emails    	= $this->input->post('email');
		$invito		= $this->input->post('invito');
		
		if (!$emails || sizeof($emails) <= 0){
			
			//Genera metatags
	        $uno = $this->uri->segment(1);
	        $op['tags'] = $this->data_model->cargarOptimizacion($uno);
			
	        $op['menu'] = $this->data_model->cargarMenuHeader();
	        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
			
			$this->load->view('google-view', $op);
			
		}else{
			
			foreach($emails as $key => $amigo){

				$info = array(
					'invito'	=> $invito,
					'amigo'     => $amigo,
				);
							  
				$this->db->insert('invitaciones', $info);
			
				$this->load->library('email');
				$this->email->set_newline("\r\n");
				$this->email->from($invito, 'Reparadores - Comunidad de Reparadores en Mexico');
				$this->email->to($amigo);
				$this->email->bcc('mdiaz@apeplazas.com');
				$this->email->subject('Invitación a la Comunidad de Reparadores.mx ');
				$this->email->message('
					<html>
					<head>
					<meta charset="utf-8">
					<title>Busca y encuentra los mejores técnicos de reparación en México</title>
					<style>
						h1, h2{font-size:20px; font-weight:700; margin:25px 0 3px}
						body{font-family:helvetica,arial,sans-serif; background-color:#f4f5f7; font-size:.8em; line-height:20px; color:#555}
						.wrap{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #c30}
						.wrapTwo{margin:10px auto; width:500px; border:1px solid #ccc; padding:20px 40px; background-color:#fff; border-top:10px solid #c30}
						a{color:#c30}
						#regEmail{display:inline; width:200px; margin:40px auto; height:28px; text-align:center; background-color: #B81C2D; color:#fff; text-transform:uppercase; font-size:16px; padding:12px 30px 9px; border-radius:6px; text-decoration:none;}
						li{list-style:none}
						ul{margin:0; padding:0}
						#foot{color:#777; font-size:10px;b order-top:1px solid #ccc; padding:10px 0}
						em{color:#c30; font-style: normal}
					</style>
					</head>
					<body style="background-color:#eee;">
					<table cellpadding="40" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px font-family:helvetica,arial,sans-serif; border:1px solid #ccc; color:#555; margin-top:30px;">
					  <tr>
					    <td>
					    <div class="wrap">
					    <h1>Hola </h1>
					    <p>Te recomiendo esta pagina web en la cual encontraras un directorio de los mejores técnicos de reparación en México.<br> <a href="http://www.reparadores.mx" title="Directorio Reparadores México" >www.reparadores.mx</a><br>Saludos</p>
					    </div>
					    </td>
					  </tr>
					</table>
					
					</body>
					</html>
			 	');
				$this->email->send();
			}
			redirect('compartir/gracias');

		}
	}
	
	
	function gracias(){
		//Genera metatags
	        $uno = $this->uri->segment(1);
	        $op['tags'] = $this->data_model->cargarOptimizacion($uno);
			
	        $op['menu'] = $this->data_model->cargarMenuHeader();
	        $op['conocimientos'] = $this->data_model->cargarConocimientosHome();
			
			$this->load->view('gracias-view', $op);

	}
	
}


