import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Location } from '@angular/common';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  loginform: FormGroup; //form group
  yes: any;
  designation:any;

  constructor(private formBuilder: FormBuilder,public http:HttpService,public router:Router,
    public toastr:ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.designation=localStorage.getItem('designation')
    console.log(this.designation);
    this.check();
    //form validator
    this.loginform=this.formBuilder.group({
      username:['',[Validators.required]],
      password:['',Validators.required],
    })
  }

  //login on click
  onSubmit():void
  {
    console.log(this.loginform.value);
    const data = {
        username: this.loginform.value.username,
        password: this.loginform.value.password
    };
    this.spinner.show();//show the spinner
    this.http.post('login/checkLogin',data).subscribe((res: any) => { //api for check login
      console.log(res);
      if(res['message']=='Login successfully')
      {
        localStorage.setItem('designation', res.designation);
        localStorage.setItem('token', res.token);
        localStorage.setItem('username', res.username);
        localStorage.setItem('first_name', res.first_name);
        localStorage.setItem('last_name', res.last_name);
        localStorage.setItem('emp_id', res.emp_id);
        this.router.navigate(['/dashboard1']);
        console.log('hii')
        console.log(localStorage.getItem(''))
      }
      else
      {
       this.toastr.error('Invalid Data!', 'Username Or Password is incorect');
      }
      this.spinner.hide();//hide spinner
    });
  }

 //navigate to forget password page
  forget()
  {
    this.router.navigate(['/forget-pass']);
  }

  check()
  {
    if(this.designation!=null){
      location.replace("/#/dashboard")
    }
    else if(this.designation!=null)
    {
      this.router.navigate(["/login"])
    }
  }
}
