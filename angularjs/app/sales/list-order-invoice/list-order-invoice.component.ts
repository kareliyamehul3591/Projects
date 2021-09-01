import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { MatDialog, MatTableDataSource } from '@angular/material';
import { MatSort } from '@angular/material/sort';
import {MatPaginator} from '@angular/material/paginator'; 
import { EditInvoiceComponent } from '../edit-invoice/edit-invoice.component';
import { ViewInvoiceComponent } from '../view-invoice/view-invoice.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-order-invoice',
  templateUrl: './list-order-invoice.component.html',
  styleUrls: ['./list-order-invoice.component.css']
})
export class ListOrderInvoiceComponent implements OnInit {

  constructor(public router:Router,public http:HttpService,private spinner: NgxSpinnerService,private toastr: ToastrService,public dialog: MatDialog) { }

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['invoice-number','customer_name','term_of_payment','invoice_date','due_date',
  'order_type','remark','description','id']; //FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch_invoice();
  }

  view_invoice(id)
  {
    this.router.navigate([`/view_invoice/${id}`])
  }
  
  //get invoice details
  fetch_invoice()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/fetch_invoice').subscribe((res:any)=>{ //api for get invoice details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
      this.spinner.hide();//hide spinner
    })
  }
  
//FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }

  //delete invoice details
  delete_invoice(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/delete_invoice/${id}`).subscribe((res: any) => { //api delete invoice delete
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//hide spinner
        });
     }
  }


  //invoice view modal
  openDialog(id) {
    console.log(id);
    localStorage.setItem('id',id);
    const dialogRef = this.dialog.open(ViewInvoiceComponent,{
      id:id,
      width: '50%',
      height:'80%',
      
    });

    dialogRef.afterClosed().subscribe(result => {
      
      console.log(`Dialog result: ${result}`);
    });
  }

 //edit invoice order 
edit_order(id)
{
  this.router.navigate([`/edit-order-invoice/${id}`])
}

}
