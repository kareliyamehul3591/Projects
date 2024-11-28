import { Component, OnInit, ViewChild} from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
@Component({
  selector: 'app-material-receipt',
  templateUrl: './material-receipt.component.html',
  styleUrls: ['./material-receipt.component.css']
})
export class MaterialReceiptComponent implements OnInit {
  materialForm: FormGroup;
  vendor_name: any;
  po_no: any;

  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }
    formData=new FormData();
    formData1=new FormData();
    
  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['po_number', 'vendor', 'purchase_date','amount','remark','upload_receipt','action']; //FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort, null) sort: MatSort;  //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.buildform();
    this.fetchmaterial();
    this.fetch_po();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildform()
  {
    this.materialForm=this.formBuilder.group({
      po_number:[''],
      vendor:[''],
      purchase_date:[''],
      amount:[''],
      remark:[''],
      upload_receipt:['']
    })
  }

  //GET PO DETAILS
  fetch_po()
  {
    this.spinner.show();//show the spinner
    this.http.get('purchase/fetch_purchase_order_info').subscribe((res:any)=>{ //API FOR GET PO DETAILS
     console.log(res);
     this.po_no=res.result;
     this.spinner.hide();//show the spinner
    });
  }

  select_po(value)
  {
    console.log(value);
    this.spinner.show();//show the spinner
    this.http.get('purchase/details_purchase_order_info',{id:value}).subscribe((res:any)=>{ //API FOR GET PO DETAILS
     console.log(res);
     this.vendor_name=res.result;
     this.spinner.hide();//show the spinner
    });
  }

  onSelectedFile(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData=new FormData();
      this.formData.append('form_recpt', event.target.files[0]);
    }
  }

  onSelectedFile1(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData1=new FormData();
      this.formData1.append('form_recpt1', event.target.files[0]);
    }
  }

  //GET MATERIAL RECEIPT DETAILS
  fetchmaterial()
  {
    this.spinner.show();//show the spinner
    this.http.get('purchase/fetchmaterial').subscribe((res:any)=>{ //API FOR GET MATERIAL RECEIPT DETAILS
     console.log(res);
     var recentvalue=res.result;
     this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
     console.log(this.listData)
     this.listData.sort=this.sort; //USE FOR FILTER VALUE 
     this.listData.paginator=this.paginator; //USE FOR PAGINATION
     this.spinner.hide(); //HIDE SPINNER
   })
  }

  //ADD MATERIAL RECEIPT DETAILS ON CLICK
  onSubmit():void
  {
    console.log('jj')
    console.log(this.materialForm.value);
    //console.log(formData['name']);
    this.formData.append('po_number',this.materialForm.get('po_number').value);
    this.formData.append('vendor',this.materialForm.get('vendor').value);
    this.formData.append('purchase_date',this.materialForm.get('purchase_date').value);
    this.formData.append('amount',this.materialForm.get('amount').value);
    this.formData.append('remark',this.materialForm.get('remark').value);
    
    this.spinner.show();//show the spinner
      this.http.post('purchase/materialadd',this.formData).subscribe((res:any)=>{ //API FOR ADD MATERIAL RECEIPT DETAILS
        console.log('hi')
    console.log(res);
    if(res['message']=='Post successfully')
    {
      this.toastr.success('Data Save Successfully','Success!');
      this.materialForm.reset();
      this.formData = new FormData();
      this.ngOnInit();
    }
    this.spinner.hide(); //HIDE SPINNER
    }, err=>{
      this.toastr.error("Some thing went wrong");
      this.spinner.hide(); //HIDE SPINNER
    })

  }

  //DELETE MATERIAL RECEIPT DETAILS
  delete_material(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`purchase/deletematerial/${id}`).subscribe((res: any) => { //API FOR DELETE MATERIAL RECEIPT DETAILS AGAINST ID
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//HIDE SPINNER
        });
     }
  }

  //UPDATE MATERIAL RECEIPT DETAILS
  update(id,vendor,purchase_date,amount,remark,upload_receipt)
  {
    console.log(vendor)
    this.formData1.append('vendor',vendor);
    this.formData1.append('purchase_date',purchase_date);
    this.formData1.append('amount',amount);
    this.formData1.append('remark',remark);
   
    this.spinner.show();//show the spinner
      this.http.put('purchase/materialupdate',this.formData1,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE MATERIAL RECEIPT DETAILS
        console.log('hi')
      console.log(res);
    if(res['message']=='Updated successfully')
    {
      this.toastr.success('Data Updated Successfully','Success!');
      this.materialForm.reset();
      this.formData1 = new FormData();
      this.ngOnInit();
    }
    this.spinner.hide(); //HIDE SPINNER
    }, err=>{
      this.toastr.error(err.message || err);
    })
  }

}
