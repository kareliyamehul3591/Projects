import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { ActivatedRoute } from '@angular/router';
import * as jspdf from 'jspdf';
import html2canvas from 'html2canvas';
@Component({
  selector: 'app-view-gen-salary',
  templateUrl: './view-gen-salary.component.html',
  styleUrls: ['./view-gen-salary.component.css']
})
export class ViewGenSalaryComponent implements OnInit {
  id:any;
  recentdata: any;
  desgn: any;
  first_name: any;
  last_name: any;
  gross_salary: any;
  month: any;
  year: any;
  basic: number;
  hr: number;
  epf: number;
  sa: number;
  lta: number;
  conveyance: any;
  cea: any;
  medical: any;
  attire: any;
  other: any;
  pt: number;
  esic: any;
  tds: any;
  advance: any;
  total_deduction: number;
  net_salary: number;
  special: number;
  hra: number;
  constructor(public http:HttpService, private _route: ActivatedRoute) { }

  ngOnInit() {
   this.fetch();
  }

  fetch()
  {
    const month_name=this._route.snapshot.paramMap.get('month')
    const year_name=this._route.snapshot.paramMap.get('year')
    this.month=month_name;
    this.year=year_name;
    const data={
     month:this.month,
     year:this.year
    }
    this.http.post('salary/Salaryslipview',data).subscribe((res:any)=>{ //api for view salary slip
     console.log(res);
     this.recentdata=res.result;
    })
  }

  //function use for convert html to pdf format
  public ConvertPDFWithWholepage()
  {
    var data=document.getElementById('contentToConvert');
    html2canvas(data).then(canvas=>{
      var imgWidth=208;
      var pageHeight=295;
      var imgHeight=canvas.height * imgWidth / canvas.width;
      var heightLeft=imgHeight;

      const contentDataURL=canvas.toDataURL('image/png')
      let pdf = new jspdf('l', 'pt', "a4");
      var postion=0;
      pdf.addImage(contentDataURL,'PNG',0,postion,imgWidth,imgHeight)
      pdf.save('File.pdf');
    });
  }
}
