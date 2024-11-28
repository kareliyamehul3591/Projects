import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
@Component({
  selector: 'app-changepassword',
  templateUrl: './changepassword.component.html',
  styleUrls: ['./changepassword.component.css']
})
export class ChangepasswordComponent implements OnInit {
  loginform: FormGroup; //form group
  pNotMatched = false;

  constructor(private formBuilder: FormBuilder,public http:HttpService, public toastr:ToastrService,
    private router:Router) { }

  ngOnInit() {
    this.buildForm(); //form validators
  }

  //validators for form's field
  buildForm(){
    this.loginform=this.formBuilder.group({     
        oldpassword: ['', Validators.required], 
        confirmPass: ['', Validators.required],
        password: ['', Validators.required]
    },{validator: this.checkIfMatchingPasswords('password', 'confirmPass')})    
  }

  //check for confirm password
  checkIfMatchingPasswords(passwordKey: string, passwordConfirmationKey: string) {
    return (group: FormGroup) => {
        let passwordInput = group.controls[passwordKey],
            passwordConfirmationInput = group.controls[passwordConfirmationKey];
        if (passwordInput.value !== passwordConfirmationInput.value && passwordConfirmationInput.value !== '') {
            return this.pNotMatched = true;
        } 
        else {
            return this.pNotMatched = false;
        }
    }
  }

  //change password on click
  onSubmit():void{
    console.log(this.loginform.value);
    const data={
      oldpassword:this.loginform.value.oldpassword,
      newpassword:this.loginform.value.password
    }
    this.http.put('emp/changepassword',data).subscribe((res:any)=>{ //api for change password
      console.log(res);
      if(res['message']=='old password not match')
      {
        this.toastr.error('Old Password Not Match','Error');
      }
      else if(res['message']=='password changed successfully')
      {
        this.toastr.success('Password Change Successfully!','Success');
        this.router.navigate(['/dashboard'])
      }
    },
    err=>{
      this.toastr.error(err.message || err);
    })
  }
  
}
