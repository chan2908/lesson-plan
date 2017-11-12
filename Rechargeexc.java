class Rechargeexc
{
   String phonenumber="9566204910";
   double currentbalance=10;
public void topup(String mobilenumber,double amount)
 {
     try
	 {
        isvalid(mobilenumber,amount);
     }
	 
	 catch(Exception e)
	 {
	 System.out.println(e.getMessage());
	 
	 }
         
 }
public void isvalid(String mobileno,double rs)throws Exception
 {
	
     if(mobileno.isEmpty())
	 {
		 throw new Exception("the phonenumber is empyt so enter your number");
	 }
     if(mobileno.equals(phonenumber))
	 {
		 System.out.println("the phonenumber is valid");
	 }
	 else
	 {
		 throw new Exception("the phonenumber is not valid so please check your number");
	 }
     if(rs>20)
       {
          System.out.println("the rs is valid");
       }
     else
       {
          
            throw new Exception("the rs is not valid");
        }
		 currentbalance=currentbalance+rs;
	 {
		 System.out.println("the recharge amount:"+currentbalance);
	 } 
  }
public static void main(String arg[])throws Exception
 {
      Rechargeexc r=new Rechargeexc();
	  try
	  {
		  r.topup("",50);
	  }
	  catch(Exception e)
	  {
		  System.out.println(e.getMessage());
	  }
	 try
     {
         r.topup("9566204910",5);
	 }
    catch(Exception e)
     {
	System.out.println(e.getMessage());
     }
	 try
	 {
	     r.topup("9566204911",50);
	}
	catch(Exception e)
	 {
	 System.out.println(e.getMessage());
	}
	try
	{
		r.topup("9566204910",50);
	}
	catch(Exception e)
	{
		System.out.println(e.getMessage());
	}
 }
}