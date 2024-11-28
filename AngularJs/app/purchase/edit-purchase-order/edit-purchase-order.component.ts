import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatTableDataSource } from '@angular/material';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-purchase-order',
  templateUrl: './edit-purchase-order.component.html',
  styleUrls: ['./edit-purchase-order.component.css']
})
export class EditPurchaseOrderComponent implements OnInit {
  purchaseOrderForm: FormGroup; //form group
  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['name-of-material', 'description', 'quantity', 'unit-rate', 'amount', 'action']; //FEILDS NAME SHOW IN TABLE
  vendor_name: any;
  id: any;
  events: any;
  cal: any;
  discount: any;
  sgst_tax_per: any;
  cgst_tax_per:any;
  igst_tax_per:any;
  shipping_amt:any;
  other_amt:any;
  sub_total: number;
  constructor(private formBuilder: FormBuilder,private _route: ActivatedRoute, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
    this.sgst_tax_per='0';
    this.cgst_tax_per='0';
    this.igst_tax_per='0';
      console.log(this.id);
  });
    this.buildForm();
    this.fetchvendor();
    this.fetch_purchase_order();
    this.fetch_purchase_order_details();
  }

  //validators for form's field
  buildForm() {
    this.purchaseOrderForm = this.formBuilder.group({
      vendor_name: [''],
      payment_term: [''],
      delivery_date: [''],
      purchase_category: [''],
      sub_total:['0'],
      discount:['0'],
      net_amt:['0'],
      grand_total:['0'],
      sgst_tax_per:['0'],
      sgst_tax_amt:['0'],
      cgst_tax_per:['0'],
      cgst_tax_amt:['0'],
      igst_tax_per:['0'],
      igst_tax_amt:['0'],
      shipping_amt:['0'],
      other_amt:['0'],
      material_info: this.formBuilder.array([])
    });
  }

    //get vndor name
    fetchvendor()
    {
      this.spinner.show();//show the spinner
        this.http.get('sales/fetchvendor').subscribe((res:any)=>{ //api for get vendor details
          console.log(res);
          this.vendor_name=res.result;
          this.spinner.hide(); //hide spinner
        })
    }

    fetch_purchase_order(){
      this.spinner.show();//show the spinner
      this.http.get('purchase/details_purchase_order_info',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
          console.log(res);
           this.discount=res.result[0].discount;
           this.shipping_amt=res.result[0].shipping_amt;
           this.other_amt=res.result[0].other_amt;
          this.purchaseOrderForm.patchValue({
              vendor_name: res.result[0].vendor_name,
              payment_term: res.result[0].payment_term,
              delivery_date:res.result[0].delivery_date,
              purchase_category:res.result[0].purchase_category,
              sub_total:res.result[0].sub_total,
              discount:res.result[0].discount,
              net_amt:res.result[0].net_amt,
              sgst_tax_per:res.result[0].sgst_tax_per,
              sgst_tax_amt:res.result[0].sgst_tax_amt,
              cgst_tax_per:res.result[0].cgst_tax_per,
              cgst_tax_amt: res.result[0].cgst_tax_amt,
              igst_tax_per:res.result[0].igst_tax_per,
              igst_tax_amt:res.result[0].igst_tax_amt,
              shipping_amt:res.result[0].shipping_amt,
              other_amt:res.result[0].other_amt,
              grand_total:res.result[0].grand_total
          })
          this.spinner.hide(); //hide spinner
      })
  }

  fetch_purchase_order_details(){
    this.spinner.show();//show the spinner
    this.http.get('purchase/purchase_order_details',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
        console.log(res);
        this.events = res.result;
        console.log(this.events)
        for(let event of this.events){
            this.addPurchaseOrder(event)
        }
        this.spinner.hide(); //hide spinner
    })
}

createPurchaseOrder(event)
{
  var event;
        console.log(event);
        if (!event) {
            event = this.formBuilder.group({
              material_name: [''],
              description:[''],
              quantity: [0],
              unit_price: [0],
              amount: [0],
              
        });
        } else {
            event = this.formBuilder.group({
              material_name: [event.material_name],
              description: [event.description],
              quantity: [event.quantity],
              unit_price: [event.unit_price],
              amount: [event.amount],
              
            });
        }
        return event;
}

// add purchase request info
addPurchaseOrder(event): void {
  const control = <FormArray>this.purchaseOrderForm.get('material_info');
  control.push(this.createPurchaseOrder(event));
}

// remove pucahse request info
public removeSection(i){
  const control = <FormArray>this.purchaseOrderForm.get('material_info');
  control.removeAt(i);
}

 //purchase request info form array
 getSections(purchaseOrderForm) {
  return purchaseOrderForm.controls.material_info.controls;
} 

calculateAmountAndTotal(index,value) {
  if (Number(value) > 0) {
      let total1=0
      let amount1 = 0
      const control = <FormArray>this.purchaseOrderForm.get('material_info');
      console.log(control)
      let quantity = Number(control.value[index]['quantity']);
      let unitPrice = Number(control.value[index]['unit_price']);
      
      //Quantity*Actual Rate)+GST%
      amount1 =(quantity * unitPrice)
      this.purchaseOrderForm['controls']['material_info']['controls'][index]
      ['controls'].amount.patchValue(amount1);
      
      //calculate total
      this.getTotal();
  }
}

getTotal() {
  let igst_tax_amt=0;
  let sgst_tax_amt=0;
  let net_amt=0;
  let sub_total = 0;
  let cgst_tax_amt=0;
  let grand_total=0;
  let shipping_amt=0;
  let other_amt=0;
  const control = <FormArray>this.purchaseOrderForm.get('material_info');
  //console.log(control)
  this.cal='1';
 
  this.purchaseOrderForm['controls']['material_info']['controls'].forEach(element => {
      sub_total += (element.value.amount)*1;
      this.sub_total=sub_total
      console.log(this.sub_total)
      net_amt=(this.sub_total - this.discount);
      console.log(this.discount)
      console.log(net_amt)
      sgst_tax_amt=Math.round((net_amt/100)*this.sgst_tax_per);
      cgst_tax_amt=Math.round((net_amt/100)*this.cgst_tax_per);
      igst_tax_amt=Math.round((net_amt/100)*this.igst_tax_per);
      shipping_amt=this.shipping_amt;
      other_amt=this.other_amt;
      grand_total=Math.round((sub_total*1)+(sgst_tax_amt*1)+(cgst_tax_amt*1)+(igst_tax_amt*1)+(shipping_amt*1)+(other_amt*1));
      console.log(sub_total*1);
      console.log(sgst_tax_amt*1);
      console.log(cgst_tax_amt*1);
  })
  this.purchaseOrderForm.patchValue({
    sub_total:sub_total,
    net_amt:net_amt,
    sgst_tax_amt:sgst_tax_amt,
    cgst_tax_amt:cgst_tax_amt,
    igst_tax_amt:igst_tax_amt,
    grand_total:grand_total,
  })
  this.purchaseOrderForm.updateValueAndValidity();
}


calculate_discount(value)
  {
    this.discount=value
    this.getTotal()
  }

  calculate_sgst(value)
  {
    this.sgst_tax_per=value;
    this.getTotal()
  }

  calculate_cgst(value)
  {
    this.cgst_tax_per=value;
    this.getTotal()
  }

  calculate_igst(value)
  { 
    this.igst_tax_per=value;
    this.getTotal()
  }

  calculate_shipping_amt(value)
  {
    this.shipping_amt=value;
    this.getTotal()
  }

  calculate_other_amt(value)
  {
    this.other_amt=value;
    this.getTotal()
  }

  //update purchase order
  onSubmit() {
    console.log('ttttt', this.purchaseOrderForm.value)
    this.http.put('purchase/update_purchase_order',this.purchaseOrderForm.value,{id:this.id}).subscribe((res:any)=>{ //api for add purchase request details
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
