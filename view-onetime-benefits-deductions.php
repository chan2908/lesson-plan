<?php
ini_set('display_errors','0');
error_reporting(E_ALL);
include "db-connect.php";
  session_start();
$name= $_SESSION["name"];
$access= $_SESSION["access"];
$id= $_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$gid= $_SESSION["gid"];
$bid= $_SESSION["bid"];
if($_SESSION["name"]=='' && $_SESSION["access"]=='' && $_SESSION["id"]=='' && $_SESSION["email"]=='' && $_SESSION["company_name"]=='' && $_SESSION["bid"]=='' && $_SESSION["gid"]==''){header('Location:https://incometax.indiafilings.com/tds_filing/salary/session/session.php');} 


$selectQuery= pg_query($db,"select employee_id,employee_name,onetime_benefits,onetime_deductions from onetime_benefits_deductions where bid='$bid'"); 
		while($benefits_deductions= pg_fetch_assoc($selectQuery))
		{
			 $employee_id=($benefits_deductions['employee_id']);
			 $employee_name=($benefits_deductions['employee_name']);
			 $onetime_benefits=json_decode($benefits_deductions['onetime_benefits'],true);  
			 $onetime_deductions=json_decode($benefits_deductions['onetime_deductions'],true);
		} 		
?>
	<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
		View OneTime Benefits and Deductions
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
				active: function() {
						sessionStorage.fonts = true;
				}
			});
		</script>	
		<link href="assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/vendors/custom/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" />
<style>
.form-control
{
background-color: #e9ecef;
 
}
html, body {
    font-weight: 400!important;
}    
  
 
</style>		
	</head>
	<?php include "HR-left-header-1.php"; ?>	 
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
	
			<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<?php include "left-menu.php"; ?>
			<!-- END: Left Aside -->
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
		
					
<div class="m-content">
						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text" style="color:#34bfa3;">
											One Time Benefits and Deductions
										</h3>
									</div>
								</div>
							</div>
							
							<div class="m-portlet__body">
							<table class="table table-striped- table-bordered table-hover table-checkable deductions " id="m_table_1">
									<thead>
										<tr>
											
											
											<th colspan="2"></th>
											<th style="text-align: center;<?php if(sizeof($onetime_benefits)==0){echo "display:none";}?>" colspan="<?php echo sizeof($onetime_benefits);?>">BENEFITS</th>
											<th style="text-align: center;" colspan="<?php echo sizeof($onetime_deductions);?>">DEDUCTIONS</th>
										</tr>
										<th> Employee Id</th>
										<th> Employee Name</th>
										<?php
											 foreach($onetime_benefits as $key1 => $value){ 
										?>
											<th>
												<?php echo $key1;?>
											</th>
										<?php	 } ?>
										<?php
											 foreach($onetime_deductions as $key => $value){ 
										?>
											<th>
											<?php echo $key; ?>
											</th>
										<?php	 } ?>
										</thead>	 
					 
									<tbody>
											<?php
				
											$selectQuery= pg_query($db,"select employee_id,employee_name,onetime_benefits,onetime_deductions from onetime_benefits_deductions where bid='$bid'"); 
			 
											 while($benefits_deductions= pg_fetch_assoc($selectQuery))
											{
											 $employee_id=($benefits_deductions['employee_id']);
											 $employee_name=($benefits_deductions['employee_name']);
											 $onetime_benefits=json_decode($benefits_deductions['onetime_benefits'],true);  
											 $onetime_deductions=json_decode($benefits_deductions['onetime_deductions'],true);
					
											?> 
											<tr>									
												<td>  <?php echo $employee_id;?></td>
												<td>  <?php echo $employee_name;?></td> 
												<td> <?php  echo $onetime_benefits[$key1]; ?></td>
												<td> <?php  echo $onetime_deductions[$key]; ?></td>
											  
									 <?php  }?> 
						   
											</tr>
									</tbody>
								</table>
							</div>
						</div>
						</div>
					
					
	
<!-- </div>
</div> -->
</div>
</div>
<?php  //include "master_view.php"; ?>		
<?php include "footer.php"; ?>		

		<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
		<script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script> 
		<script src="assets/demo/datatable/dataTables.bootstrap.js" type="text/javascript"></script>   
<script src="assets/demo/datatable/jquery.dataTables.js" type="text/javascript"></script>   
		<script>


 /*   table = $('#m_table_1').DataTable( {
    retrieve: true,
    paging: true,
	destroy: true,
} );
   */
$(function () {

        var localTable =$('#m_table_1').dataTable({
            "bProcessing": true,
            "iDisplayLength": <?php echo sizeof($employee_id); ?>,
            "bPaginate": [ [50],[100],[200], [<?php echo sizeof($employee_id); ?>] ],
            "sPagingType": "full_numbers"
             
        });

    }); 
	
$("#export").click(function(){
  $("#m_table_1").tableToCSV();
   //filename: 'table.csv'
});	 
	</script>
		
		
</body>
</html>
