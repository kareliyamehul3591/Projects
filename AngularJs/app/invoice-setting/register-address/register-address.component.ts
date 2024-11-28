import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-register-address',
  templateUrl: './register-address.component.html',
  styleUrls: ['./register-address.component.css']
})
export class RegisterAddressComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm()
    this.showregister();
  }

  //form validators
  buildForm(){
    this.registerform=this.formBuilder.group({
      address:['',Validators.required]
    })
  }

  //show register address  details
  showregister()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/viewregister',{id:1}).subscribe((res:any)=>{ //API FOR VIEW register address 
    console.log(res);    
    console.log(res.result[0].address);
      this.registerform.patchValue({
        address: res.result[0].address
      })
      this.spinner.hide(); //HIDE SPINNER
    })
  }

//update register address note
onSubmit():void
{
  console.log(this.registerform.value)
  const data=
  {
    address:this.registerform.value.address
  }
  console.log(data)
    this.http.put('invoice/update-register',data,{id:1}).subscribe((res)=>{ //api for add address in register_add_info
        console.log(res);
        this.spinner.show();//show the spinner
        if(res['message']=='Updated successfully')
        {
            console.log('save');
            this.toastr.success('Data update successfully!', 'SUCCESS!');
            this.registerform.reset();
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
            console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    })
}

}
