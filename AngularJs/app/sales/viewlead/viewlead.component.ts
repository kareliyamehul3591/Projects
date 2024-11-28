import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-viewlead',
  templateUrl: './viewlead.component.html',
  styleUrls: ['./viewlead.component.css']
})
export class ViewleadComponent implements OnInit {
  recentdata: any;
  id: string;
  email: any;
  company: any;
  phone_no: any;
  fname:any;
  lname: any;
  website: any;
  lead_source: any;
  follow_up: any;
  lead_status: any;
  industry: any;
  rating: any;
  annual_revenue: any;
  employee_no: any;
  street: any;
  city: any;
  state: any;
  country: any;
  description: any;
  postal_code: any;

  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch();
  }

  //get lead details
  fetch()
  {
    this.id=localStorage.getItem('id');
    this.spinner.show();//show the spinner
    this.http.get(`sales/viewlead`,{id:this.id}).subscribe((res:any)=>{ //api for show lead details
      console.log(res);
       this.recentdata=res.result[0];
       this.fname=this.recentdata.fname,
       this.lname=this.recentdata.lname,
       this.phone_no=this.recentdata.phone_no,
       this.company=this.recentdata.company,
       this.email=this.recentdata.email,

       this.website=this.recentdata.website,
       this.lead_source=this.recentdata.lead_source,
       this.follow_up=this.recentdata.follow_up,
       this.lead_status=this.recentdata.lead_status,
       this.industry=this.recentdata.industry,

       this.rating=this.recentdata.rating,
       this.annual_revenue=this.recentdata.annual_revenue,
       this.employee_no=this.recentdata.employee_no,
       this.street=this.recentdata.street,
       this.city=this.recentdata.city
       this.state=this.recentdata.state,
       this.postal_code=this.recentdata.postal_code,
       this.country=this.recentdata.country,
       this.description=this.recentdata.description
  })
}

}
