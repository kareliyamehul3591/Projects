import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormSixteenComponent } from './form-sixteen/form-sixteen.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { SalarySlipComponent } from './salary-slip/salary-slip.component';
import { CompanyPolicyComponent } from './company-policy/company-policy.component';
import { CreatePolicyComponent } from './create-policy/create-policy.component';
import { CreateForm16Component } from './create-form16/create-form16.component';
import { CreateSalarySlipComponent } from './create-salary-slip/create-salary-slip.component';
import { FormBuilder, FormGroup, Validators, FormControl, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ShowSalaryComponent } from './show-salary/show-salary.component';
import { ViewSalaryComponent } from './view-salary/view-salary.component';
import { MatDialogModule } from '@angular/material/dialog';
import { EditSalaryComponent } from './edit-salary/edit-salary.component';
import { GenerateSlipComponent } from './generate-slip/generate-slip.component';
import { SalaryHistoryComponent } from './salary-history/salary-history.component';
import { ViewGenSalaryComponent } from './view-gen-salary/view-gen-salary.component';
import { UpdategensalaryComponent } from './updategensalary/updategensalary.component';
import { SalarySheetComponent } from './salary-sheet/salary-sheet.component';
import { PdfSalarySlipComponent } from './pdf-salary-slip/pdf-salary-slip.component';
import { NgxSpinnerModule } from 'ngx-spinner';
import {MatTableModule} from '@angular/material/table';
import {MatSortModule} from '@angular/material/sort';
import {MatPaginatorModule} from '@angular/material/paginator';
import { MatButtonModule } from '@angular/material';

@NgModule({
  declarations: [FormSixteenComponent, SalarySlipComponent, CompanyPolicyComponent, CreatePolicyComponent, CreateForm16Component, CreateSalarySlipComponent, ShowSalaryComponent, ViewSalaryComponent, EditSalaryComponent,
     GenerateSlipComponent, SalaryHistoryComponent, ViewGenSalaryComponent, UpdategensalaryComponent,SalarySheetComponent, PdfSalarySlipComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatDialogModule,
    NgxSpinnerModule,
    MatTableModule,MatSortModule,
    MatPaginatorModule,MatButtonModule
  ],
  exports:[
    FormSixteenComponent,
    ShowSalaryComponent,
    ViewSalaryComponent,
    SalarySheetComponent
  ]
})
export class MyOfficalDocumentsModule { }
