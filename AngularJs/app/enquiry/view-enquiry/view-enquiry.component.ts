import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
@Component({
  selector: 'app-view-enquiry',
  templateUrl: './view-enquiry.component.html',
  styleUrls: ['./view-enquiry.component.css']
})
export class ViewEnquiryComponent implements OnInit {
  registerform: FormGroup;
  id:string;
  showdata: any;
  enquiry_id: any;
  constructor(public http:HttpService,private formBuilder: FormBuilder) { }

  ngOnInit() {
  this.showenquiry();
  this.registerform=this.formBuilder.group({
    ref_name:[''],
    address:[''],
    name:[''],
    project_name:[''],
    mobile_no:[''],
    email:[''],
    qualification:[''],
    fees:[''],
    status:[''],
    edu_quali:[''],
    company_name:[''],
    ctc:[''],
    designation:[''],
    gstin:[''],
    st_name:[''],
    po:[''],
  })
  }

  showenquiry()
  {
    this.id=localStorage.getItem('id');
    console.log(this.id);
    this.http.get('enquiry/fetchenquiry',{id:this.id}).subscribe((res:any)=>{
      console.log(res);    
      
      this.showdata=res;
      console.log(res.result[0].enquiry_type_id);
      this.enquiry_id=res.result[0].enquiry_type_id,
      console.log(this.enquiry_id);
      this.registerform.patchValue({
        ref_name: res.result[0].ref_name,
      address: res.result[0].address,
      name: res.result[0].name,
      project_name: res.result[0].project_name,
      mobile_no: res.result[0].mobile_no,
      email: res.result[0].email,
      qualification: res.result[0].qualification,
      fees: res.result[0].fees,
      status: res.result[0].status,
      edu_quali: res.result[0].edu_quali,
      company_name: res.result[0].company_name,
      ctc: res.result[0].ctc,
      designation:res.result[0].designation,
      gstin:res.result[0].gstin,
      st_name:res.result[0].st_name,
      po:res.result[0].po,
      })
    })
  }
}
