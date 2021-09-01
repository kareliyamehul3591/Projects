import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-view-salary',
  templateUrl: './view-salary.component.html',
  styleUrls: ['./view-salary.component.css']
})
export class ViewSalaryComponent implements OnInit {
  recentdata: any;
  basic: any;
  conveyance: any;
  cea: any;
  lta: any;
  medical: any;
  attire: any;
  special: any;
  other: any;
  pt: any;
  esic: any;
  epf: any;
  tds: any;
  advance: any;
  hra: any;
  id: string;
  gross_salary: any;
 
 
  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch();
  }

  fetch()
  {
    this.id=localStorage.getItem('id');
    this.spinner.show();//show the spinner
    this.http.get(`salary/ViewSalary`,{id:this.id}).subscribe((res:any)=>{ //api for view salary details
      console.log(res);
      this.recentdata=res.result[0];
      this.basic=this.recentdata.basic,
      this.hra=this.recentdata.hra,
      this.conveyance=this.recentdata.conveyance,
      this.cea=this.recentdata.cea,
      this.lta=this.recentdata.lta,
      this.medical=this.recentdata.medical,
      this.attire=this.recentdata.attire,
      this.special=this.recentdata.special,
      this.other=this.recentdata.other,
      this.gross_salary=this.recentdata.gross_salary,
      this.pt=this.recentdata.pt,
      this.epf=this.recentdata.epf
      this.spinner.hide();
    })
  }

}
