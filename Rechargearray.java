import java.util.*;
class Rechargearray
{
   String phonenumber[]={"9566204910","9944483167","9514543089","12345679","987654321"};
   double currentbalance[]={10,20,30,40,50,60};
   int index =-1;
   
public void topup(String mobilenumber,double amount)throws Exception
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
	 for(int i=0;i<5;i++)
	 {	
        if(mobileno.equals(phonenumber[i]))
        {
		 System.out.println("the phonenumber is valid");
		 index=i;
		}
	 }
	 if(mobileno.isEmpty())
	    {
		 throw new Exception("the phonenumber is empyt so enter your number");
	    }
         
     if(rs>5)
       {
          System.out.println("the rs is valid");
       }
     else
       {
          
            throw new Exception("the rs is not valid");
        }
		if(index>-1)
		{
		 currentbalance[index]=currentbalance[index]+rs;
	 
		 System.out.println("the recharge amount:"+currentbalance[index]);
		}
  }
public static void main(String arg[])throws Exception
 {
      Rechargearray r=new Rechargearray();
	/*  try
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
		r.topup("9566204910",5);
	}
	catch(Exception e)
	{
		System.out.println(e.getMessage());
	}
	*/
	try
	{
		r.topup("9944483167",20);
	}
	catch(Exception e)
	{
		System.out.println(e.getMessage());
	}
 }
 }
