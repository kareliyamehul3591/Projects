import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
import { Router } from '@angular/router';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-customer',
  templateUrl: './edit-customer.component.html',
  styleUrls: ['./edit-customer.component.css']
})
export class EditCustomerComponent implements OnInit {
  customerForm: FormGroup;
  id: string;
  recentdata: any;
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
    this.fetch();
  }

  fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get(`sales/viewcustomer`,{id:this.id}).subscribe((res:any)=>{
      console.log(res);
      this.recentdata=res.result[0];
      this.customerForm.patchValue({
        created_by: res.result[0].created_by,
        title: res.result[0].title,
        first_name: res.result[0].first_name,
        contact_number_1: res.result[0].contact_number_1,
        contact_number_2: res.result[0].contact_number_2,
        email: res.result[0].email,
        website: res.result[0].website,
        birth_date: res.result[0].birth_date,
        anniversary_date:res.result[0].anniversary_date,
        mailing_address: res.result[0].mailing_address,
        mailing_street: res.result[0].mailing_street,
        city: res.result[0].city,
        state: res.result[0].state,
        zip_code: res.result[0].zip_code,
        country: res.result[0].country,
        description: res.result[0].description,
      })
      this.spinner.hide();//hide spinner
    })
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.customerForm=this.formBuilder.group({
      created_by: [],
      title:['',[Validators.required]],
      first_name:['',[Validators.required]],
      //last_name:  ['',[Validators.required, Validators.pattern('[a-zA-Z]{3,20}')]],
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

  //update vendor
  onsubmit():void {
    console.log(this.customerForm.value);
    const data={
      title:this.customerForm.value.title,
      firstName:this.customerForm.value.first_name,
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
    this.http.put('sales/update_customer',data,{id:this.id}).subscribe((res:any)=>{ //api for update vendor
      console.log(res);
        if(res['message']=='Updated successfully')
        {
        console.log('save');
        this.toastr.success('Data Updated successfully!', 'SUCCESS!');
        }
        else{
        this.toastr.error('Something wrong!', 'Error!');
        console.log('not');
        }
        this.spinner.hide();//hide spinner
    });
  }


}
