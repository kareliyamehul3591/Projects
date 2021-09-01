import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
@Component({
  selector: 'app-create-vendor',
  templateUrl: './create-vendor.component.html',
  styleUrls: ['./create-vendor.component.css']
})
export class CreateVendorComponent implements OnInit {
  registerform: FormGroup;
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
      vendor_name:['',[Validators.required]],
      vendor_address:['',[Validators.required]],
      contact_person:['',[Validators.required]],
      contact_number:['',[Validators.required]],
      email:[],
      landline:[],
      vendor_code:['',[Validators.required]],
      gst:['',[Validators.required]],
      bank_name:['',[Validators.required]],
      account_number:['',[Validators.required]],
      product_category:[''],
      ifsc_code:['',[Validators.required]],
    });  
  }

  //add ventor
  onsubmit():void
  {
    console.log(this.registerform.value)
    const data={
      vendorName:this.registerform.value.vendor_name,
      vendoraAddress:this.registerform.value.vendor_address,
      contactPerson:this.registerform.value.contact_person,
      contactNumber:this.registerform.value.contact_number,
      email:this.registerform.value.email,
      landline:this.registerform.value.landline,
      vendorCode:this.registerform.value.vendor_code,
      gst:this.registerform.value.gst,
      bankName:this.registerform.value.bank_name,
      accountNumber:this.registerform.value.account_number,
      productCategory:this.registerform.value.product_category,
      ifscCode:this.registerform.value.ifsc_code  
    }
    this.spinner.show();//show the spinner
    this.http.post('sales/addvendor',data).subscribe((res:any)=>{ //api for add vendor details
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
      this.spinner.hide();
    })
  }

}
