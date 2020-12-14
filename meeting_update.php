<?php
	include_once("inc/UserHeader.php");
	include_once("classes/User.php");
	
 ?>
 <?php
 	$usr = new User();
 	
 	$userId = Session::get("userId");
 	if(isset($_GET['mid_up']))
	{
		$mid = (int) $_GET['mid_up'];
		$get_updata = $usr->getMeetingUpData($mid);
		$get_up_usr_data = $usr->getMeetUpUserData($mid);
	}
	
	$get_data = $usr->getAllData($userId);
	$arr_all_usr_id = array();
	$arr_all_usr_name = array();
	while($list1 =  $get_data->fetch_assoc())
	{
		array_push($arr_all_usr_id,$list1['id']);
		
		array_push($arr_all_usr_name, $list1['name']);
	}

	$arr_usr_id = array();
	while($list =  $get_up_usr_data->fetch_assoc())
	{
		array_push($arr_usr_id,$list['user_id']);
	}

 ?>

 <?php
 	if(isset($insertdata))
 	{
 		echo $insertdata;
 	}
 ?>
 <style type="text/css">
 	p.pfield-wrapper input {
  float: right;
}
p.pfield-wrapper::after {
  content: "\00a0\00a0 "; /* keeps spacing consistent */
  float: right;
}
p.required-field::after {
  content: "*";
  float: right;
  margin-left: -3%;
  color: red;
}
 </style>


		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>
					<a class="btn btn-success" href="create_metting.php">Create Meeting</a>
					<a class="btn btn-info pull-right" href="UserIndex.php">Back</a>
				</h2>
			</div>

			<div class="panel-body">
				<form action="" method="post" id="framework_form">
					<?php 
						
									
									if($get_updata)
									{
										while ($value = $get_updata->fetch_assoc()) {
								?>

					<div class="form-group">
						<label for="roll">Meeting Title</label>
						<input type="text" class="form-control" name="m_title" id="m_title" value="<?php echo $value['m_title']; ?>" >
					</div>
					
					
					<div class="form-group">
						<label for="sel1">Meeting Select Partner:</label><br>
					      	<select id="framework" name="framework[]" multiple class="form-control" >
					      		<?php 
						
								
									if($get_data)
									{ 
										
									
										for ($x = 0; $x < count($arr_all_usr_id); $x++)
										{
											$usr_id = $arr_all_usr_id[$x];
											$usr_name = $arr_all_usr_name[$x];
											if(in_array($usr_id, $arr_usr_id, True))
											{

												echo "<option selected = 'selected' value='".$usr_id."'>".$usr_name."</option>";
											}
											else
											{
												echo "<option value='".$usr_id."'>".$usr_name."</option>";
											}
										
						

										}
									}	
								?>  
						    </select>
					</div>
					
					<div class="form-group">
						<label for="roll">Meeting Date</label>
						<input id="m_date" type="text" name="m_date" style="width:155px" value="<?php echo $value['m_date']; ?>" >
					</div>
					<div class="form-group">
						<label for="roll">Meeting Time</label>
						<input type="time" name="m_time" id="m_time" required="" value="<?php echo $value['m_time']; ?>" >
					</div>
					<?php }} ?>
					
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="submit" value="Update">
					</div>
					<input type="hidden" name="userId" id ="userId" value="<?php echo $userId ?>" autofocus="autofocus">
					<input type="hidden" name="m_id" id ="m_id" value="<?php echo $mid ?>" autofocus="autofocus">
				</form>
			</div>
			<span id="msg"></span>
		</div>


<script>

$(document).ready(function(){
 $('#framework').multiselect({
  nonSelectedText: 'Select Framework',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
 
 $('#framework_form').on('submit', function(event){
  event.preventDefault();
  var m_title 		= $("#m_title").val();
  var framework = $("#framework").val()
  var m_date 	= $("#m_date").val();
  var m_time 	= $("#m_time").val();
  var userId    = $("#userId").val();
  var m_id    = $("#m_id").val();
var form_data 	= 'm_title='+m_title+'&framework='+framework+'&m_date='+m_date+'&m_time='+m_time+'&userId='+userId+'&m_id='+m_id;
  $.ajax({
   url:"update_meeting_data.php",
   method:"POST",
   data:form_data,
   success:function(data)
   {
	$('#framework_form').trigger("reset");
	$('#framework option:selected').each(function(){
     $(this).prop('selected', false);
    });
    $('#framework').multiselect('refresh');

    $("#msg").html(data);									
	setTimeout(function(){
		$("#msg").fadeOut();
		},4000);

	setTimeout(function(){
		location.reload();
	},1000)
   }
  });
 });
 
 
});


    
</script>
<script type="text/javascript">
	 $(function () {
       var $dp1 = $("#m_date");
      $(document).ready(function () {
  
      $dp1.datepicker({
        changeYear: true,
        changeMonth: true,
            minDate: '0',
        dateFormat: "dd-m-yy",
        yearRange: "-100:+20",
      });
     });              
     
     });
           
   



               
</script>
<?php include "inc/UserFooter.php"; ?>