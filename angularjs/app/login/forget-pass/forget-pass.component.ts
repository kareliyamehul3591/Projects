import { Component, OnInit, ComponentFactoryResolver } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-forget-pass',
  templateUrl: './forget-pass.component.html',
  styleUrls: ['./forget-pass.component.css']
})
export class ForgetPassComponent implements OnInit {
  forgetform: FormGroup; //form group

  constructor(public router:Router,private formBuilder: FormBuilder,
    public http:HttpService,private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
      //form validators
    this.forgetform=this.formBuilder.group({
      email:['',[Validators.required, Validators.email]],
    })
  }

  
  onSubmit():void
  {
    console.log(this.forgetform.value);
    const data = {
        personalEmail: this.forgetform.value.email,
    };
    this.spinner.show();//show the spinner
    this.http.post('login/forgetpass',data).subscribe((res: any) => { //api for forget password
      console.log(res);
      if(res['message']=='Auth failed')
      {
        this.toastr.error('Invalid Email Id');
      }
      else if(res['message']=='Email transfer failed')
      {
        this.toastr.error('Something Wrong!');
      }
      else if(res['message']=='Email sent')
      {
        this.toastr.success('Please Check Ur Mail');
        this.forgetform.reset();
      }
      this.spinner.hide();//hide spinner
    })
  }

  //navigate to back to login page
  back()
  {
    this.router.navigate(['/login']);
  }
}
