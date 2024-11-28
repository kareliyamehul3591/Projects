import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AddAttendanceComponent } from './add-attendance/add-attendance.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { ApplyLeaveComponent } from './apply-leave/apply-leave.component';
//import { ShowAttendanceComponent } from './show-attendance/show-attendance.component';
import { FormBuilder, FormGroup, Validators, FormControl, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ToastrModule } from 'ngx-toastr';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HolidayCalenderComponent } from './holiday-calender/holiday-calender.component';
import { NgxSpinnerModule } from 'ngx-spinner';
import { MatButtonModule, MatTableModule, MatSortModule, MatFormFieldModule, MatPaginatorModule } from '@angular/material';


@NgModule({
  declarations: [AddAttendanceComponent, ApplyLeaveComponent, HolidayCalenderComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserAnimationsModule, // required animations module
    ToastrModule.forRoot(), // ToastrModule added,
    NgxSpinnerModule,
    MatButtonModule,
    MatTableModule,
    MatSortModule,
    MatFormFieldModule,MatPaginatorModule,
    
  ],
  exports:[
    AddAttendanceComponent,
    
 
  ],
})
export class AttendanceModule { }
