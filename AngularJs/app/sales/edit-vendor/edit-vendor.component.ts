import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
import { Router, ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-vendor',
  templateUrl: './edit-vendor.component.html',
  styleUrls: ['./edit-vendor.component.css']
})
export class EditVendorComponent implements OnInit {
  id: any;
  registerform: FormGroup;
  recentvalue: any;
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

  //get vendor details
  fetch()
  {
    console.log(this.id);
   this.spinner.show();//show the spinner
   this.http.get('sales/viewvendor',{id:this.id}).subscribe((res:any)=>{ //api for show vendor details
     console.log(res);
      this.registerform.patchValue({
        vendor_name: res.result[0].vendor_name,
        vendor_address: res.result[0].vendor_address,
        contact_person: res.result[0].contact_person,
        contact_number: res.result[0].contact_number,
        email: res.result[0].email,
        landline: res.result[0].landline,
        vendor_code: res.result[0].vendor_code,
        gst: res.result[0].gst,
        bank_name: res.result[0].bank_name,
        account_number: res.result[0].account_number,
        product_category: res.result[0].product_category,
        ifsc_code: res.result[0].ifsc_code,
      })
      console.log('k');
      this.spinner.hide();//hide the spinner
   });
  }

   //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
      vendor_name:['',[Validators.required]],
      vendor_address:['',[Validators.required]],
      contact_person:['',[Validators.required]],
      contact_number:['',[Validators.required]],
      email:[''],
      landline:[''],
      vendor_code:['',[Validators.required]],
      gst:['',[Validators.required]],
      bank_name:['',[Validators.required]],
      account_number:['',[Validators.required]],
      product_category:[''],
      ifsc_code:['',[Validators.required]],
    });  
  }

  //edit ventor details
  onsubmit():void
  {
    //this.id=localStorage.getItem('id');
    console.log(this.registerform.value);
    const data={
      vendorName:this.registerform.value.vendor_name,
      vendorAddress:this.registerform.value.vendor_address,
      contactPerson:this.registerform.value.contact_person,
      contactNumber:this.registerform.value.contact_number,
      email:this.registerform.value.email,
      landline:this.registerform.value.landline,
      vendorCode:this.registerform.value.vendor_code,
      gst:this.registerform.value.gst,
      bankName:this.registerform.value.bank_name,
      accountNumber:this.registerform.value.account_number,
      productCategory:this.registerform.value.product_category,
      ifscCode:this.registerform.value.ifsc_code,
    }
    this.spinner.show();//show the spinner
    this.http.put('sales/updatevendor',data,{id:this.id}).subscribe((res:any)=>{ //api for edit vendor details
    console.log(res);
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('Data Updated successfully!', 'SUCCESS!');
      }
      this.spinner.hide();//hide the spinner
    }); 
  }


}
