import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder,FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatTableDataSource } from '@angular/material';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-create-order-invoice',
  templateUrl: './create-order-invoice.component.html',
  styleUrls: ['./create-order-invoice.component.css']
})
export class CreateOrderInvoiceComponent implements OnInit {

  invoiceForm: FormGroup;
  recentvalue: any;

  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['sr-no', 'description', 'quantity', 'unit-rate', 'gst', 'amount', 'action']; //FEILDS NAME SHOW IN TABLE
    id: any;
    inputvalue: number;
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

    ngOnInit() {
        //get id by params
        this._route.params.subscribe(params => {
        let id = params['id'];
        this.id=id;
        this.inputvalue=1;
        console.log(this.id)
        });
      this.buildForm();
      this.getorderno();
      this.fetch_invoice_info();
    }
  
    //VALIDATOR FOR FORM'S FIELD
    buildForm(){
        this.invoiceForm=this.formBuilder.group({
            order_number:[''],
            order_name:[''],
            customer_address:[],
            term_of_payment:[''],
            invoice_date:[],
            due_date :[],
            order_type:[],
            remark:[],
            description:[],
            total:[0],
            order_invoice_info:this.formBuilder.array([])
        });  
    }

    //fetch_invoiceinfo
    fetch_invoice_info()
    {
        this.spinner.show();//show the spinner
        this.http.get('sales/vieworder',{id:this.id}).subscribe((res:any)=>{ //api for add invoice details
        
        let order_number=res.result[0].order_number;
        console.log('j')
         console.log(res)
         this.invoiceForm.patchValue({
            order_number: res.result[0].order_number,
            order_name: res.result[0].order_name,
            order_type:res.result[0].order_type,
            customer_address:res.result[0].customer_address
         })
        this.spinner.hide(); //hide spinner
        })
    }
    // create or added order invoice info form
    createOrderInvoiceInfoForm(orderInvoice?: any): FormGroup {
        let data;
            if (!orderInvoice) {
                data = this.formBuilder.group({
                invoice_description: [''],
                quantity: [0],
                unit_price: [0],
                gst: [0],
                invoice_amount: [0],
            });
            } else {
                data = this.formBuilder.group({
                invoice_description: [orderInvoice.invoice_description],
                quantity: [orderInvoice.quantity],
                unit_price: [orderInvoice.unit_price],
                gst: [orderInvoice.gst],
                invoice_amount: [orderInvoice.invoice_amount],
                });
            }
            return data;
        }

    // add order invoice  info
    addPurchaseRequest(): void {
        this.purchaseRequestFormArray.push(this.createOrderInvoiceInfoForm());
        this.listData = this.invoiceForm.controls['order_invoice_info'].value
    }

    // remove order invoice info
    removePurchaseRequest(pos: number): void {
        this.purchaseRequestFormArray.removeAt(pos);
        this.listData = this.invoiceForm.controls['order_invoice_info'].value
    }

    //order invoice info form array
    get purchaseRequestFormArray(): FormArray {
        return this.invoiceForm.get('order_invoice_info') as FormArray;
    }

    calculateAmountAndTotal(index, value) {
        if (Number(value) > 0) {
        let invoice_amount = 0
        console.log(this.purchaseRequestFormArray.controls[index]['controls']['invoice_amount'])
        let quantity = Number(this.purchaseRequestFormArray.controls[index]['controls']['quantity'].value);
        let unitPrice = Number(this.purchaseRequestFormArray.controls[index]['controls']['unit_price'].value);
        let gstValue = Number(this.purchaseRequestFormArray.controls[index]['controls']['gst'].value);
        //Quantity*Actual Rate)+GST%
        invoice_amount =((quantity * unitPrice) + (gstValue))
       
        //console.log(this.purchaseRequestFormArray.controls[index]);
        this.purchaseRequestFormArray.controls[index].patchValue({
        invoice_amount: invoice_amount
      })
        //calculate total
        this.getTotal();
        }
    }

    getTotal() {
        let total = 0;
        this.purchaseRequestFormArray.controls.forEach(element => {
        total += element.value.invoice_amount;
        })
        this.invoiceForm.patchValue({
            total:total
        })
        this.invoiceForm.updateValueAndValidity();
    }

    ////add invoice details
    onSubmit() {
        this.spinner.show();//show the spinner
        this.http.post('sales/addinvoice',this.invoiceForm.value).subscribe((res:any)=>{ //api for add invoice details
        if(res['message']=='Post successfully')
        {
            this.toastr.success('Data save successfully!', 'SUCCESS!');
            this.invoiceForm.reset();
        }
        this.spinner.hide(); //hide spinner
        }, err=>{
        this.toastr.error(err.message || err);
        this.spinner.hide(); //hide spinner
        })
    }
    
    //get order no details
    getorderno()
    {
        this.spinner.show();//show the spinner
        this.http.get('sales/fetchorder').subscribe((res:any)=>{ //api for show order no details
        //console.log(res);
        this.recentvalue=res.result;
        //console.log(this.recentvalue)
        this.spinner.hide();
        })
    }

    //get order number
    getcustomer_name(value)
    {
       /// console.log(value);
        this.spinner.show();//show the spinner
        this.http.get('sales/order_no_view',{id:value}).subscribe((res:any)=>{//api for show status
          //console.log(res);
        this.invoiceForm.patchValue({
            order_name: res.result[0].order_name,
            order_type: res.result[0].order_type,
            customer_address: res.result[0].customer_address
        })
          this.spinner.hide();
        })
    }
}
