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
		 
		 public void setPhonenumber(String phonenumber)
		 {
		 this.phonenumber = phonenumber;
		 }

	public String getName()
	  {
		return name;
		}
		
		public void setName(String name)
	  {
		this.name=name;
		}
		
    public String getAddress()
	  {
		return address;
		}	
		
		public void setAddress(String address)
	  {
		this.address=address;
		}
		
	public double getCurrentbalance()
	  {
		return currentbalance;
	  }
	
		public void setCurrentbalance(Double currentbalance)
	  {
		this.currentbalance=currentbalance;
	  }
 }
 
 
class Customerdetails
{ 
   
 public void topup(String mobilenumber, Double amount, HashMap<String, Customer> recharge)throws Exception
   {
       try
      	 {
						Customer customer = recharge.get(mobilenumber);
						if(customer == null)
					 System.out.println("Number is not present");	
				    double currentbalance = customer.getCurrentbalance();
				    System.out.println("the old balance is:"+currentbalance);
					currentbalance=currentbalance+amount;

					System.out.println("the recharge is successfully:"+currentbalance);
     }
	         
	 catch(Exception e)
	 {
	 System.out.println(e.getMessage());
	 }
   }
   
}



public class Service
{
    public static void main(String args[])

     {
			Customerdetails cust = new Customerdetails();
            try {
		
 HashMap<String, Customer> recharge=new HashMap<String, Customer>();
	   
   Customer customer1=new Customer("9944483167","chan","cpt",2.50d);
   Customer customer2=new Customer("9566204910","aslam","poththeri",3d);
   Customer customer3=new Customer("9876543210","basha","kachipuran",4d);
   Customer customer4=new Customer("9514543089","hema","cpt",5d);

   recharge.put(customer1.getPhonenumber(),customer1);
   recharge.put(customer2.getPhonenumber(),customer2);
   recharge.put(customer3.getPhonenumber(),customer3);
   recharge.put(customer4.getPhonenumber(),customer4); 
         
		 cust.topup("9566204910",15d, recharge);
	 }
    catch(Exception e)
     {
	System.out.println(e.getMessage());
     }
	 }
}