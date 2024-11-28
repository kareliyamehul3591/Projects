import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AddleadComponent } from './addlead/addlead.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { EditleadComponent } from './editlead/editlead.component';
import { LeadlistComponent } from './leadlist/leadlist.component';
import { MatDialogModule } from '@angular/material/dialog';
import { NgxSpinnerModule } from 'ngx-spinner';
import { ToastrModule } from 'ngx-toastr';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatTableModule } from '@angular/material/table';
import { MatSortModule } from '@angular/material/sort';
import { MatPaginatorModule } from '@angular/material/paginator';
import { ViewleadComponent } from './viewlead/viewlead.component';
import { CreateOrderComponent } from './create-order/create-order.component';
import { ListOrderComponent } from './list-order/list-order.component';
import { EditOrderComponent } from './edit-order/edit-order.component';
import { CreateMilestonesComponent } from './create-milestones/create-milestones.component';
import { ListMilestonesComponent } from './list-milestones/list-milestones.component';
import { CreateVendorComponent } from './create-vendor/create-vendor.component';
import { ListVendorComponent } from './list-vendor/list-vendor.component';

//mat button
import {MatButtonModule} from '@angular/material/button';

//mat icon module
import {MatIconModule} from '@angular/material/icon';

//mat input module
import {MatInputModule} from '@angular/material';

// check box
import { MatCheckboxModule } from '@angular/material/checkbox';
import { ListOrderInvoiceComponent } from './list-order-invoice/list-order-invoice.component';
import { CreateOrderInvoiceComponent } from './create-order-invoice/create-order-invoice.component';
import { ListCustomerComponent } from './list-customer/list-customer.component';
import { CreateCustomerComponent } from './create-customer/create-customer.component';
import { ViewVendorComponent } from './view-vendor/view-vendor.component';
import { EditVendorComponent } from './edit-vendor/edit-vendor.component';
import { ViewInvoiceComponent } from './view-invoice/view-invoice.component';
import { EditInvoiceComponent } from './edit-invoice/edit-invoice.component';
import { ViewCustomerComponent } from './view-customer/view-customer.component';
import { EditCustomerComponent } from './edit-customer/edit-customer.component';
import { EditMilestoneComponent } from './edit-milestone/edit-milestone.component';


@NgModule({
  declarations: [AddleadComponent, EditleadComponent, LeadlistComponent, ViewleadComponent, CreateOrderComponent, ListOrderComponent, EditOrderComponent, CreateMilestonesComponent, ListMilestonesComponent, CreateVendorComponent, ListVendorComponent, ListOrderInvoiceComponent, CreateOrderInvoiceComponent, ListCustomerComponent, CreateCustomerComponent, ViewVendorComponent, EditVendorComponent, ViewInvoiceComponent, EditInvoiceComponent, ViewCustomerComponent, EditCustomerComponent, EditMilestoneComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatDialogModule,
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule,BrowserAnimationsModule, // required animations module
    MatTableModule,MatSortModule,
    MatFormFieldModule,MatPaginatorModule,
    MatDialogModule,
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule,
    MatButtonModule,
    MatIconModule,
    MatCheckboxModule,
    MatInputModule,
  ]
})
export class SalesModule { }
