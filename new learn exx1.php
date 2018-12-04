<?php
ini_set('display_errors','0');
error_reporting(E_ALL);
include "db-connect.php";
        
     if(isset($_POST['sub'])){
		
		 // echo 'hii';exit;
		 $emp_id=$_POST['emp_id'];
	
	     $emp_name=$_POST['emp_name'];
		 
	     $emp_dob=$_POST['emp_dob'];
		
		 $select=pg_query($db,"select * from public.learn_exx1");
		
	     $sql =pg_query($db,"insert into public.learn_exx1(emp_id, emp_name,emp_dob)VALUES ('$emp_id','$emp_name','$emp_dob')");
		 
			 }
	//echo $sql;exit;
 /*  if($sql)
			
 {
echo "<script>alert('INSERTED SUCCESSFULLY');</script>";
}
else
 {
 echo "<script>alert('FAILED TO INSERT');</script>";s
 }  
  */
 
	/* if(count($sel_query_resp)!=0)
	{
		while($result=pg_fetch_array($sel_query_resp))
		{
			$emp=$result;
		?> 
		   <tr>
               <td><?php echo $emp['emp_id'] ?></td>
			   <td><?php echo $emp['emp_name'] ?></td>
			   <td><?php echo $emp['emp_dob'] ?></td>
		   </tr>
		
	<?php
		}
	}*/
		
?> 
 
 

    <!DOCTYPE html>
    <html lang="en">
    <!-- begin::Head -->

    <head>
        <meta charset="utf-8" />
        <meta name="description" content="Latest updates and statistic charts">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
                google: {
                    "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
                },
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>
        <title>
            My page
        </title>

        <!--begin::Base Styles -->
        <!--begin::Page Vendors -->
        <link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Page Vendors -->
        <link href="assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
        <script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
        <script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript">
        </script>

        <!-- begin:: Page   m-footer--push m-aside--offcanvas-default m-brand--minimize m-aside-left--minimize  -->
    </head>

    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default" style="">

            <!-- BEGIN: Header -->
            <header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
                <div class="m-container m-container--fluid m-container--full-height">
                    <div class="m-stack m-stack--ver m-stack--desktop">
                         <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                        <!-- BEGIN: Brand -->
                        <div class="m-stack__item m-brand  m-brand--skin-dark ">
                            <div class="m-stack m-stack--ver m-stack--general">

                                <div class="m-stack__item m-stack__item--middle m-brand__tools">

                                    <!-- BEGIN: Left Aside Minimize Toggle -->
                                    <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                                        <span></span>
                                    </a>

                                </div>
                            </div>
                        </div>                                          
                        </div>
                    </div>
                </div>
            </header>

            <!-- END: Header -->

            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
               
                <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
                    </div>
                <!-- END: Subheader -->
                <div class="m-content">

                    <div class="m-portlet m-portlet--mobile" style="width: 800px">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <div class="col-xl-8 order-2 order-xl-1">
                                        <h3 class="m-portlet__head-text">
											Employee Details
										</h3>
                                    </div>

                                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                                        <button type="button" class="btn btn-focus" data-toggle="modal" data-target="#emp_details">Add Employee Details</button>
                                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
<style>
#view
{
	 border-collapse: collapse;
    width: 100%;
}
#view td, #view th{
	 border: 1px solid #ddd;
    padding: 8px;
}
#view tr:nth-child(even)
{
	background-color:#f2f2f2;
}
#view tr:hover
{
	background-color:#ddd;
}
#view th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
</style>
                        <div class="m-portlet__body">
                                  
                            <!--begin: Datatable -->
                            <div class="m_datatable m-datatable m-datatable--default m-datatable--loaded  " id="local_data" style="">
							 <table id="view">
                               
		                 <thead class="m-datatable__head">
                                        <tr class="m-datatable__row">
												
												<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
												<span style="width: 110px;">Employee_Id</span>
												</th>
												<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
												<span style="width: 110px;">Employee_Name</span>
												</th>
												<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
												<span style="width: 110px;">Employee_dob</span>
												</th>
												  
												 
												 
												</tr>
											  </thead>	
											  <tbody>
											  <?php
											   $sel_query_resp=pg_query($db,"select * from public.learn_exx1");
												  $count=pg_num_rows($sel_query_resp);
												  $row=pg_fetch_array($sel_query_resp);
												  $emp_id=$row['emp_id'];
												  $emp_name=$row['emp_name'];
												  $emp_dob=$row['emp_dob'];	
									  while($result=pg_fetch_array($sel_query_resp)){
												  ?>
													
										    <tr class="m-datatable__row">
												
										    	<td>
                                                  <?php echo $result['emp_id'];?>
												</td>
											   <td>
											   <?php echo $result['emp_name']; ?>
											   </td>
											   <td>
											   <?php echo $result['emp_dob'];?>
											   </td>
								              
										  </tr>

											<?php
											  }
											?>
											 
											  </tbody>
							    </table>
                               
                            </div>
						
	                       </div>
                      <!--end: Datatable -->
                        </div>
                    </div>
                </div>
           

         

		
        <!-- end:: Body -->

        <div class="modal fade show" id="emp_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title " style="color:blue;" id="exampleModalLabel">Add The Employee Details Here</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="m-portlet m-portlet--tab ">

                        <!--begin::Form-->
                        <form class="m-form m-form--fit m-form--label-align-right emp"  method="post" id="login >
                            <div class="m-portlet__body">

                                <div class="form-group m-form__group row">
                                    <label for="Employee Id" class="col-2 col-form-label">Employee Id
                                    </label>
                                    <div class="col-10">
                                        <input class="form-control m-input" type="text" id="id" name="emp_id">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="Employee Name" class="col-2 col-form-label">Employee Name
                                    </label>
                                    <div class="col-10">
                                        <input class="form-control m-input" type="text"  id="name" name="emp_name">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="Employee DOB" class="col-2 col-form-label">Employee DOB</label>
                                    <div class="col-10">
                                        <input class="form-control m-input" type="text" id="dob" name="emp_dob">
                                    </div>
                                </div>
                                   <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close </button>
                                    <button onclick="myfun()" class="btn btn-primary modal_submit" id="submit" name="sub" >save</button>
                                </div>
	    					</div>
							
							 <script>
							 
	     function myfun(){
			//  var form=$('#login');
         // form.on('submit',function())
		 // {
			 // alert(a);
             // alert("hiii");
               var id = $('#id').val();
                var name = $('#name').val();
                var dob = $('#dob').val();
                if (id.trim() == '') {
                    alert('Please enter your id.');
                
                }      else if (name.trim() == '') {
                    alert('Please enter your name.');
                   
                } else if (dob.trim() == '') {
                    alert('Please enter your dob.');
                    
                }  else {
                    $.ajax({
                        //alert(1);
                        url: "learn.php",
                        type: "post",
                        data: $(".emp").serialize(),
						//alert(data);
                      success: function(data) {
                         // alert(data);
                        if (data!=0) {
                            toastr.success("data insert successfully");
						}
						else{
							toastr.error("data not inserted");
						}  
                             
                        }
                    });
                } 
           // }
			}
			
        </script>
<!--<style>
.toastr-success {
background-color: ##6610f2;
}
</style>-->
						</form>
                    </div>
                </div>         
            </div>
        </div>

    </body>

    </html>