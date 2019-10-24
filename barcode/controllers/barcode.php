<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
ICT Agency,
160/24, Kirimandala Mawatha,
Colombo 05, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Author: Mr. Jayanath Liyanage   jayanathl@icta.lk
                 
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/
class Barcode extends MX_Controller {
	 function __construct(){
		parent::__construct();
		$this->load->helper('url');
        $this->load->library('session');
	}
	
	public function index(){
		echo 'Nothing here<br><a href="'.site_url("").'">Go to Home</a>';
		return;
	}

	/*
	 *	After scaning a patient card system will redirect to the related page depend his/her user group
	 */
	public function scan($barcode)
	{
		$direct_page = $this->session->userdata('Scan_Redirect');
                $ugroup=$this->session->userdata('UserGroup');
                $wtype=$this->session->userdata('WT');
		//echo 'Re-Directing to page "' . $direct_page . '"........';
		if ($direct_page != ""){
			$new_page   =   site_url($direct_page.'/'.$barcode);
			header("Status: 200");
			header("Location: ".$new_page);
		}
		else{
                    if($ugroup=='LabTech'){
			$new_page   =   site_url('laboratory/process_result/'.$barcode.'?CONTINUE=search/lab_orders/');
			header("Status: 200");
			header("Location: ".$new_page);
                    }
                    else if($ugroup=='Pharm' || $ugroup=='CPharm'){
                        if($wtype=='1'){ //OPD
                        $this->load->model('mpharmacy');
                        $prsid = $this->mpharmacy->getprsid($barcode);
                        if($prsid) {$new_page   =   site_url('pharmacy/dispense/'.$prsid.'');
			header("Status: 200");
			header("Location: ".$new_page);
                        }
                         else{
			$new_page   =   site_url('pharmacy');
			header("Status: 200");
			header("Location: ".$new_page);
		}
                        }
                      else if($wtype=='2'){ //clinic
                        $this->load->model('mpharmacy');
                        $prsid_clinic = $this->mpharmacy->getprsid_clinic($barcode);
                        if($prsid_clinic) {$new_page   =   site_url('pharmacy/clinic_dispense/'.$prsid_clinic.'');
			header("Status: 200");
			header("Location: ".$new_page);
                        }
                         else{
			$new_page   =   site_url('pharmacy');
			header("Status: 200");
			header("Location: ".$new_page);
		}
                        }
                    }
                   else{
			$new_page   =   site_url('patient/basic_info/'.$barcode);
			header("Status: 200");
			header("Location: ".$new_page);
		}
		}
    }
} 


//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
