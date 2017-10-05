import java.util.*;
class Customer
{
	
   private String phonenumber;
	 private String name;
	 private String address;
         private Double currentbalance;
  public Customer()
  {
	  System.out.println("mobile recharge");
  }
      
	       
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

public class Customer1
{		
  
     public static void main(String arg[])throws Exception
   
        {
			
	   
   HashMap<Integer,Customer> recharge=new HashMap<Integer,Customer>();
   
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
        isvalid(mobilenumber,amount);
     }
	 
	 catch(Exception e)
	 {
	 System.out.println(e.getMessage());
	 }
   }
   
public void isvalid(String mobileno,Double rs)throws Exception
 {
	 
	 
	 //get the set of key from hashmap
	 Set setOfKey=recharge.keySet();
	
	//get the iterator from set 
	 Iterator iterator=setOfKey.iterator();

	 //loop the iterator
	 while(iterator.hasNext())
	 {   
        //next() method return the next key from iterator instance
		 Integer key=(Integer)iterator.next();
		 
		//get the value from hashmap 
		 Customer value =(Customer)recharge.get(key);
	 }
	 
	  
               if(mobileno.equals(phonenumber()))
                  {
	            	 System.out.println("the phonenumber is valid");
		 
		          }
	      //}
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
		
		
		 currentbalance=currentbalance+rs;
	 
		 System.out.println("the recharge amount:"+currentbalance);
		
 }
	
   Customer1 r=new Customer1();
   {
		try
	     {
		r.topup("9944483167",20d);
	     }
	catch(Exception e)
	      {
		System.out.println(e.getMessage());
		  }	
   
	 /* try
	 {
		  r.topup("",50);
	  }
	  catch(Exception e)
	  {
		  System.out.println(e.getMessage());
	  }
	/* try
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
 
   
   }
 
   }