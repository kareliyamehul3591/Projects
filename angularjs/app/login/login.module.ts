import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LoginComponent } from './login/login.component';
import { FormBuilder, FormGroup, Validators, FormControl, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ForgetPassComponent } from './forget-pass/forget-pass.component';
import { ChangePassComponent } from './change-pass/change-pass.component';
import { ChangepasswordComponent } from './changepassword/changepassword.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { NgxSpinnerModule } from "ngx-spinner";
import { ProfileComponent } from './profile/profile.component';



@NgModule({
  declarations: [LoginComponent, ForgetPassComponent, ChangePassComponent, ChangepasswordComponent, ProfileComponent],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    SlizzingModule,
    NgxSpinnerModule
  ],


  exports:[
    LoginComponent,
    ForgetPassComponent,
    ChangepasswordComponent
 
  ]
})
export class LoginModule { }
