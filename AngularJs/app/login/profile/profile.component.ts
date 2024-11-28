import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {
  recentvalue: any; //DECLARE
  join_date: any; //DECLARE
  emp_code: any; //DECLARE
  desname: any; //DECLARE
  first_name: any; //DECLARE
  last_name: any; //DECLARE
  address: any; //DECLARE
  mobile_no: any; //DECLARE
  alternate_mobile_no: any; //DECLARE
  personal_email: any; //DECLARE
  professional_email: any; //DECLARE
  gender: any; //DECLARE
  pan: any; //DECLARE
  qualification: any; //DECLARE
  aadhaar: any; //DECLARE
  dob: any; //DECLARE

  constructor(public http:HttpService) { }

  ngOnInit() {
    this.fetch(); //GET EMPLOYEE PROFILE DETAILS
  }

  fetch()
  {
    this.http.get('leave/profileview').subscribe((res:any)=>{ //API FOR VIEW PROFILE DETAILS
      console.log(res);
      this. recentvalue=res.result;
      this.join_date=res.result[0]['doj'],
      this.emp_code=res.result[0]['emp_code'],
      this.desname=res.result[0]['desname'],
      this.first_name=res.result[0]['first_name'],
      this.last_name=res.result[0]['last_name'],
      this.address=res.result[0]['address'],
      this.mobile_no=res.result[0]['mobile_no'],
      this.alternate_mobile_no=res.result[0]['alternate_mobile_no'],
      this.personal_email=res.result[0]['personal_email'],
      this.professional_email=res.result[0]['professional_email'],
      this.gender=res.result[0]['gender'],
      this.pan=res.result[0]['pan'],
      this.aadhaar=res.result[0]['aadhaar'],
      this.qualification=res.result[0]['qualification'],
      this.dob=res.result[0]['dob'],
      console.log('hee');
      console.log(this.join_date);
    })
  }
}
