import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatTableDataSource } from '@angular/material';

@Component({
  selector: 'app-create-purchase',
  templateUrl: './create-purchase.component.html',
  styleUrls: ['./create-purchase.component.css']
})
export class CreatePurchaseComponent implements OnInit {

  purchaseOrderForm: FormGroup; //form group
  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['name-of-material', 'description', 'quantity', 'unit-rate', 'amount', 'action']; //FEILDS NAME SHOW IN TABLE
  vendor_name: any;
  sub_total: number;
  discount: any;
  net_amount1: number;
  sgst_tax_per: any;
  cgst_tax_per:any;
  igst_tax_per:any;
  shipping_amt:any;
  other_amt:any;
  constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
    this.fetchvendor();
    this.discount='0';
    this.sgst_tax_per='0';
    this.cgst_tax_per='0';
    this.igst_tax_per='0';
    this.shipping_amt='0';
    this.other_amt='0';
  }

  //validators for form's field
  buildForm() {
    this.purchaseOrderForm = this.formBuilder.group({
      vendor_name: [''],
      term_of_payment: [''],
      delivery_date: [''],
      purchase_category: [''],
      sub_total:['0'],
      discount:['0'],
      net_amount:['0'],
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

  // create or added purchase material info form
  createMaterialInfoForm(materialInfo?: any): FormGroup {
    if (!materialInfo) {
      return this.formBuilder.group({
        name_of_material: [''],
        description: [''],
        quantity: [0],
        unit_rate: [0],
        amount: [0],
      });
    } else {
      return this.formBuilder.group({
        name_of_material: [materialInfo.name_of_material],
        description: [materialInfo.description],
        quantity: [materialInfo.quantity],
        unit_rate: [materialInfo.unit_rate],
        amount: [materialInfo.amount],
      });
    }
  }

  // add purchase material info
  addMaterialInfo(): void {
    this.purchaseMaterialInfoFormArray.push(this.createMaterialInfoForm());
    this.listData = this.purchaseOrderForm.controls['material_info'].value

  }

  // remove material info
  removeMaterialInfo(pos: number): void {
    this.purchaseMaterialInfoFormArray.removeAt(pos);
    this.listData = this.purchaseOrderForm.controls['material_info'].value
  }

  //material info form array
  get purchaseMaterialInfoFormArray(): FormArray {
    return this.purchaseOrderForm.get('material_info') as FormArray;
  }


  //calculate amount
  calculateAmount(index, value) {
    if (Number(value) > 0) {
      let amount = 0
      let quantity = Number(this.purchaseMaterialInfoFormArray.controls[index]['controls']['quantity'].value);
      let unitRate = Number(this.purchaseMaterialInfoFormArray.controls[index]['controls']['unit_rate'].value);
      amount = (quantity * unitRate);
      this.purchaseMaterialInfoFormArray.controls[index].patchValue({
        amount: amount
      })
       //calculate total
       this.getTotal();
    }
  }

  getTotal() {
    let igst_tax_amt=0;
    let sgst_tax_amt=0;
    let net_amount=0;
    let sub_total = 0;
    let cgst_tax_amt=0;
    let grant_total=0;
    let shipping_amt=0;
    let other_amt=0;
    this.purchaseMaterialInfoFormArray.controls.forEach(element => {
      sub_total += element.value.amount;
      console.log(sub_total)
      console.log(this.discount);
      this.sub_total=sub_total
      console.log(this.sub_total)
      net_amount=(this.sub_total - this.discount);
      
      sgst_tax_amt=Math.round((net_amount/100)*this.sgst_tax_per);
      cgst_tax_amt=Math.round((net_amount/100)*this.cgst_tax_per);
      igst_tax_amt=Math.round((net_amount/100)*this.igst_tax_per);
      shipping_amt=this.shipping_amt;
      other_amt=this.other_amt;
      grant_total=Math.round((sub_total*1)+(sgst_tax_amt*1)+(cgst_tax_amt*1)+(igst_tax_amt*1)+(shipping_amt*1)+(other_amt*1));
      //grant_total=(net_amount + sgst_tax_amt + cgst_tax_amt +igst_tax_amt + shipping_amt + other_amt);
    })
    this.purchaseOrderForm.patchValue({
      sub_total:sub_total,
      net_amount:net_amount,
      sgst_tax_amt:sgst_tax_amt,
      cgst_tax_amt:cgst_tax_amt,
      igst_tax_amt:igst_tax_amt,
      grand_total:grant_total,
    })
    
    this.purchaseOrderForm.updateValueAndValidity();
    //calculate total
  }

  //on click event of discunt input
  calculate_discount(value)
  {
    this.discount=value
    this.getTotal()
  }

  //on click event of sgst input
  calculate_sgst(value)
  {
    this.sgst_tax_per=value;
    this.getTotal()
  }

  //on click event of cgst input
  calculate_cgst(value)
  {
    this.cgst_tax_per=value;
    this.getTotal()
  }

  //on click event of igst input
  calculate_igst(value)
  { 
    this.igst_tax_per=value;
    this.getTotal()
  }

  //on click event of shipping input
  calculate_shipping_amt(value)
  {
    this.shipping_amt=value;
    this.getTotal()
  }

  //on click event of other input
  calculate_other_amt(value)
  {
    this.other_amt=value;
    this.getTotal()
  }


  //add purchase order
  onSubmit() {
    console.log('ttttt', this.purchaseOrderForm.value)
    this.http.post('purchase/addpurchase_order',this.purchaseOrderForm.value).subscribe((res:any)=>{ //api for add purchase request details
      console.log(res);
      if(res['message']=='Post successfully')
      {
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.purchaseOrderForm.reset();
      }
      this.spinner.hide(); //hide spinner
    }, err=>{
      this.toastr.error(err.message || err);
      this.spinner.hide(); //hide spinner
    })
  }

}
