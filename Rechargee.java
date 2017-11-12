import java.io.*;
class Rechargee
{
double phonenumber=9566204910d;
double currentbalance=12.50;
public void topup(double mobilenumber,double amount)
{
isvalid(mobilenumber,amount);

currentbalance=currentbalance+amount;
System.out.println("the recharge amount:"+currentbalance);

}
public void isvalid(double mobileno,double rs)
{
if(phonenumber==mobileno)
{
System.out.println("the phonenumber is valid");
}
else
{
System.out.println("the phonenumber is not valid");  
}
if(rs>20)
System.out.println("the rs is valid");
else
System.out.println("the rs is notvalid");
}
public static void main(String arg[])
{
Rechargee r=new Rechargee();
r.topup(9566204910d,50);
}
}




