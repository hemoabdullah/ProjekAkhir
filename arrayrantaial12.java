import java.util.Scanner;
public class arrayrantaial12 {
public static void main(String[] args) {
Scanner scan = new Scanner(System.in);
    int[]nilaiMhs;
    double totalmin = 0;
    double totalmaks = 0;
    double ratamin;
    double ratamaks;
    int banyakmin = 0;
    int jumlah = 0 ;
    int banyakmaks=0;
    double tidaklulus = 0;
System.out.println("Masukkan jumlah mahasiswa : ");
   jumlah = scan.nextInt();
   nilaiMhs = new int[jumlah];

for(int i = 0;i<jumlah;i++){
    System.out.println("Masukkan nilai Mahasiswa ke - "+(i+1)+" : ");
    nilaiMhs[i]= scan.nextInt();

    //if(nilaiMhs[i]<70){
       // tidaklulus++;
   // }

    }
for(int i = 0;i<nilaiMhs.length;i++){
    if(nilaiMhs[i]<=70){
        totalmin += nilaiMhs[i];
        banyakmin++;
    }
    else if(nilaiMhs[i]> 70){
        totalmaks += nilaiMhs[i];
        banyakmaks++;
    }
}
ratamin=  totalmin/banyakmin;
ratamaks = totalmaks/banyakmaks;
System.out.println("Rata rata nilai tidak lulus = " +ratamin);
System.out.println("Rata rata nilai lulus = "+ratamaks );
    }
}