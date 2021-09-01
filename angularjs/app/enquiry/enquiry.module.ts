import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AddEnquiryComponent } from './add-enquiry/add-enquiry.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {MatTableModule} from '@angular/material/table';
import {MatSortModule} from '@angular/material/sort';
import {MatFormFieldModule} from '@angular/material/form-field'; 
import {MatPaginatorModule} from '@angular/material/paginator';
import { ViewEnquiryComponent } from './view-enquiry/view-enquiry.component';
import { MatDialogModule } from '@angular/material/dialog';
import { UpdateEnquiryComponent } from './update-enquiry/update-enquiry.component';
import { MatButtonModule, MatIconModule } from '@angular/material';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ToastrModule } from 'ngx-toastr';
import { NgxSpinnerModule } from 'ngx-spinner';

@NgModule({
  declarations: [AddEnquiryComponent, ViewEnquiryComponent, UpdateEnquiryComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatTableModule,MatSortModule,
    MatFormFieldModule,MatPaginatorModule,
    MatDialogModule,
    MatButtonModule,
    BrowserAnimationsModule, // required animations module
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule,
    MatIconModule
  ],
  exports:[
    AddEnquiryComponent
  ]
})
export class EnquiryModule { }
