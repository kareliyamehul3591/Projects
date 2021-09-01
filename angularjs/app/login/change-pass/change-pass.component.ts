import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ActivatedRoute } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
@Component({
  selector: 'app-change-pass',
  templateUrl: './change-pass.component.html',
  styleUrls: ['./change-pass.component.css']
})
export class ChangePassComponent implements OnInit {
  loginform: FormGroup; //form group
  pNotMatched = false;
  eid: string; //declare
  id: any; //declare

  constructor(private router:Router,private formBuilder: FormBuilder,public http:HttpService,
    private _route: ActivatedRoute,public toastr:ToastrService) { }
    
  ngOnInit() {
    this.buildForm(); //validator form
    console.log('hee');
    //get id by params
    this._route.params.subscribe(params => {
      let id = params['id'];
      this.eid=id
      console.log(this.eid);
      });
  }

  //validator for form's field
  buildForm(){
    this.loginform=this.formBuilder.group({      
        confirmPass: ['', Validators.required],
        password: ['', Validators.required]
    },{validator: this.checkIfMatchingPasswords('password', 'confirmPass')})    
  }

  //function for check password and confirm password
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
  
  //login on submit 
  onSubmit():void{
   console.log(this.loginform.value);
    const data={
        password:this.loginform.value.password,
        eid:this.eid
    }
    this.http.put('login/update-pass',data).subscribe((res:any)=>{ //api for reset password
        console.log(res)
        if(res['message']=='Updated successfully')
        {
            this.toastr.success('Password Update Successfully!','Success');
            this.router.navigate(['/login'])
        }
    },
    err=>{
        this.toastr.error(err.message || err);
    })
  }

  //redirect to login page
  login()
  {
    this.router.navigate(['/login']);
  }
}
