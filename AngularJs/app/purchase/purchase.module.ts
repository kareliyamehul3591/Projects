import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListPurchaseRequestComponent } from './list-purchase-request/list-purchase-request.component';
import { CreatePurchaseRequestComponent } from './create-purchase-request/create-purchase-request.component';
import { ListPurchaseComponent } from './list-purchase/list-purchase.component';
import { CreatePurchaseComponent } from './create-purchase/create-purchase.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule, MatDialogModule, MatTableModule, MatSortModule, MatPaginatorModule, MatButtonModule, MatIconModule, MatCheckboxModule, MatInputModule } from '@angular/material';
import { ToastrModule } from 'ngx-toastr';
import { NgxSpinnerModule } from 'ngx-spinner';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { QuotationComponent } from './quotation/quotation.component';
import { MaterialReceiptComponent } from './material-receipt/material-receipt.component';
import { PurchaseInvoiceComponent } from './purchase-invoice/purchase-invoice.component';
import { ViewPurchaseReqComponent } from './view-purchase-req/view-purchase-req.component';
import { EditPurchaseReqComponent } from './edit-purchase-req/edit-purchase-req.component';
import {MatSelectModule} from '@angular/material/select';
import { EditPurchaseOrderComponent } from './edit-purchase-order/edit-purchase-order.component';
import { ViewPurchaseOrderComponent } from './view-purchase-order/view-purchase-order.component';


@NgModule({
  declarations: [ListPurchaseRequestComponent, CreatePurchaseRequestComponent,ListPurchaseComponent, CreatePurchaseComponent, QuotationComponent, MaterialReceiptComponent, PurchaseInvoiceComponent, ViewPurchaseReqComponent, EditPurchaseReqComponent, EditPurchaseOrderComponent, ViewPurchaseOrderComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatSelectModule,
    MatDialogModule,
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule,
    BrowserAnimationsModule, // required animations module
    MatTableModule,
    MatSortModule,
    MatFormFieldModule,
    MatPaginatorModule,
    MatDialogModule,
    ToastrModule.forRoot(), // ToastrModule added
    NgxSpinnerModule,
    MatButtonModule,
    MatIconModule,
    MatCheckboxModule,
    MatInputModule,
  ]
})
export class PurchaseModule { }
