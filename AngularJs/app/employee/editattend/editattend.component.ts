import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import {formatDate,Location } from '@angular/common';
@Component({
  selector: 'app-editattend',
  templateUrl: './editattend.component.html',
  styleUrls: ['./editattend.component.css']
})
export class EditattendComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  id: string; //declare
  recentdata: any;  //declare
  today= new Date(); //DISPLAY CURRENT SYSTEM DATE
  jstoday = '';

  constructor(private formBuilder: FormBuilder,public http:HttpService,public toastr:ToastrService) {

    //FUNCTION USE FOR DISPLAY SYSTEM YEAR DATE TIME
    setInterval(() => {
      this.jstoday = formatDate(this.today, 'yyyy-MM-dd', 'en-US', '+0530');
    }, 1);
  }

  ngOnInit() {
    this.fetch();
    //VALIDATOR FORM'S FIELDS
    this.registerform=this.formBuilder.group({
      date:['',Validators.required],
      check_in:['',Validators.required],
      check_out:['',Validators.required]
    })
  }

  //GET ATTENDANCE DETAILS
  fetch()
  {
    this.id=localStorage.getItem('id');
    this.http.get('leave/getattend',{id:this.id}).subscribe((res:any)=>{ //API FOR GET ATTENDANCE
        console.log(res);
        this.recentdata=res;
        console.log(res.result[0].date);
        this.registerform.patchValue({
            date: res.result[0].date,
            check_in: res.result[0].check_in,
            check_out: res.result[0].check_out
        })
    })
  }

  //SUBMIT ATTENDANCE DETAILS ON CLICK
  onSubmit()
  {
    console.log(this.jstoday)
    this.id=localStorage.getItem('id');
    console.log(this.registerform.value);
    const data={
        date:this.registerform.value.date,
        checkIn:this.registerform.value.check_in ,
        checkOut:this.registerform.value.check_out
    }
    console.log('j')
    console.log(data)
    this.http.put(`leave/editempAtd`,data,{id:this.id}).subscribe((res:any)=>{ //API FOR EDIT EMPLOYEE ATTENDANCE
      console.log(res);
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('Data updated successfully!', 'SUCCESS!');
        this.ngOnInit();
      }
      else{
        this.toastr.error('Something goes wrong!','Error!');
      }
    })
  }

}
