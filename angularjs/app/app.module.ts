import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { EmployeeModule } from './employee/employee.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { SlizzingModule } from './slizzing/slizzing.module';
import { LoginModule } from './login/login.module';
import { HttpClientModule } from '@angular/common/http';
import { AttendanceModule } from './attendance/attendance.module';
import { MyOfficalDocumentsModule } from './my-offical-documents/my-offical-documents.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatSliderModule } from '@angular/material/slider';
import {MatDialogModule} from '@angular/material/dialog';
import { DialogComponent } from './dialog/dialog.component';
import { EnquiryModule } from './enquiry/enquiry.module';
import { ExpenseModule } from './expense/expense.module';
import { AuthGuard } from './auth.guard';
import { HttpService } from './http.service';
import { MyOfficeComponent } from './my-office/my-office.component';
import { HashLocationStrategy, LocationStrategy } from '@angular/common';
import { SalesModule } from './sales/sales.module';
import { PurchaseModule } from './purchase/purchase.module';
import { InvoiceSettingModule } from './invoice-setting/invoice-setting.module';



@NgModule({
  declarations: [
    AppComponent,
    DashboardComponent,
    DialogComponent,
    MyOfficeComponent,
   
    
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    EmployeeModule,
    SlizzingModule,
    LoginModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    AttendanceModule,
    MyOfficalDocumentsModule,
    BrowserAnimationsModule,
    MatSliderModule,
    MatDialogModule,
    EnquiryModule,
    ExpenseModule,
    SalesModule,
    PurchaseModule,
    InvoiceSettingModule
  ],
  providers: [AuthGuard,HttpService,{provide: LocationStrategy, useClass: HashLocationStrategy}],
  bootstrap: [AppComponent]
})
export class AppModule { }
