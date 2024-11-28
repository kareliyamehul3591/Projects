import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-view-emp',
  templateUrl: './view-emp.component.html',
  styleUrls: ['./view-emp.component.css']
})
export class ViewEmpComponent implements OnInit {
  registerform: FormGroup; //form group
  id:string; //declare
  showdata: any; //declare
  fetchdesign:any; //declare
  fetchloc: any; //declare

  constructor(public http:HttpService,private formBuilder: FormBuilder,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch_location();  //get job location
    this.fetch_design();  //get designation
    this.showemp(); //get employee details
    this.buildform(); //validator for form
  }

//validators form's fields
  buildform()
  {
    this.registerform=this.formBuilder.group({
        emp_code:['',Validators.required],
        first_name:['',Validators.required],
        middle_name:['',Validators.required],
        last_name:['',Validators.required],
        dob:['',Validators.required],
        gender:['',Validators.required],
        mobile_no:['',[Validators.required, Validators.minLength(10),Validators.maxLength(10)]],
        alternate_mobile_no:['',[Validators.required, Validators.minLength(10),Validators.maxLength(10)]],
        personal_email:['',[Validators.required, Validators.email]],
        professional_email:['',[Validators.required, Validators.email]],
        address:['',Validators.required],
        aadhaar:['',[Validators.required, Validators.minLength(12)]],
        pan:['',[Validators.required, Validators.minLength(12)]],
        account_no:['',[Validators.required, Validators.minLength(12)]],
        bank_name:['',Validators.required],
        ifsc:['',Validators.required],
        qualification:['',Validators.required],
        doj:['',Validators.required],
        designation_id:['',Validators.required],
        location_id:['',Validators.required],
        salary:['',Validators.required],
        sl_entitled:['',Validators.required],
        cl_entitled:['',Validators.required],
    })
  }

  //get employee details 
  showemp()
  {
    this.id=localStorage.getItem('id');
    console.log(this.id);
    this.spinner.show();//show the spinner
    this.http.get('emp/fetchemp',{id:this.id}).subscribe((res:any)=>{ //api for get employee deatils against id
      console.log(res);    
      this.showdata=res;
      console.log(res.result[0].gender);
      this.registerform.patchValue({
        emp_code: res.result[0].emp_code,
        first_name: res.result[0].first_name,
        middle_name: res.result[0].middle_name,
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
        salary: res.result[0].salary,
        sl_entitled: res.result[0].sl_entitled,
        cl_entitled: res.result[0].cl_entitled,
      })
      this.spinner.hide(); //hide spinner
    })
  }

  //get designation
  fetch_design()
  {
    this.http.get(`emp/design`).subscribe((res:any)=>{ //api for get employee designation
      console.log(res);
      this.fetchdesign=res.result;
    })
  }

  //get job location
  fetch_location()
  {
    this.http.get(`emp/location`).subscribe((res:any)=>{ //api for fet employee's job location
      console.log(res);
      this.fetchloc=res.result;
    });
  }
}
