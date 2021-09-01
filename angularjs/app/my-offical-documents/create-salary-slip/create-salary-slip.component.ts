import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { ThrowStmt } from '@angular/compiler';
import { iif } from 'rxjs';
import { NgxSpinnerService } from 'ngx-spinner';
@Component({
  selector: 'app-create-salary-slip',
  templateUrl: './create-salary-slip.component.html',
  styleUrls: ['./create-salary-slip.component.css']
})
export class CreateSalarySlipComponent implements OnInit {
  registerform: FormGroup; //form group
  recentdata: any; //declare
  getdata: any; //declare
  fetch: any; //declare
  lname: any; //declare
  fname: any; //declare
  cal: number; //declare
  basic:number; //declare
  hr: number; //declare
  epf: number; //declare
  sa: number; //declare
  lta: number; //declare
  total_earn: number; //declare
  basic_pay: any; //declare
  sum: number; //declare
  conveyance: any; //declare
  cea: any; //declare
  medical: any; //declare
  attire: any; //declare
  special: any; //declare
  other: any; //declare
  v:any; //declare
  net:any; //declare
  grossalary: any; //declare
  netsalary: any; //declare

  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.v=1;
    this.fetch_empid();
    this.buildform();
  }

  //validators form's field
  buildform()
  {
    this.registerform=this.formBuilder.group({
        fname:[''],
        emp_id:['',Validators.required],
        basic_pay:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        hra:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        conveyance:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        cea:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        lta:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        medical:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        attire:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        special:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        other:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        gross_salary:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        pt:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        epf:['',[Validators.required,Validators.pattern('([0-9]){1,}')]],
        net_salary:[Validators.pattern('([0-9]){1,}')],
    })
  }

  //add salary details on click
  onSubmit():void
  {
    console.log(this.registerform.value);
    const data={
        empId:this.registerform.value.emp_id,
        basicPay:this.registerform.value.basic_pay,
        hra:this.registerform.value.hra,
        conveyance:this.registerform.value.conveyance,
        cea:this.registerform.value.cea,
        lta:this.registerform.value.lta,
        medical:this.registerform.value.medical,
        attire:this.registerform.value.attire,
        special:this.registerform.value.special,
        other:this.registerform.value.other,
        grossalary:this.registerform.value.gross_salary,
        pt:this.registerform.value.pt,
        epf:this.registerform.value.epf,
        netsalary:(this.registerform.value.gross_salary)-(+this.registerform.value.pt + +this.registerform.value.epf)//calculation for count netsalary
    }
    this.netsalary=(this.registerform.value.gross_salary)-(+this.registerform.value.pt + +this.registerform.value.epf)//netslary

    //sum of basic+hra+conveyance+cea+lta+medical+attire+special+other
    this.sum=+this.registerform.value.basic_pay + this.registerform.value.hra + +this.registerform.value.conveyance
            + +this.registerform.value.cea + +this.registerform.value.lta + +this.registerform.value.medical
            + +this.registerform.value.attire + +this.registerform.value.special + +this.registerform.value.other

    //net value(gross salary)        
    this.net=this.registerform.value.gross_salary
    if(this.sum==this.net)
    {
        if(this.net > this.netsalary && this.netsalary>=0)
        {
        this.spinner.show();//show the spinner
        this.http.post('salary/addsalary',data).subscribe((res:any)=>{
        console.log(res);
            if(res['message']=='Post successfully')
            {
                this.toastr.success('Data save successfully!', 'SUCCESS!');
                this.registerform.value.fname='';
                console.log(this.registerform.value.fname);
                this.registerform.reset();
            }this.spinner.hide();//hide the spinner
        })
        }
        else{
        this.toastr.warning('Invalid net salary', 'warning!');
        }
    }
    else {
    this.toastr.warning('Gross salary and Total earning not match', 'warning!');
    }
 }

  selectOption(id)
  { 
    this.spinner.show();//show the spinner
    this.http.get('emp/vieEmpName',{id:id}).subscribe((res:any)=>{ //api for get employee name
    console.log(res);
    this.fname=res.result[0].first_name;
    this.lname=res.result[0].last_name;
    this.spinner.hide();//hide the spinner
    })
 }

 fetch_empid()
 {
  this.spinner.show();//show the spinner
    this.http.get('emp/getallemp').subscribe((res:any)=>{ //api for get employee id
    console.log(res);
    this.recentdata=res.result;
    this.spinner.hide();//hide the spinner
  })
}

 selectgross(value)
 {
    console.log(value);
    this.basic=Math.round(value*0.3); //gross value*0.3
    this.hr=Math.round(value*0.4); //gross value*0.4
    this.sa=Math.round(value*0.0833); //gross value*0.0833
    this.lta=Math.round(value*0.15); //gross value*0.15
    console.log(this.basic);
    this.registerform.patchValue({
    basic_pay: this.basic,
    hra: this.hr,
    conveyance: 0,
    cea: 0,
    lta: 0,
    medical: 0,
    attire: 0,
    special: this.sa,
    other: 0,
    })
 }

}
