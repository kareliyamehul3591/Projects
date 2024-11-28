import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-employee',
  templateUrl: './edit-employee.component.html',
  styleUrls: ['./edit-employee.component.css']
})
export class EditEmployeeComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  id: string; //DECLARE
  showdata: any; //DECLARE
  fetchdesign:any; //DECLARE
  fetchloc: any; //DECLARE
  eid: any;

  constructor(private formBuilder: FormBuilder,public http:HttpService,private toastr: ToastrService,
    public router:Router,private _route: ActivatedRoute,private spinner: NgxSpinnerService) { }

  ngOnInit() {
 //get id by params
 this._route.params.subscribe(params => {
  let id = params['id'];
  this.id=id
  console.log(this.id);
  });
    this.fetch_location();
    this.fetch_design();
    this.showemp();
    this.buildform();
  }

  //VALIDATORS FOR FORM'S FIELD
  buildform()
  {
    this.registerform=this.formBuilder.group({
        emp_code:['',Validators.required],
        first_name:['',Validators.required],
        last_name:['',Validators.required],
        dob:['',Validators.required],
        gender:['',Validators.required],
        mobile_no:['',[Validators.required, Validators.pattern('([0-9]){10}')]],
        alternate_mobile_no:['',[Validators.required, Validators. pattern('([0-9]){10}')]],
        personal_email:['',[Validators.required, Validators.email]],
        professional_email:['',[Validators.required, Validators.email]],
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
        sl_entitled:['',[Validators.required,Validators .pattern('([0-9]){2}')]],
        cl_entitled:['',[Validators.required,Validators.pattern('([0-9]){2}')]],
    })
  }
  
  //show empolyee details
  showemp()
  {
    // this.id=localStorage.getItem('id');
    console.log(this.id)
    this.spinner.show();//show the spinner
    this.http.get('emp/fetchemp',{id:this.id}).subscribe((res:any)=>{ //API FOR VIEW EMPLOYEE 
      console.log(res);    
    this.showdata=res;
        console.log(res.result[0].gender);
        this.registerform.patchValue({
            emp_code: res.result[0].emp_code,
            first_name: res.result[0].first_name,
            last_name: res.result[0].last_name,
            dob: res.result[0].dob,
            gender: res.result[0].gender,
            mobile_no: res.result[0].mobile_no,
            alternate_mobile_no: res.result[0].alternate_mobile_no,
            personal_email: res.result[0].personal_email,
            professional_email: res.result[0].professional_email,
            address: res.result[0].address,
            aadhaar: res.result[0].aadhaar,
            pan: res.result[0].pan,
            account_no: res.result[0].account_no,
            bank_name: res.result[0].bank_name,
            ifsc: res.result[0].ifsc,
            qualification: res.result[0].qualification,
            doj: res.result[0].doj,
            designation_id: res.result[0].designation_id,
            location_id: res.result[0].location_id,
            sl_entitled: res.result[0].sl_entitled,
            cl_entitled: res.result[0].cl_entitled,
        })
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  //GET EMPLOYEE DESIGNATION
  fetch_design()
  {
    this.http.get(`emp/design`).subscribe((res:any)=>{ //API FOR GET DESIGNATION DETAILS
      console.log(res);
      this.fetchdesign=res.result;
    })
  }

  //GET JOB LOCATION
  fetch_location()
  {
    this.http.get(`emp/location`).subscribe((res:any)=>{ //API FOR GET JOB LOCATION
      console.log(res);
      this.fetchloc=res.result;
    });
  }

  //SUBMIT EMPLOYEE DETAILS ON CLICK
  onSubmit():void
  {
    //this.id=localStorage.getItem('id');
    console.log(this.id);
    console.log(this.registerform.value);
    const data={
        empCode:this.registerform.value.emp_code,
        firstName:this.registerform.value.first_name,
        lastName:this.registerform.value.last_name,
        gender:this.registerform.value.gender,
        dob:this.registerform.value.dob,
        mobileNo:this.registerform.value.mobile_no,
        alternateMobileNo:this.registerform.value.alternate_mobile_no,
        personalEmail:this.registerform.value.personal_email,
        professionalEmail:this.registerform.value.professional_email,
        address:this.registerform.value.address,
        aadhaar:this.registerform.value.aadhaar,
        pan:this.registerform.value.pan,
        qualification:this.registerform.value.qualification,
        doj:this.registerform.value.doj,
        designationId:this.registerform.value.designation_id,
        locationId:this.registerform.value.location_id,
        accountNo:this.registerform.value.account_no,
        ifsc:this.registerform.value.ifsc,
        bankName:this.registerform.value.bank_name,
        slEntitled:this.registerform.value.sl_entitled,
        clEntitled:this.registerform.value.cl_entitled,
    }
    this.http.put(`emp/update`,data, {id:this.id}).subscribe((res:any)=>{ //API FOR UPDATE EMPLOYEE DETAILS
      console.log(res);
      if(res['message']=='Update successfully')
      {
        this.toastr.success('Data Update successfully!', 'SUCCESS!');
      
      }
    });
  }

}
