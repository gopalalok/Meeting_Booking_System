<?php
	$filepath = realpath(dirname(__FILE__));
	include_once($filepath.'/../lib/Session.php');
	include_once($filepath.'/../lib/Database.php');
	include_once($filepath.'/../lib/Format.php');
?>
<?php
	Class User
	{
		private $db;
		private $fm;
		
		public function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function getStudents()
		{
			$query = "SELECT * FROM tbl_student";
			$result = $this->db->select($query);
			return $result;
		}

		public function insertMeetingData($m_title,$framework,$m_date,$m_time,$userId)
		{
			$m_title = $this->fm->validation($m_title);
			$framework = $this->fm->validation($framework);
			$m_date = $this->fm->validation($m_date);
			$m_time = $this->fm->validation($m_time);


			$m_title = mysqli_real_escape_string($this->db->link,strtoupper($m_title));
			$framework = mysqli_real_escape_string($this->db->link,$framework);
			$m_date = mysqli_real_escape_string($this->db->link,$m_date);
			$m_time = mysqli_real_escape_string($this->db->link,$m_time);
			if(empty($m_title) || ($framework == "null") || empty($m_date) || empty($m_time))
			{
				echo "<div class='alert alert-danger'><strong>Error!</strong>Field must not be empty !</div>";
				exit();
			}

			$names = explode(',', $framework);
			$c = count($names);
			
			$s_time = date('H:i', strtotime($m_time.'-1 hour'));
			$e_time = date('H:i', strtotime($m_time.'+1 hour'));
			$search_query = "SELECT m_id FROM tbl_create_meeting WHERE m_date = '$m_date' AND m_time BETWEEN '$s_time' AND '$e_time' ";

			$result = $this->db->select($search_query);
			
			if($result)
			{

				$m_idd = array();
				while($value = $result->fetch_assoc())
				{
					$m_idd[] = $value['m_id'];
				}
				foreach($m_idd as $m_iddd)
				{
					
					$userid_search = "SELECT user_id FROM tbl_meeting_mem WHERE m_id = '$m_iddd'";
					$userid_search2 = $this->db->select($userid_search);
					if($userid_search2)
					{
						$usr_id_val = array();
						while($value2 = $userid_search2->fetch_assoc())
						{
							$usr_id_val[] = $value2['user_id'];
						}
						if(in_array($userId, $usr_id_val, True))
						{
							echo "<div class='alert alert-danger'><strong>Error! </strong>This slot is not available !</div>";
									exit();
						}
						for ($x = 0; $x < $c; $x++)
						{
							$usr_id = $names[$x];
							
							if(in_array($usr_id, $usr_id_val, True))
							{
								echo "<div class='alert alert-danger'><strong>Error! </strong>This slot is not available !</div>";
									exit();
							}
						}
						
					}
				}
			}
			

			else
			{
				$meeting_Id = rand();
				$data_query = "INSERT INTO tbl_create_meeting(m_title,m_date,m_time,m_id) VALUES('$m_title','$m_date','$m_time','$meeting_Id')";
				$d_query = $this->db->insert($data_query);

				$data2_query = "INSERT INTO tbl_meeting_mem(m_id,user_id) VALUES('$meeting_Id','$userId')";
				$d_query = $this->db->insert($data2_query);

				$names = explode(',', $framework);
				$c = count($names);
				for ($x = 0; $x < $c; $x++) 
				{
					$usr_id = $names[$x];
					  $data_query = "INSERT INTO tbl_meeting_mem(m_id,user_id) VALUES('$meeting_Id','$usr_id')";
					  $d_query = $this->db->insert($data_query);
				}
				if($d_query)
				{
					echo "<div class='alert alert-success'><strong>Success!</strong>Meeting added successfully!</div>";
					exit();
				}
				else
				{
					echo "<div class='alert alert-danger'><strong>Error!</strong>Meeting data not inserted!</div>";
					exit();
				}
			}
		}

		public function updateMeetingData($m_title,$framework,$m_date,$m_time,$userId,$m_id)
		{
			$m_title = $this->fm->validation($m_title);
			$framework = $this->fm->validation($framework);
			$m_date = $this->fm->validation($m_date);
			$m_time = $this->fm->validation($m_time);
			$m_id 	=  $this->fm->validation($m_id);


			$m_title = mysqli_real_escape_string($this->db->link,strtoupper($m_title));
			$framework = mysqli_real_escape_string($this->db->link,$framework);
			$m_date = mysqli_real_escape_string($this->db->link,$m_date);
			$m_time = mysqli_real_escape_string($this->db->link,$m_time);
			$m_id = mysqli_real_escape_string($this->db->link,$m_id);

			$names = explode(',', $framework);
			$c = count($names);
			if(empty($m_title) || ($framework == "null") ||  empty($m_date) || empty($m_time))
			{
				echo "<div class='alert alert-danger'><strong>Error!</strong>Field must not be empty !</div>";
				exit();
			}
			else
			{
					$s_time = date('H:i', strtotime($m_time.'-1 hour'));
					$e_time = date('H:i', strtotime($m_time.'+1 hour'));

					$search_query = "SELECT m_id FROM tbl_create_meeting WHERE m_id <> '$m_id' AND m_date = '$m_date' AND m_time BETWEEN '$s_time' AND '$e_time' ";

					$result = $this->db->select($search_query);
					if($result)
					{
						$m_idd = array();
						while($value = $result->fetch_assoc())
						{
							$m_idd[] = $value['m_id'];
						}
						foreach($m_idd as $m_iddd)
						{
							
							$userid_search = "SELECT user_id FROM tbl_meeting_mem WHERE m_id = '$m_iddd'";
							$userid_search2 = $this->db->select($userid_search);
							if($userid_search2)
							{
								$usr_id_val = array();
								while($value2 = $userid_search2->fetch_assoc())
								{
									$usr_id_val[] = $value2['user_id'];
								}
								if(in_array($userId, $usr_id_val, True))
								{
									echo "<div class='alert alert-danger'><strong>Error! </strong>This slot is not available !</div>";
									exit();
								}
								for ($x = 0; $x < $c; $x++)
								{
									$usr_id = $names[$x];
									if(in_array($usr_id, $usr_id_val, True))
									{
										echo "<div class='alert alert-danger'><strong>Error! </strong>This slot is not available !</div>";
										exit();
									}
								}
								
							}
						}
					}
			


					else
					{
						$up_query = "UPDATE tbl_create_meeting SET m_title = '$m_title',m_date = '$m_date',m_time = '$m_time' WHERE m_id = '$m_id'";
						$updated_row = $this->db->update($up_query);

						$query = "DELETE FROM tbl_meeting_mem WHERE m_id = '$m_id'";
						$deldata = $this->db->delete($query);

						$data2_query = "INSERT INTO tbl_meeting_mem(m_id,user_id) VALUES('$m_id','$userId')";
						$d_query = $this->db->insert($data2_query);


						$names = explode(',', $framework);
						$c = count($names);
						for ($x = 0; $x < $c; $x++)
						{
							$usr_id = $names[$x];
							$data_query = "INSERT INTO tbl_meeting_mem(m_id,user_id) VALUES('$m_id','$usr_id')";
					  		$d_query = $this->db->insert($data_query);
						}
						if($d_query)
						{
							echo "<div class='alert alert-success'><strong>Success! </strong>Meeting updated successfully!</div>";
							exit();
						}
						else
						{
							echo "<div class='alert alert-danger'><strong>Error! </strong>Meeting data not updated!</div>";
							exit();
						}
					}

				
			}
			
		}

		public function getTotalRows()
		{
			$query = "SELECT * FROM tbl_student";
			$getResult = $this->db->select($query);
			$total = $getResult->num_rows;
			return $total;
		}

		

		public function deleteMeeting($delid)
		{
			$query = "DELETE FROM tbl_create_meeting WHERE m_id = '$delid'";
			$deldata = $this->db->delete($query);

			$query = "DELETE FROM tbl_meeting_mem WHERE m_id = '$delid'";
			$deldata = $this->db->delete($query);
			if($deldata)
			{
				$msg = "<div class='alert alert-success'><strong>Success!</strong> Meeting deleted successfully !</div>";
				return $msg;
			}
			else
			{
				$msg = "<div class='alert alert-danger'><strong>Meeting Not Removed !</strong></div>";
				return $msg;
			}
		}

		public function getAllData($userId)
		{
			$query = "SELECT * FROM tbl_user_reg WHERE id != '$userId'";
			$result = $this->db->select($query);
			return $result;
		}

		

		public function userRegistration($data)
		{
			$name 		= 		$this->fm->validation($data['name']);
			$password 	= 		$this->fm->validation($data['password']);
			$cnfpassword= 		$this->fm->validation($data['cnfpassword']);
			$email 		= 		$this->fm->validation($data['email']);
			
			$name	= mysqli_real_escape_string($this->db->link,strtoupper($name));
			$password 	= mysqli_real_escape_string($this->db->link,$password);
			$cnfpassword 	= mysqli_real_escape_string($this->db->link,$cnfpassword);
			$email 		= mysqli_real_escape_string($this->db->link,$email);
			
			if($name == "" || $password == "" || $cnfpassword == "" || $email == "" )
			{
				$msg = "<span class='danger'>Field must not be empty!</span>";
				return $msg;
			}
			else
			{

				if(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password) === 0)
				{
					$msg = "<span class='danger'>Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit</span>";
					return $msg;
				}
				if($password != $cnfpassword)
				{
					$msg = "<span class='danger'>pleace enter same password!</span>";
					return $msg;
				}
				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$msg = "<span class='danger'>Invalid email address!</span>";
					return $msg;
				}
				
				$chkquery = "SELECT * FROM tbl_user_reg WHERE email='$email'";
				$chkresult = $this->db->select($chkquery);
				if($chkresult != false)
				{
					$msg = "<span class='danger'>Email already exit!</span>";
					return $msg;
				}
				else
				{
					$password = md5($password);
					$query = "INSERT INTO tbl_user_reg(name,email,password) VALUES('$name','$email','$password')";
					$insert_row = $this->db->insert($query);
					if($insert_row)
					{
						header("Location:Registration_Completed_Sucess_Msg.php");
					}
					else
					{
						$msg = "<span class='danger'>Error try again!</span>";
						return $msg;
					}
				}
			}

		}

		public function userLogin($data)
		{
			$email 		= $this->fm->validation($data['email']);
			$password 	= $this->fm->validation($data['password']);
			$email 		= mysqli_real_escape_string($this->db->link,$email);
			$password 	= mysqli_real_escape_string($this->db->link,md5($password));
			
			if($email == "" || $password == "")
			{
				$msg = "<span class='danger'>enter email & password!</span>";
				return $msg;
			}
			else
			{
				$query = "SELECT * FROM tbl_user_reg WHERE email='$email' AND password='$password'";
				$result = $this->db->select($query);
				if($result != false)
				{
						$value = $result->fetch_assoc();
						Session::init();
						Session::set("userLogin",true);
						Session::set("userId",$value['id']);
						Session::set("userEmail",$value['email']);
						header("Location:UserIndex.php");
				}
				else
				{
					$msg = "<span class='danger'>Email or Password doesn't matched!</span>";
						return $msg;
				}
			}
		}

		public function getUserData($email)
		{
			$email 	= $this->fm->validation($email);
			$email	= mysqli_real_escape_string($this->db->link,$email);
			$query = "SELECT * FROM tbl_user_reg WHERE email='$email' ";
			$result = $this->db->select($query);
			return $result;
		}

		public function getMeetUpUsrData($mid_up)
		{
			$mid_up 	= $this->fm->validation($mid_up);
			$mid_up	= mysqli_real_escape_string($this->db->link,$mid_up);
			$query = "SELECT * FROM tbl_create_meeting WHERE m_id='$mid_up' ";
			$result = $this->db->select($query);			
			return $result;
		}

		public function getMeetUpUserData($mid_up)
		{
			$mid_up 	= $this->fm->validation($mid_up);
			$mid_up	= mysqli_real_escape_string($this->db->link,$mid_up);
			$query = "SELECT user_id FROM tbl_meeting_mem WHERE m_id='$mid_up' ";
			$result = $this->db->select($query);			
			return $result;
		}

		public function getUserName($mid_up)
		{
			$mid_up 	= $this->fm->validation($mid_up);
			$mid_up	    = mysqli_real_escape_string($this->db->link,$mid_up);

			$usr_id     = $this->getMeetUpUserData($mid_up);
			
			$resultset = array();		
			while ($row = $usr_id->fetch_assoc())
			{
				$row2 = $row['user_id'];
    			$query  = "SELECT name FROM tbl_user_reg WHERE id = '$row2' ";
    			$result = $this->db->select($query);
    			while ($result2 = $result->fetch_assoc())
    			{
    					$resultset[] = $result2['name'];	
    			}	
				
			}
			return $resultset;

			
		}

		public function getMeetingUpData($mid_up)
		{
			$mid_up 	= $this->fm->validation($mid_up);
			$mid_up	= mysqli_real_escape_string($this->db->link,$mid_up);
			$query = "SELECT * FROM tbl_create_meeting WHERE m_id='$mid_up' ";
			$result = $this->db->select($query);			
			return $result;
		}

		public function getMeetingData($userId)
		{
			$StuRoll 	= $this->fm->validation($userId);
			$StuRoll 	= mysqli_real_escape_string($this->db->link,$userId);
			$query = "SELECT * FROM tbl_create_meeting INNER JOIN tbl_meeting_mem ON tbl_create_meeting.m_id = tbl_meeting_mem.m_id WHERE tbl_meeting_mem.user_id = '$userId'  ORDER BY m_date DESC";
			$result = $this->db->select($query);
			return $result;
		}

		
								
							
			
		
		
	}
?>