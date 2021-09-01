import { Component, OnInit, ComponentFactoryResolver } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-holiday-calender',
  templateUrl: './holiday-calender.component.html',
  styleUrls: ['./holiday-calender.component.css']
})
export class HolidayCalenderComponent implements OnInit {
  register: FormGroup; //FORM GROUP
  recentdata: any[]; //DECLARE
 
  constructor(public http:HttpService,private formBuilder: FormBuilder,
    public toastr:ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.spinner.show();//show the spinner
    this.http.get('leave/showholidayAll').subscribe((res:any)=>{ //API FOR SHOW HOLIDAYS
      console.log(res);
      this.recentdata=res.result;
      this.spinner.hide();//HIDE the spinner
    })
    //VALIDATORS FORM'S FIELDS
    this.register=this.formBuilder.group({
      name:['',Validators.required],
      date:['',Validators.required]
    })
  }

  //ON CLICK SUBMIT FUNCTION
  OnSubmit():void{
    console.log(this.register.value);
    const data={
     name:this.register.value.name,
     date:this.register.value.date
    }
    console.log(data)
    this.spinner.show();//show the spinner
    this.http.post('leave/addholiday',data).subscribe((res:any)=>{ //API FOR ADD HOLIDAYS
      console.log(res);
      this.toastr.success('Data save successfully!', 'SUCCESS!');
      this.register.reset();
      this.ngOnInit();
      this.spinner.hide();//HIDE the spinner
    })
  }

  //GET EDIT ID
  edit(id)
  {
    console.log(id);
  }

  //DELETE HOLIDAY
  delete(id)
  {
    console.log(id);
    this.spinner.show();//show the spinner
    this.http.delete(`leave/holiday/${id}`).subscribe((res:any)=>{ //API FOR DELETE HOLIDAY
      console.log(res);
      this.toastr.success('Data Deleted Successfully');
    this.ngOnInit()
    this.spinner.hide();//HIDE the spinner
    })
  }

  //UPDATE HOLIDAY 
  update(id,name,date)
  {
    const data={
      name,
      date
    }
    console.log(data);
    this.spinner.show();//show the spinner
    this.http.put(`leave/updatecalender`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE HOLIDAY FIELD AGIANST ID
     console.log(res);
     if(res['message']=='Updated successfully')
     {
      this.toastr.success('Data Updated Successfully');
      this.ngOnInit()
     }
     this.spinner.hide();//HIDE the spinner
   })
  }
  
}
