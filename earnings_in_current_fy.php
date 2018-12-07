<?php
ini_set('display_errors','0');
error_reporting(E_ALL);
include "db-connect.php";
//$bid=7894;
  session_start();
//print_r($_SESSION);exit;
$name= $_SESSION["name"];

$access= $_SESSION["access"];
$id= $_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$gid= $_SESSION["gid"];
$bid= $_SESSION["bid"];
//echo $gid; exit;
if($_SESSION["name"]=='' && $_SESSION["access"]=='' && $_SESSION["id"]=='' && $_SESSION["email"]=='' && $_SESSION["company_name"]=='' && $_SESSION["bid"]=='' && $_SESSION["gid"]==''){header('Location:https://incometax.indiafilings.com/tds_filing/salary/session/session.php');} 


?>


<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
		    Uploaded Prior_Earnings
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
.m-portlet--tab
{
	width:100%;
}
 .paginate_disabled_previous
 {
	    margin-right: 20%;
		
		  
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
											Uploaded Prior_Earnings
										</h3>
									</div>
								</div>
							</div>
							
							<div class="m-portlet__body">
							<table class="table table-striped- table-bordered table-hover table-checkable deductions " id="m_table_1">
							<thead style="background-color:#F4F3F8;">

										<tr>
										<th>Employee_Id  </th>
										<th>Employee_Name </th>
										
   	
							   <?php 
						        $sel_sql=pg_query($db,"select DISTINCT month FROM prior_earnings");
								while($res=pg_fetch_array($sel_sql)){								
							    $result=$res['month'];	
										 												
						        ?>	 
							 	<th ><?php echo $result;?></th>							     
                                <?php }?>
								</tr>
									
								</thead>
								
								<tbody>
													
						        <?php
					 	          $sel_sql1=pg_query($db,"select  DISTINCT employee_id, employee_name FROM public.prior_earnings");
							      while($result1=pg_fetch_array($sel_sql1)){
							    $id=$result1['employee_id'];
								$name=$result1['employee_name'];
						        ?> 
								<tr>
								
								<td>
                                 <?php echo $id ;?>
							   </td>
						      <td>
								 <?php echo $name; ?>
							  </td>	 
							  <?php 
						      $sel_sql=pg_query($db,"select DISTINCT month FROM prior_earnings");
		
								while($res=pg_fetch_array($sel_sql)){								
							    $result=$res['month'];	
											 												
					     	?>	 
							 <td>
						 
                        <?php $month=pg_query($db,"select earnings_in_current_fy from prior_earnings where month='$result' and employee_id='$id'");	
							
						 $result2=0;
                            while($res=pg_fetch_assoc($month))
								{
								$result2=$res['earnings_in_current_fy'];									
										                          
						?>	 
                			<?php  } ?>	
		
						 <?php echo $result2;?>
						 
						 </td>                      																
						<?php  }?>	
								</tr>
								<?php
						} 						
						?>				
																														
							</tbody>
								
							</table>
							</div>
				</div>
	
		</div>	
		
	</div>

</div>

	
<?php include "footer.php"; ?>	
		<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
		<script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script> 
		<script src="assets/demo/datatable/dataTables.bootstrap.js" type="text/javascript"></script>   
<script src="assets/demo/datatable/jquery.dataTables.js" type="text/javascript"></script>   
<script>
$(function () {

        var localTable =$('#m_table_1').dataTable({
            "bProcessing": true,
            "iDisplayLength":10,
            "bPaginate": [ [50],[100],[200],[<?php echo sizeof($employee_id); ?>]],
            "sPagingType": "full_numbers"
             
        });

    }); 
	
</script>	
	
</body>
</html>