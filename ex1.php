<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
$name= $_SESSION["name"];
$access= $_SESSION["access"];
$id= 4;//$_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$bid= $_SESSION["bid"];
$gid= $_SESSION["gid"];
//echo $id;exit;
 if($name=='' || $access=='' || $id=='' || $email=='' || $company_name=='' || $bid=='' || $gid==''){header('Location:https://incometax.indiafilings.com/tds_filing/salary/session/session.php');} 

include "db-connect.php";

$current_month =  date('M');
$current_month1 = strtoupper($current_month);
$current_year = date('Y');






// fetch query for select box
$query2 = "SELECT distinct year FROM employee_overall_gross_detail  ";
$result2 = pg_query($db, $query2);
while($res=pg_fetch_assoc($result2)){
$temp1[]=$res;
}
if(isset($_POST['year']))
{ 
$insert_data=json_encode($_POST);
}


if(isset($_POST['month1']))
{ 
$month= $_POST['month1'];
$year=$_POST['year1'];
$select_query="select a.basic, a.gross_salary_breakup, b.actuals_so_far, b.user_deduction from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail b ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name  where a.month='$month' and a.year='$year' AND  a.bid='$bid' and a.employee_id='$id' and a.company_name='$company_name' ";

//print_r($select_query);exit;
$resp_query=pg_query($db,$select_query);
$resp_data=pg_fetch_assoc($resp_query);
//echo $resp_data;exit;
$basic=$resp_data['basic'];
//echo $basic;exit;
$take_home=$resp_data['actuals_so_far'];
//echo $take_home;exit;
$gross_salary_breakup1 = $resp_data['gross_salary_breakup'];
//echo $gross_salary_breakup1;exit;
$user_deduction =$resp_data['user_deduction'];

//echo $user_deduction; exit;

$basic1 = '{"basic":"'.$basic.'"}';

//$actuals_so_far1 = '{"actuals_so_far":"'.$take_home.'"}';
$sel_finaldata1 = pg_query($db, "select '$gross_salary_breakup1' || '$basic1' ::jsonb as final_data from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail B ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name where a.month='$month' and a.year='$year'  and a.bid='$bid' and a.employee_id='$id'");


$resp_fdata=pg_fetch_assoc($sel_finaldata1);
$gross_salary_breakup2 = $resp_fdata['final_data'];




//echo $gross_salary_breakup2;exit;
//die($gross_salary_breakup3);


// $sel_finaldata2 = pg_query($db, "select '$gross_salary_breakup2' || '$user_deduction' ::jsonb as final_data2 from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail B ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name where a.month='$month' and a.year='$year' AND   a.bid='$bid' and a.employee_id='$id' ");

// $resp_fdata2=pg_fetch_assoc($sel_finaldata2);
// $gross_salary_breakup3 = $resp_fdata2['final_data2'];
//echo $take_home;exit;
//echo $sel_finaldata2;exit;
// $gross_salary_breakup3='{"GrossTotal":108389,"basic_earning":65032,"MedicalAllowance":1129,"SpecialAllowance":21273,"ConveyanceAllowance":1445,"PF":0,"Hra":19510}';


die($gross_salary_breakup2."~".$take_home."~".$user_deduction);



}


//echo $basic_v;exit;


 // query for showing current month details
if(isset($_POST['curr_month']))
{ 

$curr_month= $_POST['curr_month'];
$curr_year=$_POST['curr_year'];
$select_query="select a.basic, a.gross_salary_breakup, b.actuals_so_far,  b.user_deduction  from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail b ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name  where a.month='$curr_month' and a.year='$curr_year' and  a.bid='$bid' and a.employee_id='$id' ";

$resp_query=pg_query($db,$select_query);
$resp_data=pg_fetch_assoc($resp_query);
$basic=$resp_data['basic'];
//echo $basic_v;exit;
$take_home=$resp_data['actuals_so_far'];
$gross_salary_breakup1 = $resp_data['gross_salary_breakup'];
$user_deduction =$resp_data['user_deduction'];
$basic1 = '{"basic":"'.$basic.'"}';
//$actuals_so_far1 = '{"actuals_so_far":"'.$take_home.'"}';
$sel_finaldata1 = pg_query($db, "select '$gross_salary_breakup1' || '$basic1' ::jsonb as final_data from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail B ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name where a.month='$curr_month' and a.year='$curr_year' and  a.bid='$bid' and a.employee_id='$id' ");


$resp_fdata=pg_fetch_assoc($sel_finaldata1);
$gross_salary_breakup2 = $resp_fdata['final_data'];

// $sel_finaldata2 = pg_query($db, "select '$gross_salary_breakup2' || '$user_deduction' ::jsonb as final_data1 from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail B ON a.employee_id=b.employee_id and a.month=b.current_month and a.employee_name=b.employee_name where a.month='$curr_month' and a.year='$curr_year' AND      a.bid='$bid' and a.employee_id='$id'  ");

// $resp_fdata1=pg_fetch_assoc($sel_finaldata2);
// $gross_salary_breakup3 = $resp_fdata1['final_data1'];
//echo $gross_salary_breakup3;exit;
// echo "hai";exit;
 
 
die($gross_salary_breakup2."~".$take_home."~".$user_deduction);
}
 
 

?>




<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<meta charset="utf-8" />
<title>
IndiaFilings - Salary Details
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
<style>
element.style {
    margin-top: 18%;
    margin-left: -23%;
    padding: 27px;
}
.label1
{
	 margin-top:20px;
}
.m-portlet .m-portlet__head .m-portlet__head-tools .nav.nav-pills, .m-portlet .m-portlet__head .m-portlet__head-tools .nav.nav-tabs{
	
	
	margin: 0;
    height: 100%;
    position: relative;
    border-bottom-color: transparent;
    bottom: -20px;
}

.m-body .m-content {
    padding: 31px 30px;
}



</style>

<!--end::Web font -->
<!--begin::Base Styles -->  
<!--begin::Page Vendors -->
<link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Page Vendors -->
		<link href="assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
		<script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
		 
<!-- begin:: Page   m-footer--push m-aside--offcanvas-default m-brand--minimize m-aside-left--minimize  -->
</head>
<?php include "profile-header.php";?>
<!-- begin::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">



	  <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

<button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
<i class="la la-close"></i>
<?php include "profile-aside.php";?>
</button>
	  
     



<div class="m-grid__item m-grid__item--fluid m-wrapper" >
<div class="m-content" >
<div class="row">
<div class="col-xl-3 ">
<div class="m-portlet m-portlet--full-height  ">
									<div class="m-portlet__body">
										
								</div>
							</div>
</div>

<div class=" col-xl-9 ">
<div class="m-portlet m-portlet--bordered" >
<div class="m-portlet__head">
										<div class="m-portlet__head-tools pull-left">										
											<ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--left m-tabs-line--primary">
												<li class="nav-item m-tabs__item">
													<a class="nav-link m-tabs__link" href="javascript:;">
														<i class="flaticon-share m--hide"></i>
														<h4>My Information</h4>
													</a>
												</li>
											</ul>
										</div>		
                    <div class="m-portlet__head-tools pull-right">
											<ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary1" role="tablist">
												<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_user_profile_tab_1" role="tab" aria-selected="true"><i class="flaticon-share m--hide"></i>Information</a></li>
												<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_3" role="tab" aria-selected="false"><i class="flaticon-share m--hide"></i>Salary</a></li>
												<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_5" role="tab" aria-selected="false"><i class="flaticon-share m--hide"></i>Personal</a></li>
												<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2" role="tab">Bank Details</a></li>
												<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_6" role="tab">Tax</a></li>
											</ul>
										</div>
									</div>
									<div class="tab-pane" id="m_user_profile_tab_3">
                      <form class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body" >

											<div class="m-form__group row" style="margin-left:2%; margin-right:2%">
		<div class="form-group col-sm-6">
			<label for="text" style="margin-top:10px;color: #3f4047;">Select Year </label>
			<select  class="form-control m-input  file_validation"  required="" >
												
		    <?php 
			foreach($temp1 as $key=> $value)
			{
			?>
<option <?php if($temp1[$key]['year']==$current_year){ echo "selected"; } ?> value='<?php echo $temp1[$key]['year']; ?>'><?php echo $temp1[$key]['year'];?></option>												
			<?php } ?>									
</select>
</div>
<div class="form-group col-sm-6">
<label for="text" style="margin-top:10px;color: #3f4047;">Select Month</label>
<select  class="form-control m-input  select_month"  required="" >
<option  value="">Select </option>
<option <?php if($current_month1=='JAN') {echo "selected";}  ?> value="JAN">January </option>
<option <?php if($current_month1=='FEB') {echo "selected";}  ?> value="FEB">Febuary </option>
<option <?php if($current_month1=='MAR') {echo "selected";}  ?> value="MAR">March</option>
<option <?php if($current_month1=='APR') {echo "selected";}  ?> value="APR">April</option>
<option <?php if($current_month1=='MAY') {echo "selected";}  ?> value="MAY">May</option>
<option <?php if($current_month1=='JUN') {echo "selected";}  ?> value="JUN">June</option>
<option <?php if($current_month1=='JUL') {echo "selected";}  ?> value="JUL">July</option>
<option <?php if($current_month1=='AUG') {echo "selected";}  ?> value="AUG">August</option>
<option <?php if($current_month1=='SEP') {echo "selected";}  ?> value="SEP">September</option>
<option <?php if($current_month1=='OCT') {echo "selected";}  ?> value="OCT">October</option>
<option <?php if($current_month1=='NOV') {echo "selected";}  ?> value="NOV">November</option>
<option <?php if($current_month1=='DEC') {echo "selected";}  ?> value="DEC">December</option>
</select>
</div>
</div>

<div class="row show_record " style="display:none; margin-left:2%; margin-right:2%"> 
<div class="col-lg-6">
<div class="m-portlet m-portlet--bordered m-portlet--rounded  m-portlet--last" >
<div class="m-portlet__head">
<div class="m-portlet__head-caption">
<div class="m-portlet__head-title" >
<h3 class="m-portlet__head-text">
Earnings
</h3>
</div>
</div>
</div>


<div class=" m-portlet__body col-md-8 " style="margin-left:8%; margin-bottom:0%"  >
<label style="text-transform:uppercase">Basic</label>
<input type="text" class="form-control m-input basic_1" value="<?php echo $basic; ?>"  disabled="">
</div>
<div class=" col-md-10 append_data"  style="margin-top:-5%;margin-left:0%;">

</div>
<div class=" m-portlet__body col-md-8 " style="margin-left:8%; margin-top:-2%;"  >
<label style="text-transform:uppercase">Gross Total</label>
<input type="text" class="form-control m-input gross" value=""  disabled="">
</div></br>

</div></div> 
<div class="col-lg-6">
<div class="m-portlet m-portlet--bordered m-portlet--rounded  m-portlet--last">
<div class="m-portlet__head">
<div class="m-portlet__head-caption">
<div class="m-portlet__head-title">
<h3 class="m-portlet__head-text">
Deduction
</h3>
</div>
</div>
</div>

<div class="m-portlet__body append_data1">
</div>

</div>
</div>  

</div> </br>


<div class="row "> 
<div class="col-lg-12 ">
<div class="m-portlet__body no_record"  style="">
					<div class="m-section__content">
						<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
							<div class="m-demo__preview m-demo__preview--badge">
								<div class="m-divider">
									<span></span>
									<span>No records Found.</span>
									<span></span>
								</div>	
                            </div>
						</div>
					</div>
			</div>

</div>
</div>
<div class="m-portlet__foot m-portlet__foot--fit ">
<div class="col-md-12 ">
<div class="form-group m-form__group row ">
												<div class="col-lg-4">
													<label class="label" style="text-transform:uppercase">Take Home</label>
													<input type="text"  class="form-control m-input take_home1"  value =" " disabled="">
												</div>
												<div class="col-lg-6">
												<form id="view_payslip" method="post" target="_blank" action="backup/payslip_backup.php">
													<input type="hidden" class="get_month" name="get_month" >
													<input type="hidden" class="get_year" name="get_year">
													<button class="btn btn-info btn-sm view_payslip_button" style="margin-top:20px; float:right;"> View Salaryslip</a>
												</form>
													
												</div>
											</div>

</div>
</div> 

</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>

</div>


<?php include "footer.php"; ?>



	
	
</body>
<script>


$(document).on("change",".select_month",function(){
		var month=$(this).val();
		//alert(month);
		//a='<?php echo $basic_v;?>'; alert(a);
		var year=$(".file_validation").val();
		//alert(company_name);
		$.ajax({
        type: "post",
        data: {month1:month,year1:year} ,
        success: function (response) {
			
			
	if(response!='')
	{
			$(".show_record").show();
			
			$(".no_record").hide();
		}
		else 
		{
			
			
			$(".show_record").hide();
			
			$(".no_record").show();
		}


			var split_data=response.split("~");
			//alert(split_data[0]);	
			$(".append_data").empty();
			$(".append_data1").empty();
			$(".take_home1").val('');
			$(".basic_1").val('');
			$(".gross").val('');
			var response1 = JSON.parse(split_data[0]);
			var response2 = JSON.parse(split_data[2]);
			
	    	$(".take_home1").val(split_data[1]);
			
			
			
			for(var key in response1)
			{ 
		
			//alert (response1[key]);
		    
			    // $(".Gratuity").hide();
			    // $(".SuperAnnuation").hide();
				$(".basic_earning").hide();
				$(".basic").hide();
			    $(".GrossTotal").hide();
				//$(".gross").val(GrossTotal);
				if(key =='basic_earning')
				{
				var data_s =response1['basic_earning'];
				//alert(data_s);
				$(".basic_1").val(data_s);
				}
                if(key =='GrossTotal')
				  {
				var data_g = response1['GrossTotal'];
				//alert(data_g);
				  $(".gross").val(data_g);
				  }
				
				if(response1[key]!=0)
				{
				
				if(key =='PF' )
				{
				$(".append_data1").append('<div class="col-md-12 '+key+'" style="margin-top:2%;"><div class="form-group m-form__group"><label>'+key+'</label><input type="text" class="form-control m-input" value="'+response1[key]+'" disabled=""></div></div>');
				}
				else{
				$(".append_data").append('<div class="col-md-12 '+key+'" style="margin-top:2%;"><div class="form-group m-form__group"><label>'+key+'</label><input type="text" class="form-control m-input" value="'+response1[key]+'" disabled=""></div></div>');
				
				}
				}
				
			}
			
			
			
			
			// $(".append_data").append('<div class="col-md-12 " style="margin-top:2%;"><div class="form-group m-form__group"><label> GrossTotal</label><input type="text" class="form-control m-input" value="'+data['GrossTotal']+'" disabled=""></div></div>');	
			
			for(var key in response2)
			{ 
			for(var res in response2[key])
			{ // alert(key);
		for(var json in response2[key][res])
		{ 
					 var json1=response2[key][res][json];
					 //alert(json1);
			   
			
	
				
				if(json1!=0)
				{
				if(json!='Payable' && json!='plan' )
					
				{	
				
				
				
			
						$(".append_data1").append('<div class="col-md-12 '+res+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+json+'</label><input type="text" class="form-control m-input" value="'+json1+'" disabled=""></div></div>');
						
					//}
				// if(data =='PF' )
				// {
				// $(".append_data1").append('<div class="col-md-12 '+data+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+data+'</label><input type="text" class="form-control m-input" value="'+data1+'" disabled=""></div></div>');
				// }
				// else
				// {
				// $(".append_data").append('<div class="col-md-12 '+data+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+data+'</label><input type="text" class="form-control m-input" value="'+data1+'" disabled=""></div></div>');
			
				}
				
				}
					
				}
			}
				
			}	
					
			
			}
			
			
		});
		
		
	});
	
	$(document).on("click", ".view_payslip_button", function(){
			var selected_month = $(".select_month").val();
			var selected_year = $(".file_validation").val();
			$(".get_month").val(selected_month);
			$(".get_year").val(selected_year);	
			
		});
	function get_salary_details()
	{
		var curr_month = '<?php echo $current_month1; ?>';
		var curr_year = '<?php echo $current_year; ?>';
			$.ajax({
        type: "post",
		data: {curr_month:curr_month,curr_year:curr_year} ,
         success: function (response) {
			 
			if(response!='')
	{
		$(".show_record").show();
			
			$(".no_record").hide();
			
		}
		else 
		{
			
			
			
			$(".show_record").hide();
			$(".no_record").show();
		}



			var split_data=response.split("~");
			//alert(split_data[0]);	
			$(".append_data").empty();
			$(".append_data1").empty();
			$(".take_home1").val('');
			$(".basic_1").val('');
			$(".gross").val('');
			var response1 = JSON.parse(split_data[0]);
			var response2 = JSON.parse(split_data[2]);
			
	    	$(".take_home1").val(split_data[1]);
			
			
			
			for(var key in response1)
			{ 
		
			//alert (response1[key]);
		    
			    // $(".Gratuity").hide();
			    // $(".SuperAnnuation").hide();
				$(".basic_earning").hide();
				$(".basic").hide();
			    $(".GrossTotal").hide();
				//$(".gross").val(GrossTotal);
				if(key =='basic_earning')
				{
				var data_s =response1['basic_earning'];
				//alert(data_s);
				$(".basic_1").val(data_s);
				}
                if(key =='GrossTotal')
				  {
				var data_g = response1['GrossTotal'];
				//alert(data_g);
				  $(".gross").val(data_g);
				  }
				
				if(response1[key]!=0)
				{
				
				if(key =='PF' )
				{
				$(".append_data1").append('<div class="col-md-12 '+key+'" style="margin-top:2%;"><div class="form-group m-form__group"><label>'+key+'</label><input type="text" class="form-control m-input" value="'+response1[key]+'" disabled=""></div></div>');
				}
				else{
				$(".append_data").append('<div class="col-md-12 '+key+'" style="margin-top:2%;"><div class="form-group m-form__group"><label>'+key+'</label><input type="text" class="form-control m-input" value="'+response1[key]+'" disabled=""></div></div>');
				
				}
				}
				
			}
			
			
			
			
			// $(".append_data").append('<div class="col-md-12 " style="margin-top:2%;"><div class="form-group m-form__group"><label> GrossTotal</label><input type="text" class="form-control m-input" value="'+data['GrossTotal']+'" disabled=""></div></div>');	
			
			for(var key in response2)
			{ 
			for(var res in response2[key])
			{ // alert(data);
		for(var json in response2[key][res])
		{ 
					 var json1=response2[key][res][json];
					 //alert(json1);
			   
			
	
				
				if(json1!=0)
				{
				if(json!='Payable' && json!='plan' )
					
				{	
				
				
				
			
						$(".append_data1").append('<div class="col-md-12 '+res+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+json+'</label><input type="text" class="form-control m-input" value="'+json1+'" disabled=""></div></div>');
						
					//}
				// if(data =='PF' )
				// {
				// $(".append_data1").append('<div class="col-md-12 '+data+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+data+'</label><input type="text" class="form-control m-input" value="'+data1+'" disabled=""></div></div>');
				// }
				// else
				// {
				// $(".append_data").append('<div class="col-md-12 '+data+'" style="margin-top:2%;"><div class="form-group m-form__group"><label style="text-transform:uppercase">'+data+'</label><input type="text" class="form-control m-input" value="'+data1+'" disabled=""></div></div>');
			
				}
				
				}
					
				}
			}
				
			}
		 }
		 
		
		});
		
		
	}
	
	
	get_salary_details();
	
	
	
	</script>
	</html>
