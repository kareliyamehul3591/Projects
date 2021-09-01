import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
@Component({
  selector: 'app-add-location',
  templateUrl: './add-location.component.html',
  styleUrls: ['./add-location.component.css']
})
export class AddLocationComponent implements OnInit {
  locationform: FormGroup; //FORM GROUP
  recentdata: any;
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
   this.buildForm();
   this.fetch_loc();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.locationform=this.formBuilder.group({
      name:['',Validators.required]
    })
  }

  //fetch location details
  fetch_loc(){
  this.spinner.show();//show the spinner
    this.http.get('emp/showlocation').subscribe((res:any)=>{ //API FOR SHOW HOLIDAYS
      console.log(res);
      this.recentdata=res.result;
      this.spinner.hide();//HIDE the spinner
    })
  }

   //SUBMIT LOCATION NAME DETAILS ON CLICK
   onSubmit():void
   {
     console.log(this.locationform.value);
     const data={
      name:this.locationform.value.name
     }
      this.spinner.show();//show the spinner
      this.http.post('emp/addLocation',data).subscribe((res:any)=>{ //API FOR ADD EMPLOYEE DETAILS
       console.log(res);
        if(res['message']=='Post successfully')
        {
            this.toastr.success('Data save successfully!', 'SUCCESS!');
            this.locationform.reset();
            this.ngOnInit();
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
        }
        this.spinner.hide();//HIDE SPINNER
      });
    }

    //GET EDIT ID
  edit(id)
  {
    console.log(id);
  }

  //DELETE LOCATION
  delete(id)
  {
    console.log(id);
    this.spinner.show();//show the spinner
    this.http.delete(`emp/del_location/${id}`).subscribe((res:any)=>{ //API FOR DELETE LOCATION
      console.log(res);
      this.toastr.success('Data Deleted Successfully');
    this.ngOnInit()
    this.spinner.hide();//HIDE the spinner
    })
  }

  //UPDATE HOLIDAY 
  update(id,name)
  {
    const data={
      name
    }
    console.log(data);
    this.spinner.show();//show the spinner
    this.http.put(`emp/location-update`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE LOCATION FIELD AGIANST ID
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
