import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ImportantNoteComponent } from './important-note/important-note.component';
import { MatButtonModule, MatIconModule, MatTableModule, MatSortModule, MatFormFieldModule, MatPaginatorModule, MatDialogModule, MatInputModule } from '@angular/material';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgxSpinnerModule } from 'ngx-spinner';
import { OfficeAddressComponent } from './office-address/office-address.component';
import { RegisterAddressComponent } from './register-address/register-address.component';
import { UploadLogoComponent } from './upload-logo/upload-logo.component';
import { TermCondComponent } from './term-cond/term-cond.component';
import { AddTermCondComponent } from './add-term-cond/add-term-cond.component';
import { AddRegisterationComponent } from './add-registeration/add-registeration.component';
import {CKEditorModule} from 'ckeditor4-angular';


@NgModule({
  declarations: [ImportantNoteComponent, OfficeAddressComponent, RegisterAddressComponent, UploadLogoComponent, TermCondComponent, AddTermCondComponent, AddRegisterationComponent],
  imports: [
    CommonModule,
    MatButtonModule,
    MatIconModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    NgxSpinnerModule,
    MatTableModule,MatSortModule,
    MatFormFieldModule,MatPaginatorModule,
    MatDialogModule,
    CKEditorModule,
    MatInputModule
  ]
})
export class InvoiceSettingModule { }
