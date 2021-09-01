import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-salary',
  templateUrl: './edit-salary.component.html',
  styleUrls: ['./edit-salary.component.css']
})
export class EditSalaryComponent implements OnInit {
  registerform: FormGroup; //form group
  id: any; //declare
  getdata: any; //declare
  basic: number; //declare
  hr: number; //declare
  epf: number; //declare
  sa: number; //declare
  lta: number; //declare
  gross_salary: any; //declare
  sum: any; //declare
  deduct: any; //declare
  netSalary: any; //declare
 
  constructor(public http:HttpService,private formBuilder: FormBuilder,private toastr: ToastrService
    ,private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

  ngOnInit() {
     //get id by params
 this._route.params.subscribe(params => {
  let id = params['id'];
  this.id=id;
 });
  console.log(this.id);
    this.fetchsalary();
    this.buildform();
  }

  //validators form's feild
  buildform()
  {
    this.registerform=this.formBuilder.group({
        basic:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        hra:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        conveyance:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        cea:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        lta:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        medical:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        attire:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        special:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        other:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        gross_salary:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        pt:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        epf:['',[Validators.required,Validators.pattern('([0-9.]){1,}')]],
        net_salary:[],
    })
  }

  //get salary details of employee
  fetchsalary()
  {
    console.log(this.id);
    this.spinner.show();//show the spinner
    this.http.get('salary/ViewSalary',{id:this.id}).subscribe((res:any)=>{ //api for view salary details of employee
    console.log(res);
    this.gross_salary=res.result[0].gross_salary;
    console.log(this.gross_salary);
        this.registerform.patchValue({
            basic: res.result[0].basic,
            hra: res.result[0].hra,
            conveyance: res.result[0].conveyance,
            cea: res.result[0].cea,
            lta: res.result[0].lta,
            medical: res.result[0].medical,
            attire: res.result[0].attire,
            special: res.result[0].special,
            other: res.result[0].other,
            pt: res.result[0].pt,
            esic: res.result[0].esic,
            epf: res.result[0].epf,
            tds: res.result[0].tds,
            advance: res.result[0].advance,
            gross_salary: res.result[0].gross_salary,
        })
        this.spinner.hide();//hide the spinner
    })
  }

  selectgross(value)
  {
    console.log(value);
    this.basic=Math.round(value*0.3);
    this.hr=Math.round(value*0.12);
    this.epf=Math.round(value*0.036);
    this.sa=Math.round(value*0.02);
    this.lta=Math.round(value*0.045);
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
        pt: 0,
        esic: 0,
        epf: this.epf,
        tds:0,
        advance: 0
    })
 }

onSubmit():void
  {
    this.id=localStorage.getItem('id');
    console.log(this.registerform.value);
    const data={
        basic:this.registerform.value.basic,
        hra:this.registerform.value.hra,
        conveyance:this.registerform.value.conveyance,
        cea:this.registerform.value.cea,
        lta:this.registerform.value.lta,
        medical:this.registerform.value.medical,
        attire:this.registerform.value.attire,
        special:this.registerform.value.special,
        other:this.registerform.value.other,
        gross_salary:this.registerform.value.gross_salary,
        pt:this.registerform.value.pt,
        epf:this.registerform.value.epf,
    }
    //sum of all earning fields
    this.sum=(this.registerform.value.basic*1 + this.registerform.value.hra*1 
            + this.registerform.value.conveyance*1 + this.registerform.value.cea*1
            + this.registerform.value.lta*1 + this.registerform.value.medical*1
            + this.registerform.value.attire*1 + this.registerform.value.special*1 +
            this.registerform.value.other*1)
    console.log(this.sum);
    //deduction value(sum of pt+epf)
    this.deduct=(this.registerform.value.pt*1 + this.registerform.value.epf*1)
    console.log(this.deduct);
    //net salary(total earning - total deduction)    
    this.netSalary=(this.sum*1 - this.deduct*1);
    if(this.sum==this.gross_salary)
    {
        if(this.gross_salary > this.netSalary && this.netSalary>=0)
        {
            this.spinner.show();//show the spinner
            this.http.put('salary/updatesalary',data,{id:this.id}).subscribe((res:any)=>{ //api for update salary details of employee against id
            console.log(res);
            if(res['message']=='Updated successfully')
            {
                this.toastr.success('Data Updated successfully!', 'SUCCESS!');
            }
            this.spinner.hide();//hide the spinner
            })
        } 
        else{
            this.toastr.warning('Invalid net salary', 'warning!');
        }
    }
    else
    {
        this.toastr.warning('Gross salary and Total earning not match', 'warning!');
    }
  }

}
