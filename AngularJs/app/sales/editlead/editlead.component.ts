import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-editlead',
  templateUrl: './editlead.component.html',
  styleUrls: ['./editlead.component.css']
})
export class EditleadComponent implements OnInit {
  id: any;
  registerform: FormGroup;
  recentvalue: any;
  yes: number;
  sales: any;
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,public router:Router,private _route: ActivatedRoute) { }

    ngOnInit() {
      //get id by params
    this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      console.log(this.id);
      });
      this.buildForm();
      //this.id=localStorage.getItem('id');
      this.fetch();
      this.check_customer_exist();
      this.fetch_sales();
    }
  
    //get lead details
    fetch()
    {
      console.log(this.id);
     this.spinner.show();//show the spinner
     this.http.get('sales/viewlead',{id:this.id}).subscribe((res:any)=>{ //appi for view lead details
       console.log(res);
        this.registerform.patchValue({
          fname: res.result[0].fname,
          lname: res.result[0].lname,
          phone_no: res.result[0].phone_no,
          mobile_no: res.result[0].mobile_no,
          company: res.result[0].company,
          email: res.result[0].email,
          website: res.result[0].website,
          lead_source: res.result[0].lead_source,
          follow_up: res.result[0].follow_up,
          lead_status: res.result[0].lead_status,
          industry: res.result[0].industry,
          rating: res.result[0].rating,
          annual_revenue: res.result[0].annual_revenue,
          employee_no: res.result[0].employee_no,
          street: res.result[0].street,
          city: res.result[0].city,
          state: res.result[0].state,
          zip: res.result[0].postal_code,
          country: res.result[0].country,
          description: res.result[0].description,
        })
       this.spinner.hide();//hide the spinner
     });
    }

    //fetch sales person
    fetch_sales()
    {
      this.spinner.show();//show the spinner
      this.http.get('emp/get_sales_person').subscribe((res:any)=>{ //api for edit lead details
      console.log(res);
      this.sales=res.result;
      this.spinner.hide();//hide the spinner
     });
    }
  
     //VALIDATOR FOR FORM'S FIELD
    buildForm(){
      this.registerform=this.formBuilder.group({
        assign_lead:['',[Validators.required]],
        fname:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
        lname:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
        phone_no:['',[Validators.required, Validators. pattern('([0-9]){10}')]],
        mobile_no:['',[Validators.required, Validators. pattern('([0-9]){10}')]],
        company:['',[Validators.required]],
        website:[''],
        lead_source:[''],
        industry:[''],
        rating:[''],
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
      
    //edit lead details
    onSubmit():void
    {
    //console.log(this.registerform.value);
    const data={
      assignLead:this.registerform.value.assign_lead,
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
    console.log(data);
    this.spinner.show();//show the spinner
    this.http.put('sales/updateLead',data,{id:this.id}).subscribe((res:any)=>{ //api for edit lead details
      console.log(res);
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('Data Updated successfully!', 'SUCCESS!');
      }
      this.spinner.hide();//hide the spinner
     });
  }
  
  check_customer_exist()
  {
    this.http.get('sales/view_lead_customer',{lead_id:this.id}).subscribe((res:any)=>{ //api for edit lead details
      console.log(res);
      const customer=res.result[0].lead_id;
      if(customer)
      this.yes=1;
     });
  }

  redirect_add_customer()
  {
    this.router.navigate([`/add-customer/${this.id}`]);
  }

}
