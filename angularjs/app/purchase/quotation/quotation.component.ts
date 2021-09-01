import { Component, OnInit, ViewChild} from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
@Component({
  selector: 'app-quotation',
  templateUrl: './quotation.component.html',
  styleUrls: ['./quotation.component.css']
})
export class QuotationComponent implements OnInit {
  quotationForm: FormGroup;
  purchasevalue: any;
  vendor_name: any;
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns: string[] = ['quotation_no','pr_id', 'vendor', 'sale_date', 'quantity','amount','remark','action'];//FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.buildForm();
    this.fetchquotation();
    this.get_purchase_req_no();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.quotationForm=this.formBuilder.group({
      pr_no:[''],
      vendor:[''],
      sale_date:[],
      quantity:[''],
      amount:[''],
      remark: [''],
    });  
  }

  change_po_no(value)
  {
    console.log(value);
    this.spinner.show();//show the spinner
    this.http.get('purchase/get_purchase_info',{id:value}).subscribe((res:any)=>{ //API FOR ADD INVOIE
       
      console.log(res);
      this.vendor_name=res.result;
      this.spinner.hide(); //HIDE SPINNER
        })
  }

  get_purchase_req_no()
  {
    this.http.get('sales/fetch_purchase_req').subscribe((res:any)=>{ //API FOR ADD INVOIE
      console.log(res);
      this.purchasevalue=res.result;
      })
  }

  fetchquotation()
  {
   this.spinner.show();//show the spinner
    this.http.get('purchase/fetchquotation').subscribe((res:any)=>{ //API FOR GET QUOTATION DETAILS
     console.log(res);
     var recentvalue=res.result;
     this.listData=new MatTableDataSource(recentvalue);  //SET VALUE INTO LIST FROM GET API
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

  //ADD QUOTATION DETAILS
  onsubmit():void {
    console.log(this.quotationForm.value);
    const data={
      prNo:this.quotationForm.value.pr_no,
      vendor:this.quotationForm.value.vendor,
      saleDate:this.quotationForm.value.sale_date,
      quantity:this.quotationForm.value.quantity,
      amount:this.quotationForm.value.amount,
      remark:this.quotationForm.value.remark
    }
    this.spinner.show();//show the spinner
    this.http.post('purchase/addquotation',data).subscribe((res:any)=>{ //API FOR ADD QUOTATION
      console.log(res);
        if(res['message']=='Post successfully')
        {
        console.log('save');
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.quotationForm.reset();
        this.ngOnInit();
        }
        else{
        this.toastr.error('Something wrong!', 'Error!');
        console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    });
  }

  //UPDATE QUOTATION
  update(id,vendor,sale_date,quantity,amount,remark)
  {
    const data={
      vendor,
      sale_date,
      quantity,
      amount,
      remark
    }
     console.log(data);
     this.spinner.show();//show the spinner
     this.http.put(`purchase/updatequotation`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE QUOTATION
     console.log(res);
     if(res['message']=='Updated successfully')
     {
      this.toastr.success('Data Updated Successfully');
      this.ngOnInit()
     }
     this.spinner.hide();//HIDDE the spinner
   })
  }

  //DELETE QUOTATION
  delete_quotation(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`purchase/deletequotation/${id}`).subscribe((res: any) => { //API FOR DELETE QUOTATION
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//HIDE SPINNER
        });
     }
  }
}
