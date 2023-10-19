/**
 * ProjekAkhir 
 * Group Member : 1. Bayu 
 *                2. Hammam 
 *                3. Ridho
 */

//import
import java.util.Scanner;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.ParseException;
//import end

public class ProjekAkhir {
    public static void main(String[] args) {
        //var 
        Scanner input = new Scanner(System.in);
        boolean inputBalance = false;
        String name, address, job, ml = "Mr. ", fm = "Ms. ", cName; 
        int loanTerm, menu, approval, back, gender, age, month;
        double interest = 0.005, remainingBalance=0.0, monthlyInterest=0.0, monthlyInstallments=0.0, loanAmount=0.0, remainingdebt=0.0, totalDebttobePaid=0.0, remainingInstallments=0.0;
        //format Rp
        DecimalFormat kursIndonesia = (DecimalFormat) DecimalFormat.getCurrencyInstance();
        DecimalFormatSymbols formatRp = new DecimalFormatSymbols();
        kursIndonesia.setDecimalFormatSymbols(formatRp);
        //format Rp end
        //var end

        //title
        System.out.println("██╗    ██╗███████╗██╗      ██████╗ ██████╗ ███╗   ███╗███████╗\r\n" + //
                           "██║    ██║██╔════╝██║     ██╔════╝██╔═══██╗████╗ ████║██╔════╝\r\n" + //
                           "██║ █╗ ██║█████╗  ██║     ██║     ██║   ██║██╔████╔██║█████╗  \r\n" + //
                           "██║███╗██║██╔══╝  ██║     ██║     ██║   ██║██║╚██╔╝██║██╔══╝  \r\n" + //
                           "╚███╔███╔╝███████╗███████╗╚██████╗╚██████╔╝██║ ╚═╝ ██║███████╗\r\n" + //
                           " ╚══╝╚══╝ ╚══════╝╚══════╝ ╚═════╝ ╚═════╝ ╚═╝     ╚═╝╚══════╝\r\n" + //
                "                                                              \n");
        //title end

        //register
        System.out.println("\r\n" + //
                "╔════════════════════════╗\r\n" + //
                "║ PLEASE REGISTER FIRST  ║\r\n" + //
                "╚════════════════════════╝\r\n" + //
                "\r" + //
                "");
        System.out.print("Enter full name: ");
        name = input.nextLine();
        System.out.print("Gender (Male:1,Female0): ");
        gender = input.nextInt();
        if (gender == 1) {
            cName = ml+name;
        }else{
            cName = fm+name;
        }
        System.out.print("Age: ");
        age = input.nextInt();
        System.out.print("Enter your job: ");
        job = input.next();
        System.out.print("Address: ");
        address = input.next();
        System.out.println("\r\n" + //
                "           ID CARD         \r\n" + //
                " ========================== \r\n" + //
                "  Name    \t" +name        +"\r\n" + //
                "  Age     \t" +age         +"\r\n" + //
                "  Job     \t" +job         +"\r\n" + //
                "  Address \t" +address     +"\r\n" + //
                " ==========================");
        System.out.println("== REGISTRATION SUCCESSFUL ==\n");
        //register end

        //menu selection
        do {
            System.out.println("\r\n" + //
                    "+---------------------+\r\n" + //
                    "| PLEASE CHOOSE MENU  |\r\n" + //
                    "+---------------------+\r\n" + //
                    "| 1. Balance          |\r\n" + //
                    "| 2. Loan application |\r\n" + //
                    "| 3. Loan repayment   |\r\n" + //
                    "| 0. Exit             |\r\n" + //
                    "+---------------------+");
            System.out.print("=== Choose the number: ");
            menu = input.nextInt();

            switch (menu) {
                case 1:
                    System.out.println("\n-- BALANCE --\n");
                    System.out.print("Do you want to enter the balance? (true/false): ");
                    inputBalance = input.nextBoolean();
                    if (inputBalance) {
                        System.out.print("The balance amount you want to input (fill in 0 if you choose not to enter the balance or choose 0): Rp.");
                        remainingBalance = input.nextDouble();
                        System.out.println(cName + " your balance entry request has been submitted");
                    }
                    System.out.println(" - THANK YOU -\n");
                    break;

                case 2:
                    System.out.println("\n-- LOAN APPLICATION --\n");
                    System.out.print("How much loan amount you want: Rp.");
                    loanAmount = input.nextDouble();
                    System.out.print("How long is your proposed payback period (months): ");
                    loanTerm = input.nextInt();
                    System.out.print("Are you willing to bear the cost of repaying the loan at 5% interest per year? (1/0): ");
                    approval = input.nextInt();
                    if (approval == 1) {
                        System.out.println("\n# LOAN REQUEST APPROVED #\n");
                    }
                    if (inputBalance) {
                        remainingdebt = loanAmount - remainingBalance;
                        monthlyInterest = loanTerm * interest * remainingdebt;
                         totalDebttobePaid = monthlyInterest + remainingdebt;
                        monthlyInstallments = totalDebttobePaid / loanTerm; 
                        String x = kursIndonesia.format(monthlyInstallments);
                         String y = kursIndonesia.format(monthlyInterest);
                         System.out.println("\r\n" + //
                                 " ======================================================================= \r\n" + //
                                 "                              Dear "  +cName                     +"\r\n" + //
                                 " ======================================================================= \r\n" + //
                                 "  Your interest per month "  +y                        +"\r\n" + //
                                 "  The bill you have to pay per month amounts to " +x  +"\r\n" + //
                                 " ======================================================================= ");
                        System.out.println(" - THANK YOU -\n");  
                    } else {
                        remainingdebt = loanAmount - 0;
                        monthlyInterest = loanTerm * interest * remainingdebt;
                        totalDebttobePaid = monthlyInterest + remainingdebt;
                        monthlyInstallments = totalDebttobePaid / loanTerm;
                        String x = kursIndonesia.format(monthlyInstallments);   
                        String y = kursIndonesia.format(monthlyInterest);
                          System.out.println("\r\n" + //
                                 " ======================================================================= \r\n" + //
                                 "                               Dear "  +cName                     +"\r\n" + //
                                 " ======================================================================= \r\n" + //
                                 "  Your interest per month "  +y                        +"\r\n" + //
                                 "  The bill you have to pay per month amounts to " +x  +"\r\n" + //
                                 " ======================================================================= ");
                        System.out.println(" - THANK YOU -\n");  
                    }
                    break;

                case 3:
                    String a = kursIndonesia.format(totalDebttobePaid);
                    String x = kursIndonesia.format(monthlyInstallments);

                    System.out.println("\n-- LOAN REPAYMENT --\n");
                    System.out.println("Dear "+cName);
                    System.out.println("\r\n" + //
                            " ====================================================================== \r\n" + //
                            "                         Dear" +cName                 +"\r\n" + //
                            " ====================================================================== \r\n" + //
                            "  The loan amount you have to pay is:" +a                 +"\r\n" + //
                            "  The loan amount you are required to pay monthly is:" +x +"\r\n" + //
                            " ====================================================================== \r\n" + //
                            "\r\n" + //
                            "");
                    System.out.println("The loan amount you have to pay is: "+a);
                    System.out.println("The loan amount you are required to pay monthly is: "+x);
                    System.out.println("\n# If you want to repay the loan, you will have to pay the monthly installments set when you applied for the loan #");
                    System.out.print("How many months do you want to pay monthly installments: ");
                    month = input.nextInt();
                    remainingInstallments = totalDebttobePaid - (monthlyInstallments * month);
                    String c = kursIndonesia.format(remainingInstallments);
                    System.out.println("    # Your installment payment has been received #");
                    System.out.println("\r\n" + //
                            " =================================================================================== \r\n" + //
                            " "+cName  +" The remaining loan that you have to pay now is:" +c  +"\r\n" + //
                            " =================================================================================== \r\n" + //
                            "\r\n" + //
                            "");
                    System.out.println(cName +" The remaining loan that you have to pay now is: "+c);
                    System.out.println("-- THANK YOU FOR PAYING THE INSTALLMENT --\n");
                    break;

                case 0:
                    System.out.println("Exiting...");
                    break;
                    
                default:
                    System.out.println("Invalid choice. Please try again.");
                    break;
            }
            //menu selection end
        } while (menu != 0);

    }
}