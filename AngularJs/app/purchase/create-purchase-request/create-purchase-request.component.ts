import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { MatTableDataSource } from '@angular/material';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { element } from 'protractor';

@Component({
  selector: 'app-create-purchase-request',
  templateUrl: './create-purchase-request.component.html',
  styleUrls: ['./create-purchase-request.component.css']
})
export class CreatePurchaseRequestComponent implements OnInit {

  purchaseRequestForm: FormGroup; //form group

  listData: MatTableDataSource<any>;
  displayedColumns: string[] = ['sr-no', 'description', 'vendor-name', 'quantity', 'unit_price', 'gst', 'amount', 'action'];
  getemp: any;
  getvendor: any;
  

  constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
    this.getemp_name();
    this.get_vendor();
  }

  buildForm() {
    this.purchaseRequestForm = this.formBuilder.group({
      date: [''],
      request_generated_by: [''],
      request_generated: [''],
      request_approved: [''],
      description: [''],
      total: [0],
      purchase_request_info: this.formBuilder.array([])
    });
  }

  // create or added purchase request info form
  createPurchaseRequestInfoForm(purchaseRequest?: any): FormGroup {
    let data;
  
    if (!purchaseRequest) {
      data = this.formBuilder.group({
        purchase_description: [''],
        vendor_name: [''],
        quantity: [0],
        unit_price: [0],
        gst: [0],
        amount: [0],
      });
    } else {
      data = this.formBuilder.group({
        purchase_description: [purchaseRequest.purchase_description],
        vendor_name: [purchaseRequest.vendor_name],
        quantity: [purchaseRequest.quantity],
        unit_price: [purchaseRequest.unit_price],
        gst: [purchaseRequest.gst],
        amount: [purchaseRequest.amount],
      });
    }

    return data;
  }

  // add purchase request info
  addPurchaseRequest(): void {
    this.purchaseRequestFormArray.push(this.createPurchaseRequestInfoForm());
    this.listData = this.purchaseRequestForm.controls['purchase_request_info'].value

  }

  // remove pucahse request info
  removePurchaseRequest(pos: number): void {
    this.purchaseRequestFormArray.removeAt(pos);
    this.listData = this.purchaseRequestForm.controls['purchase_request_info'].value
  }

  //purchase request info form array
  get purchaseRequestFormArray(): FormArray {
    return this.purchaseRequestForm.get('purchase_request_info') as FormArray;
  }

  calculateAmountAndTotal(index, value) {
    if (Number(value) > 0) {
      let amount = 0
      console.log(this.purchaseRequestFormArray.controls[index]['controls']['amount'])
      let quantity = Number(this.purchaseRequestFormArray.controls[index]['controls']['quantity'].value);
      let unitPrice = Number(this.purchaseRequestFormArray.controls[index]['controls']['unit_price'].value);
      let gstValue = Number(this.purchaseRequestFormArray.controls[index]['controls']['gst'].value);
      amount =((quantity * unitPrice) + (gstValue))
      //amount =((quantity * actualRate) + (quantity * actualRate * gstValue))
      this.purchaseRequestFormArray.controls[index].patchValue({
        amount: amount
      })

      //calculate total
      this.getTotal();
    }
  }

  //calculate total
  getTotal() {
    let total = 0;
    this.purchaseRequestFormArray.controls.forEach(element => {
      total += element.value.amount;
    })
    this.purchaseRequestForm.patchValue({
      total:total
    })
    this.purchaseRequestForm.updateValueAndValidity();
  }

  //get emp details
  getemp_name()
  {
    this.spinner.show();//show the spinner
    this.http.get('emp/Empallview').subscribe((res:any)=>{ //api for get emp details
      console.log(res);
      this.getemp=res.result;
      this.spinner.hide(); //hide spinner
    })
  }

  //get vendor details
  get_vendor()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/fetchvendor').subscribe((res:any)=>{ //api for get vendor name details
      console.log(res);
      this.getvendor=res.result;
      this.spinner.hide(); //hide spinner
    })
  }

  //add purchase request details
  onSubmit() {
    console.log('ttttt', this.purchaseRequestForm.value)
    this.spinner.show();//show the spinner
    this.http.post('purchase/add-purchase-req',this.purchaseRequestForm.value).subscribe((res:any)=>{ //api for add purchase request details
      console.log(res);
      if(res['message']=='Post successfully')
      {
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.purchaseRequestForm.reset();
      }
      this.spinner.hide(); //hide spinner
    }, err=>{
      this.toastr.error(err.message || err);
      this.spinner.hide(); //hide spinner
    })
  }

}
