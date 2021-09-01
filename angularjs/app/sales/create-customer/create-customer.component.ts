import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-create-customer',
  templateUrl: './create-customer.component.html',
  styleUrls: ['./create-customer.component.css']
})
export class CreateCustomerComponent implements OnInit {
  fullName: string = '';
  customerForm: FormGroup;
  id: any;
  first_name: string;
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

  ngOnInit() {
     //get id by params
     this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      console.log(this.id);
      });
    // login user full anme from local storage
    this.fullName=localStorage.getItem('first_name') + ' ' + localStorage.getItem('last_name');
    console.log('fullname', this.fullName)
    this.buildForm();
    this.fetch_lead_info();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.customerForm=this.formBuilder.group({
      title:['',[Validators.required]],
      first_name:['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
      last_name:  ['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
      contact_number_1:['',Validators.pattern('([0-9]){10}')],
      contact_number_2:['',Validators.pattern('([0-9]){10}')],
      email:['',[Validators.email]],
      website:[''],
      birth_date:[''],
      anniversary_date:[''],
      mailing_address:[''],
      mailing_street:[''],
      city: [''],
      state: [''],
      zip_code: [''],
      country: [''],
      description: ['']
    });  
  }

  //fetch fname,lname against lead id
  fetch_lead_info()
  {
   this.http.get('sales/viewlead',{id:this.id}).subscribe((res:any)=>{ //api for show vendor details
   console.log(this.first_name);
      this.customerForm.patchValue({
        first_name:res.result[0].fname,
        last_name:res.result[0].lname,
        contact_number_1:res.result[0].phone_no,
        contact_number_2:res.result[0].mobile_no,
        email:res.result[0].email,
        website:res.result[0].website,
        mailing_street:res.result[0].street,
        city:res.result[0].city,
        zip_code:res.result[0].postal_code,
        state:res.result[0].state,
        country:res.result[0].country,
      })
      
   });
  }
  
  //ADD CUSTOMER DETAILS
  onsubmit():void {
    console.log(this.customerForm.value);
   if(this.id==":id")
   {
     console.log('y');
    const data={
     createdBy:this.fullName,
      title:this.customerForm.value.title,
      firstName:this.customerForm.value.first_name,
      lastName:this.customerForm.value.last_name,
      contactNumber1:this.customerForm.value.contact_number_1,
      contactNumber2:this.customerForm.value.contact_number_2,
      email:this.customerForm.value.email,
      website:this.customerForm.value.website,
      birthDate:this.customerForm.value.birth_date,
      anniversaryDate:this.customerForm.value.anniversary_date,
      mailingAddress:this.customerForm.value.mailing_address,
      mailingStreet:this.customerForm.value.mailing_street,
      city:this.customerForm.value.city,
      state:this.customerForm.value.state,
      zipCode:this.customerForm.value.zip_code,
      country:this.customerForm.value.country,
      description:this.customerForm.value.description,
    }
    this.spinner.show();//show the spinner
    this.http.post('sales/addcustomer',data).subscribe((res:any)=>{ //API FOR ADD CUSTOMER
      console.log(res);
        if(res['message']=='Post successfully')
        {
        console.log('save');
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.customerForm.reset();
        }
        else{
        this.toastr.error('Something wrong!', 'Error!');
        console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    });
   }
   else{
    console.log('n');
    const data={
      leadId:this.id,
      createdBy:this.fullName,
       title:this.customerForm.value.title,
       firstName:this.customerForm.value.first_name,
       lastName:this.customerForm.value.last_name,
       contactNumber1:this.customerForm.value.contact_number_1,
       contactNumber2:this.customerForm.value.contact_number_2,
       email:this.customerForm.value.email,
       website:this.customerForm.value.website,
       birthDate:this.customerForm.value.birth_date,
       anniversaryDate:this.customerForm.value.anniversary_date,
       mailingAddress:this.customerForm.value.mailing_address,
       mailingStreet:this.customerForm.value.mailing_street,
       city:this.customerForm.value.city,
       state:this.customerForm.value.state,
       zipCode:this.customerForm.value.zip_code,
       country:this.customerForm.value.country,
       description:this.customerForm.value.description,
     }
     this.spinner.show();//show the spinner
    this.http.post('sales/addcustomer',data).subscribe((res:any)=>{ //API FOR ADD CUSTOMER
      console.log(res);
        if(res['message']=='Post successfully')
        {
        console.log('save');
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.customerForm.reset();
        }
        else{
        this.toastr.error('Something wrong!', 'Error!');
        console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    });
   }
   }

}
