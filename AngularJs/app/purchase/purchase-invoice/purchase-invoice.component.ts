import { Component, OnInit, ViewChild} from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
@Component({
  selector: 'app-purchase-invoice',
  templateUrl: './purchase-invoice.component.html',
  styleUrls: ['./purchase-invoice.component.css']
})
export class PurchaseInvoiceComponent implements OnInit {
  invoiceForm: FormGroup;
  po_no: any;
  vendor_name: any;

  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }
    formData=new FormData();
    formData1=new FormData();

  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['po_number', 'vendor', 'invoice_date','amount','remark','upload_receipt','action']; //FEILDS NAME SHOW IN TABLE
  @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

 ngOnInit() {
    this.buildForm();
    this.fetchinvoice();
    this.fetch_po();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.invoiceForm=this.formBuilder.group({
      po_number:[''],
      vendor:[''],
      invoice_date:[''],
      amount:[''],
      remark: [''],
      upload_receipt:['']
    });  
  }

  fetch_po()
  {
    this.spinner.show();//show the spinner
    this.http.get('purchase/fetch_purchase_order_info').subscribe((res:any)=>{ //API FOR GET MATERIAL RECEIPT DETAILS
     console.log(res);
     this.po_no=res.result;
     this.spinner.hide();//show the spinner
    });
  }

  select_po(value)
  {
    console.log(value);
    this.spinner.show();//show the spinner
    this.http.get('purchase/details_purchase_order_info',{id:value}).subscribe((res:any)=>{ //API FOR GET MATERIAL RECEIPT DETAILS
     console.log(res);
     this.vendor_name=res.result;
     this.spinner.hide();//show the spinner
    });
  }
  
  fetchinvoice()
  {
    this.spinner.show();//show the spinner
   this.http.get('purchase/fetchinvoice').subscribe((res:any)=>{ //API FOR GET PURCHASE INVOICE DETAILS
     console.log(res);
     var recentvalue=res.result;
     this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
     console.log(this.listData)
     this.listData.sort=this.sort; //USE FOR FILTER VALUE 
     this.listData.paginator=this.paginator; //USE FOR PAGINATION
     this.spinner.hide(); //HIDE SPINNER
   })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }

  onSelectedFile(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData=new FormData();
      this.formData.append('form_invoice', event.target.files[0]);
    }
  }

  onSelectedFile1(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData1=new FormData();
      this.formData1.append('form_invoice1', event.target.files[0]);
    }
  }

  //ADD INVOICE DETAILS
  onsubmit():void
  {
    console.log(this.invoiceForm.value);
    this.formData.append('po_number',this.invoiceForm.get('po_number').value);
    this.formData.append('vendor',this.invoiceForm.get('vendor').value);
    this.formData.append('invoice_date',this.invoiceForm.get('invoice_date').value);
    this.formData.append('amount',this.invoiceForm.get('amount').value);
    this.formData.append('remark',this.invoiceForm.get('remark').value);
    
    this.spinner.show();//show the spinner
      this.http.post('purchase/invoiceadd',this.formData).subscribe((res:any)=>{ //API FOR ADD INVOIE
        console.log('hi')
    console.log(res);
    if(res['message']=='Post successfully')
    {
      this.toastr.success('Data Save Successfully','Success!');
      this.invoiceForm.reset();
      this.formData = new FormData();
      this.ngOnInit();
    }
    this.spinner.hide(); //HIDE SPINNER
    }, err=>{
      this.toastr.error(err.message || err);
    })

  }

  //DELETE PURCHASE INVOICE 
  delete_invoice(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`purchase/deleteinvoice/${id}`).subscribe((res: any) => { //API FOR DELETE INVOICE AGINST ID
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//HIDE SPINNER
        });
     }
  }

  //UPDATE PURCHASE INVOICE
  update(id,vendor,invoice_date,amount,remark)
  {
    console.log(vendor)
    this.formData1.append('vendor',vendor);
    this.formData1.append('invoice_date',invoice_date);
    this.formData1.append('amount',amount);
    this.formData1.append('remark',remark);
    this.spinner.show();//show the spinner
      this.http.put('purchase/invoiceupdate',this.formData1,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE INCOICE DETAILS
        console.log('hi')
        console.log(res);
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('Data Updated Successfully','Success!');
        this.invoiceForm.reset();
        this.formData1 = new FormData();
        this.ngOnInit();
      }
      this.spinner.hide();//HIDE SPINNER
      }, err=>{
        this.toastr.error(err.message || err);
      })
    }
}
