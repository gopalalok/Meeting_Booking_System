<?php
	include_once("inc/UserHeader.php");
	include_once("classes/User.php"); 
 ?>
 <?php
 	$usr = new User();
 	if($_SERVER['REQUEST_METHOD'] == 'POST')
 	{
 		$insertdata = $usr->insertData($_POST);
	
 	}
 	$userId = Session::get("userId");
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
					

					<div class="form-group">
						<label for="roll">Meeting Title</label>
						<input type="text" class="form-control" name="m_title" id="m_title" required="">
					</div>
					
					
					<div class="form-group">
						<label for="sel1">Meeting Select Partner:</label><br>
					      	<select id="framework" name="framework[]" multiple class="form-control" >
					      		<?php 
						
									$get_data = $usr->getAllData($userId);
									if($get_data)
									{
										while ($value = $get_data->fetch_assoc()) {
								?>
						      <option class="allnames" value="<?php echo $value['id']; ?>"><?php echo $value['name'] ?></option>
						      <?php }} ?>
						    </select>
					</div>
					
					<div class="form-group">
						<label for="roll">Meeting Date</label>
						<input id="m_date" type="text" name="m_date"  style="width:155px;" value="">
					</div>
					<div class="form-group">
						<label for="roll">Meeting Time</label>
						<input type="time" name="m_time" id="m_time">
					</div>
					
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="submit" value="Add">
					</div>
					<input type="hidden" name="userId" id ="userId" value="<?php echo $userId ?>" autofocus="autofocus">
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
var form_data 	= 'm_title='+m_title+'&framework='+framework+'&m_date='+m_date+'&m_time='+m_time+'&userId='+userId;
  $.ajax({
   url:"insert_meeting_data.php",
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