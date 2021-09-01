import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
@Component({
  selector: 'app-view-customer',
  templateUrl: './view-customer.component.html',
  styleUrls: ['./view-customer.component.css']
})
export class ViewCustomerComponent implements OnInit {
  recentdata: any;
  id: string;
  created_by: any;
  title: any;
  first_name: any;
  last_name: any;
  contact_number_1: any;
  contact_number_2: any;
  email: any;
  website: any;
  lead_status: any;
  birth_date: any;
  mailing_address: any;
  mailing_street: any;
  city: any;
  state: any;
  zip_code: any;
  country: any;
  description: any;
  anniversary_date: any;
  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch();
  }

  //get customer details 
  fetch()
  {
    this.id=localStorage.getItem('id');
    this.spinner.show();//show the spinner
    this.http.get(`sales/viewcustomer`,{id:this.id}).subscribe((res:any)=>{ //api for show customer details against id
      console.log(res);
       this.recentdata=res.result[0];
       console.log(this.recentdata.created_by);
       this.created_by=this.recentdata.created_by,
       
       this.title=this.recentdata.title,
       this.first_name=this.recentdata.first_name,
       this.last_name=this.recentdata.last_name,
       this.contact_number_1=this.recentdata.contact_number_1,

       this.contact_number_2=this.recentdata.contact_number_2,
       this.email=this.recentdata.email,
       this.website=this.recentdata.website,
   
       this.birth_date=this.recentdata.birth_date,
       this.anniversary_date=this.recentdata.anniversary_date,
       this.mailing_address=this.recentdata.mailing_address,
       this.mailing_street=this.recentdata.mailing_street,
       this.city=this.recentdata.city,
       this.state=this.recentdata.state,
       this.zip_code=this.recentdata.zip_code
       this.country=this.recentdata.country,
       this.description=this.recentdata.description

       this.spinner.hide();//hide the spinner
  })
}

}
