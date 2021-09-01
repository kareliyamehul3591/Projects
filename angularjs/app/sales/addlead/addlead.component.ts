import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
@Component({
  selector: 'app-addlead',
  templateUrl: './addlead.component.html',
  styleUrls: ['./addlead.component.css']
})
export class AddleadComponent implements OnInit {
  registerform: FormGroup;
  salesemp: any;
  salesemp1: any;
  first_name: any;
  last_name: any;
  constructor(public router:Router,
    private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }
    
  ngOnInit() {
    this.buildForm();
    this.first_name=localStorage.getItem('first_name');
    this.last_name=localStorage.getItem('last_name');
    //this.fetch_salesemp();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
      fname:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
      lname:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
      phone_no:['',[Validators.required, Validators.pattern('([0-9]){10}')]],
      mobile_no:['',[Validators.required, Validators.pattern('([0-9]){10}')]],
      company:['',[Validators.required]],
      website:[],
      lead_source:[''],
      industry:[''],
      rating:[],
      email:['',[Validators.required]],
      follow_up:['',[Validators.required]],
      lead_status:['',[Validators.required]],
      annual_revenue:[],
      employee_no:[],
      street:[],
      city:[],
      state:[],
      zip:[],
      country:[],
      description:[]
    });  
  }

  //fetch all sales employees
  fetch_salesemp()
  {
    this.http.get('sales/viewsalesemp').subscribe((res:any)=>{  //api for fetch sales employee
      console.log(res);
      this.salesemp=res.result[0].first_name + " " + res.result[0].last_name;
      console.log(this.salesemp);
    });
  }

  //ADD LEAD DETAILS
  onSubmit():void
  {
    console.log(this.registerform.value);
    const data={
      asignLead:this.first_name + " " + this.last_name,
      firstName:this.registerform.value.fname,
      lastName:this.registerform.value.lname,
      phoneNo:this.registerform.value.phone_no,
      mobileNo:this.registerform.value.mobile_no,
      company:this.registerform.value.company,
      website:this.registerform.value.website,
      leadSource:this.registerform.value.lead_source,
      industry:this.registerform.value.industry,
      rating:this.registerform.value.rating,
      email:this.registerform.value.email,
      followUp:this.registerform.value.follow_up,
      leadStatus:this.registerform.value.lead_status,
      annualRevenue:this.registerform.value.annual_revenue,
      employeeNo:this.registerform.value.employee_no,
      street:this.registerform.value.street,
      city:this.registerform.value.city,
      state:this.registerform.value.state,
      zip:this.registerform.value.zip,
      country:this.registerform.value.country,
      description:this.registerform.value.description
    }
    console.log(data)
    this.spinner.show();//show the spinner
    this.http.post('sales/addlead',data).subscribe((res:any)=>{ //API FOR ADD LEAD DETAILS
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


}