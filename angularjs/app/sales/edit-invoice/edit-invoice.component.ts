import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl,FormArray } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
import { Router, ActivatedRoute } from '@angular/router';
import { MatTableDataSource } from '@angular/material';
@Component({
  selector: 'app-edit-invoice',
  templateUrl: './edit-invoice.component.html',
  styleUrls: ['./edit-invoice.component.css']
})
export class EditInvoiceComponent implements OnInit {
  invoiceForm: FormGroup;
  id: string;
  recentdata: any;
  eid: any;
  customer_name: any;
  customer_address: any;
  term_of_payment: any;
  invoice_date: any;
  due_date: any;
  order_type: any;
  remark: any;
  description: any;
  order_no:any;
  // amount: any;
  recentvalue: any;
  recentvalue1: any;
 
  events: any;
  total: any;
  cal: string;
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,public router:Router,private _route: ActivatedRoute) { }

  ngOnInit() {
     //get id by params
     this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      console.log(this.id);
      });
    this.buildForm();
    this.get_invoice_info();
    this.get_invoice_details();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.invoiceForm=this.formBuilder.group({
      order_no:[''],
      customer_name:[''],
        customer_address:[''],
        payment_term:[''],
        invoice_date:[''],
        due_date :[''],
        order_type:[''],
        remark:[''],
        description:[''],
        total:[0],
        order_invoice_info:this.formBuilder.array([])
    });  
  }

  //get invoice info
  get_invoice_info()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/info_invoice_details',{id:this.id}).subscribe((res:any)=>{ //api for show order no details
        console.log(res);
        this.total=res.result[0].total,
        this.invoiceForm.patchValue({
            order_no: res.result[0].order_no,
            customer_name: res.result[0].customer_name,
            invoice_date:res.result[0].invoice_date,
            customer_address:res.result[0].customer_address,
            due_date:res.result[0].due_date,
            order_type:res.result[0].order_type,
            payment_term:res.result[0].payment_term,
            remark:res.result[0].remark,
            description:res.result[0].description,
            total:res.result[0].total,
        })
        this.recentvalue1=res.result;
        console.log(this.recentvalue1)
        this.spinner.hide();
    })
  }
 
  get_invoice_details()
  {
    //this.spinner.show();//show the spinner
    this.http.get('sales/details_invoice_info',{id:this.id}).subscribe((res:any)=>{ //api for show order no details
        console.log(res);
        this.events = res.result;
        console.log(this.events)
        for(let event of this.events){
          this.addPurchaseRequest(event)
        }
    })
  }
  
 // create or added purchase request info form
 createPurchaseRequestInfoForm(event){
  var event;
  console.log(event);
  console.log(event);
  if (!event) {
    event = this.formBuilder.group({
      invoice_description: [''],
      quantity: [0],
      unit_price: [0],
      gst: [0],
      invoice_amount: [0],
      
    });
  } else {
    event = this.formBuilder.group({
      invoice_description: [event.invoice_description],
      quantity: [event.quantity],
      unit_price: [event.unit_price],
      gst: [event.gst],
      invoice_amount: [event.invoice_amount],
     
    });
  }

  return event;
}

  // add purchase request info
  addPurchaseRequest(event): void {
    const control = <FormArray>this.invoiceForm.get('order_invoice_info');
    control.push(this.createPurchaseRequestInfoForm(event));
  }

  
// remove purchase request info
  public removeSection(i){
    const control = <FormArray>this.invoiceForm.get('order_invoice_info');
     control.removeAt(i);
  }
   
  //milestone form array
  getSections(invoiceForm) {
    return invoiceForm.controls.order_invoice_info.controls;
  }

  calculateAmountAndTotal(index,value) {
    if (Number(value) > 0) {
      let total1=0
     let invoice_amount1 = 0
     const control = <FormArray>this.invoiceForm.get('order_invoice_info');
     console.log(control)
     let quantity = Number(control.value[index]['quantity']);
     let unitPrice = Number(control.value[index]['unit_price']);
     let gstValue = Number(control.value[index]['gst']);

    //Quantity*Actual Rate)+GST%
    invoice_amount1 =((quantity * unitPrice) + (gstValue))
     this.invoiceForm['controls']['order_invoice_info']['controls'][index]
     ['controls'].invoice_amount.patchValue(
      invoice_amount1
     );
      //calculate total
      this.getTotal();
    }
}

//calculate total
getTotal() {
  const control = <FormArray>this.invoiceForm.get('order_invoice_info');
  this.cal='1';
  let total = 0;
  console.log(total);
  this.invoiceForm['controls']['order_invoice_info']['controls'].forEach(element => {
  total += (element.value.invoice_amount)*1;
  })
  this.invoiceForm.patchValue({
      total:total
  })
  this.invoiceForm.updateValueAndValidity();
}

  //edit invoice details
  onsubmit():void{
    console.log(this.invoiceForm.value);
    console.log('ttttt', this.invoiceForm.value)
    this.spinner.show();//show the spinner
    this.http.put('sales/update_invoice',this.invoiceForm.value,{id:this.id}).subscribe((res:any)=>{ //api for add purchase request details
      console.log(res);
      if(res['message']=='Post successfully')
      {
          this.toastr.success('Data save successfully!', 'SUCCESS!');
      }
      this.spinner.hide(); //hide spinner
      }, err=>{
      this.toastr.error(err.message || err);
      this.spinner.hide(); //hide spinner
      })
   }
}
