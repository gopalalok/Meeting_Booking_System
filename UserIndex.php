<?php
 include_once("inc/UserHeader.php");
 include_once("classes/User.php");
 $userEmail = Session::get("userEmail");
 $userId = Session::get("userId");
 $usr = new User();
?>
<?php
 	if(isset($_GET['del']))
 	{
 		$delid = (int)$_GET['del'];
 		$del_met = $usr->deleteMeeting($delid);
 	} 
?>
<?php
 		if(isset($del_met))
		{
			echo $del_met;
		}
			 
?>

		<div class="panel panel-default">
			
			<div class="panel-body">
				<div class="well text-center" style="font-size: 20px">
					<strong>Today Date: </strong>
					<?php
						$cur_date = date('Y-m-d');
						echo $cur_date;
					?>
					|| <strong>Time : </strong>
					<?php
						date_default_timezone_set("Asia/Kolkata");
						echo date("h:i:sa");
					?>
				</div>
				
					
					<table class="table table-striped">

					<?php 
						$usr = new User();
						$get_data = $usr->getUserData($userEmail);
						if($get_data)
						{
							
							while ($value = $get_data->fetch_assoc())
							{
					?>
						<tr>
							<td> Name: 
							<?php echo $value['name']; ?>
							<a class="btn btn-primary pull-right" href="create_metting.php"> Create Meeting </a></td>
						</tr>
					<?php }} ?>

					</table>

					<form action="" method="post">
						<table class="table table-striped">
							<tr>
								<th width="10%">Serial</th>
								<th width="30%">Title</th>
								<th width="20%">Member</th>
								<th width="10%">Date</th>
								<th width="10%">Time</th>
								<th width="20%">Action</th>
							</tr>

						<?php 
							$usr = new User();
							$get_meeting_data = $usr->getMeetingData($userId);
							if($get_meeting_data)
							{
								$i = 0;
								while ($value = $get_meeting_data->fetch_assoc())
								{
									$i++;
									$m_id = $value['m_id'];
									$get_user_name = $usr->getUserName($m_id);
						?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $value['m_title']; ?></td>
								<td>
									<?php 
									if($get_user_name)
									{ 
										foreach($get_user_name as $item) 
										{
        									echo $item."<br>";
    									}

									} 
									?>
										
								</td>
								<td><?php echo $value['m_date']; ?></td>
								<td><?php echo $value['m_time']; ?></td>
								<td>
								<a class="btn btn-info" href="meeting_update.php?mid_up=<?php echo $value['m_id']; ?>">Update</a>
								
								<a onclick="return confirm('Are you sure to Remove')" class="btn btn-danger" href="?del=<?php echo $value['m_id']; ?>">Delete</a>
								</td>
									
							</tr>
						<?php } }?>

						</table>
					
					</form>
					
				
			</div>
		</div>
<?php include "inc/UserFooter.php"; ?>