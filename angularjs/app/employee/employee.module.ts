import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AddEmployeeComponent } from './add-employee/add-employee.component';
import { ShowEmployeeComponent } from './show-employee/show-employee.component';
import { HeaderComponent } from '../slizzing/header/header.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormBuilder, FormGroup, Validators, FormControl, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ShowEmpLeaveComponent } from './show-emp-leave/show-emp-leave.component';
import { EmpLeaveDetailsComponent } from './emp-leave-details/emp-leave-details.component';
import { ShowEmpAttendComponent } from './show-emp-attend/show-emp-attend.component';
import {MatDialogModule} from '@angular/material/dialog';
import { ViewEmpComponent } from './view-emp/view-emp.component';
import {MatFormFieldModule} from '@angular/material/form-field';
import { EditEmployeeComponent } from './edit-employee/edit-employee.component';
import { ToastrModule } from 'ngx-toastr';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { EditattendComponent } from './editattend/editattend.component';
import {MatTableModule} from '@angular/material/table';
import {MatSortModule} from '@angular/material/sort';
import {MatPaginatorModule} from '@angular/material/paginator';
import { NgxSpinnerModule } from "ngx-spinner";
import { AddLocationComponent } from './add-location/add-location.component';
import { MatButtonModule } from '@angular/material';
@NgModule({
  declarations: [AddEmployeeComponent,ShowEmployeeComponent, ShowEmpLeaveComponent, EmpLeaveDetailsComponent, ShowEmpAttendComponent, ViewEmpComponent, EditEmployeeComponent, EditattendComponent, AddLocationComponent],
  exports:[
    AddEmployeeComponent,
    ShowEmployeeComponent,
    ShowEmpLeaveComponent,
    EmpLeaveDetailsComponent,
    ViewEmpComponent
  ],

  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatDialogModule,
    MatButtonModule,
    BrowserAnimationsModule, // required animations module
    MatTableModule,MatSortModule,
    MatFormFieldModule,MatPaginatorModule,
    MatDialogModule,
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule
  ]
})
export class EmployeeModule { }
