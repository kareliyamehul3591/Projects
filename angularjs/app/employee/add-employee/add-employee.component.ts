import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { Subject,Observable } from "rxjs";
import { debounceTime,distinctUntilChanged } from "rxjs/operators";
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-add-employee',
  templateUrl: './add-employee.component.html',
  styleUrls: ['./add-employee.component.css']
})
export class AddEmployeeComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  fetchloc: any[]; //DECLARE
  fetchdesign:any[]; //DECLARE
  username: string; //DECLARE
  error: any; //DECLARE
  error1:any; //DECLARE
  f: number; //DECLARE
  email: string; //DECLARE
  f1: number; //DECLARE
  fetchdesign1: any; //DECLARE
  designation: string; //DECLARE
  pack: number; //DECLARE
  perday: number; //DECLARE

  constructor(public router:Router,private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch_location();
    this.fetch_design();
    this.buildForm();
    this.fetch_design1();
    this.designation=localStorage.getItem('designation');
  }
  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
        username:['',Validators.required],
        password:['',Validators.required],
        first_name:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
        middle_name:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
        last_name:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
        dob:['',Validators.required],
        gender:['',Validators.required],
        mobile_no:['',[Validators.required, Validators.pattern('([0-9]){10}')]],
        alternate_mobile_no:['',[Validators.required, Validators. pattern('([0-9]){10}')]],
        personal_email:['',[Validators.required]],
        professional_email:['',[Validators.required]],
        address:['',Validators.required],
        aadhaar:['',[Validators.required, Validators.pattern('([0-9]){4}([0-9]){4}([0-9]){4}')]],
        pan:['',[Validators.required, Validators.pattern('([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}')]],
        account_no:['',[Validators.required]],
        bank_name:['',Validators.required],
        ifsc:['',[Validators.required,Validators.pattern('^[A-Za-z]{4}0[A-Z0-9a-z]{6}$')]],
        qualification:['',Validators.required],
        doj:['',Validators.required],
        designation_id:['',Validators.required],
        location_id:['',Validators.required],
        sl_entitled:['',[Validators.required,Validators.pattern('([0-9]){2}')]],
        cl_entitled:['',[Validators.required,Validators.pattern('([0-9]){2}')]],
    });  
  }

  //SUBMIT EMPLOYEE DETAILS ON CLICK
  onSubmit():void
  {
    console.log(this.registerform.value);
    const data={
        userName:this.registerform.value.username,
        password:this.registerform.value.password,
        firstName:this.registerform.value.first_name,
        middleName:this.registerform.value.middle_name,
        lastName:this.registerform.value.last_name,
        dob:this.registerform.value.dob,
        gender:this.registerform.value.gender,
        mobileNo:this.registerform.value.mobile_no,
        alternateMobileNo:this.registerform.value.alternate_mobile_no,
        personalEmail:this.registerform.value.personal_email,
        professionalEmail:this.registerform.value.professional_email,
        address:this.registerform.value.address,
        aadhaar:this.registerform.value.aadhaar,
        pan:this.registerform.value.pan,
        accountNo:this.registerform.value.account_no,
        bankName:this.registerform.value.bank_name,
        ifsc:this.registerform.value.ifsc,
        qualification:this.registerform.value.qualification,
        doj:this.registerform.value.doj,
        designationId:this.registerform.value.designation_id,
        locationId:this.registerform.value.location_id,
        slEntitled:this.registerform.value.sl_entitled,
        clEntitled:this.registerform.value.cl_entitled,
       // yearly_package:this.registerform.value.yearly_package,
    };
    
    this.spinner.show();//show the spinner
    this.http.post('emp/addEmp1',data).subscribe((res:any)=>{ //API FOR ADD EMPLOYEE DETAILS
       console.log(res);
        if(res['message']=='Post successfully')
        {
            console.log('save');
            this.toastr.success('Data save successfully!', 'SUCCESS!');
            this.registerform.reset();
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
            console.log('not');
        }
    this.spinner.hide();//HIDE SPINNER
    });
  }

  //FUNCTION FOR RESTRICT CHARACTER TO INPUT IN FIELD
  numberOnly(value)
  {
    console.log(value)
    this.pack=value/12;
    this.perday=this.pack/22;
    console.log(this.pack)
    console.log(this.perday);
  }

  //GET JOB LOCATION
  fetch_location()
  {
    this.http.get(`emp/location`).subscribe((res:any)=>{ //API FOR GET JOB LOCATION
      console.log(res);
      this.fetchloc=res.result;
    });
  }

  //GET DESIGNATION FOR ADMIN PORTAL
  fetch_design()
  {
    this.http.get(`emp/design`).subscribe((res:any)=>{ //API FOR GET DESIGNATION
      console.log(res);
      this.fetchdesign=res.result;
    })
  }

  //GET DESIGNATION FOR HR PORTAL
  fetch_design1()
  {
    this.http.get(`emp/hrdesign`).subscribe((res:any)=>{ //API FOR GET DESIGNATION
      console.log(res);
      this.fetchdesign1=res.result;
    })
  }

  //CHECK USERNAME IS EXIST OR NOT
  onSearchChange(searchValue: string): void {  
  const data={
  username:searchValue
  }
    console.log(data.username);
    this.http.post('emp/usercheck',data).subscribe((res:any)=>{ //API FOR CHECK USER NAME
        console.log(res);
        if(res['message']=='already')
        {
            this.error='exist';
            this.f=1;
        }
        else
        {
            this.error='';
            this.f=0;
        }
    })
  }

}
