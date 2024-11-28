import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
@Component({
  selector: 'app-view-vendor',
  templateUrl: './view-vendor.component.html',
  styleUrls: ['./view-vendor.component.css']
})
export class ViewVendorComponent implements OnInit {
  id: string;
  recentdata: any;
  vendor_name: any;
  vendor_address: any;
  contact_person: any;
  contact_number: any;
  email: any;
  landline: any;
  vendor_code: any;
  gst: any;
  bank_name: any;
  account_number: any;
  product_category: any;
  ifsc_code: any;

  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch();
  }

  //get vendor details
  fetch()
  {       
    this.id=localStorage.getItem('id');
    this.spinner.show();//show the spinner
    this.http.get(`sales/viewvendor`,{id:this.id}).subscribe((res:any)=>{ //api for show vendor details
      console.log(res);
       this.recentdata=res.result[0];
       this.vendor_name=this.recentdata.vendor_name,
       this.vendor_address=this.recentdata.vendor_address,
       this.contact_person=this.recentdata.contact_person,
       this.contact_number=this.recentdata.contact_number,
       this.email=this.recentdata.email,
       this.landline=this.recentdata.landline,
       this.vendor_code=this.recentdata.vendor_code,
       this.gst=this.recentdata.gst,
       this.bank_name=this.recentdata.bank_name,
       this.account_number=this.recentdata.account_number,
       this.product_category=this.recentdata.product_category,
       this.ifsc_code=this.recentdata.ifsc_code

       this.spinner.hide();//hide spinner
    })
  }
}
