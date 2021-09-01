import { Component, OnInit,ViewChild,ElementRef } from '@angular/core';
import * as jspdf from 'jspdf';
import html2canvas from 'html2canvas';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-salary-slip',
  templateUrl: './salary-slip.component.html',
  styleUrls: ['./salary-slip.component.css']
})
export class SalarySlipComponent implements OnInit {
  registerform: FormGroup; //form group
  name = 'Angular Html To Pdf ';
  recentdata: any;
  getyear: any;
  desgn: any;
  emp_code: any;
  first_name: any;
  last_name: any;
  gross_salary: any;
  lwp: any;
  sl: any;
  cl: any;
  weekly_off:any;
  holidays:any;
  pt:any;
  esic:any;
  conveyance:any;
  advance:any;
  medical:any;
  epf:any;
  month_name: any;
  payble_days: any;
  year: any;
  getsalary: any;
  basic: any;
  hr: number;
  sa: number;
  lta: number;
  cea:any;
  tds:any;
  attire:any;
  special: any;
  other:any;
  total_deduction:any;
  net_salary:any;
 
  constructor(public http:HttpService,private formBuilder: FormBuilder,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.http.get('salary/getmonth').subscribe((res:any)=>{ //api for get month value
      console.log(res);
      this.recentdata=res.result;
    })
    this.spinner.show();//show the spinner
    this.http.get('salary/getyear').subscribe((res:any)=>{  //api for get year
      console.log(res);
      this.getyear=res.result;
      this.spinner.hide();//hide spinner
    })
    //validators for forms field
    this.registerform=this.formBuilder.group({
      month:['',Validators.required],
      year:['',Validators.required]
    })
  }

  onsubmit():void
  {
   console.log(this.registerform.value);
   const data={
     month:this.registerform.value.month,
     year:this.registerform.value.year
   }
   this.spinner.show();//show the spinner
   this.http.post('salary/salaryslipshow',data).subscribe((res:any)=>{ //show salary slip
     console.log(res);
    this.getsalary=res.result;
    this.desgn=this.getsalary[0].desgn,
    this.emp_code=this.getsalary[0].emp_code,
    this.first_name=this.getsalary[0].first_name,
    this.last_name=this.getsalary[0].last_name,
    this.gross_salary=this.getsalary[0].gross_salary,
    this.lwp=this.getsalary[0].lwp,
    this.sl=this.getsalary[0].sl,
    this.cl=this.getsalary[0].cl,
    this.weekly_off=this.getsalary[0].weekly_off,
    this.holidays=this.getsalary[0].holidays,
    this.month_name=this.getsalary[0].month_name,
    this.payble_days=this.getsalary[0].payble_days,
    this.pt=this.getsalary[0].pt,
    this.esic=this.getsalary[0].esic,
    this.conveyance=this.getsalary[0].conveyance,
    this.epf=this.getsalary[0].epf,
    this.cea=this.getsalary[0].cea,
    this.tds=this.getsalary[0].tds,
    this.advance=this.getsalary[0].advance,
    this.medical=this.getsalary[0].medical,
    this.year=this.getsalary[0].year,
    this.basic=this.gross_salary*0.3,
    this.hr=this.gross_salary*0.4,
    this.lta=this.getsalary[0].lta,
    this.attire=this.getsalary[0].attire,
    this.special=this.getsalary[0].special,
    this.other=this.getsalary[0].other,
    this.total_deduction=this.getsalary[0].total_deduction,
    this.net_salary=this.getsalary[0].net_salary
    this.spinner.hide();//hide spinner
   })
  }

  //function for convert html to pdf formate
  public ConvertPDFWithWholepage()
  {
    var data=document.getElementById('contentToConvert');
    html2canvas(data).then(canvas=>{
      var postion=0;
      let pdf = new jspdf('l', 'pt', "a4");
      var imgWidth = pdf.internal.pageSize.getWidth();
      var imgHeight = pdf.internal.pageSize.getHeight();
      const contentDataURL=canvas.toDataURL('image/png');
      pdf.addImage(contentDataURL,'PNG',0,postion,imgWidth,imgHeight)
      pdf.save('File.pdf');
    });
  }
}
