import { Component, OnInit } from '@angular/core';
import {formatDate } from '@angular/common';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { ToastrService } from 'ngx-toastr';
import { ActivatedRoute, Router } from '@angular/router';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-salary-sheet',
  templateUrl: './salary-sheet.component.html',
  styleUrls: ['./salary-sheet.component.css']
})
export class SalarySheetComponent implements OnInit {
  currentDate1:any; //declare
  currentDate: number = Date.now(); //use for show system date 
  months;
  years = []; //declare
  registerform: FormGroup; //form group
  showvalue:any; //declare

  constructor(private _route:ActivatedRoute,public _router:Router,private formBuilder: FormBuilder,public http:HttpService,
    public toastr:ToastrService,private spinner: NgxSpinnerService) { }
    
  ngOnInit() {
    this.fetchvalue();
    this.getDates();
    //validators for form feild
    this.registerform=this.formBuilder.group({
      month:['',Validators.required]
    })
  }

  fetchvalue()
  {
    this.spinner.show();//show the spinner
    this.http.get('salary/value_salarysheet').subscribe((res:any)=>{ //api for get salary sheet
      console.log(res);
      this.showvalue=res.result;
      this.spinner.hide();//hide the spinner
    })
  }

  //get date, year value
  getDates() {
    var date = new Date();
    var currentYear = date.getFullYear();
   console.log(date);
    //set values for year dropdown
    for (var i = 0; i <= 5; i++) {
      this.years.push(currentYear - i);
    console.log(this.years[0])
    }
    //set values for month dropdown
    this.months = [{name:"Jan",id:1}, {name:"Feb",id:2},{name:"Mar",id:3},{name:"Apr",id:4},
                   {name:"May",id:5},{name:"Jun",id:6},{name:"Jul",id:7},{name:"Aug",id:8},
                   {name:"Sep",id:9},{name:"Oct",id:10},{name:"Nov",id:11},{name:"Dec",id:12}]
    console.log(this.months)
  }

  onSubmit():void
  {
   this.currentDate1 = ((new Date()).getFullYear());
   console.log(this.currentDate1);
   console.log(this.registerform.value);
   const data={
    month:this.registerform.value.month,
     year:this.currentDate1
    }
    console.log('hi');
    console.log(data);
    this.spinner.show();//show the spinner
    this.http.post('salary/madeSalaryslip',data).subscribe((res:any)=>{ //api for generate salary slip
    console.log('here');
    console.log(res);
        if(res['message']=='Post successfully')
        {
            this.toastr.success('Salary Sheet Added Successfully');
            this.ngOnInit()
        }
        else if(res['message']=='data already exists')
        {
            this.toastr.error('This month salary sheet already created','Error!');
        }
        this.spinner.hide();//hide the spinner
        }, err=>{
        this.toastr.error(err.message || err);
        })
  }

  salary_sheet_excel(month,year)
  {
    this._router.navigate(['/generate-salary',{month,year}])
  }
}
