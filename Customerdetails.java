import java.util.*;
class Customer
{
	
     private String phonenumber;
	 private String name;
	 private String address;
     private Double currentbalance;

      
	       
  public Customer(String phonenumber,String name,String address,Double currentbalance)
      {
     	this.phonenumber=phonenumber;
     	this.name=name;
     	this.address=address;
     	this.currentbalance=currentbalance;
      }
    public String getPhonenumber()
	   {
		return phonenumber;
	   }		
	public String getName()
	  {
		return name;
	  }
    public String getAddress()
	  {
		return address;
	  }	
	public double getCurrentbalance()
	  {
		return currentbalance;
	  }
	public void setPhonenumber(String phonenumber)
	  {
		this.phonenumber=phonenumber;
	  }
	public void setName(String name)
	  {
		this.name=name;
	  }
	public void setAddress(String address)
	  {
		this.address=address;
	  }
	public void setCurrentbalance(Double currentbalance)
	  {
		this.currentbalance=currentbalance;
	  }
 }
class Customerdetails
{
	private int index;
		
	public int getIndex() 
	{
		return index;
	}

	public void setIndex(int index)
	{
		this.index = index;
	}
   HashMap<Integer,Customer> recharge=new HashMap<Integer,Customer>();
   {
   Customer customer1=new Customer("9944483167","chan","cpt",2.50d);
   Customer customer2=new Customer("9566204910","aslam","poththeri",3d);
   Customer customer3=new Customer("9876543210","basha","kachipuran",4d);
   Customer customer4=new Customer("9514543089","hema","cpt",5d);

   recharge.put(1,customer1);
   recharge.put(2,customer2);
   recharge.put(3,customer3);
   recharge.put(4,customer4);
   }
     public void topup(String mobilenumber,Double amount)throws Exception
   {
       try
	 {
				if(isvalid(mobilenumber,amount)==true)
				{
					System.out.println(recharge.get(getIndex()).getCurrentbalance());
						recharge.get(getIndex()).setCurrentbalance(recharge.get(getIndex()).getCurrentbalance()+amount);
						System.out.println(recharge.get(getIndex()).getCurrentbalance());
				}

     }
	 
	 catch(Exception e)
	 {
	 System.out.println(e.getMessage());
	 }
   }
   public boolean isvalid(String mobileno,Double rechargeamount)throws Exception
    {
		    boolean yesOrNo=false;
			
			//to find the index
					for(int i=1;i<recharge.size();i++)
					{
						if(mobileno.equals(recharge.get(i).getPhonenumber()))
						{
							setIndex(i);
						}
					}


   
	 
	         if(mobileno.equals(recharge.get(index).getPhonenumber()))
			     {
						 yesOrNo=true;			 
						System.out.println("the phonenumber is valid");
		 
					 }
			   else
			     {
				    throw new Exception("the mobilenumber is not valid");
					 }
		   	
               if(rechargeamount>5)
			     {
						 yesOrNo=true;
			       System.out.println("the amount is valid");
			     }
			   else
			     {
		             throw new Exception("the rechargeamount is not valid");  	   
			     }
			   
			return yesOrNo;
		   
		   
	}
}
class Service
{
    public static void main(String args[])

     {
			Customerdetails cust=new Customerdetails();
            try
     {
			 
         cust.topup("9566204910",15d);
	 }
    catch(Exception e)
     {
	System.out.println(e.getMessage());
     }
	 }
}